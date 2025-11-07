<?php
/**
 * Hub functionality manager
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Hub Manager Class
 */
class SourceHub_Hub_Manager {

    /**
     * Track posts that need syncing after Newspaper saves its data
     */
    private $pending_syncs = array();

    /**
     * Sync lock to prevent duplicate syncs
     */
    private $sync_locks = array();

    /**
     */
    public function init() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_post_meta'), 99, 2); // High priority to run after theme meta saves
        add_action('post_updated', array($this, 'handle_post_update'), 100, 3); // Run AFTER save_post_meta (priority 99)
        add_action('wp_after_insert_post', array($this, 'handle_post_insert'), 100, 4); // Handle NEW posts - fires AFTER save_post and meta
        
        // Handle scheduled posts when they publish
        add_action('future_to_publish', array($this, 'handle_scheduled_post_publish'));
        
        // Multiple hooks to catch Newspaper meta at different points
        add_action('updated_post_meta', array($this, 'meta_updated_check'), 10, 4);
        add_action('admin_footer', array($this, 'final_sync_check'), 999);
        add_action('shutdown', array($this, 'check_pending_syncs'));
        
        // Hook into Yoast SEO save to trigger sync when Yoast meta is updated
        add_action('wpseo_saved_postdata', array($this, 'handle_yoast_meta_save'));
        
        // REST API for async callbacks
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        add_action('wp_ajax_sourcehub_test_connection', array($this, 'ajax_test_connection'));
        add_action('wp_ajax_sourcehub_sync_post', array($this, 'ajax_sync_post'));
        
        // Schedule timeout check for stuck processing jobs
        if (!wp_next_scheduled('sourcehub_check_timeouts')) {
            wp_schedule_event(time(), 'hourly', 'sourcehub_check_timeouts');
        }
        add_action('sourcehub_check_timeouts', array($this, 'check_processing_timeouts'));
        
