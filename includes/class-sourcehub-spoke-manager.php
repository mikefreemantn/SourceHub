<?php
/**
 * Spoke functionality manager
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Spoke Manager Class
 */
class SourceHub_Spoke_Manager {

    /**
     * Initialize spoke functionality
     */
    public function init() {
        // Guard against duplicate hook registration (matches pattern used in
        // SourceHub_Hub_Manager, SourceHub_Admin, and SourceHub_Validation).
        // See class-sourcehub-hub-manager.php init() for full rationale.
        static $initialized = false;
        if ($initialized) {
            error_log('SourceHub: Spoke_Manager::init() called again in same request - ignoring duplicate registration');
            return;
        }
        $initialized = true;

        add_action('wp_head', array($this, 'add_canonical_tags'));
        add_filter('the_content', array($this, 'maybe_add_attribution'), 999);
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        add_action('sourcehub_process_sync_job', array($this, 'process_sync_job'));
        add_action('sourcehub_retry_hub_callback', array($this, 'handle_callback_retry'), 10, 4);

        // 3rd-tier safety net: detect & report stuck SourceHub drafts to the hub
        add_filter('cron_schedules', array($this, 'add_cron_schedules'));
        add_action('sourcehub_check_stuck_drafts', array($this, 'check_stuck_drafts'));
        if (!wp_next_scheduled('sourcehub_check_stuck_drafts')) {
            wp_schedule_event(time() + 60, 'sourcehub_every_five_minutes', 'sourcehub_check_stuck_drafts');
        }
    }

    /**
     * Register custom cron schedule for stuck-draft check
     */
    public function add_cron_schedules($schedules) {
        if (!isset($schedules['sourcehub_every_five_minutes'])) {
            $schedules['sourcehub_every_five_minutes'] = array(
                'interval' => 5 * MINUTE_IN_SECONDS,
                'display'  => __('Every 5 minutes (SourceHub)', 'sourcehub'),
            );
        }
        return $schedules;
    }

    /**
     * Register REST API routes for spoke functionality
     */
    public function register_rest_routes() {
        register_rest_route('sourcehub/v1', '/receive-post', array(
            'methods' => 'POST',
            'callback' => array($this, 'receive_post'),
            'permission_callback' => array($this, 'check_api_permission'),
            'args' => array(
                'hub_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Original hub post ID'
                ),
                'title' => array(
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Post title'
                ),
                'content' => array(
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Post content'
                ),
                'hub_url' => array(
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Hub site URL'
                )
            )
        ));

