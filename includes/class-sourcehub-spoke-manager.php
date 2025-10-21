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
        add_action('wp_head', array($this, 'add_canonical_tags'));
        add_filter('the_content', array($this, 'maybe_add_attribution'), 999);
        add_action('rest_api_init', array($this, 'register_rest_routes'));
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

        if (empty($api_key) || empty($stored_key)) {
            return false;
        }

        return hash_equals($stored_key, $api_key);
    }

    /**
     * Receive new post from hub
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function receive_post($request) {
        try {
            $data = $request->get_json_params();
            
            // Validate required data
            $validation = $this->validate_post_data($data);
            if (!$validation['valid']) {
                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => implode(', ', $validation['errors'])
                ), 400);
            }

            // Create the post
            $post_id = $this->create_post_from_data($data);
            
            if (is_wp_error($post_id)) {
                SourceHub_Logger::error(
                    'Failed to create post from hub data: ' . $post_id->get_error_message(),
                    $data,
                    null,
                    null,
                    'receive_post'
                );

                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => $post_id->get_error_message()
                ), 500);
            }

            SourceHub_Logger::success(
                sprintf('Successfully received post "%s" from hub', $data['title']),
                array('hub_id' => $data['hub_id'], 'local_id' => $post_id),
                $post_id,
                null,
                'receive_post'
            );

            return new WP_REST_Response(array(
                'success' => true,
                'message' => __('Post created successfully', 'sourcehub'),
                'post_id' => $post_id,
                'post_url' => get_permalink($post_id)
            ), 201);

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

            // Update the post
            $result = $this->update_post_from_data($existing_post->ID, $data);
            
            if (is_wp_error($result)) {
                SourceHub_Logger::error(
                    'Failed to update post from hub data: ' . $result->get_error_message(),
                    $data,
                    $existing_post->ID,
                    null,
                    'update_post'
                );

                return new WP_REST_Response(array(
                    'success' => false,
                    'message' => $result->get_error_message()
                ), 500);
            }

            SourceHub_Logger::success(
                sprintf('Successfully updated post "%s" from hub', $data['title']),
                array('hub_id' => $data['hub_id'], 'local_id' => $existing_post->ID),
                $existing_post->ID,
                null,
                'update_post'
            );

            return new WP_REST_Response(array(
                'success' => true,
                'message' => __('Post updated successfully', 'sourcehub'),
                'post_id' => $existing_post->ID,
                'post_url' => get_permalink($existing_post->ID)
            ), 200);

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
        
        // Prepare post data
        $post_data = array(
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => $data['content'],
            'post_excerpt' => isset($data['excerpt']) ? sanitize_textarea_field($data['excerpt']) : '',
            'post_status' => isset($data['status']) ? sanitize_text_field($data['status']) : 'publish',
            'post_type' => isset($data['post_type']) ? sanitize_text_field($data['post_type']) : 'post',
            'post_name' => isset($data['slug']) ? sanitize_title($data['slug']) : '',
            'post_date' => isset($data['date']) ? sanitize_text_field($data['date']) : current_time('mysql'),
            'post_date_gmt' => isset($data['date_gmt']) ? sanitize_text_field($data['date_gmt']) : current_time('mysql', 1)
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

        // Handle featured image
        if (isset($data['featured_image'])) {
            $this->set_featured_image($post_id, $data['featured_image']);
        }

        // Handle Yoast SEO meta
        if (isset($data['yoast_meta']) && !empty($data['yoast_meta'])) {
            $allow_override = get_option('sourcehub_allow_yoast_override', true);
            SourceHub_Yoast_Integration::set_post_meta($post_id, $data['yoast_meta'], $allow_override);
            
            // Set canonical URL
            $canonical_url = SourceHub_Yoast_Integration::generate_canonical_url($post_id, $data['hub_url'], $data['hub_id']);
            SourceHub_Yoast_Integration::set_canonical_url($post_id, $canonical_url);
        }

        // Handle Newspaper theme meta
        if (isset($data['newspaper_meta']) && !empty($data['newspaper_meta'])) {
            $allow_override = get_option('sourcehub_allow_newspaper_override', true);
            SourceHub_Newspaper_Integration::set_post_meta($post_id, $data['newspaper_meta'], $allow_override);
        }

        // Handle custom fields
        if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
            foreach ($data['custom_fields'] as $key => $value) {
                update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
            }
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
        // Temporarily disable content filtering to preserve iframes and embeds
        add_filter('wp_kses_allowed_html', array($this, 'allow_all_html_for_sourcehub'), 10, 2);
        
        // Prepare post data
        $post_data = array(
            'ID' => $post_id,
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => $data['content'],
            'post_excerpt' => isset($data['excerpt']) ? sanitize_textarea_field($data['excerpt']) : '',
            'post_status' => isset($data['status']) ? sanitize_text_field($data['status']) : 'publish',
            'post_name' => isset($data['slug']) ? sanitize_title($data['slug']) : '',
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

        // Update the post
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

        // Update featured image
        if (isset($data['featured_image'])) {
            $this->set_featured_image($post_id, $data['featured_image']);
        }

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

        return true;
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

        // Download image using wp_remote_get
        $response = wp_remote_get($image_data['url'], array(
            'timeout' => 30,
            'sslverify' => false
        ));
        
        if (is_wp_error($response)) {
            SourceHub_Logger::error(
                'Failed to download featured image: ' . $response->get_error_message(),
                array('url' => $image_data['url'], 'error' => $response->get_error_message()),
                $post_id,
                null,
                'set_featured_image'
            );
            return;
        }
        
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            SourceHub_Logger::error(
                'Featured image download failed with status code: ' . $response_code,
                array('url' => $image_data['url'], 'response_code' => $response_code),
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
        
        SourceHub_Logger::success(
            'Featured image set successfully',
            array('attachment_id' => $attachment_id, 'post_id' => $post_id),
            $post_id,
            null,
            'set_featured_image'
        );
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

}