        add_action('wp_ajax_sourcehub_send_to_spoke', array($this, 'ajax_send_to_spoke'));
        add_action('wp_ajax_sourcehub_manual_cron', array($this, 'ajax_manual_cron'));
    }

    /**
     * Register REST API routes
     */
    public function register_rest_routes() {
        register_rest_route('sourcehub/v1', '/sync-complete', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_sync_complete'),
            'permission_callback' => '__return_true' // Spokes will authenticate via data
        ));
    }

    /**
     * Handle sync completion callback from spoke
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response
     */
    public function handle_sync_complete($request) {
        $data = $request->get_json_params();
        
        error_log('SourceHub Hub: Received sync completion callback: ' . print_r($data, true));
        
        $job_id = $data['job_id'] ?? null;
        $hub_post_id = $data['hub_post_id'] ?? null;
        $spoke_post_id = $data['spoke_post_id'] ?? null;
        $status = $data['status'] ?? 'failed';
        $error_message = $data['error_message'] ?? null;
        $spoke_url = $data['spoke_url'] ?? null;
        
        if (!$hub_post_id) {
            return new WP_REST_Response(array('success' => false, 'message' => 'Missing hub_post_id'), 400);
        }
        
        // Find the connection by spoke URL
        $connection = null;
        if ($spoke_url) {
            $connections = SourceHub_Database::get_connections();
            foreach ($connections as $conn) {
                if (trailingslashit($conn->url) === trailingslashit($spoke_url)) {
                    $connection = $conn;
                    break;
                }
            }
        }
        
        if (!$connection) {
            error_log('SourceHub Hub: Could not find connection for spoke URL: ' . $spoke_url);
            return new WP_REST_Response(array('success' => false, 'message' => 'Connection not found'), 404);
        }
        
        // Update sync status
        $sync_status = get_post_meta($hub_post_id, '_sourcehub_sync_status', true);
        if (!is_array($sync_status)) {
            $sync_status = array();
        }
        
        if ($status === 'completed') {
            $sync_status[$connection->id] = array(
                'status' => 'success',
                'last_sync' => current_time('mysql'),
                'action' => $sync_status[$connection->id]['action'] ?? 'create',
                'spoke_post_id' => $spoke_post_id
            );
            
            error_log(sprintf('SourceHub Hub: Post %d synced successfully to %s (spoke post ID: %d)', 
                $hub_post_id, $connection->name, $spoke_post_id));
        } else {
            $sync_status[$connection->id] = array(
                'status' => 'failed',
                'last_sync' => current_time('mysql'),
                'action' => $sync_status[$connection->id]['action'] ?? 'create',
                'error' => $error_message ?? 'Unknown error'
            );
            
            error_log(sprintf('SourceHub Hub: Post %d failed to sync to %s: %s', 
                $hub_post_id, $connection->name, $error_message));
        }
        
        update_post_meta($hub_post_id, '_sourcehub_sync_status', $sync_status);
        
        return new WP_REST_Response(array('success' => true, 'message' => 'Status updated'), 200);
    }

    /**
     * Add meta boxes to post editor
     */
    public function add_meta_boxes() {
        $post_types = apply_filters('sourcehub_supported_post_types', array('post', 'page'));

        foreach ($post_types as $post_type) {
            add_meta_box(
                'sourcehub-syndication',
                __('SourceHub Syndication', 'sourcehub'),
                array($this, 'render_syndication_meta_box'),
                $post_type,
                'side',
                'high'
            );
        }
    }

    /**
     * Render syndication meta box
     *
     * @param WP_Post $post Current post object
     */
    public function render_syndication_meta_box($post) {
        wp_nonce_field('sourcehub_syndication_nonce', 'sourcehub_syndication_nonce');

        $connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
        $selected_connections = get_post_meta($post->ID, '_sourcehub_selected_spokes', true);
        $syndicated_connections = get_post_meta($post->ID, '_sourcehub_syndicated_spokes', true);
        $sync_status = get_post_meta($post->ID, '_sourcehub_sync_status', true);
        $ai_overrides = get_post_meta($post->ID, '_sourcehub_ai_overrides', true);
        $last_sync = get_post_meta($post->ID, '_sourcehub_last_sync', true);

        if (!is_array($selected_connections)) {
            $selected_connections = array();
        }

        if (!is_array($syndicated_connections)) {
            $syndicated_connections = array();
        }
        
        if (!is_array($sync_status)) {
            $sync_status = array();
        }

        if (!is_array($ai_overrides)) {
            $ai_overrides = array();
        }

        ?>
        <div class="sourcehub-syndication-meta-box">
            <?php if (empty($connections)): ?>
                <div class="sourcehub-notice sourcehub-notice-warning">
                    <p><?php _e('No spoke connections configured. Please add spoke connections in the SourceHub settings.', 'sourcehub'); ?></p>
                    <p><a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="button button-secondary"><?php _e('Manage Connections', 'sourcehub'); ?></a></p>
                </div>
            <?php else: ?>
                <div class="sourcehub-connections-list">
                    <h4><?php _e('Select Spokes to Syndicate To:', 'sourcehub'); ?></h4>
                    <?php foreach ($connections as $connection): 
                        $ai_settings = json_decode($connection->ai_settings, true);
                        $has_ai_enabled = !empty($ai_settings['enabled']);
                        $connection_id = $connection->id;
                        $ai_override_key = 'disable_ai_' . $connection_id;
                        $ai_disabled_for_post = isset($ai_overrides[$ai_override_key]) ? $ai_overrides[$ai_override_key] : false;
                    ?>
                        <div class="sourcehub-connection-item">
                            <label class="connection-main-label">
                                <input type="checkbox" 
                                       name="sourcehub_selected_spokes[]" 
                                       value="<?php echo esc_attr($connection->id); ?>"
                                       <?php checked(in_array($connection->id, $selected_connections)); ?>
                                       <?php echo $connection->status !== 'active' ? 'disabled' : ''; ?>>
                                <span class="connection-name"><?php echo esc_html($connection->name); ?></span>
                                <span class="connection-status status-<?php echo esc_attr($connection->status); ?>">
                                    <?php echo esc_html(ucfirst($connection->status)); ?>
                                </span>
                                <?php if ($has_ai_enabled): ?>
                                    <span class="ai-indicator" title="<?php _e('AI Rewriting Available', 'sourcehub'); ?>">
                                        <span class="dashicons dashicons-admin-generic"></span>
                                    </span>
                                <?php endif; ?>
                            </label>
                            
                            <?php if ($has_ai_enabled): ?>
                                <div class="ai-override-control">
                                    <label class="ai-toggle-label">
                                        <input type="checkbox" 
                                               name="sourcehub_ai_overrides[<?php echo esc_attr($ai_override_key); ?>]" 
                                               value="1"
                                               <?php checked($ai_disabled_for_post); ?>>
                                        <span><?php _e('Disable AI rewriting for this post', 'sourcehub'); ?></span>
                                        <small class="ai-help-text">
                                            <?php _e('Check to send original content without AI rewriting', 'sourcehub'); ?>
                                        </small>
                                    </label>
                                </div>
                            <?php endif; ?>
                            
                            <div class="syndication-info">
                                <?php 
                                $status_info = isset($sync_status[$connection->id]) ? $sync_status[$connection->id] : null;
                                $has_status = $status_info && isset($status_info['status']);
                                $is_success = $has_status && $status_info['status'] === 'success';
                                $is_failed = $has_status && $status_info['status'] === 'failed';
                                $is_processing = $has_status && $status_info['status'] === 'processing';
                                ?>
                                
                                <?php if ($is_processing): ?>
                                    <span class="sync-status-badge sync-processing">
                                        <span class="dashicons dashicons-update"></span>
                                        <small><?php _e('Processing...', 'sourcehub'); ?></small>
                                    </span>
                                <?php elseif ($is_success): ?>
                                    <span class="sync-status-badge sync-success">
                                        <span class="dashicons dashicons-yes-alt"></span>
                                        <small><?php _e('Synced', 'sourcehub'); ?></small>
                                    </span>
                                    <button type="button" 
                                            class="button button-small sourcehub-resync-btn" 
                                            data-connection-id="<?php echo esc_attr($connection->id); ?>"
                                            data-post-id="<?php echo esc_attr($post->ID); ?>">
                                        <?php _e('Re-sync', 'sourcehub'); ?>
                                    </button>
                                <?php elseif ($is_failed): ?>
                                    <span class="sync-status-badge sync-failed" title="<?php echo esc_attr($status_info['error']); ?>">
                                        <span class="dashicons dashicons-warning"></span>
                                        <small><?php _e('Failed', 'sourcehub'); ?></small>
                                    </span>
                                    <button type="button" 
                                            class="button button-small button-primary sourcehub-send-now-btn" 
                                            data-connection-id="<?php echo esc_attr($connection->id); ?>"
                                            data-post-id="<?php echo esc_attr($post->ID); ?>">
                                        <?php _e('Retry', 'sourcehub'); ?>
                                    </button>
                                    <small class="sync-error-message" style="color: #d63638; display: block; margin-top: 4px;">
                                        <?php echo esc_html($status_info['error']); ?>
                                    </small>
                                <?php else: ?>
                                    <span class="sync-status-badge sync-never">
                                        <span class="dashicons dashicons-minus"></span>
                                        <small style="color: #666;"><?php _e('Not synced', 'sourcehub'); ?></small>
                                    </span>
                                    <button type="button" 
                                            class="button button-small button-primary sourcehub-send-now-btn" 
                                            data-connection-id="<?php echo esc_attr($connection->id); ?>"
                                            data-post-id="<?php echo esc_attr($post->ID); ?>">
                                        <?php _e('Send Now', 'sourcehub'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($last_sync): ?>
                    <div class="sourcehub-sync-info">
                        <p><strong><?php _e('Last Sync:', 'sourcehub'); ?></strong> 
                           <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($last_sync))); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="sourcehub-actions">
                    <button type="button" id="sourcehub-test-connections" class="button button-secondary">
                        <?php _e('Test Connections', 'sourcehub'); ?>
                    </button>
                    <div id="sourcehub-test-results"></div>
                </div>
            <?php endif; ?>
        </div>

        <style>
        .sourcehub-syndication-meta-box {
            font-size: 13px;
        }
        
        .sourcehub-notice {
            padding: 8px 12px;
            margin: 0 0 15px;
            border-left: 4px solid;
            background: #fff;
        }
        
        .sourcehub-notice-warning {
            border-left-color: #ffb900;
            background: #fff8e5;
        }
        
        .sourcehub-connection-item {
            margin-bottom: 12px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }
        
        .connection-main-label {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .connection-name {
            flex: 1;
            margin-left: 8px;
            font-weight: 500;
        }
        
        .connection-status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .ai-indicator {
            margin-left: 8px;
            color: #0073aa;
        }
        
        .ai-override-control {
            margin-left: 20px;
            margin-top: 8px;
            padding: 8px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
        }
        
        .ai-toggle-label {
            display: block;
            font-size: 12px;
        }
        
        .ai-help-text {
            display: block;
            color: #666;
            font-style: italic;
            margin-top: 2px;
        }
        
        .syndication-info {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .sync-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .sync-status-badge .dashicons {
            font-size: 16px;
            width: 16px;
            height: 16px;
        }
        
        .sync-success {
            background: #d4edda;
            color: #155724;
        }
        
        .sync-processing {
            background: #cfe2ff;
            color: #084298;
        }
        
        .sync-processing .dashicons {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .sync-failed {
            background: #f8d7da;
            color: #721c24;
            cursor: help;
        }
        
        .sync-never {
            background: #e2e3e5;
            color: #6c757d;
        }
        
        .sync-error-message {
            width: 100%;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .sourcehub-resync-btn,
        .sourcehub-send-now-btn {
            margin-left: auto;
        }
        
        .sourcehub-sync-info {
            margin: 15px 0;
            padding: 8px;
            background: #e7f3ff;
            border-radius: 4px;
        }
        
        .sourcehub-actions {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        #sourcehub-test-results {
            margin-top: 10px;
        }
        
        .test-result {
            padding: 5px 8px;
            margin: 5px 0;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .test-result.success {
            background: #d4edda;
            color: #155724;
        }
        
        .test-result.error {
            background: #f8d7da;
            color: #721c24;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Test connections
            $('#sourcehub-test-connections').on('click', function() {
                var $button = $(this);
                var $results = $('#sourcehub-test-results');
                
                $button.prop('disabled', true).text('<?php _e('Testing...', 'sourcehub'); ?>');
                $results.html('<div class="spinner is-active"></div>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'sourcehub_test_connection',
                        nonce: '<?php echo wp_create_nonce('sourcehub_test_connection'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            var html = '';
                            $.each(response.data, function(id, result) {
                                var className = result.success ? 'success' : 'error';
                                html += '<div class="test-result ' + className + '">' + result.message + '</div>';
                            });
                            $results.html(html);
                        } else {
                            $results.html('<div class="test-result error">' + response.data + '</div>');
                        }
                    },
                    error: function() {
                        $results.html('<div class="test-result error"><?php _e('Connection test failed.', 'sourcehub'); ?></div>');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text('<?php _e('Test Connections', 'sourcehub'); ?>');
                    }
                });
            });
            
            // Re-sync individual posts
            $('.sourcehub-resync-btn').on('click', function() {
                var $button = $(this);
                var connectionId = $button.data('connection-id');
                var postId = $button.data('post-id');
                
                $button.prop('disabled', true).text('<?php _e('Syncing...', 'sourcehub'); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'sourcehub_sync_post',
                        connection_id: connectionId,
                        post_id: postId,
                        nonce: '<?php echo wp_create_nonce('sourcehub_sync_post'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $button.text('<?php _e('Synced!', 'sourcehub'); ?>');
                            setTimeout(function() {
                                $button.text('<?php _e('Re-sync', 'sourcehub'); ?>');
                            }, 2000);
                        } else {
                            alert('<?php _e('Sync failed:', 'sourcehub'); ?> ' + response.data);
                        }
                    },
                    error: function() {
                        alert('<?php _e('Sync failed due to connection error.', 'sourcehub'); ?>');
                    },
                    complete: function() {
                        $button.prop('disabled', false);
                    }
                });
            });
            
            // Send Now - create post on new spoke
            $('.sourcehub-send-now-btn').on('click', function() {
                var $button = $(this);
                var connectionId = $button.data('connection-id');
                var postId = $button.data('post-id');
                
                $button.prop('disabled', true).text('<?php _e('Sending...', 'sourcehub'); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'sourcehub_send_to_spoke',
                        connection_id: connectionId,
                        post_id: postId,
                        nonce: '<?php echo wp_create_nonce('sourcehub_send_to_spoke'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $button.text('<?php _e('Sent!', 'sourcehub'); ?>');
                            // Reload page after 1 second to show updated UI
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            alert('<?php _e('Send failed:', 'sourcehub'); ?> ' + response.data);
                            $button.prop('disabled', false).text('<?php _e('Send Now', 'sourcehub'); ?>');
                        }
                    },
                    error: function() {
                        alert('<?php _e('Send failed due to connection error.', 'sourcehub'); ?>');
                        $button.prop('disabled', false).text('<?php _e('Send Now', 'sourcehub'); ?>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Save post meta
     *
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     */
    public function save_post_meta($post_id, $post) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if this is a revision
        if (wp_is_post_revision($post_id)) {
            return;
        }
        
        // Skip auto-draft posts - wait until they're actually being published
        if ($post->post_status === 'auto-draft') {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check nonce - but only if SourceHub data is being submitted
        // On brand new posts, the meta box might not be rendered yet, so no nonce
        $has_sourcehub_data = isset($_POST['sourcehub_selected_spokes']) || isset($_POST['sourcehub_ai_overrides']);
        
        if ($has_sourcehub_data) {
            if (!isset($_POST['sourcehub_syndication_nonce']) || !wp_verify_nonce($_POST['sourcehub_syndication_nonce'], 'sourcehub_syndication_nonce')) {
                error_log('SourceHub: Nonce check failed for post ' . $post_id);
                return;
            }
        } else {
            // No SourceHub data in POST, nothing to save
            error_log('SourceHub: No SourceHub data in POST for post ' . $post_id);
            return;
        }
        
        error_log('SourceHub: Nonce verified, processing meta save for post ' . $post_id);
        error_log('SourceHub: Full $_POST data: ' . print_r($_POST, true));

        // Save selected spokes FIRST (before any early returns)
        // This ensures spoke selection is always saved, even if we return early for Newspaper theme
        // Only update if the form was submitted (nonce verified above)
        // If checkboxes are unchecked, $_POST['sourcehub_selected_spokes'] won't exist,
        // but we still want to save empty array to clear previous selections
        $selected_spokes = isset($_POST['sourcehub_selected_spokes']) ? 
            array_map('intval', $_POST['sourcehub_selected_spokes']) : array();
        
        error_log('SourceHub: Saving spoke selection for post ' . $post_id . ': ' . print_r($selected_spokes, true));
        error_log('SourceHub: POST data sourcehub_selected_spokes: ' . (isset($_POST['sourcehub_selected_spokes']) ? print_r($_POST['sourcehub_selected_spokes'], true) : 'NOT SET'));
        
        update_post_meta($post_id, '_sourcehub_selected_spokes', $selected_spokes);
        
        // Verify it was saved
        $saved_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        error_log('SourceHub: Verified saved spokes: ' . print_r($saved_spokes, true));

        // Save AI overrides
        $ai_overrides = array();
        if (isset($_POST['sourcehub_ai_overrides'])) {
            $ai_overrides = array_map(function($value) {
                return intval($value);
            }, $_POST['sourcehub_ai_overrides']);
        }
        update_post_meta($post_id, '_sourcehub_ai_overrides', $ai_overrides);

        // Syndication is handled by handle_post_insert (new posts) and handle_post_update (updates)
        // No need to do anything else here
    }

    /**
     * Handle new post insertion (first publish)
     * Uses wp_after_insert_post which fires AFTER save_post and post meta are saved
     *
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     * @param bool $update Whether this is an update
     * @param WP_Post $post_before Post object before update (null for new posts)
     */
    public function handle_post_insert($post_id, $post, $update, $post_before) {
        // Only handle NEW posts (not updates)
        if ($update) {
            return;
        }
        
        error_log('SourceHub: handle_post_insert called for NEW post ' . $post_id);
        
        // Only handle published posts
        if ($post->post_status !== 'publish') {
            error_log('SourceHub: Skipping - post status is ' . $post->post_status);
            return;
        }

        // Skip if this is an autosave or revision
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            error_log('SourceHub: Skipping - autosave or revision');
            return;
        }

        // Get selected spokes
        $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        
        error_log('SourceHub: Selected spokes for NEW post: ' . print_r($selected_spokes, true));
        
        if (!is_array($selected_spokes)) {
            $selected_spokes = array();
        }
        
        // Syndicate to selected spokes
        if (!empty($selected_spokes)) {
            error_log('SourceHub: Syndicating NEW post to ' . count($selected_spokes) . ' spoke(s)');
            $this->syndicate_post($post_id, $selected_spokes);
        }
    }

    /**
     * Handle post updates
     *
     * @param int $post_id Post ID
     * @param WP_Post $post_after Post object after update
     * @param WP_Post $post_before Post object before update
     */
    public function handle_post_update($post_id, $post_after, $post_before) {
        error_log('SourceHub: handle_post_update called for post ' . $post_id);
        
        // Only handle published posts
        if ($post_after->post_status !== 'publish') {
            error_log('SourceHub: Skipping - post status is ' . $post_after->post_status);
            return;
        }

        // Skip if this is an autosave or revision
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            error_log('SourceHub: Skipping - autosave or revision');
            return;
        }

        // Get selected and syndicated spokes
        $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
        
        error_log('SourceHub: Selected spokes: ' . print_r($selected_spokes, true));
        error_log('SourceHub: Syndicated spokes: ' . print_r($syndicated_spokes, true));
        
        if (!is_array($selected_spokes)) {
            $selected_spokes = array();
        }
        if (!is_array($syndicated_spokes)) {
            $syndicated_spokes = array();
        }
        
        // Find NEW spokes (selected but not yet syndicated)
        $new_spokes = array_diff($selected_spokes, $syndicated_spokes);
        
        // Find EXISTING spokes (already syndicated and still selected)
        $existing_spokes = array_intersect($selected_spokes, $syndicated_spokes);
        
        error_log('SourceHub: New spokes to create: ' . print_r($new_spokes, true));
        error_log('SourceHub: Existing spokes to update: ' . print_r($existing_spokes, true));
        
        // Create posts on NEW spokes
        if (!empty($new_spokes)) {
            error_log('SourceHub: Creating posts on ' . count($new_spokes) . ' new spoke(s)');
            $this->syndicate_post($post_id, $new_spokes);
        }
        
        // Update posts on EXISTING spokes
        if (!empty($existing_spokes)) {
            SourceHub_Logger::info(
                sprintf('Syncing updates for post "%s" to %d existing spoke sites', $post_after->post_title, count($existing_spokes)),
                array('spoke_ids' => $existing_spokes),
                $post_id,
                null,
                'update'
            );
            
            $this->update_syndicated_post($post_id, $existing_spokes);
        }
    }

    /**
     * Handle scheduled post publishing
     * Triggered when a scheduled post transitions to published
     *
     * @param WP_Post $post Post object
     */
    public function handle_scheduled_post_publish($post) {
        $post_id = $post->ID;
        
        error_log('SourceHub: Scheduled post ' . $post_id . ' is now publishing');
        
        // Get selected spokes
        $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        if (empty($selected_spokes) || !is_array($selected_spokes)) {
            error_log('SourceHub: No spokes selected for scheduled post ' . $post_id);
            return;
        }
        
        // Get already syndicated spokes
        $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
        if (!is_array($syndicated_spokes)) {
            $syndicated_spokes = array();
        }
        
        // Find NEW spokes that need posts created
        $new_spokes = array_diff($selected_spokes, $syndicated_spokes);
        
        if (!empty($new_spokes)) {
            error_log('SourceHub: Syndicating scheduled post ' . $post_id . ' to ' . count($new_spokes) . ' spoke(s)');
            $this->syndicate_post($post_id, $new_spokes);
        } else {
            error_log('SourceHub: Scheduled post ' . $post_id . ' already syndicated to all selected spokes');
        }
    }

    /**
     * Handle Yoast meta save
     * Triggered after Yoast SEO saves its meta data
     *
     * @param int $post_id Post ID
     */
    public function handle_yoast_meta_save($post_id) {
        // Get the post
        $post = get_post($post_id);
        
        // Only handle published posts
        if (!$post || $post->post_status !== 'publish') {
            return;
        }

        // Skip if this is an autosave or revision
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        // Get previously syndicated spokes
        $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
        if (!empty($syndicated_spokes) && is_array($syndicated_spokes)) {
            // Yoast has a timing issue where meta isn't fully available on first save
            // Similar to Newspaper theme issue - schedule a delayed sync to ensure Yoast data is ready
            error_log('SourceHub: Yoast meta saved for post ' . $post_id . ', scheduling delayed sync in 3 seconds');
            wp_schedule_single_event(time() + 3, 'sourcehub_delayed_sync', array($post_id));
            
            // For local development, also trigger cron manually after a short delay
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('SourceHub: Setting up manual cron trigger for Yoast sync (local development)');
                add_action('admin_footer', function() use ($post_id) {
                    echo '<script>
                    console.log("SourceHub: Setting up delayed Yoast sync for post ' . $post_id . '");
                    setTimeout(function() {
                        console.log("SourceHub: Triggering manual cron for Yoast sync post ' . $post_id . '");
                        fetch("' . admin_url('admin-ajax.php') . '", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: "action=sourcehub_manual_cron&post_id=' . $post_id . '"
                        }).then(response => response.json())
                          .then(data => console.log("SourceHub: Manual cron response:", data))
                          .catch(error => console.error("SourceHub: Manual cron error:", error));
                    }, 3000);
                    </script>';
                });
            }
        }
    }

    /**
     * Handle post status transitions
     *
     * @param string $new_status New post status
     * @param string $old_status Old post status
     * @param WP_Post $post Post object
     */
    public function handle_post_status_transition($new_status, $old_status, $post) {
        // Only handle published posts
        if ($new_status !== 'publish') {
            return;
        }

        // Get selected spokes
        $selected_spokes = get_post_meta($post->ID, '_sourcehub_selected_spokes', true);
        if (!empty($selected_spokes) && is_array($selected_spokes)) {
            $this->syndicate_post($post->ID, $selected_spokes);
        }
    }

    /**
     * Syndicate post to selected spokes
     *
     * @param int $post_id Post ID
     * @param array $spoke_ids Array of spoke connection IDs
     */
    public function syndicate_post($post_id, $spoke_ids) {
        // Check if sync is already in progress for this post
        if (isset($this->sync_locks[$post_id])) {
            error_log('SourceHub: Sync already in progress for post ' . $post_id . ', skipping');
            return;
        }
        
        // Check if post should be syndicated (validation filter)
        $should_syndicate = apply_filters('sourcehub_should_syndicate_post', true, $post_id);
        if (!$should_syndicate) {
            error_log('SourceHub: Syndication halted for post ' . $post_id . ' by validation filter');
            return;
        }
        
        // Set sync lock
        $this->sync_locks[$post_id] = true;
        
        $post = get_post($post_id);
        if (!$post) {
            unset($this->sync_locks[$post_id]);
            return;
        }

        $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
        if (!is_array($syndicated_spokes)) {
            $syndicated_spokes = array();
        }

        $ai_overrides = get_post_meta($post_id, '_sourcehub_ai_overrides', true);
        if (!is_array($ai_overrides)) {
            $ai_overrides = array();
        }
        
        // Get current sync status
        $sync_status = get_post_meta($post_id, '_sourcehub_sync_status', true);
        if (!is_array($sync_status)) {
            $sync_status = array();
        }

        foreach ($spoke_ids as $spoke_id) {
            $connection = SourceHub_Database::get_connection($spoke_id);
            if (!$connection || $connection->status !== 'active') {
                continue;
            }

            $ai_settings = json_decode($connection->ai_settings, true);
            $has_ai_enabled = !empty($ai_settings['enabled']);
            $ai_disabled_for_post = isset($ai_overrides['disable_ai_' . $spoke_id]) ? $ai_overrides['disable_ai_' . $spoke_id] : false;

            $result = $this->send_post_to_spoke($post, $connection, $has_ai_enabled && !$ai_disabled_for_post, false);
            
            if ($result['success']) {
                $syndicated_spokes[] = $spoke_id;
                
                // Check if async processing
                if (!empty($result['async'])) {
                    // Store processing status with timestamp for timeout detection
                    $sync_status[$spoke_id] = array(
                        'status' => 'processing',
                        'last_sync' => current_time('mysql'),
                        'started_at' => current_time('mysql'),
                        'action' => 'create',
                        'job_id' => $result['job_id'] ?? null
                    );
                    
                    SourceHub_Logger::info(
                        sprintf(__('Post "%s" queued for syndication to %s', 'sourcehub'), $post->post_title, $connection->name),
                        $result,
                        $post_id,
                        $spoke_id,
                        'syndicate_queued'
                    );
                } else {
                    // Store success status
                    $sync_status[$spoke_id] = array(
                        'status' => 'success',
                        'last_sync' => current_time('mysql'),
                        'action' => 'create'
                    );
                    
                    SourceHub_Logger::success(
                        sprintf(__('Post "%s" successfully syndicated to %s', 'sourcehub'), $post->post_title, $connection->name),
                        $result,
                        $post_id,
                        $spoke_id,
                        'syndicate'
                    );
                }
            } else {
                // Store failure status
                $sync_status[$spoke_id] = array(
                    'status' => 'failed',
                    'last_sync' => current_time('mysql'),
                    'error' => $result['message'],
                    'action' => 'create'
                );
                
                SourceHub_Logger::error(
                    sprintf(__('Failed to syndicate post "%s" to %s: %s', 'sourcehub'), $post->post_title, $connection->name, $result['message']),
                    $result,
                    $post_id,
                    $spoke_id,
                    'syndicate'
                );
            }
        }

        // Update syndicated spokes list and sync status
        update_post_meta($post_id, '_sourcehub_syndicated_spokes', array_unique($syndicated_spokes));
        update_post_meta($post_id, '_sourcehub_sync_status', $sync_status);
        update_post_meta($post_id, '_sourcehub_last_sync', current_time('mysql'));
        
        // Release sync lock
        unset($this->sync_locks[$post_id]);
    }

    /**
     * Update syndicated post on spokes
     *
     * @param int $post_id Post ID
     * @param array $spoke_ids Array of spoke connection IDs
     */
    public function update_syndicated_post($post_id, $spoke_ids) {
        // Check if sync is already in progress for this post
        if (isset($this->sync_locks[$post_id])) {
            error_log('SourceHub: Update sync already in progress for post ' . $post_id . ', skipping');
            return;
        }
        
        // Set sync lock
        $this->sync_locks[$post_id] = true;
        
        $post = get_post($post_id);
        if (!$post) {
            unset($this->sync_locks[$post_id]);
            return;
        }

        // Get currently selected spokes (respects user's current checkbox selection)
        $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        if (!is_array($selected_spokes)) {
            $selected_spokes = array();
        }
        
        // Only update spokes that are both syndicated AND currently selected
        $spokes_to_update = array_intersect($spoke_ids, $selected_spokes);
        
        if (empty($spokes_to_update)) {
            error_log('SourceHub: No spokes selected for update, skipping');
            unset($this->sync_locks[$post_id]);
            return;
        }
        
        error_log('SourceHub: Updating ' . count($spokes_to_update) . ' selected spokes out of ' . count($spoke_ids) . ' previously syndicated');

        $ai_overrides = get_post_meta($post_id, '_sourcehub_ai_overrides', true);
        if (!is_array($ai_overrides)) {
            $ai_overrides = array();
        }
        
        // Get current sync status
        $sync_status = get_post_meta($post_id, '_sourcehub_sync_status', true);
        if (!is_array($sync_status)) {
            $sync_status = array();
        }

        foreach ($spokes_to_update as $spoke_id) {
            $connection = SourceHub_Database::get_connection($spoke_id);
            if (!$connection) {
                continue;
            }

            $ai_settings = json_decode($connection->ai_settings, true);
            $has_ai_enabled = !empty($ai_settings['enabled']);
            $ai_disabled_for_post = isset($ai_overrides['disable_ai_' . $spoke_id]) ? $ai_overrides['disable_ai_' . $spoke_id] : false;

            $result = $this->send_post_to_spoke($post, $connection, $has_ai_enabled && !$ai_disabled_for_post, true);
            
            if ($result['success']) {
                // Check if async processing
                if (!empty($result['async'])) {
                    // Store processing status with timestamp for timeout detection
                    $sync_status[$spoke_id] = array(
                        'status' => 'processing',
                        'last_sync' => current_time('mysql'),
                        'started_at' => current_time('mysql'),
                        'action' => 'update',
                        'job_id' => $result['job_id'] ?? null
                    );
                    
                    SourceHub_Logger::info(
                        sprintf(__('Post "%s" update queued for %s', 'sourcehub'), $post->post_title, $connection->name),
                        $result,
                        $post_id,
                        $spoke_id,
                        'update_queued'
                    );
                } else {
                    // Store success status
                    $sync_status[$spoke_id] = array(
                        'status' => 'success',
                        'last_sync' => current_time('mysql'),
                        'action' => 'update'
                    );
                    
                    SourceHub_Logger::success(
                        sprintf(__('Post "%s" successfully updated on %s', 'sourcehub'), $post->post_title, $connection->name),
                        $result,
                        $post_id,
                        $spoke_id,
                        'update'
                    );
                }
            } else {
                // Store failure status
                $sync_status[$spoke_id] = array(
                    'status' => 'failed',
                    'last_sync' => current_time('mysql'),
                    'error' => $result['message'],
                    'action' => 'update'
                );
                
                SourceHub_Logger::error(
                    sprintf(__('Failed to update post "%s" on %s: %s', 'sourcehub'), $post->post_title, $connection->name, $result['message']),
                    $result,
                    $post_id,
                    $spoke_id,
                    'update'
                );
            }
        }

        update_post_meta($post_id, '_sourcehub_sync_status', $sync_status);
        update_post_meta($post_id, '_sourcehub_last_sync', current_time('mysql'));
        
        // Release sync lock
        unset($this->sync_locks[$post_id]);
    }

    /**
     * Send post to spoke site with retry logic
     *
     * @param WP_Post $post Post object
     * @param object $connection Connection object
     * @param bool $use_ai Whether to use AI rewriting
     * @param bool $is_update Whether this is an update
     * @param int $attempt Current attempt number (internal use)
     * @return array Result array
     */
    private function send_post_to_spoke($post, $connection, $use_ai = false, $is_update = false, $attempt = 1) {
        $max_attempts = 1; // Disabled auto-retry to prevent duplicates from timeouts
        
        // Prepare post data
        $post_data = $this->prepare_post_data($post, $connection, $use_ai);
        
        // Debug: Log final content being sent to spoke
        SourceHub_Logger::info(
            'DEBUG: Final content being sent to spoke: ' . substr($post_data['content'], 0, 500),
            array('connection_name' => $connection->name, 'full_content_length' => strlen($post_data['content'])),
            $post->ID,
            $connection->id,
            'debug_content'
        );
        
        // Wake up the spoke site before sending content
        $wake_up_success = $this->wake_up_spoke($connection);
        if (!$wake_up_success) {
            SourceHub_Logger::warning(
                'Failed to wake up spoke site, proceeding anyway',
                array('connection_name' => $connection->name, 'url' => $connection->url),
                $post->ID,
                $connection->id,
                'wake_up'
            );
        }
        
        // Determine endpoint
        $endpoint = $is_update ? 'update-post' : 'receive-post';
        $url = trailingslashit($connection->url) . 'wp-json/sourcehub/v1/' . $endpoint;
        
        // Debug: Log API key being sent (first 8 chars only for security)
        error_log(sprintf('SourceHub: Sending to %s with API key: %s...', $url, substr($connection->api_key, 0, 8)));
        
        // Send request with timeout
        $response = wp_remote_post($url, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-SourceHub-API-Key' => $connection->api_key,
            ),
            'body' => json_encode($post_data),
            'timeout' => 30, // 30 seconds to stay under WP Engine/Cloudflare 60s limit
        ));

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            
            // Retry logic - ONLY retry for updates, not creates (to prevent duplicates)
            if ($attempt < $max_attempts && $is_update) {
                $wait_seconds = $attempt * 2; // 2s, 4s (reduced to prevent gateway timeouts)
                error_log(sprintf('SourceHub: Attempt %d/%d failed for %s: %s. Retrying in %d seconds...', 
                    $attempt, $max_attempts, $connection->name, $error_message, $wait_seconds));
                
                sleep($wait_seconds);
                return $this->send_post_to_spoke($post, $connection, $use_ai, $is_update, $attempt + 1);
            }
            
            // Don't retry creates to prevent duplicate posts
            if (!$is_update && $attempt < $max_attempts) {
                error_log(sprintf('SourceHub: Not retrying create operation for %s to prevent duplicates', $connection->name));
            }
            
            // All retries exhausted
            error_log(sprintf('SourceHub: All %d attempts failed for %s: %s', $max_attempts, $connection->name, $error_message));
            return array(
                'success' => false,
                'message' => sprintf(__('Failed after %d attempts: %s', 'sourcehub'), $max_attempts, $error_message),
                'data' => $post_data
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        
        // Handle 202 Accepted (async processing)
        if ($response_code === 202) {
            $body_data = json_decode($response_body, true);
            error_log(sprintf('SourceHub: Spoke returned 202 Accepted, job queued: %s', $body_data['job_id'] ?? 'unknown'));
            
            return array(
                'success' => true,
                'message' => __('Post queued for processing', 'sourcehub'),
                'data' => $body_data,
                'async' => true,
                'job_id' => $body_data['job_id'] ?? null
            );
        }

        if (!in_array($response_code, [200, 201])) {
            // Clean up error message - strip HTML and extract meaningful text
            $clean_body = strip_tags($response_body);
            $clean_body = trim(preg_replace('/\s+/', ' ', $clean_body)); // Remove extra whitespace
            
            // If body is too long or is HTML garbage, just use the status code
            if (strlen($clean_body) > 100 || empty($clean_body)) {
                $status_text = wp_remote_retrieve_response_message($response);
                $error_message = sprintf(__('HTTP %d: %s', 'sourcehub'), $response_code, $status_text);
            } else {
                $error_message = sprintf(__('HTTP %d: %s', 'sourcehub'), $response_code, $clean_body);
            }
            
            // Retry logic for HTTP errors (except 401/403 which are auth issues)
            // ONLY retry for updates, not creates (to prevent duplicates)
            if ($attempt < $max_attempts && !in_array($response_code, [401, 403]) && $is_update) {
                $wait_seconds = $attempt * 2; // 2s, 4s (reduced to prevent gateway timeouts)
                error_log(sprintf('SourceHub: Attempt %d/%d failed for %s: %s. Retrying in %d seconds...', 
                    $attempt, $max_attempts, $connection->name, $error_message, $wait_seconds));
                
                sleep($wait_seconds);
                return $this->send_post_to_spoke($post, $connection, $use_ai, $is_update, $attempt + 1);
            }
            
            // Don't retry creates to prevent duplicate posts
            if (!$is_update && $attempt < $max_attempts && !in_array($response_code, [401, 403])) {
                error_log(sprintf('SourceHub: Not retrying create operation for %s to prevent duplicates', $connection->name));
            }
            
            // All retries exhausted or auth error (don't retry auth failures)
            if (in_array($response_code, [401, 403])) {
                error_log(sprintf('SourceHub: Authentication error for %s, not retrying: %s', $connection->name, $error_message));
            } else {
                error_log(sprintf('SourceHub: All %d attempts failed for %s: %s', $max_attempts, $connection->name, $error_message));
            }
            
            return array(
                'success' => false,
                'message' => $attempt > 1 ? sprintf(__('Failed after %d attempts: %s', 'sourcehub'), $attempt, $error_message) : $error_message,
                'data' => $post_data
            );
        }

        // Success!
        if ($attempt > 1) {
            error_log(sprintf('SourceHub: Succeeded on attempt %d/%d for %s', $attempt, $max_attempts, $connection->name));
        }
        
        return array(
            'success' => true,
            'message' => __('Post sent successfully', 'sourcehub'),
            'data' => json_decode($response_body, true)
        );
    }

    /**
     * Prepare post data for syndication
     *
     * @param WP_Post $post Post object
     * @param object $connection Connection object
     * @param bool $use_ai Whether to use AI rewriting
     * @return array
     */
    private function prepare_post_data($post, $connection, $use_ai = false) {
        // Get page template
        $page_template = get_page_template_slug($post->ID);
        error_log('SourceHub: Collecting page template for post ' . $post->ID . ': ' . ($page_template ? $page_template : 'DEFAULT'));

        // Basic post data
        // Get RAW content directly from database to preserve ALL shortcodes
        // Using $post->post_content can have filters applied that process shortcodes
        global $wpdb;
        $raw_content = $wpdb->get_var($wpdb->prepare(
            "SELECT post_content FROM $wpdb->posts WHERE ID = %d",
            $post->ID
        ));
        
        // Debug: Log original content
        SourceHub_Logger::info(
            'DEBUG: Original post content: ' . substr($raw_content, 0, 500),
            array('full_content_length' => strlen($raw_content)),
            $post->ID,
            null,
            'debug_content'
        );
        
        // Extract gallery images from RAW content BEFORE processing shortcodes
        $gallery_image_ids = SourceHub_Gallery_Handler::extract_image_ids($raw_content);
        
        // For syndication, we want to preserve ALL shortcodes as-is
        // So we DON'T process shortcodes - just send raw content
        // The spoke site will handle gallery remapping and shortcode processing
        $processed_content = $raw_content;
        
        // Apply any custom filters but NOT do_shortcode
        $processed_content = apply_filters('sourcehub_before_syndication', $processed_content);
        
        $data = array(
            'hub_id' => $post->ID,
            'title' => $post->post_title,
            'content' => $processed_content,
            'excerpt' => $post->post_excerpt,
            'status' => $post->post_status,
            'slug' => $post->post_name,
            'date' => $post->post_date,
            'date_gmt' => $post->post_date_gmt,
            'modified' => $post->post_modified,
            'modified_gmt' => $post->post_modified_gmt,
            'post_type' => $post->post_type,
            'page_template' => $page_template,
            'hub_url' => home_url()
        );

        // Add author information
        $author = get_userdata($post->post_author);
        if ($author) {
            $data['author'] = array(
                'name' => $author->display_name,
                'email' => $author->user_email,
                'login' => $author->user_login
            );
        }

        // Add categories
        $sync_settings = json_decode($connection->sync_settings, true);
        
        $categories = get_the_category($post->ID);
        if ($categories && !empty($sync_settings['categories'])) {
            $data['categories'] = array_map(function($cat) {
                return array(
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'description' => $cat->description
                );
            }, $categories);
        }

        // Add tags
        $tags = get_the_tags($post->ID);
        if ($tags && !empty($sync_settings['tags'])) {
            $data['tags'] = array_map(function($tag) {
                return array(
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'description' => $tag->description
                );
            }, $tags);
        }

        // Add post format (standard, video, audio, etc.)
        $post_format = get_post_format($post->ID);
        if ($post_format) {
            $data['post_format'] = $post_format;
        }

        // Add featured image
        $featured_image_id = get_post_thumbnail_id($post->ID);
        
        if ($featured_image_id && !empty($sync_settings['featured_image'])) {
            $image_data = wp_get_attachment_image_src($featured_image_id, 'full');
            if ($image_data) {
                $data['featured_image'] = array(
                    'url' => $image_data[0],
                    'width' => $image_data[1],
                    'height' => $image_data[2],
                    'title' => get_the_title($featured_image_id),
                    'alt' => get_post_meta($featured_image_id, '_wp_attachment_image_alt', true),
                    'caption' => wp_get_attachment_caption($featured_image_id),
                    'description' => get_post_field('post_content', $featured_image_id)
                );
            }
        }

        // Add gallery images (already extracted from raw content above)
        if (!empty($gallery_image_ids)) {
            $data['gallery_images'] = array();
            error_log('SourceHub Gallery: Extracting data for ' . count($gallery_image_ids) . ' image IDs: ' . implode(', ', $gallery_image_ids));
            SourceHub_Logger::info('Extracting ' . count($gallery_image_ids) . ' gallery images from post', array('image_ids' => $gallery_image_ids), $post->ID, null, 'gallery_sync');
            
            foreach ($gallery_image_ids as $image_id) {
                $image_data = SourceHub_Gallery_Handler::get_image_data($image_id);
                if ($image_data) {
                    $data['gallery_images'][] = $image_data;
                    error_log('SourceHub Gallery: Successfully got data for image ' . $image_id . ' - URL: ' . $image_data['url']);
                } else {
                    error_log('SourceHub Gallery: WARNING - Failed to get data for image ID ' . $image_id);
                    SourceHub_Logger::warning('Failed to get data for gallery image ID ' . $image_id, array(), $post->ID, null, 'gallery_sync');
                }
            }
            error_log('SourceHub: Found ' . count($data['gallery_images']) . ' gallery images for post ' . $post->ID);
            SourceHub_Logger::success('Prepared ' . count($data['gallery_images']) . ' gallery images for syndication', array(), $post->ID, null, 'gallery_sync');
        } else {
            SourceHub_Logger::info('No gallery images found in post content', array(), $post->ID, null, 'gallery_sync');
        }

        // Add Yoast SEO data
        $data['yoast_meta'] = SourceHub_Yoast_Integration::get_post_meta($post->ID);

        // Add Newspaper theme data
        $data['newspaper_meta'] = SourceHub_Newspaper_Integration::get_post_meta($post->ID);

        // Add custom fields (filtered)
        $custom_fields = get_post_meta($post->ID);
        $allowed_fields = apply_filters('sourcehub_syndicated_meta_fields', array());
        
        if (!empty($allowed_fields)) {
            $data['custom_fields'] = array();
            foreach ($allowed_fields as $field) {
                if (isset($custom_fields[$field])) {
                    $data['custom_fields'][$field] = $custom_fields[$field][0];
                }
            }
        }

        // Apply AI rewriting if enabled for this connection
        if ($use_ai && class_exists('SourceHub_AI_Rewriter')) {
            $ai_rewriter = new SourceHub_AI_Rewriter();
            if ($ai_rewriter->is_configured()) {
                $data = $ai_rewriter->rewrite_content($data, json_decode($connection->ai_settings, true));
            } else {
                SourceHub_Logger::warning(
                    'AI rewriter not configured - missing OpenAI API key',
                    array('connection_name' => $connection->name),
                    $post->ID,
                    null,
                    'ai_rewrite'
                );
            }
        }

        // Process smart links for this specific spoke
        if (class_exists('SourceHub_Smart_Links')) {
            $original_content = $data['content'];
            $original_title = $data['title'];
            $original_excerpt = $data['excerpt'];
            
            error_log('SourceHub Smart Links: Starting processing for connection: ' . $connection->name . ' (' . $connection->url . ')');
            error_log('SourceHub Smart Links: Original content: ' . substr($original_content, 0, 200) . '...');
            
            // Process regular smart links in content, title, and excerpt
            $data['content'] = SourceHub_Smart_Links::process_content($data['content'], $connection);
            $data['title'] = SourceHub_Smart_Links::process_title($data['title'], $connection);
            $data['excerpt'] = SourceHub_Smart_Links::process_excerpt($data['excerpt'], $connection);
            
            // Process custom smart links in content, title, and excerpt
            $data['content'] = SourceHub_Smart_Links::process_custom_content($data['content'], $connection);
            $data['title'] = SourceHub_Smart_Links::process_custom_title($data['title'], $connection);
            $data['excerpt'] = SourceHub_Smart_Links::process_custom_excerpt($data['excerpt'], $connection);
            
            error_log('SourceHub Smart Links: Processed content: ' . substr($data['content'], 0, 200) . '...');
            
            // Log smart link processing for debugging
            SourceHub_Smart_Links::log_processing($original_content, $data['content'], $connection);
        } else {
            error_log('SourceHub Smart Links: Class not found!');
        }

        return apply_filters('sourcehub_prepare_post_data', $data, $post, $connection);
    }

    /**
     * Wake up a spoke site before sending content
     *
     * @param object $connection Connection object
     * @return bool True if wake-up successful or site already awake
     */
    private function wake_up_spoke($connection) {
        $spoke_url = rtrim($connection->url, '/');
        
        SourceHub_Logger::info(
            'Attempting to wake up spoke site',
            array('connection_name' => $connection->name, 'url' => $spoke_url),
            null,
            $connection->id,
            'wake_up'
        );
        
        // 1. Quick health check - see if site is already responsive
        $health_check = wp_remote_get($spoke_url . '/wp-json/', array(
            'timeout' => 8,
            'headers' => array(
                'User-Agent' => 'SourceHub-Health-Check/1.0'
            )
        ));
        
        if (!is_wp_error($health_check)) {
            $response_code = wp_remote_retrieve_response_code($health_check);
            if ($response_code === 200) {
                SourceHub_Logger::info(
                    'Spoke site is already awake',
                    array('connection_name' => $connection->name, 'response_code' => $response_code),
                    null,
                    $connection->id,
                    'wake_up'
                );
                return true; // Site is already awake
            }
        }
        
        // 2. Send wake-up ping to homepage
        SourceHub_Logger::info(
            'Sending wake-up ping to spoke site',
            array('connection_name' => $connection->name),
            null,
            $connection->id,
            'wake_up'
        );
        
        wp_remote_get($spoke_url, array(
            'timeout' => 15,
            'headers' => array(
                'User-Agent' => 'SourceHub-Wake-Up/1.0'
            )
        ));
        
        // 3. Brief pause to let site wake up
        sleep(2);
        
        // 4. Verify the site is now responsive
        $verify_check = wp_remote_get($spoke_url . '/wp-json/', array(
            'timeout' => 12,
            'headers' => array(
                'User-Agent' => 'SourceHub-Verify/1.0'
            )
        ));
        
        $is_awake = false;
        if (!is_wp_error($verify_check)) {
            $response_code = wp_remote_retrieve_response_code($verify_check);
            $is_awake = ($response_code === 200);
        }
        
        if ($is_awake) {
            SourceHub_Logger::info(
                'Successfully woke up spoke site',
                array('connection_name' => $connection->name),
                null,
                $connection->id,
                'wake_up'
            );
        } else {
            SourceHub_Logger::warning(
                'Wake-up attempt completed but site may still be sleeping',
                array(
                    'connection_name' => $connection->name,
                    'verify_error' => is_wp_error($verify_check) ? $verify_check->get_error_message() : 'HTTP ' . wp_remote_retrieve_response_code($verify_check)
                ),
                null,
                $connection->id,
                'wake_up'
            );
        }
        
        return $is_awake;
    }

    /**
     * AJAX handler for manual cron trigger
     */
    public function ajax_manual_cron() {
        check_ajax_referer('sourcehub_manual_cron', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        error_log('SourceHub: Manual cron triggered for post ' . $post_id);
        
        // Run the delayed sync immediately
        $this->handle_delayed_sync($post_id);
        
        wp_die('Manual cron executed');
    }

    /**
     * AJAX handler for testing connections
     */
    public function ajax_test_connection() {
        check_ajax_referer('sourcehub_test_connection', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'sourcehub'));
        }

        $connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
        $results = array();

        foreach ($connections as $connection) {
            $url = trailingslashit($connection->url) . 'wp-json/sourcehub/v1/status';
            
            $response = wp_remote_get($url, array(
                'timeout' => 10,
                'headers' => array(
                    'X-SourceHub-API-Key' => $connection->api_key
                )
            ));

            if (is_wp_error($response)) {
                $results[$connection->id] = array(
                    'success' => false,
                    'message' => sprintf(__('%s: %s', 'sourcehub'), $connection->name, $response->get_error_message())
                );
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                if (!in_array($response_code, [200, 201])) {
                    $results[$connection->id] = array(
                        'success' => false,
                        'message' => sprintf(__('%s: HTTP %d', 'sourcehub'), $connection->name, $response_code)
                    );
                } else {
                    $results[$connection->id] = array(
                        'success' => true,
                        'message' => sprintf(__('%s: Connection successful', 'sourcehub'), $connection->name)
                    );
                }
            }
        }

        wp_send_json_success($results);
    }

    /**
     * Check for delayed sync after post insertion
     */
    public function delayed_sync_check($post_id, $post, $update) {
        // Only for updates, not new posts
        if (!$update) {
            return;
        }
        
        // Check if this post should be synced
        $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        if (empty($selected_spokes)) {
            return;
        }
        
        // Check if Newspaper meta exists now
        $newspaper_meta = get_post_meta($post_id, 'td_post_theme_settings', true);
        error_log('SourceHub: delayed_sync_check for post ' . $post_id . ', Newspaper meta exists: ' . ($newspaper_meta ? 'YES' : 'NO'));
        
        if ($newspaper_meta) {
            // Trigger sync with a slight delay
            wp_schedule_single_event(time() + 2, 'sourcehub_delayed_sync', array($post_id));
        }
    }
    
    /**
     * Check when Newspaper meta is updated
     */
    public function meta_updated_check($meta_id, $post_id, $meta_key, $meta_value) {
        // Only care about Newspaper theme settings
        if ($meta_key !== 'td_post_theme_settings') {
            return;
        }
        
        error_log('SourceHub: Newspaper meta updated for post ' . $post_id . ': ' . json_encode($meta_value));
        
        // Remove from pending syncs since Newspaper has now saved its data
        if (isset($this->pending_syncs[$post_id])) {
            unset($this->pending_syncs[$post_id]);
            error_log('SourceHub: Removed post ' . $post_id . ' from pending syncs');
        }
        
        // Check if this post should be synced
        $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
        
        if (!empty($selected_spokes)) {
            // Use smart routing logic
            if (!empty($syndicated_spokes) && is_array($syndicated_spokes)) {
                error_log('SourceHub: Post already syndicated, updating existing posts with Newspaper template');
                $this->update_syndicated_post($post_id, $syndicated_spokes);
            } else {
                error_log('SourceHub: First time syndication with Newspaper template');
                $this->syndicate_post($post_id, $selected_spokes);
            }
        }
    }
    
    /**
     * Handle delayed sync
     */
    public function handle_delayed_sync($post_id) {
        error_log('SourceHub: handle_delayed_sync called for post ' . $post_id);
        
        $post = get_post($post_id);
        if ($post) {
            // Check if Newspaper meta exists now
            $newspaper_meta = get_post_meta($post_id, 'td_post_theme_settings', true);
            error_log('SourceHub: Delayed sync - Newspaper meta exists: ' . (!empty($newspaper_meta) ? 'YES' : 'NO'));
            
            $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
            $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
            
            if (!empty($selected_spokes)) {
                error_log('SourceHub: Running delayed sync for post ' . $post_id . ' to ' . count($selected_spokes) . ' spokes');
                
                // Check if this post has been syndicated before
                if (!empty($syndicated_spokes) && is_array($syndicated_spokes)) {
                    error_log('SourceHub: Post already syndicated, updating existing posts with Newspaper template');
                    $this->update_syndicated_post($post_id, $syndicated_spokes);
                } else {
                    error_log('SourceHub: First time syndication with Newspaper template');
                    $this->syndicate_post($post_id, $selected_spokes);
                }
            } else {
                error_log('SourceHub: No selected spokes found for post ' . $post_id);
            }
        } else {
            error_log('SourceHub: Post ' . $post_id . ' not found for delayed sync');
        }
    }
    
    /**
     * Final sync check - runs at the very end of admin requests
     */
    public function final_sync_check() {
        // Prevent multiple executions
        if (defined('SOURCEHUB_FINAL_SYNC_DONE')) {
            return;
        }
        define('SOURCEHUB_FINAL_SYNC_DONE', true);
        
        error_log('SourceHub: Running final sync check');
        
        // Check all pending syncs one more time
        foreach ($this->pending_syncs as $post_id => $pending) {
            if (!$pending) continue;
            
            // Check if Newspaper meta exists now
            $newspaper_meta = get_post_meta($post_id, 'td_post_theme_settings', true);
            $has_newspaper_meta = !empty($newspaper_meta);
            if ($has_newspaper_meta) {
                error_log('SourceHub: Final sync check found Newspaper meta for post ' . $post_id . ', syncing now');
                $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
                $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
                
                if (!empty($selected_spokes)) {
                    // Check if this post has been syndicated before
                    if (!empty($syndicated_spokes) && is_array($syndicated_spokes)) {
                        error_log('SourceHub: Post already syndicated, updating existing posts with Newspaper template');
                        $this->update_syndicated_post($post_id, $syndicated_spokes);
                    } else {
                        error_log('SourceHub: First time syndication with Newspaper template');
                        $this->syndicate_post($post_id, $selected_spokes);
                    }
                    // Remove from pending syncs
                    unset($this->pending_syncs[$post_id]);
                }
            }
        }
    }
    
    /**
     * Check for pending syncs at shutdown
     */
    public function check_pending_syncs() {
        // Check for pending first syncs (for Yoast meta)
        global $wpdb;
        $transients = $wpdb->get_results(
            "SELECT option_name, option_value FROM $wpdb->options 
            WHERE option_name LIKE '_transient_sourcehub_pending_first_sync_%'"
        );
        
        foreach ($transients as $transient) {
            $post_id = str_replace('_transient_sourcehub_pending_first_sync_', '', $transient->option_name);
            $new_spokes = maybe_unserialize($transient->option_value);
            
            if (!empty($new_spokes) && is_array($new_spokes)) {
                error_log('SourceHub: Processing delayed first syndication for post ' . $post_id . ' to ' . count($new_spokes) . ' spokes (Yoast meta should be ready now)');
                $this->syndicate_post($post_id, $new_spokes);
                delete_transient('sourcehub_pending_first_sync_' . $post_id);
            }
        }
        
        // Check for pending Newspaper syncs
        if (empty($this->pending_syncs)) {
            return;
        }
        
        error_log('SourceHub: Checking pending syncs at shutdown: ' . json_encode(array_keys($this->pending_syncs)));
        
        foreach ($this->pending_syncs as $post_id => $pending) {
            if (!$pending) continue;
            
            // Check if Newspaper meta exists now
            $newspaper_meta = get_post_meta($post_id, 'td_post_theme_settings', true);
            if ($newspaper_meta) {
                error_log('SourceHub: Found Newspaper meta at shutdown for post ' . $post_id . ', syncing now');
                $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
                $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
                
                if (!empty($selected_spokes)) {
                    // Check if this post has been syndicated before
                    if (!empty($syndicated_spokes) && is_array($syndicated_spokes)) {
                        error_log('SourceHub: Post already syndicated, updating existing posts with Newspaper template');
                        $this->update_syndicated_post($post_id, $syndicated_spokes);
                    } else {
                        error_log('SourceHub: First time syndication with Newspaper template');
                        $this->syndicate_post($post_id, $selected_spokes);
                    }
                }
            } else {
                error_log('SourceHub: Still no Newspaper meta at shutdown for post ' . $post_id);
            }
        }
        
        // Clear pending syncs
        $this->pending_syncs = array();
    }

    /**
     * Handle AJAX sync post request (update existing)
     */
    public function ajax_sync_post() {
        check_ajax_referer('sourcehub_sync_post', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(__('Insufficient permissions', 'sourcehub'));
            return;
        }

        $connection_id = intval($_POST['connection_id']);
        $post_id = intval($_POST['post_id']);

        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(__('Post not found', 'sourcehub'));
        }

        $connection = SourceHub_Database::get_connection($connection_id);
        if (!$connection) {
            wp_send_json_error(__('Connection not found', 'sourcehub'));
        }

        $ai_settings = json_decode($connection->ai_settings, true);
        $has_ai_enabled = !empty($ai_settings['enabled']);
        $ai_disabled_for_post = get_post_meta($post_id, '_sourcehub_ai_overrides', true);
        $ai_disabled_for_post = isset($ai_disabled_for_post['disable_ai_' . $connection_id]) ? $ai_disabled_for_post['disable_ai_' . $connection_id] : false;

        $result = $this->send_post_to_spoke($post, $connection, $has_ai_enabled && !$ai_disabled_for_post, true);

        if ($result['success']) {
            update_post_meta($post_id, '_sourcehub_last_sync', current_time('mysql'));
            wp_send_json_success($result['message']);
        } else {
            wp_send_json_error($result['message']);
        }
    }

    /**
     * Handle AJAX send to spoke request (create new)
     */
    public function ajax_send_to_spoke() {
        check_ajax_referer('sourcehub_send_to_spoke', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(__('Insufficient permissions', 'sourcehub'));
            return;
        }

        $connection_id = intval($_POST['connection_id']);
        $post_id = intval($_POST['post_id']);

        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(__('Post not found', 'sourcehub'));
        }

        $connection = SourceHub_Database::get_connection($connection_id);
        if (!$connection) {
            wp_send_json_error(__('Connection not found', 'sourcehub'));
        }

        // Get AI settings
        $ai_settings = json_decode($connection->ai_settings, true);
        $has_ai_enabled = !empty($ai_settings['enabled']);
        $ai_disabled_for_post = get_post_meta($post_id, '_sourcehub_ai_overrides', true);
        $ai_disabled_for_post = isset($ai_disabled_for_post['disable_ai_' . $connection_id]) ? $ai_disabled_for_post['disable_ai_' . $connection_id] : false;

        // Send post to spoke (create new)
        $result = $this->send_post_to_spoke($post, $connection, $has_ai_enabled && !$ai_disabled_for_post, false);

        // Get current sync status
        $sync_status = get_post_meta($post_id, '_sourcehub_sync_status', true);
        if (!is_array($sync_status)) {
            $sync_status = array();
        }

        if ($result['success']) {
            // Add this spoke to the syndicated list
            $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
            if (!is_array($syndicated_spokes)) {
                $syndicated_spokes = array();
            }
            $syndicated_spokes[] = $connection_id;
            update_post_meta($post_id, '_sourcehub_syndicated_spokes', array_unique($syndicated_spokes));
            
            // Store success status
            $sync_status[$connection_id] = array(
                'status' => 'success',
                'last_sync' => current_time('mysql'),
                'action' => 'create'
            );
            update_post_meta($post_id, '_sourcehub_sync_status', $sync_status);
            update_post_meta($post_id, '_sourcehub_last_sync', current_time('mysql'));
            
            wp_send_json_success($result['message']);
        } else {
            // Store failure status
            $sync_status[$connection_id] = array(
                'status' => 'failed',
                'last_sync' => current_time('mysql'),
                'error' => $result['message'],
                'action' => 'create'
            );
            update_post_meta($post_id, '_sourcehub_sync_status', $sync_status);
            
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Check for processing jobs that have timed out
     * Runs hourly via cron to find jobs stuck in "processing" state
     */
    public function check_processing_timeouts() {
        global $wpdb;
        
        // Find all posts with sync status
        $posts_with_status = $wpdb->get_results(
            "SELECT post_id, meta_value 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_sourcehub_sync_status'"
        );
        
        $timeout_minutes = 5;
        $timeout_seconds = $timeout_minutes * 60;
        $now = current_time('timestamp');
        
        foreach ($posts_with_status as $row) {
            $post_id = $row->post_id;
            $sync_status = maybe_unserialize($row->meta_value);
            
            if (!is_array($sync_status)) {
                continue;
            }
            
            $updated = false;
            
            foreach ($sync_status as $connection_id => $status_data) {
                // Check if status is "processing"
                if (isset($status_data['status']) && $status_data['status'] === 'processing') {
                    // Check if it has a timestamp
                    if (isset($status_data['started_at'])) {
                        $started_timestamp = strtotime($status_data['started_at']);
                        $elapsed = $now - $started_timestamp;
                        
                        // If older than 5 minutes, mark as failed
                        if ($elapsed > $timeout_seconds) {
                            $connection = SourceHub_Database::get_connection($connection_id);
                            $connection_name = $connection ? $connection->name : "Connection $connection_id";
                            
                            $sync_status[$connection_id] = array(
                                'status' => 'failed',
                                'last_sync' => current_time('mysql'),
                                'action' => $status_data['action'] ?? 'unknown',
                                'error' => sprintf('Timeout: No response after %d minutes', $timeout_minutes),
                                'started_at' => $status_data['started_at']
                            );
                            
                            $updated = true;
                            
                            // Log the timeout
                            SourceHub_Logger::error(
                                sprintf('Syndication timeout for post %d to %s after %d minutes', 
                                    $post_id, $connection_name, $timeout_minutes),
                                array(
                                    'connection_id' => $connection_id,
                                    'elapsed_seconds' => $elapsed,
                                    'started_at' => $status_data['started_at']
                                ),
                                $post_id,
                                $connection_id,
                                'timeout'
                            );
                        }
                    }
                }
            }
            
            // Update post meta if any statuses changed
            if ($updated) {
                update_post_meta($post_id, '_sourcehub_sync_status', $sync_status);
            }
        }
    }

}