        register_rest_route('sourcehub/v1', '/update-post', array(
            'methods' => array('PUT', 'POST'),
            'callback' => array($this, 'update_post'),
            'permission_callback' => array($this, 'check_api_permission'),
            'args' => array(
                'hub_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Original hub post ID'
                )
            )
        ));

        register_rest_route('sourcehub/v1', '/status', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_status'),
            'permission_callback' => array($this, 'check_api_permission')
        ));

        register_rest_route('sourcehub/v1', '/trash-post', array(
            'methods' => 'POST',
            'callback' => array($this, 'trash_post'),
            'permission_callback' => array($this, 'check_api_permission'),
            'args' => array(
                'spoke_post_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Spoke post ID to trash'
                ),
                'hub_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Original hub post ID'
                )
            )
        ));

        register_rest_route('sourcehub/v1', '/untrash-post', array(
            'methods' => 'POST',
            'callback' => array($this, 'untrash_post'),
            'permission_callback' => array($this, 'check_api_permission'),
            'args' => array(
                'spoke_post_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Spoke post ID to restore'
                ),
                'hub_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Original hub post ID'
                )
            )
        ));

        register_rest_route('sourcehub/v1', '/delete-post', array(
            'methods' => 'POST',
            'callback' => array($this, 'delete_post'),
            'permission_callback' => array($this, 'check_api_permission'),
            'args' => array(
                'spoke_post_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Spoke post ID to delete'
                ),
                'hub_id' => array(
                    'required' => true,
                    'type' => 'integer',
                    'description' => 'Original hub post ID'
                ),
                'force' => array(
                    'required' => false,
                    'type' => 'boolean',
                    'default' => true,
                    'description' => 'Force permanent deletion'
                )
            )
        ));
    }

    /**
     * Check API permission
     *
     * @param WP_REST_Request $request Request object
     * @return bool
     */
    public function check_api_permission($request) {
        $api_key = $request->get_header('X-SourceHub-API-Key');
        $stored_key = get_option('sourcehub_spoke_api_key');

        // Debug logging
        error_log(sprintf('SourceHub Spoke: Received API key: %s... | Stored key: %s...', 
            substr($api_key, 0, 8), 
            substr($stored_key, 0, 8)
        ));

        if (empty($api_key) || empty($stored_key)) {
            error_log('SourceHub Spoke: API key check failed - one or both keys are empty');
            
            SourceHub_Logger::error(
                'API authentication failed: Missing API key',
                array(
                    'has_api_key' => !empty($api_key),
                    'has_stored_key' => !empty($stored_key)
                ),
                null,
                null,
                'auth_failed'
            );
            
            return false;
        }

        $matches = hash_equals($stored_key, $api_key);
        error_log('SourceHub Spoke: API key match: ' . ($matches ? 'YES' : 'NO'));
        
        if (!$matches) {
            SourceHub_Logger::error(
                'API authentication failed: Invalid API key',
                array(
                    'received_key_prefix' => substr($api_key, 0, 8),
                    'stored_key_prefix' => substr($stored_key, 0, 8)
                ),
                null,
                null,
                'auth_failed'
            );
        }
        
        return $matches;
    }

    /**
     * Receive new post from hub
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function receive_post($request) {
        try {
            $start_time = microtime(true);
            error_log('SourceHub Spoke: receive_post started');
            
            $data = $request->get_json_params();
            
            // Log incoming request immediately
            SourceHub_Logger::info(
                'Received syndication request from hub',
                array(
                    'hub_url' => $data['hub_url'] ?? 'unknown',
                    'hub_id' => $data['hub_id'] ?? 'unknown',
                    'title' => $data['title'] ?? 'unknown'
                ),
                null,
                null,
                'receive_request'
            );
            
            // Validate required data
            $validation_start = microtime(true);
            $validation = $this->validate_post_data($data);
            error_log(sprintf('SourceHub Spoke: Validation took %.2f seconds', microtime(true) - $validation_start));
            
            if (!$validation['valid']) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => implode(', ', $validation['errors'])
                ), 400);
            }

            // Stage 2/3 reconciliation: do NOT short-circuit when a post already
            // exists. The REST endpoint's only responsibility is validate + queue;
            // the SINGLE create-vs-update authority is process_sync_job(), which
            // performs a locked, uncached, idempotent upsert. Dropping a delivery
            // here (the old 'duplicate_prevented' early-return) would mean a create
            // that lands on an existing draft never gets enriched - the Little
            // Caesars bug - and that becomes fatal once Stage 3 carries the image
            // and Yoast data inside the create payload. Queuing a duplicate is
            // safe now: the upsert turns it into a harmless full update.

            // Queue job for async processing
            $job_id = $this->queue_sync_job($data, 'create');
            
            if (is_wp_error($job_id)) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => $job_id->get_error_message()
                ), 500);
            }
            
            // Log successful job queuing
            SourceHub_Logger::info(
                sprintf('Job queued for processing: "%s"', $data['title']),
                array(
                    'job_id' => $job_id,
                    'hub_id' => $data['hub_id'],
                    'hub_url' => $data['hub_url'],
                    'action' => 'create'
                ),
                null,
                null,
                'job_queued'
            );
            
            // Schedule immediate processing with Action Scheduler (reliable, no wp-cron dependency)
            as_enqueue_async_action('sourcehub_process_sync_job', array($job_id), 'sourcehub');
            
            // Return 202 Accepted immediately
            error_log(sprintf('SourceHub Spoke: Job queued in %.2f seconds, returning 202', microtime(true) - $start_time));
            
            return new WP_REST_Response(array(
                'success' => true,
                'message' => __('Post queued for processing', 'sourcehub'),
                'job_id' => $job_id,
                'status' => 'processing'
            ), 202);

        } catch (Exception $e) {
            SourceHub_Logger::error(
                'Exception in receive_post: ' . $e->getMessage(),
                array('trace' => $e->getTraceAsString()),
                null,
                null,
                'receive_post'
            );

            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Internal server error', 'sourcehub')
            ), 500);
        }
    }

    /**
     * Update existing post from hub
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function update_post($request) {
        try {
            $start_time = microtime(true);
            $data = $request->get_json_params();
            
            // Find existing post
            $existing_post = $this->find_existing_post($data['hub_id'], $data['hub_url']);
            if (!$existing_post) {
                // If post doesn't exist, create it instead of failing
                error_log('SourceHub: Post not found for update, creating new post instead. Hub ID: ' . $data['hub_id']);
                return $this->receive_post($request);
            }

            // Validate data
            $validation = $this->validate_post_data($data);
            if (!$validation['valid']) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => implode(', ', $validation['errors'])
                ), 400);
            }

            // Queue job for async processing
            $job_id = $this->queue_sync_job($data, 'update');
            
            if (is_wp_error($job_id)) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => $job_id->get_error_message()
                ), 500);
            }
            
            // Log successful job queuing
            SourceHub_Logger::info(
                sprintf('Update job queued for processing: "%s"', $data['title']),
                array(
                    'job_id' => $job_id,
                    'hub_id' => $data['hub_id'],
                    'hub_url' => $data['hub_url'],
                    'action' => 'update'
                ),
                null,
                null,
                'job_queued'
            );
            
            // Schedule immediate processing with Action Scheduler (reliable, no wp-cron dependency)
            as_enqueue_async_action('sourcehub_process_sync_job', array($job_id), 'sourcehub');
            
            // Return 202 Accepted immediately
            error_log(sprintf('SourceHub Spoke: Update job queued in %.2f seconds, returning 202', microtime(true) - $start_time));
            
            return new WP_REST_Response(array(
                'success' => true,
                'message' => __('Post update queued for processing', 'sourcehub'),
                'job_id' => $job_id,
                'status' => 'processing'
            ), 202);

        } catch (Exception $e) {
            SourceHub_Logger::error(
                'Exception in update_post: ' . $e->getMessage(),
                array('trace' => $e->getTraceAsString()),
                null,
                null,
                'update_post'
            );

            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Internal server error', 'sourcehub')
            ), 500);
        }
    }

    /**
     * Get spoke status
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function get_status($request) {
        $stats = SourceHub_Logger::get_stats(array('days' => 7));
        
        return new WP_REST_Response(array(
            'success' => true,
            'status' => 'active',
            'mode' => 'spoke',
            'version' => SOURCEHUB_VERSION,
            'site_url' => home_url(),
            'wordpress_version' => get_bloginfo('version'),
            'yoast_active' => SourceHub_Yoast_Integration::is_yoast_active(),
            'yoast_version' => SourceHub_Yoast_Integration::get_yoast_version(),
            'stats' => $stats,
            'last_activity' => $this->get_last_activity()
        ), 200);
    }

    /**
     * Validate post data
     *
     * @param array $data Post data
     * @return array
     */
    private function validate_post_data($data) {
        $validation = array(
            'valid' => true,
            'errors' => array()
        );

        // Required fields
        $required_fields = array('hub_id', 'title', 'content', 'hub_url');
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $validation['valid'] = false;
                $validation['errors'][] = sprintf(__('Missing required field: %s', 'sourcehub'), $field);
            }
        }

        // Validate hub_id
        if (isset($data['hub_id']) && !is_numeric($data['hub_id'])) {
            $validation['valid'] = false;
            $validation['errors'][] = __('Hub ID must be numeric', 'sourcehub');
        }

        // Validate hub_url
        if (isset($data['hub_url']) && !filter_var($data['hub_url'], FILTER_VALIDATE_URL)) {
            $validation['valid'] = false;
            $validation['errors'][] = __('Invalid hub URL', 'sourcehub');
        }

        // Validate Yoast data if present
        if (isset($data['yoast_meta']) && !empty($data['yoast_meta'])) {
            $yoast_validation = SourceHub_Yoast_Integration::validate_meta_data($data['yoast_meta']);
            if (!$yoast_validation['valid']) {
                $validation['valid'] = false;
                $validation['errors'] = array_merge($validation['errors'], $yoast_validation['errors']);
            }
        }

        return $validation;
    }

    /**
     * Find existing post by hub ID and URL
     *
     * @param int $hub_id Hub post ID
     * @param string $hub_url Hub URL
     * @return WP_Post|null
     */
    private function find_existing_post($hub_id, $hub_url) {
        $posts = get_posts(array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_sourcehub_hub_id',
                    'value' => $hub_id,
                    'compare' => '='
                ),
                array(
                    'key' => '_sourcehub_origin_url',
                    'value' => $hub_url,
                    'compare' => '='
                )
            ),
            'post_status' => 'any',
            'numberposts' => 1
        ));

        return !empty($posts) ? $posts[0] : null;
    }

    /**
     * Find existing post by hub ID and URL using a DIRECT, UNCACHED query.
     *
     * Stage 2: get_posts()/WP_Query results are object-cached on managed hosts
     * (e.g. WPEngine/Flywheel). Under concurrent syndication that stale cache is
     * exactly what let two jobs both believe no post existed and create/duplicate
     * (the race_prevented churn). The authoritative create-vs-update decision in
     * process_sync_job() must therefore read straight from the database.
     *
     * @param int    $hub_id  Hub post ID
     * @param string $hub_url Hub URL
     * @return int Spoke post ID, or 0 if none
     */
    private function find_existing_post_uncached($hub_id, $hub_url) {
        global $wpdb;

        $post_id = $wpdb->get_var($wpdb->prepare(
            "SELECT p.ID
             FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} m1 ON p.ID = m1.post_id
                 AND m1.meta_key = '_sourcehub_hub_id' AND m1.meta_value = %d
             INNER JOIN {$wpdb->postmeta} m2 ON p.ID = m2.post_id
                 AND m2.meta_key = '_sourcehub_origin_url' AND m2.meta_value = %s
             WHERE p.post_status NOT IN ('trash', 'auto-draft', 'inherit')
             ORDER BY p.ID ASC
             LIMIT 1",
            $hub_id,
            $hub_url
        ));

        return $post_id ? (int) $post_id : 0;
    }

    /**
     * Allow all HTML for SourceHub syndicated content
     * This temporarily disables WordPress content filtering to preserve iframes and embeds
     *
     * @param array $allowed_html Allowed HTML tags
     * @param string $context Context
     * @return array Modified allowed HTML tags
     */
    public function allow_all_html_for_sourcehub($allowed_html, $context) {
        // Only apply this for post content context
        if ($context === 'post') {
            // Allow all HTML tags and attributes
            return array_merge($allowed_html, array(
                'iframe' => array(
                    'src' => true,
                    'width' => true,
                    'height' => true,
                    'frameborder' => true,
                    'allowfullscreen' => true,
                    'allow' => true,
                    'title' => true,
                    'loading' => true,
                    'class' => true,
                    'id' => true,
                    'style' => true,
                    'scrolling' => true,
                    'name' => true
                ),
                'script' => array(
                    'src' => true,
                    'type' => true,
                    'async' => true,
                    'defer' => true,
                    'crossorigin' => true,
                    'integrity' => true
                ),
                'style' => array(
                    'type' => true,
                    'media' => true,
                    'scoped' => true
                ),
                'embed' => array(
                    'src' => true,
                    'width' => true,
                    'height' => true,
                    'type' => true,
                    'class' => true,
                    'id' => true
                ),
                'object' => array(
                    'data' => true,
                    'type' => true,
                    'width' => true,
                    'height' => true,
                    'class' => true,
                    'id' => true
                )
            ));
        }
        
        return $allowed_html;
    }

    /**
     * Create post from hub data
     *
     * @param array $data Post data from hub
     * @return int|WP_Error Post ID or error
     */
    private function create_post_from_data($data) {
        // Debug: Log content received from hub
        SourceHub_Logger::info(
            'DEBUG: Content received from hub: ' . substr($data['content'], 0, 500),
            array('full_content_length' => strlen($data['content'])),
            null,
            null,
            'debug_content'
        );
        
        // Temporarily disable content filtering to preserve iframes and embeds
        add_filter('wp_kses_allowed_html', array($this, 'allow_all_html_for_sourcehub'), 10, 2);
        
        // Store the intended status for later
        $intended_status = isset($data['status']) ? sanitize_text_field($data['status']) : 'publish';
        
        // Prepare post data - CREATE AS DRAFT initially to prevent publishing with broken images
        $post_data = array(
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => $data['content'],
            'post_excerpt' => isset($data['excerpt']) ? sanitize_textarea_field($data['excerpt']) : '',
            'post_status' => 'draft', // Always draft initially
            'post_type' => isset($data['post_type']) ? sanitize_text_field($data['post_type']) : 'post',
            'post_name' => isset($data['slug']) ? sanitize_title($data['slug']) : '',
            'post_date' => isset($data['date']) ? sanitize_text_field($data['date']) : current_time('mysql'),
            'post_date_gmt' => isset($data['date_gmt']) ? sanitize_text_field($data['date_gmt']) : current_time('mysql', 1)
        );
        
        error_log("SourceHub: Creating post as draft, will publish as '{$intended_status}' after processing");

        // Handle author
        if (isset($data['author'])) {
            $author_id = $this->find_or_create_author($data['author']);
            if ($author_id) {
                $post_data['post_author'] = $author_id;
            } else {
                // Fallback to default author if author matching fails
                $default_author = get_option('sourcehub_default_author', 1);
                $post_data['post_author'] = $default_author;
            }
        }

        // Insert the post
        $post_id = wp_insert_post($post_data);
        
        // Re-enable content filtering
        remove_filter('wp_kses_allowed_html', array($this, 'allow_all_html_for_sourcehub'), 10);
        
        if (is_wp_error($post_id)) {
            return $post_id;
        }

        // Update post metadata
        // Store hub metadata
        update_post_meta($post_id, '_sourcehub_hub_id', intval($data['hub_id']));
        update_post_meta($post_id, '_sourcehub_origin_url', esc_url_raw($data['hub_url']));
        update_post_meta($post_id, '_sourcehub_received_at', current_time('mysql'));
        // Public-facing marker so 3rd-tier stuck-draft safety net can identify SourceHub posts
        update_post_meta($post_id, 'from_SourceHub', true);

        // Handle page template
        if (isset($data['page_template']) && !empty($data['page_template'])) {
            update_post_meta($post_id, '_wp_page_template', sanitize_text_field($data['page_template']));
            error_log('SourceHub: Set page template for post ' . $post_id . ': ' . $data['page_template']);
        }

        // Handle categories
        if (isset($data['categories']) && is_array($data['categories'])) {
            $this->set_post_categories($post_id, $data['categories']);
        }

        // Handle tags
        if (isset($data['tags']) && is_array($data['tags'])) {
            $this->set_post_tags($post_id, $data['tags']);
        }

        // Handle post format
        if (isset($data['post_format'])) {
            $this->set_post_format($post_id, $data['post_format']);
        }

        // Handle featured image
        if (isset($data['featured_image'])) {
            $image_start = microtime(true);
            $this->set_featured_image($post_id, $data['featured_image']);
            error_log(sprintf('SourceHub Spoke: Featured image processing took %.2f seconds', microtime(true) - $image_start));
        }

        // Handle gallery images
        if (isset($data['gallery_images']) && is_array($data['gallery_images'])) {
            $gallery_start = microtime(true);
            error_log('SourceHub Gallery: Received ' . count($data['gallery_images']) . ' gallery images for post ' . $post_id);
            SourceHub_Logger::info('Received ' . count($data['gallery_images']) . ' gallery images for processing', array(), $post_id, null, 'gallery_sync');
            error_log('SourceHub Gallery: Content before remap: ' . substr($data['content'], 0, 500));
            
            $image_id_map = $this->download_gallery_images($post_id, $data['gallery_images']);
            error_log(sprintf('SourceHub Spoke: Gallery images download took %.2f seconds', microtime(true) - $gallery_start));
            
            error_log('SourceHub Gallery: Image ID map: ' . print_r($image_id_map, true));
            
            // Remap gallery IDs in post content
            if (!empty($image_id_map)) {
                SourceHub_Logger::success('Downloaded ' . count($image_id_map) . ' gallery images', array('image_map' => $image_id_map), $post_id, null, 'gallery_sync');
                
                $updated_content = SourceHub_Gallery_Handler::process_galleries(
                    $data['content'],
                    $data['hub_id'],
                    $post_id,
                    $image_id_map
                );
                
                error_log('SourceHub Gallery: Content after remap: ' . substr($updated_content, 0, 500));
                
                // Update post content with remapped gallery IDs
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_content' => $updated_content
                ));
                
                error_log('SourceHub: Remapped gallery IDs for post ' . $post_id);
                SourceHub_Logger::success('Remapped gallery IDs in post content', array(), $post_id, null, 'gallery_sync');
            } else {
                error_log('SourceHub Gallery: WARNING - No images were downloaded, image_id_map is empty!');
                SourceHub_Logger::warning('No gallery images were downloaded - image_id_map is empty', array(), $post_id, null, 'gallery_sync');
            }
        } else {
            error_log('SourceHub Gallery: No gallery_images found in data for post ' . $post_id);
            SourceHub_Logger::info('No gallery_images found in syndication data', array(), $post_id, null, 'gallery_sync');
        }

        // Handle Newspaper theme meta (before publish)
        if (isset($data['newspaper_meta']) && !empty($data['newspaper_meta'])) {
            $allow_override = get_option('sourcehub_allow_newspaper_override', true);
            SourceHub_Newspaper_Integration::set_post_meta($post_id, $data['newspaper_meta'], $allow_override);
        }

        // Handle custom fields (before publish)
        if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $key => $value) {
                update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
            }
        }

        // NEW APPROACH: Keep post as draft, update with Yoast as draft, then publish
        error_log("SourceHub: Post {$post_id} created as draft, now updating with Yoast meta (still as draft)");
        SourceHub_Logger::info(
            'Post created as draft, now updating with Yoast meta',
            array('post_id' => $post_id, 'intended_status' => $intended_status),
            $post_id,
            null,
            'yoast_sync'
        );
        
        // Temporarily change status to draft for the update
        $original_status = $data['status'];
        $data['status'] = 'draft';
        
        // Call update_post_from_data to set Yoast meta while still draft
        $update_result = $this->update_post_from_data($post_id, $data);
        
        if (is_wp_error($update_result)) {
            error_log("SourceHub: Error updating post {$post_id} after creation: " . $update_result->get_error_message());
            SourceHub_Logger::error(
                'Failed to update post after creation: ' . $update_result->get_error_message(),
                array('post_id' => $post_id),
                $post_id,
                null,
                'yoast_sync'
            );
        } else {
            error_log("SourceHub: Successfully updated post {$post_id} with Yoast meta (still draft)");
            SourceHub_Logger::success(
                'Post updated with Yoast meta as draft',
                array('post_id' => $post_id),
                $post_id,
                null,
                'yoast_sync'
            );
        }
        
        // NOW publish the post if intended status was publish
        if ($intended_status !== 'draft') {
            // Get the post to preserve its date
            $post = get_post($post_id);

            // STALE-FUTURE GUARD: prevent WP from silently auto-flipping a 'future'
            // status to 'publish' when the scheduled time has already passed.
            $status_to_set = $intended_status;
            if ($status_to_set === 'future' && !empty($post->post_date_gmt)) {
                $sched_ts = strtotime($post->post_date_gmt . ' UTC');
                if ($sched_ts && $sched_ts <= time()) {
                    SourceHub_Logger::warning(
                        sprintf('Spoke create-path: stale future for post %d (scheduled %s UTC) - coercing to publish',
                            $post_id, $post->post_date_gmt),
                        array('post_id' => $post_id),
                        $post_id,
                        null,
                        'stale_future_guard_spoke'
                    );
                    $status_to_set = 'publish';
                }
            }

            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => $status_to_set,
                'post_date' => $post->post_date,
                'post_date_gmt' => $post->post_date_gmt
            ));
            error_log("SourceHub: Published post {$post_id} with status '{$status_to_set}' after Yoast meta set");
            SourceHub_Logger::success(
                'Post published after Yoast meta set',
                array('post_id' => $post_id, 'status' => $intended_status),
                $post_id,
                null,
                'yoast_sync'
            );
        }

        return $post_id;
    }

    /**
     * Update post from hub data
     *
     * @param int $post_id Local post ID
     * @param array $data Post data from hub
     * @return bool|WP_Error
     */
    private function update_post_from_data($post_id, $data) {
        // Process gallery images FIRST to get the correct image ID mapping
        // This prevents the post from briefly showing with wrong gallery IDs
        $content_to_save = $data['content'];
        
        if (isset($data['gallery_images']) && is_array($data['gallery_images'])) {
            error_log('SourceHub Update: Pre-processing ' . count($data['gallery_images']) . ' gallery images BEFORE post update');
            
            $image_id_map = $this->download_gallery_images($post_id, $data['gallery_images']);
            error_log('SourceHub Update: Image ID map: ' . print_r($image_id_map, true));
            
            // Remap gallery IDs in content before saving
            if (!empty($image_id_map)) {
                $content_to_save = SourceHub_Gallery_Handler::process_galleries(
                    $data['content'],
                    $data['hub_id'],
                    $post_id,
                    $image_id_map
                );
                
                error_log('SourceHub Update: Gallery IDs remapped BEFORE post update');
            } else {
                error_log('SourceHub Update: No image ID map generated - using original content');
            }
        }
        
        // Temporarily disable content filtering to preserve iframes and embeds
        add_filter('wp_kses_allowed_html', array($this, 'allow_all_html_for_sourcehub'), 10, 2);
        
        // STALE-FUTURE GUARD: If the hub sent status='future' but post_date_gmt has
        // already passed (hub WP-cron lag, manual retry, etc.), WordPress core would
        // silently auto-flip the post to 'publish' on save. Detect and coerce
        // explicitly so we (a) log the anomaly and (b) avoid surprising behavior.
        $incoming_status = isset($data['status']) ? sanitize_text_field($data['status']) : 'publish';
        if ($incoming_status === 'future' && !empty($data['date_gmt'])) {
            $sched_ts = strtotime($data['date_gmt'] . ' UTC');
            if ($sched_ts && $sched_ts <= time()) {
                SourceHub_Logger::warning(
                    sprintf('Spoke received stale future status for post %d (scheduled %s UTC, now %s UTC) - coercing to publish to prevent WP auto-flip',
                        $post_id, $data['date_gmt'], gmdate('Y-m-d H:i:s')),
                    array('post_id' => $post_id, 'hub_id' => isset($data['hub_id']) ? $data['hub_id'] : null),
                    $post_id,
                    null,
                    'stale_future_guard_spoke'
                );
                $incoming_status = 'publish';
            }
        }

        // Prepare post data with already-processed gallery content
        $post_data = array(
            'ID' => $post_id,
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => $content_to_save, // Use processed content with correct gallery IDs
            'post_excerpt' => isset($data['excerpt']) ? sanitize_textarea_field($data['excerpt']) : '',
            'post_status' => $incoming_status,
            'post_name' => isset($data['slug']) ? sanitize_title($data['slug']) : '',
            'post_date' => isset($data['date']) ? sanitize_text_field($data['date']) : current_time('mysql'),
            'post_date_gmt' => isset($data['date_gmt']) ? sanitize_text_field($data['date_gmt']) : current_time('mysql', 1),
            'post_modified' => isset($data['modified']) ? sanitize_text_field($data['modified']) : current_time('mysql'),
            'post_modified_gmt' => isset($data['modified_gmt']) ? sanitize_text_field($data['modified_gmt']) : current_time('mysql', 1)
        );

        // Handle author
        if (isset($data['author'])) {
            $author_id = $this->find_or_create_author($data['author']);
            if ($author_id) {
                $post_data['post_author'] = $author_id;
            } else {
                // Fallback to default author if author matching fails
                $default_author = get_option('sourcehub_default_author', 1);
                $post_data['post_author'] = $default_author;
            }
        }

        // Update the post (now with correct gallery IDs already in place)
        $result = wp_update_post($post_data, true);
        
        // Re-enable content filtering
        remove_filter('wp_kses_allowed_html', array($this, 'allow_all_html_for_sourcehub'), 10);
        
        if (is_wp_error($result)) {
            return $result;
        }

        // Update metadata
        update_post_meta($post_id, '_sourcehub_last_sync', current_time('mysql'));

        // Update page template
        if (isset($data['page_template']) && !empty($data['page_template'])) {
            update_post_meta($post_id, '_wp_page_template', sanitize_text_field($data['page_template']));
            error_log('SourceHub: Updated page template for post ' . $post_id . ': ' . $data['page_template']);
        }

        // Update categories
        if (isset($data['categories']) && is_array($data['categories'])) {
            $this->set_post_categories($post_id, $data['categories']);
        }

        // Update tags
        if (isset($data['tags']) && is_array($data['tags'])) {
            $this->set_post_tags($post_id, $data['tags']);
        }

        // Update post format
        if (isset($data['post_format'])) {
            $this->set_post_format($post_id, $data['post_format']);
        }

        // Update featured image
        if (isset($data['featured_image'])) {
            $this->set_featured_image($post_id, $data['featured_image']);
        }

        // Gallery images already processed before post update (see above)
        // No need to process them again here

        // Update Yoast SEO meta
        if (isset($data['yoast_meta']) && !empty($data['yoast_meta'])) {
            $allow_override = get_option('sourcehub_allow_yoast_override', true);
            SourceHub_Yoast_Integration::set_post_meta($post_id, $data['yoast_meta'], $allow_override);
        }

        // Update Newspaper theme meta
        if (isset($data['newspaper_meta']) && !empty($data['newspaper_meta'])) {
            $allow_override = get_option('sourcehub_allow_newspaper_override', true);
            SourceHub_Newspaper_Integration::set_post_meta($post_id, $data['newspaper_meta'], $allow_override);
        }

        // Update custom fields
        if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $key => $value) {
                update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
            }
        }

        return $post_id;
    }

    /**
     * Find or create author
     *
     * @param array $author_data Author data
     * @return int|false User ID or false
     */
    private function find_or_create_author($author_data) {
        if (empty($author_data['email'])) {
            SourceHub_Logger::warning(
                'Author data missing email, using default author',
                array('author_data' => $author_data),
                null,
                null,
                'find_author'
            );
            return get_option('sourcehub_default_author', 1);
        }

        // Try to find existing user by email
        $user = get_user_by('email', $author_data['email']);
        if ($user) {
            SourceHub_Logger::info(
                'Found matching author by email: ' . $author_data['email'],
                array('matched_user_id' => $user->ID, 'display_name' => $user->display_name),
                null,
                null,
                'find_author'
            );
            return $user->ID;
        }

        // Try to find by username as fallback
        if (!empty($author_data['login'])) {
            $user = get_user_by('login', $author_data['login']);
            if ($user) {
                SourceHub_Logger::info(
                    'Found matching author by username: ' . $author_data['login'],
                    array('matched_user_id' => $user->ID, 'display_name' => $user->display_name),
                    null,
                    null,
                    'find_author'
                );
                return $user->ID;
            }
        }

        // No matching user found, use default author
        $default_author = get_option('sourcehub_default_author', 1);
        SourceHub_Logger::info(
            'No matching author found for email: ' . $author_data['email'] . ', using default author',
            array('default_author_id' => $default_author, 'original_author' => $author_data),
            null,
            null,
            'find_author'
        );
        
        return $default_author;
    }

    /**
     * Set post categories
     *
     * @param int $post_id Post ID
     * @param array $categories Categories data
     */
    private function set_post_categories($post_id, $categories) {
        $category_ids = array();

        foreach ($categories as $category) {
            $term = get_term_by('slug', $category['slug'], 'category');
            
            if (!$term) {
                // Create category if it doesn't exist
                $term_data = wp_insert_term(
                    $category['name'],
                    'category',
                    array(
                        'slug' => $category['slug'],
                        'description' => isset($category['description']) ? $category['description'] : ''
                    )
                );
                
                if (!is_wp_error($term_data)) {
                    $category_ids[] = $term_data['term_id'];
                }
            } else {
                $category_ids[] = $term->term_id;
            }
        }

        if (!empty($category_ids)) {
            wp_set_post_categories($post_id, $category_ids);
        }
    }

    /**
     * Set post tags
     *
     * @param int $post_id Post ID
     * @param array $tags Tags data
     */
    private function set_post_tags($post_id, $tags) {
        $tag_names = array();

        foreach ($tags as $tag) {
            $tag_names[] = $tag['name'];
        }

        if (!empty($tag_names)) {
            wp_set_post_tags($post_id, $tag_names);
        }
    }

    /**
     * Set post format
     *
     * @param int $post_id Post ID
     * @param string $post_format Post format (video, audio, gallery, etc.)
     */
    private function set_post_format($post_id, $post_format) {
        if (!empty($post_format)) {
            set_post_format($post_id, $post_format);
        }
    }

    /**
     * Set featured image from URL
     *
     * @param int $post_id Post ID
     * @param array $image_data Image data
     */
    private function set_featured_image($post_id, $image_data) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        if (empty($image_data['url'])) {
            return;
        }

        // Check if we should download images
        $download_images = get_option('sourcehub_download_images', true);
        if (!$download_images) {
            return;
        }

        // Check if this post already has a featured image with the same URL
        $current_thumbnail_id = get_post_thumbnail_id($post_id);
        if ($current_thumbnail_id) {
            $current_image_url = wp_get_attachment_url($current_thumbnail_id);
            $current_image_filename = basename($current_image_url);
            $new_image_filename = basename($image_data['url']);
            
            // If the filenames match, the image is already set - skip download
            if ($current_image_filename === $new_image_filename) {
                error_log('SourceHub Spoke: Featured image already exists for post ' . $post_id . ', skipping download');
                // Still make sure Yoast's OG image points at the existing
                // thumbnail - corrects posts whose OG image was computed before
                // the thumbnail existed. Cheap and idempotent.
                $this->sync_yoast_og_image($post_id, $current_thumbnail_id);
                return;
            }
        }

        // Download image using wp_remote_get. We allow a single retry on a
        // *connection* error (not on an HTTP status, which won't fix itself) to
        // ride out a transient network blip. Per-attempt timeout is kept low so
        // the worst-case total (2 x 45s) does not exceed the previous single
        // 90s attempt - we don't want to lengthen spoke processing, which would
        // widen the window for the hub-side duplicate-sync race.
        $max_attempts = 2;
        $response = null;
        $response_code = 0;
        $last_error = '';

        for ($attempt = 1; $attempt <= $max_attempts; $attempt++) {
            $response = wp_remote_get($image_data['url'], array(
                'timeout' => 45,
                'sslverify' => false
            ));

            // Connection-level error - worth one retry.
            if (is_wp_error($response)) {
                $last_error = $response->get_error_message();
                error_log(sprintf('SourceHub Spoke: Featured image download attempt %d/%d failed for post %d: %s',
                    $attempt, $max_attempts, $post_id, $last_error));
                $response = null;
                continue;
            }

            // Got a response - a non-200 status won't change on retry, so stop.
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code !== 200) {
                $last_error = 'HTTP status ' . $response_code;
                $response = null;
            }
            break;
        }

        if (!$response) {
            SourceHub_Logger::error(
                'Failed to download featured image: ' . $last_error,
                array('url' => $image_data['url'], 'error' => $last_error),
                $post_id,
                null,
                'set_featured_image'
            );
            return;
        }
        
        $image_data_content = wp_remote_retrieve_body($response);
        if (empty($image_data_content)) {
            SourceHub_Logger::error(
                'Downloaded featured image is empty',
                array('url' => $image_data['url']),
                $post_id,
                null,
                'set_featured_image'
            );
            return;
        }
        
        // Create temporary file
        $temp_file = wp_tempnam();
        if (!$temp_file) {
            SourceHub_Logger::error(
                'Failed to create temporary file for featured image',
                array('url' => $image_data['url']),
                $post_id,
                null,
                'set_featured_image'
            );
            return;
        }
        
        // Write image data to temp file
        $bytes_written = file_put_contents($temp_file, $image_data_content);
        if ($bytes_written === false) {
            SourceHub_Logger::error(
                'Failed to write featured image data to temp file',
                array('url' => $image_data['url']),
                $post_id,
                null,
                'set_featured_image'
            );
            @unlink($temp_file);
            return;
        }

        // Upload and attach
        $file_array = array(
            'name' => basename($image_data['url']),
            'tmp_name' => $temp_file
        );

        $attachment_id = media_handle_sideload($file_array, $post_id);
        
        if (is_wp_error($attachment_id)) {
            SourceHub_Logger::error(
                'Failed to attach featured image: ' . $attachment_id->get_error_message(),
                array('url' => $image_data['url'], 'error' => $attachment_id->get_error_message()),
                $post_id,
                null,
                'set_featured_image'
            );
            @unlink($temp_file);
            return;
        }

        // Set image metadata
        if (!empty($image_data['title'])) {
            wp_update_post(array(
                'ID' => $attachment_id,
                'post_title' => sanitize_text_field($image_data['title'])
            ));
        }

        if (!empty($image_data['alt'])) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($image_data['alt']));
        }

        if (!empty($image_data['caption'])) {
            wp_update_post(array(
                'ID' => $attachment_id,
                'post_excerpt' => sanitize_text_field($image_data['caption'])
            ));
        }

        if (!empty($image_data['description'])) {
            wp_update_post(array(
                'ID' => $attachment_id,
                'post_content' => wp_kses_post($image_data['description'])
            ));
        }

        set_post_thumbnail($post_id, $attachment_id);

        // Point Yoast's OpenGraph/social image at this attachment. Yoast only
        // falls back to the featured image when its OG image is computed *after*
        // the thumbnail exists; because syndication attaches the image after the
        // post is already saved/published, that fallback frequently misses and
        // social platforms get the site default. Setting it explicitly (and
        // refreshing the indexable) makes the behaviour deterministic.
        $this->sync_yoast_og_image($post_id, $attachment_id);

        SourceHub_Logger::success(
            'Featured image set successfully',
            array('attachment_id' => $attachment_id, 'post_id' => $post_id),
            $post_id,
            null,
            'set_featured_image'
        );
    }

    /**
     * Point Yoast's OpenGraph image at the given attachment and refresh the
     * stored indexable so the change takes effect immediately.
     *
     * Safe no-op when Yoast is not active or the attachment is invalid.
     *
     * @param int $post_id       Post ID
     * @param int $attachment_id Attachment ID to use as the OG image
     */
    private function sync_yoast_og_image($post_id, $attachment_id) {
        if (empty($attachment_id)) {
            return;
        }

        if (!class_exists('SourceHub_Yoast_Integration') || !SourceHub_Yoast_Integration::is_yoast_active()) {
            return;
        }

        $image_url = wp_get_attachment_image_url($attachment_id, 'full');
        if (!$image_url) {
            return;
        }

        update_post_meta($post_id, '_yoast_wpseo_opengraph-image', esc_url_raw($image_url));
        update_post_meta($post_id, '_yoast_wpseo_opengraph-image-id', (int) $attachment_id);

        SourceHub_Logger::info(
            'Set Yoast OpenGraph image from featured image',
            array('attachment_id' => (int) $attachment_id, 'image_url' => $image_url),
            $post_id,
            null,
            'yoast_og_image'
        );

        $this->refresh_yoast_indexable($post_id);
    }

    /**
     * Force Yoast to rebuild its indexable for a post.
     *
     * Yoast caches the computed OG image in the wp_yoast_indexable table. When
     * we attach the featured image out-of-band (after the post is saved), that
     * cached value can be stale (often the site default). Deleting the indexable
     * makes Yoast regenerate it lazily on next access with the correct image.
     *
     * Fully guarded - any failure is logged and ignored.
     *
     * @param int $post_id Post ID
     */
    private function refresh_yoast_indexable($post_id) {
        clean_post_cache($post_id);

        if (!function_exists('YoastSEO')) {
            return;
        }

        try {
            $repository = YoastSEO()->classes->get(\Yoast\WP\SEO\Repositories\Indexable_Repository::class);
            $indexable = $repository->find_by_id_and_type($post_id, 'post', false);
            if ($indexable && $indexable->id) {
                $indexable->delete();
                error_log('SourceHub Spoke: Refreshed Yoast indexable for post ' . $post_id);
            }
        } catch (\Throwable $e) {
            error_log('SourceHub Spoke: Yoast indexable refresh skipped for post ' . $post_id . ': ' . $e->getMessage());
        }
    }

    /**
     * Download gallery images and create ID mapping
     *
     * @param int $post_id Spoke post ID
     * @param array $gallery_images Array of gallery image data from hub
     * @return array Map of hub image IDs to spoke image data
     */
    private function download_gallery_images($post_id, $gallery_images) {
        $image_map = array();
        
        foreach ($gallery_images as $image_data) {
            error_log('SourceHub Gallery: Processing image data: ' . print_r($image_data, true));
            $hub_id = isset($image_data['id']) ? $image_data['id'] : 'UNKNOWN';
            $image_url = isset($image_data['url']) ? $image_data['url'] : 'MISSING';
            error_log('SourceHub Gallery: Hub ID: ' . $hub_id . ', URL: ' . $image_url);
            
            if (empty($image_data['url'])) {
                error_log('SourceHub Gallery: Skipping image ' . $hub_id . ' - URL is empty or missing');
                continue;
            }
            
            // Download the image
            $temp_file = download_url($image_data['url']);
            
            if (is_wp_error($temp_file)) {
                error_log('SourceHub Gallery: Failed to download image ' . $hub_id . ': ' . $temp_file->get_error_message());
                continue;
            }
            
            // Prepare file array for media_handle_sideload
            $file_array = array(
                'name' => $image_data['filename'],
                'tmp_name' => $temp_file
            );
            
            $attachment_id = media_handle_sideload($file_array, $post_id);
            
            if (is_wp_error($attachment_id)) {
                error_log('SourceHub Gallery: Failed to attach image ' . $hub_id . ': ' . $attachment_id->get_error_message());
                @unlink($temp_file);
                continue;
            }
            
            // Set image metadata
            if (!empty($image_data['title'])) {
                wp_update_post(array(
                    'ID' => $attachment_id,
                    'post_title' => sanitize_text_field($image_data['title'])
                ));
            }
            
            if (!empty($image_data['alt'])) {
                update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($image_data['alt']));
            }
            
            if (!empty($image_data['caption'])) {
                wp_update_post(array(
                    'ID' => $attachment_id,
                    'post_excerpt' => sanitize_text_field($image_data['caption'])
                ));
            }
            
            if (!empty($image_data['description'])) {
                wp_update_post(array(
                    'ID' => $attachment_id,
                    'post_content' => wp_kses_post($image_data['description'])
                ));
            }
            
            // Map hub ID to spoke ID and URLs
            $image_map[$hub_id] = array(
                'spoke_id' => $attachment_id,
                'hub_url' => $image_data['url'],
                'spoke_url' => wp_get_attachment_url($attachment_id)
            );
            
            error_log("SourceHub Gallery: Downloaded image - Hub ID: {$hub_id} -> Spoke ID: {$attachment_id}");
        }
        
        error_log('SourceHub Gallery: Downloaded ' . count($image_map) . ' gallery images for post ' . $post_id);
        
        return $image_map;
    }

    /**
     * Add canonical tags to head
     */
    public function add_canonical_tags() {
        if (!is_singular()) {
            return;
        }

        global $post;
        
        $hub_id = get_post_meta($post->ID, '_sourcehub_hub_id', true);
        $hub_url = get_post_meta($post->ID, '_sourcehub_origin_url', true);
        
        if ($hub_id && $hub_url) {
            // Let Yoast handle this if it's active
            if (!SourceHub_Yoast_Integration::is_yoast_active()) {
                $canonical_url = SourceHub_Yoast_Integration::generate_canonical_url($post->ID, $hub_url, $hub_id);
                echo '<link rel="canonical" href="' . esc_url($canonical_url) . '" />' . "\n";
            }
        }
    }

    /**
     * Maybe add attribution to content
     *
     * @param string $content Post content
     * @return string
     */
    public function maybe_add_attribution($content) {
        if (!is_singular() || !in_the_loop() || !is_main_query()) {
            return $content;
        }

        global $post;
        
        $hub_id = get_post_meta($post->ID, '_sourcehub_hub_id', true);
        $hub_url = get_post_meta($post->ID, '_sourcehub_origin_url', true);
        
        if (!$hub_id || !$hub_url) {
            return $content;
        }

        $show_attribution = get_option('sourcehub_show_attribution', true);
        if (!$show_attribution) {
            return $content;
        }

        $attribution_text = get_option('sourcehub_attribution_text', __('Originally published at %s', 'sourcehub'));
        $hub_name = get_option('sourcehub_hub_name', parse_url($hub_url, PHP_URL_HOST));
        
        $attribution = sprintf(
            '<div class="sourcehub-attribution"><p><em>%s</em></p></div>',
            sprintf($attribution_text, '<a href="' . esc_url($hub_url) . '">' . esc_html($hub_name) . '</a>')
        );

        return $content . $attribution;
    }

    /**
     * Get last activity timestamp
     *
     * @return string|null
     */
    private function get_last_activity() {
        $logs = SourceHub_Database::get_logs(array(
            'limit' => 1,
            'order' => 'DESC'
        ));

        return !empty($logs) ? $logs[0]->created_at : null;
    }

    /**
     * Queue a sync job for async processing
     *
     * @param array $data Post data
     * @param string $action 'create' or 'update'
     * @return string|WP_Error Job ID or error
     */
    private function queue_sync_job($data, $action) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_sync_jobs';
        $job_id = wp_generate_password(32, false);
        
        $result = $wpdb->insert(
            $table,
            array(
                'job_id' => $job_id,
                'hub_post_id' => $data['hub_id'],
                'hub_url' => $data['hub_url'],
                'action' => $action,
                'payload' => json_encode($data),
                'status' => 'pending',
                'created_at' => current_time('mysql')
            ),
            array('%s', '%d', '%s', '%s', '%s', '%s', '%s')
        );
        
        if ($result === false) {
            return new WP_Error('queue_failed', 'Failed to queue sync job: ' . $wpdb->last_error);
        }
        
        return $job_id;
    }

    /**
     * Process a queued sync job
     *
     * @param string $job_id Job ID
     */
    public function process_sync_job($job_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_sync_jobs';
        $start_time = microtime(true);
        
        // Get job
        $job = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE job_id = %s AND status = 'pending'",
            $job_id
        ));
        
        if (!$job) {
            error_log("SourceHub: Job $job_id not found or already processed");
            return;
        }
        
        // Mark as processing
        $wpdb->update(
            $table,
            array('status' => 'processing', 'started_at' => current_time('mysql')),
            array('job_id' => $job_id),
            array('%s', '%s'),
            array('%s')
        );
        
        error_log("SourceHub: Processing job $job_id ({$job->action})");
        
        // Decode payload
        $data = json_decode($job->payload, true);
        
        // Log job processing start
        SourceHub_Logger::info(
            sprintf('Job processing started: "%s"', $data['title'] ?? 'Unknown'),
            array(
                'job_id' => $job_id,
                'action' => $job->action,
                'hub_id' => $data['hub_id'] ?? 'unknown'
            ),
            null,
            null,
            'job_processing'
        );
        
        // Process based on action.
        // Stage 2: serialize concurrent jobs for the same (hub_url, hub_id) so a
        // duplicate or competing delivery can never race the create.
        $lock_name = 'sourcehub_upsert_' . md5($data['hub_url'] . '|' . $data['hub_id']);
        $have_lock = false;
        try {
            // GET_LOCK is connection-scoped and blocks other async-job DB
            // connections processing the same post (up to 10s). Released in the
            // finally block below. Best-effort: if we can't get it we still run
            // the uncached upsert, which is idempotent anyway.
            $have_lock = ((int) $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, %d)', $lock_name, 10)) === 1);

            // Idempotent upsert. Resolve the existing spoke post with an UNCACHED
            // query (WP_Query is object-cached on managed hosts - the source of
            // the old race_prevented duplicates), then create or fully
            // enrich-update. The job 'action' no longer drives routing: a
            // duplicate 'create' for an existing post becomes a full update, so
            // the featured image + Yoast always land (this is the fix for the
            // bare-publish race - the old code flipped status only and skipped
            // enrichment).
            $existing_id = $this->find_existing_post_uncached($data['hub_id'], $data['hub_url']);

            // Lock safety: if we could NOT acquire the lock AND no post exists yet,
            // another job is mid-create but hasn't committed. Blind-creating here
            // is the only residual duplicate window, so refuse it - throw and let
            // the job be retried; by then the other job has committed and we
            // upsert into its post. We only ever reach create_post_from_data()
            // below while actually holding the lock.
            if (!$have_lock && !$existing_id) {
                throw new Exception(sprintf(
                    'Upsert lock not acquired for hub_id %s and no committed post yet - deferring to retry to avoid a duplicate',
                    $data['hub_id']
                ));
            }

            if ($existing_id) {
                SourceHub_Logger::info(
                    sprintf('Idempotent upsert: existing spoke post %d for hub_id %s - applying full update', $existing_id, $data['hub_id']),
                    array(
                        'hub_id' => $data['hub_id'],
                        'local_id' => $existing_id,
                        'job_id' => $job_id,
                        'requested_action' => $job->action
                    ),
                    $existing_id,
                    null,
                    'sync_upsert'
                );
                $post_id = $this->update_post_from_data($existing_id, $data);
            } else {
                $post_id = $this->create_post_from_data($data);
            }
            
            if (is_wp_error($post_id)) {
                throw new Exception($post_id->get_error_message());
            }
            
            $processing_time = microtime(true) - $start_time;
            
            // Mark as completed
            $wpdb->update(
                $table,
                array(
                    'status' => 'completed',
                    'result' => json_encode(array('post_id' => $post_id)),
                    'completed_at' => current_time('mysql')
                ),
                array('job_id' => $job_id),
                array('%s', '%s', '%s'),
                array('%s')
            );
            
            error_log(sprintf("SourceHub: Job $job_id completed in %.2f seconds (post_id: $post_id)", $processing_time));
            
            SourceHub_Logger::success(
                sprintf('Async %s completed for "%s" (%.2fs)', $job->action, $data['title'], $processing_time),
                array('hub_id' => $data['hub_id'], 'local_id' => $post_id, 'processing_time' => $processing_time, 'job_id' => $job_id),
                $post_id,
                null,
                'async_' . $job->action
            );
            
            // Notify hub of completion
            $this->notify_hub_completion($job_id, $data['hub_url'], $data['hub_id'], $post_id);
            
        } catch (Exception $e) {
            $processing_time = microtime(true) - $start_time;
            
            // Mark as failed
            $wpdb->update(
                $table,
                array(
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'completed_at' => current_time('mysql')
                ),
                array('job_id' => $job_id),
                array('%s', '%s', '%s'),
                array('%s')
            );
            
            error_log(sprintf("SourceHub: Job $job_id failed after %.2f seconds: %s", $processing_time, $e->getMessage()));
            
            SourceHub_Logger::error(
                sprintf('Async %s failed for "%s": %s', $job->action, $data['title'], $e->getMessage()),
                array('hub_id' => $data['hub_id'], 'error' => $e->getMessage(), 'job_id' => $job_id),
                null,
                null,
                'async_' . $job->action
            );
            
            // Notify hub of failure
            $this->notify_hub_completion($job_id, $data['hub_url'], $data['hub_id'], null, $e->getMessage());
        } finally {
            // Stage 2: always release the upsert lock, success or failure.
            if ($have_lock) {
                $wpdb->query($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock_name));
            }
        }
    }

    /**
     * Cron callback: scan for SourceHub-created posts that have been stuck as drafts
     * for longer than the threshold and report them back to the hub.
     *
     * This is the 3rd-tier safety net (belt + suspenders) that catches posts the
     * 2-step syndication failed to publish for any reason.
     */
    public function check_stuck_drafts() {
        $threshold_minutes = (int) apply_filters('sourcehub_stuck_draft_threshold_minutes', 10);
        $cutoff_ts = time() - ($threshold_minutes * MINUTE_IN_SECONDS);

        // Can't filter by _sourcehub_received_at in the SQL meta_query because that
        // value is stored in WP local time (via current_time('mysql')) and MySQL
        // datetime comparison would mix timezones. Filter broadly in SQL, then
        // compare ages in PHP using get_gmt_from_date() for correct tz handling.
        $candidates = get_posts(array(
            'post_type'      => 'any',
            'post_status'    => 'draft',
            'posts_per_page' => 100,
            'no_found_rows'  => true,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'from_SourceHub',
                    'value'   => '1',
                    'compare' => '=',
                ),
                array(
                    'key'     => '_sourcehub_stuck_notified',
                    'compare' => 'NOT EXISTS',
                ),
            ),
        ));

        if (empty($candidates)) {
            return;
        }

        $reported = 0;
        foreach ($candidates as $post) {
            $received_at = get_post_meta($post->ID, '_sourcehub_received_at', true);
            if (empty($received_at)) {
                continue;
            }

            // FALSE-POSITIVE GUARD: Skip drafts intentionally scheduled for future
            // publish. The hub creates a draft on the spoke immediately at scheduling
            // time but only sends the publish UPDATE when the future->publish
            // transition fires (could be days later). Such drafts are NOT stuck.
            if (!empty($post->post_date_gmt) && $post->post_date_gmt !== '0000-00-00 00:00:00') {
                $sched_ts = strtotime($post->post_date_gmt . ' UTC');
                if ($sched_ts && $sched_ts > time()) {
                    continue; // Scheduled for future, draft state is intentional
                }
            }

            // Convert the locally-stored timestamp to GMT, then to unix seconds
            $received_ts = strtotime(get_gmt_from_date($received_at) . ' UTC');
            if (!$received_ts || $received_ts > $cutoff_ts) {
                continue; // Not yet past the threshold
            }

            $hub_url = get_post_meta($post->ID, '_sourcehub_origin_url', true);
            $hub_id  = (int) get_post_meta($post->ID, '_sourcehub_hub_id', true);

            if (empty($hub_url) || empty($hub_id)) {
                // Marker present but missing origin info - mark to suppress repeated scans
                update_post_meta($post->ID, '_sourcehub_stuck_notified', current_time('mysql'));
                continue;
            }

            $age_minutes = (int) round((time() - $received_ts) / MINUTE_IN_SECONDS);
            $this->notify_hub_stuck_draft($post, $hub_url, $hub_id, $threshold_minutes, $age_minutes);

            // Mark notified so we don't spam the hub on every cron tick
            update_post_meta($post->ID, '_sourcehub_stuck_notified', current_time('mysql'));
            $reported++;

            if ($reported >= 25) {
                break; // Cap per run
            }
        }

        if ($reported > 0) {
            error_log(sprintf('SourceHub Spoke: Stuck-draft sweep reported %d draft(s) older than %d minutes',
                $reported, $threshold_minutes));
        }
    }

    /**
     * POST a stuck-draft report to the hub's /report-stuck-draft endpoint.
     *
     * @param WP_Post  $post              The stuck spoke post
     * @param string   $hub_url           The hub site URL (from _sourcehub_origin_url)
     * @param int      $hub_post_id       The original hub post ID (from _sourcehub_hub_id)
     * @param int      $threshold_minutes The threshold age that triggered this report
     * @param int|null $age_minutes       Pre-computed age in minutes (timezone-corrected)
     */
    private function notify_hub_stuck_draft($post, $hub_url, $hub_post_id, $threshold_minutes, $age_minutes = null) {
        $endpoint = trailingslashit($hub_url) . 'wp-json/sourcehub/v1/report-stuck-draft';

        $received_at = get_post_meta($post->ID, '_sourcehub_received_at', true);
        if ($age_minutes === null && $received_at) {
            // Fallback: compute via GMT-aware conversion (WP local time -> GMT -> unix)
            $received_ts = strtotime(get_gmt_from_date($received_at) . ' UTC');
            if ($received_ts) {
                $age_minutes = (int) round((time() - $received_ts) / MINUTE_IN_SECONDS);
            }
        }

        $payload = array(
            'hub_post_id'       => $hub_post_id,
            'spoke_post_id'     => $post->ID,
            'spoke_url'         => home_url(),
            'spoke_post_title'  => $post->post_title,
            'spoke_edit_url'    => get_edit_post_link($post->ID, 'raw'),
            'received_at'       => $received_at,
            'age_minutes'       => $age_minutes,
            'threshold_minutes' => $threshold_minutes,
            'reported_at'       => current_time('mysql'),
        );

        error_log(sprintf('SourceHub Spoke: Reporting stuck draft to hub - post %d (hub_id %d), age %s min',
            $post->ID, $hub_post_id, $age_minutes !== null ? $age_minutes : 'unknown'));

        $response = wp_remote_post($endpoint, array(
            'headers' => array('Content-Type' => 'application/json'),
            'body'    => wp_json_encode($payload),
            'timeout' => 15,
        ));

        if (is_wp_error($response)) {
            SourceHub_Logger::warning(
                'Failed to report stuck draft to hub',
                array(
                    'hub_url' => $hub_url,
                    'spoke_post_id' => $post->ID,
                    'error' => $response->get_error_message(),
                ),
                $post->ID,
                null,
                'stuck_draft_report_failed'
            );
            return;
        }

        $code = wp_remote_retrieve_response_code($response);
        if ($code !== 200) {
            SourceHub_Logger::warning(
                'Hub returned non-200 for stuck-draft report',
                array(
                    'hub_url' => $hub_url,
                    'spoke_post_id' => $post->ID,
                    'response_code' => $code,
                    'response_body' => wp_remote_retrieve_body($response),
                ),
                $post->ID,
                null,
                'stuck_draft_report_failed'
            );
            return;
        }

        SourceHub_Logger::warning(
            sprintf('Reported stuck draft "%s" to hub (age %s min)',
                $post->post_title,
                $age_minutes !== null ? $age_minutes : 'unknown'),
            $payload,
            $post->ID,
            null,
            'stuck_draft_reported'
        );
    }

    /**
     * Notify hub of job completion
     *
     * @param string $job_id Job ID
     * @param string $hub_url Hub URL
     * @param int $hub_post_id Hub post ID
     * @param int|null $spoke_post_id Spoke post ID (null if failed)
     * @param string|null $error_message Error message (if failed)
     */
    private function notify_hub_completion($job_id, $hub_url, $hub_post_id, $spoke_post_id = null, $error_message = null) {
        $callback_url = trailingslashit($hub_url) . 'wp-json/sourcehub/v1/sync-complete';
        
        // Include the final permalink for the spoke post so the hub can display
        // a direct "View" link in the syndication meta box.
        $spoke_post_url = '';
        if ($spoke_post_id) {
            $permalink = get_permalink($spoke_post_id);
            if ($permalink) {
                $spoke_post_url = $permalink;
            }
        }

        $payload = array(
            'job_id' => $job_id,
            'hub_post_id' => $hub_post_id,
            'spoke_post_id' => $spoke_post_id,
            'spoke_post_url' => $spoke_post_url,
            'status' => $spoke_post_id ? 'completed' : 'failed',
            'error_message' => $error_message,
            'spoke_url' => home_url()
        );
        
        error_log(sprintf('SourceHub Spoke: Notifying hub of completion - URL: %s, Payload: %s', 
            $callback_url, json_encode($payload)));
        
        // CRITICAL: Wake up hub before sending callback
        // Hub may have gone to sleep since it sent the syndication request
        // This ensures hub is awake to receive and process the callback
        $hub_base_url = rtrim($hub_url, '/');
        error_log('SourceHub Spoke: Waking up hub before callback: ' . $hub_base_url);
        
        $wake_response = wp_remote_get($hub_base_url . '/wp-json/', array(
            'timeout' => 10,
            'headers' => array(
                'User-Agent' => 'SourceHub-Spoke-Wake-Hub/1.0'
            )
        ));
        
        if (!is_wp_error($wake_response) && wp_remote_retrieve_response_code($wake_response) === 200) {
            error_log('SourceHub Spoke: Hub is awake and ready for callback');
        } else {
            error_log('SourceHub Spoke: Hub wake-up check failed, proceeding with callback anyway');
        }
        
        // Brief pause to ensure hub is fully responsive
        sleep(1);
        
        $response = wp_remote_post($callback_url, array(
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($payload),
            'timeout' => 30 // Increased from 10s to handle slow hub responses
        ));
        
        $callback_failed = false;
        
        if (is_wp_error($response)) {
            $callback_failed = true;
            error_log("SourceHub Spoke: Failed to notify hub: " . $response->get_error_message());
            
            SourceHub_Logger::error(
                'Failed to notify hub of completion',
                array(
                    'hub_url' => $hub_url,
                    'job_id' => $job_id,
                    'error' => $response->get_error_message()
                ),
                null,
                null,
                'hub_notification_failed'
            );
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);
            
            error_log(sprintf('SourceHub Spoke: Hub notification response - Code: %d, Body: %s', 
                $response_code, $response_body));
            
            if ($response_code !== 200) {
                $callback_failed = true;
                SourceHub_Logger::warning(
                    'Hub notification returned non-200 status',
                    array(
                        'hub_url' => $hub_url,
                        'job_id' => $job_id,
                        'response_code' => $response_code,
                        'response_body' => $response_body
                    ),
                    null,
                    null,
                    'hub_notification_error'
                );
            } else {
                SourceHub_Logger::info(
                    'Successfully notified hub of completion',
                    array(
                        'hub_url' => $hub_url,
                        'job_id' => $job_id,
                        'spoke_post_id' => $spoke_post_id
                    ),
                    $spoke_post_id,
                    null,
                    'hub_notified'
                );
            }
        }
        
        // If callback failed, schedule retry with Action Scheduler
        if ($callback_failed) {
            // Check if retry is already scheduled to prevent duplicates
            $existing_retries = as_get_scheduled_actions(array(
                'hook' => 'sourcehub_retry_hub_callback',
                'args' => array('job_id' => $job_id),
                'status' => 'pending'
            ));
            
            if (empty($existing_retries)) {
                as_schedule_single_action(
                    time() + 30, // Retry in 30 seconds
                    'sourcehub_retry_hub_callback',
                    array(
                        'payload' => $payload,
                        'callback_url' => $callback_url,
                        'attempt' => 1,
                        'max_attempts' => 5
                    )
                );
                
                SourceHub_Logger::warning(
                    'Hub callback failed - scheduled for retry in 30 seconds',
                    array(
                        'job_id' => $job_id,
                        'attempt' => 1,
                        'max_attempts' => 5
                    ),
                    $spoke_post_id,
                    null,
                    'callback_retry_scheduled'
                );
            }
        }
    }

    /**
     * Handle callback retry via Action Scheduler
     *
     * @param array $payload Callback payload
     * @param string $callback_url Hub callback URL
     * @param int $attempt Current attempt number
     * @param int $max_attempts Maximum retry attempts
     */
    public function handle_callback_retry($payload, $callback_url, $attempt, $max_attempts) {
        error_log(sprintf('SourceHub Spoke: Retry attempt %d/%d for job %s', 
            $attempt, $max_attempts, $payload['job_id']));
        
        $response = wp_remote_post($callback_url, array(
            'headers' => array('Content-Type' => 'application/json'),
            'body' => json_encode($payload),
            'timeout' => 30 // Increased from 10s to handle slow hub responses
        ));
        
        $retry_failed = false;
        
        if (is_wp_error($response)) {
            $retry_failed = true;
            error_log(sprintf('SourceHub Spoke: Retry attempt %d failed: %s', 
                $attempt, $response->get_error_message()));
            
            SourceHub_Logger::warning(
                sprintf('Hub callback retry attempt %d failed', $attempt),
                array(
                    'job_id' => $payload['job_id'],
                    'attempt' => $attempt,
                    'error' => $response->get_error_message()
                ),
                null,
                null,
                'callback_retry_failed'
            );
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            
            if ($response_code !== 200) {
                $retry_failed = true;
                error_log(sprintf('SourceHub Spoke: Retry attempt %d returned code %d', 
                    $attempt, $response_code));
                
                SourceHub_Logger::warning(
                    sprintf('Hub callback retry attempt %d returned non-200 status', $attempt),
                    array(
                        'job_id' => $payload['job_id'],
                        'attempt' => $attempt,
                        'response_code' => $response_code
                    ),
                    null,
                    null,
                    'callback_retry_failed'
                );
            } else {
                // Success!
                error_log(sprintf('SourceHub Spoke: Retry attempt %d succeeded for job %s', 
                    $attempt, $payload['job_id']));
                
                SourceHub_Logger::success(
                    sprintf('Hub callback succeeded on retry attempt %d', $attempt),
                    array(
                        'job_id' => $payload['job_id'],
                        'attempt' => $attempt,
                        'spoke_post_id' => $payload['spoke_post_id']
                    ),
                    $payload['spoke_post_id'],
                    null,
                    'callback_retry_success'
                );
            }
        }
        
        // If retry failed and we haven't hit max attempts, schedule another retry
        if ($retry_failed && $attempt < $max_attempts) {
            // Exponential backoff: 30s, 60s, 120s, 240s, 300s (max 5 minutes)
            $delay = min(300, 30 * pow(2, $attempt - 1));
            
            as_schedule_single_action(
                time() + $delay,
                'sourcehub_retry_hub_callback',
                array(
                    'hub_url' => $hub_url,
                    'payload' => $payload,
                    'attempt' => $attempt + 1
                ),
                'sourcehub'
            );
            
            SourceHub_Logger::info(
                sprintf('Scheduled retry attempt %d in %d seconds', $attempt + 1, $delay),
                array('job_id' => $payload['job_id'], 'delay' => $delay),
                $payload['spoke_post_id'],
                null,
                'callback_retry_scheduled'
            );
        }
    }

    /**
     * Trash a post on the spoke site
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function trash_post($request) {
        $spoke_post_id = $request->get_param('spoke_post_id');
        $hub_id = $request->get_param('hub_id');
        
        // Move post to trash
        $result = wp_trash_post($spoke_post_id);
        
        if ($result) {
            SourceHub_Logger::success(
                sprintf('Post %d trashed (hub ID: %d)', $spoke_post_id, $hub_id),
                array('spoke_post_id' => $spoke_post_id, 'hub_id' => $hub_id),
                $spoke_post_id,
                null,
                'trash'
            );
            
            return new WP_REST_Response(array(
                'success' => true,
                'message' => 'Post moved to trash',
                'spoke_post_id' => $spoke_post_id
            ), 200);
        } else {
            SourceHub_Logger::error(
                sprintf('Failed to trash post %d (hub ID: %d)', $spoke_post_id, $hub_id),
                array('spoke_post_id' => $spoke_post_id, 'hub_id' => $hub_id),
                $spoke_post_id,
                null,
                'trash'
            );
            
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Failed to trash post'
            ), 500);
        }
    }

    /**
     * Restore a post from trash on the spoke site
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function untrash_post($request) {
        $spoke_post_id = $request->get_param('spoke_post_id');
        $hub_id = $request->get_param('hub_id');
        
        // Restore post from trash
        $result = wp_untrash_post($spoke_post_id);
        
        if ($result) {
            SourceHub_Logger::success(
                sprintf('Post %d restored from trash (hub ID: %d)', $spoke_post_id, $hub_id),
                array('spoke_post_id' => $spoke_post_id, 'hub_id' => $hub_id),
                $spoke_post_id,
                null,
                'untrash'
            );
            
            return new WP_REST_Response(array(
                'success' => true,
                'message' => 'Post restored from trash',
                'spoke_post_id' => $spoke_post_id
            ), 200);
        } else {
            SourceHub_Logger::error(
                sprintf('Failed to restore post %d (hub ID: %d)', $spoke_post_id, $hub_id),
                array('spoke_post_id' => $spoke_post_id, 'hub_id' => $hub_id),
                $spoke_post_id,
                null,
                'untrash'
            );
            
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Failed to restore post'
            ), 500);
        }
    }

    /**
     * Permanently delete a post on the spoke site
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function delete_post($request) {
        $spoke_post_id = $request->get_param('spoke_post_id');
        $hub_id = $request->get_param('hub_id');
        $force = $request->get_param('force') ?? true;
        
        // Permanently delete post
        $result = wp_delete_post($spoke_post_id, $force);
        
        if ($result) {
            SourceHub_Logger::success(
                sprintf('Post %d permanently deleted (hub ID: %d)', $spoke_post_id, $hub_id),
                array('spoke_post_id' => $spoke_post_id, 'hub_id' => $hub_id),
                $spoke_post_id,
                null,
                'delete'
            );
            
            return new WP_REST_Response(array(
                'success' => true,
                'message' => 'Post permanently deleted',
                'spoke_post_id' => $spoke_post_id
            ), 200);
        } else {
            SourceHub_Logger::error(
                sprintf('Failed to delete post %d (hub ID: %d)', $spoke_post_id, $hub_id),
                array('spoke_post_id' => $spoke_post_id, 'hub_id' => $hub_id),
                $spoke_post_id,
                null,
                'delete'
            );
            
            return new WP_REST_Response(array(
                'success' => false,
                'message' => 'Failed to delete post'
            ), 500);
        }
    }
}
