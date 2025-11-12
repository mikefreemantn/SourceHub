<?php
/**
 * Admin interface for SourceHub plugin
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Admin Class
 */
class SourceHub_Admin {

    /**
     * Track if admin has been initialized
     */
    private static $initialized = false;

    /**
     * Initialize admin functionality
     */
    public function __construct() {
        // Prevent multiple initializations
        if (self::$initialized) {
            return;
        }
        self::$initialized = true;

        add_action('admin_init', array($this, 'handle_bug_submission'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
        add_action('wp_ajax_sourcehub_save_settings', array($this, 'save_settings'));
        add_action('wp_ajax_sourcehub_test_api', array($this, 'test_api_connection'));
        add_action('wp_ajax_sourcehub_regenerate_spoke_key', array($this, 'regenerate_spoke_key'));
        add_action('wp_ajax_sourcehub_clear_logs', array($this, 'clear_logs'));
        add_action('wp_ajax_sourcehub_delete_log', array($this, 'delete_log'));
        add_action('wp_ajax_sourcehub_export_logs', array($this, 'export_logs'));
        add_action('wp_ajax_sourcehub_get_logs', array($this, 'ajax_get_logs'));
        add_action('wp_ajax_sourcehub_check_timeouts', array($this, 'ajax_check_timeouts'));
        add_action('wp_ajax_sourcehub_get_connection', array($this, 'get_connection'));
        add_action('wp_ajax_sourcehub_check_sync_status', array($this, 'check_sync_status'));
        add_action('admin_notices', array($this, 'admin_notices'));
        add_filter('admin_footer_text', array($this, 'admin_footer_text'));
        add_action('admin_bar_menu', array($this, 'add_admin_bar_badge'), 100);
    }

    /**
     * Handle bug submission, archive, and unarchive before any output
     */
    public function handle_bug_submission() {
        // Only process on bug tracker pages
        if (!isset($_GET['page']) || $_GET['page'] !== 'sourcehub-bugs') {
            return;
        }
        
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        // Handle bug submission
        if (isset($_GET['action']) && $_GET['action'] === 'submit' && isset($_POST['submit_bug'])) {
            // Verify nonce
            if (!isset($_POST['sourcehub_bug_nonce']) || !wp_verify_nonce($_POST['sourcehub_bug_nonce'], 'submit_bug')) {
                return;
            }
            
            // Submit bug
            $bug_id = SourceHub_Bug_Tracker::submit_bug($_POST);
            
            if ($bug_id) {
                // Redirect to bug detail page with success message
                wp_safe_redirect(admin_url('admin.php?page=sourcehub-bugs&action=view&bug_id=' . $bug_id . '&submitted=1'));
                exit;
            }
        }
        
        // Handle bug archiving
        if (isset($_POST['delete_bug']) && isset($_GET['bug_id'])) {
            $bug_id = intval($_GET['bug_id']);
            
            // Verify nonce
            if (!isset($_POST['sourcehub_bug_nonce']) || !wp_verify_nonce($_POST['sourcehub_bug_nonce'], 'update_bug_' . $bug_id)) {
                return;
            }
            
            SourceHub_Bug_Tracker::delete_bug($bug_id);
            wp_safe_redirect(admin_url('admin.php?page=sourcehub-bugs&archived=1'));
            exit;
        }
        
        // Handle bug unarchiving
        if (isset($_POST['unarchive_bug']) && isset($_GET['bug_id'])) {
            $bug_id = intval($_GET['bug_id']);
            
            // Verify nonce
            if (!isset($_POST['sourcehub_bug_nonce']) || !wp_verify_nonce($_POST['sourcehub_bug_nonce'], 'update_bug_' . $bug_id)) {
                return;
            }
            
            SourceHub_Bug_Tracker::unarchive_bug($bug_id);
            wp_safe_redirect(admin_url('admin.php?page=sourcehub-bugs&unarchived=1'));
            exit;
        }
    }

    /**
     * Add SourceHub version to admin footer
     */
    public function admin_footer_text($text) {
        $screen = get_current_screen();
        if ($screen && strpos($screen->id, 'sourcehub') !== false) {
            $text = sprintf(
                __('SourceHub v%s | Thank you for using SourceHub!', 'sourcehub'),
                SOURCEHUB_VERSION
            );
        }
        return $text;
    }

    /**
     * Add admin menu pages
     */
    public function add_admin_menu() {
        // Check if user has any SourceHub access
        if (!$this->user_can_access_sourcehub()) {
            return; // No menu for users without access
        }

        $mode = sourcehub()->get_mode();
        
        // Determine capability based on user role
        $dashboard_capability = $this->get_dashboard_capability();
        $admin_capability = $this->get_admin_capability();

        // Main menu page - accessible to editors and above
        add_menu_page(
            __('SourceHub', 'sourcehub'),
            __('SourceHub', 'sourcehub'),
            $dashboard_capability,
            'sourcehub',
            array($this, 'render_dashboard'),
            'dashicons-networking',
            30
        );

        // Dashboard (same as main page) - accessible to editors and above
        add_submenu_page(
            'sourcehub',
            __('Dashboard', 'sourcehub'),
            __('Dashboard', 'sourcehub'),
            $dashboard_capability,
            'sourcehub',
            array($this, 'render_dashboard')
        );

        // Admin-only pages (connections and settings)
        if ($mode === 'hub') {
            // Spoke Connections - Admin only
            add_submenu_page(
                'sourcehub',
                __('Spoke Connections', 'sourcehub'),
                __('Spoke Connections', 'sourcehub'),
                $admin_capability,
                'sourcehub-connections',
                array($this, 'render_connections')
            );
            
            // Add Spoke Site - Admin only
            add_submenu_page(
                'sourcehub',
                __('Add Spoke Site', 'sourcehub'),
                __('Add Spoke Site', 'sourcehub'),
                $admin_capability,
                'sourcehub-add-spoke',
                array($this, 'render_add_spoke')
            );
        }
        // Note: Spoke mode does not have a connections page because
        // connections are always initiated from the hub, never from the spoke

        // Settings page - Admin only
        add_submenu_page(
            'sourcehub',
            __('Settings', 'sourcehub'),
            __('Settings', 'sourcehub'),
            $admin_capability,
            'sourcehub-settings',
            array($this, 'render_settings')
        );

        // Logs page - accessible to editors and above
        add_submenu_page(
            'sourcehub',
            __('Activity Logs', 'sourcehub'),
            __('Activity Logs', 'sourcehub'),
            $dashboard_capability,
            'sourcehub-logs',
            array($this, 'render_logs')
        );

        // Bug Tracker - accessible to editors and above
        add_submenu_page(
            'sourcehub',
            __('Bug Tracker', 'sourcehub'),
            __('Bug Tracker', 'sourcehub'),
            $dashboard_capability,
            'sourcehub-bugs',
            array($this, 'render_bug_tracker')
        );

        // Smart Links Documentation (only for hub mode) - accessible to editors and above
        if ($mode === 'hub') {
            add_submenu_page(
                'sourcehub',
                __('Smart Links Guide', 'sourcehub'),
                __('Smart Links Guide', 'sourcehub'),
                $dashboard_capability,
                'sourcehub-smart-links',
                array($this, 'render_smart_links_guide')
            );
        }
    }

    /**
     * Check if current user can access SourceHub
     *
     * @return bool
     */
    private function user_can_access_sourcehub() {
        return current_user_can('edit_posts') || current_user_can('manage_options');
    }

    /**
     * Get capability required for dashboard access
     *
     * @return string
     */
    private function get_dashboard_capability() {
        // Allow editors, authors, contributors, and admins
        return 'edit_posts';
    }

    /**
     * Get capability required for admin functions
     *
     * @return string
     */
    private function get_admin_capability() {
        // Only administrators
        return 'manage_options';
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook Current admin page hook
     */
    public function enqueue_admin_scripts($hook) {
        // Load TinyMCE shortcode buttons on post edit pages
        global $pagenow;
        if (in_array($pagenow, array('post.php', 'post-new.php'))) {
            wp_enqueue_script(
                'sourcehub-tinymce-shortcodes',
                SOURCEHUB_PLUGIN_URL . 'assets/js/tinymce-shortcodes.js',
                array('jquery', 'editor'),
                SOURCEHUB_VERSION,
                true
            );
            
            wp_enqueue_style(
                'sourcehub-tinymce-shortcodes',
                SOURCEHUB_PLUGIN_URL . 'assets/css/tinymce-shortcodes.css',
                array(),
                SOURCEHUB_VERSION
            );
            
            // Enqueue sync status polling script
            wp_enqueue_script(
                'sourcehub-sync-status',
                SOURCEHUB_PLUGIN_URL . 'admin/js/sync-status.js',
                array('jquery'),
                SOURCEHUB_VERSION,
                true
            );
            
            // Localize script for TinyMCE buttons and sync status
            wp_localize_script('sourcehub-tinymce-shortcodes', 'sourcehubAdmin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('sourcehub_admin_nonce'),
                'mode' => sourcehub()->get_mode()
            ));
        }
        
        // Only load on SourceHub admin pages
        if (strpos($hook, 'sourcehub') === false) {
            return;
        }

        // Enqueue WordPress media scripts for image uploads
        wp_enqueue_media();

        // Enqueue admin styles
        wp_enqueue_style(
            'sourcehub-admin',
            SOURCEHUB_PLUGIN_URL . 'admin/css/admin.css',
            array(),
            SOURCEHUB_VERSION
        );

        // Enqueue WordPress REST API script for nonce
        wp_enqueue_script('wp-api');
        
        // Enqueue admin scripts
        wp_enqueue_script(
            'sourcehub-admin',
            SOURCEHUB_PLUGIN_URL . 'admin/js/admin.js',
            array('jquery', 'wp-util', 'wp-api'),
            SOURCEHUB_VERSION,
            true
        );

        // Localize script with data
        wp_localize_script('sourcehub-admin', 'sourcehub_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url('sourcehub/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'ajax_nonce' => wp_create_nonce('sourcehub_admin_nonce'),
            'mode' => sourcehub()->get_mode(),
            'strings' => array(
                'confirm_delete' => __('Are you sure you want to delete this connection?', 'sourcehub'),
                'testing_connection' => __('Testing connection...', 'sourcehub'),
                'connection_successful' => __('Connection successful!', 'sourcehub'),
                'connection_failed' => __('Connection failed', 'sourcehub'),
                'saving' => __('Saving...', 'sourcehub'),
                'saved' => __('Settings saved!', 'sourcehub'),
                'error' => __('An error occurred', 'sourcehub')
            )
        ));
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets() {
        // Only load in hub mode
        if (sourcehub()->get_mode() !== 'hub') {
            return;
        }

        // Enqueue block editor script
        wp_enqueue_script(
            'sourcehub-block-editor',
            SOURCEHUB_PLUGIN_URL . 'admin/js/block-editor.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-rich-text'),
            SOURCEHUB_VERSION,
            true
        );

        // Enqueue block editor styles
        wp_enqueue_style(
            'sourcehub-block-editor',
            SOURCEHUB_PLUGIN_URL . 'admin/css/block-editor.css',
            array(),
            SOURCEHUB_VERSION
        );

        // Get spoke connections for smart link processing
        $connections = array();
        try {
            $connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
        } catch (Exception $e) {
            error_log('SourceHub: Error getting connections for block editor - ' . $e->getMessage());
        }

        // Localize script with spoke data
        wp_localize_script('sourcehub-block-editor', 'sourcehub_editor', array(
            'spokes' => $connections,
            'strings' => array(
                'smart_link' => __('Smart Link', 'sourcehub'),
                'add_smart_link' => __('Add Smart Link', 'sourcehub'),
                'url_path' => __('URL Path', 'sourcehub'),
                'apply_smart_link' => __('Apply Smart Link', 'sourcehub'),
                'custom_smart_link' => __('Custom Smart Link', 'sourcehub'),
                'add_custom_smart_link' => __('Add Custom Smart Link', 'sourcehub'),
                'apply_custom_smart_link' => __('Apply Custom Smart Link', 'sourcehub'),
                'cancel' => __('Cancel', 'sourcehub')
            )
        ));
    }

    /**
     * Render dashboard page
     */
    public function render_dashboard() {
        // Check permissions
        if (!current_user_can('edit_posts')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mode = sourcehub()->get_mode();
        
        // Get stats with error handling
        $stats = array(
            'total_logs' => 0,
            'success_rate' => 0
        );
        try {
            $stats = SourceHub_Logger::get_stats(array('days' => 30));
        } catch (Exception $e) {
            error_log('SourceHub: Error getting stats - ' . $e->getMessage());
        }
        
        // Get connections with error handling
        $connections = array();
        $active_connections = array();
        try {
            if ($mode === 'hub') {
                $connections = SourceHub_Database::get_connections(array('mode' => 'spoke', 'status' => null));
            } else {
                $connections = SourceHub_Database::get_connections(array('mode' => 'hub', 'status' => null));
            }
            
            if (is_array($connections)) {
                $active_connections = array_filter($connections, function($conn) {
                    return isset($conn->status) && $conn->status === 'active';
                });
            }
        } catch (Exception $e) {
            error_log('SourceHub: Error getting connections - ' . $e->getMessage());
            $connections = array();
            $active_connections = array();
        }

        // Get recent logs with error handling
        $recent_logs = array();
        try {
            $recent_logs = SourceHub_Logger::get_formatted_logs(array('limit' => 10));
            if (!is_array($recent_logs)) {
                $recent_logs = array();
            }
        } catch (Exception $e) {
            error_log('SourceHub: Error getting recent logs - ' . $e->getMessage());
            $recent_logs = array();
        }
        
        // Get spoke-specific stats if in spoke mode
        $spoke_stats = array(
            'total_syndicated' => 0,
            'syndicated_this_month' => 0,
            'last_sync' => null
        );
        
        if ($mode === 'spoke') {
            try {
                // Total syndicated posts (posts with _sourcehub_hub_id meta)
                $total_query = new WP_Query(array(
                    'post_type' => 'any',
                    'post_status' => 'any',
                    'meta_key' => '_sourcehub_hub_id',
                    'fields' => 'ids',
                    'posts_per_page' => -1,
                    'no_found_rows' => false,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false
                ));
                $spoke_stats['total_syndicated'] = $total_query->found_posts;
                
                // Posts syndicated in last 30 days
                $month_query = new WP_Query(array(
                    'post_type' => 'any',
                    'post_status' => 'any',
                    'meta_key' => '_sourcehub_hub_id',
                    'date_query' => array(
                        array(
                            'after' => '30 days ago',
                            'inclusive' => true
                        )
                    ),
                    'fields' => 'ids',
                    'posts_per_page' => -1,
                    'no_found_rows' => false,
                    'update_post_meta_cache' => false,
                    'update_post_term_cache' => false
                ));
                $spoke_stats['syndicated_this_month'] = $month_query->found_posts;
                
                // Last sync time (most recent post with _sourcehub_hub_id)
                $last_sync_query = new WP_Query(array(
                    'post_type' => 'any',
                    'post_status' => 'any',
                    'meta_key' => '_sourcehub_hub_id',
                    'posts_per_page' => 1,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'fields' => 'ids'
                ));
                
                if ($last_sync_query->have_posts()) {
                    $last_post_id = $last_sync_query->posts[0];
                    $last_post = get_post($last_post_id);
                    if ($last_post) {
                        $spoke_stats['last_sync'] = $last_post->post_date;
                    }
                }
            } catch (Exception $e) {
                error_log('SourceHub: Error getting spoke stats - ' . $e->getMessage());
            }
        }

        include SOURCEHUB_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Render connections page
     */
    public function render_connections() {
        // Check admin permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mode = sourcehub()->get_mode();
        $connection_mode = $mode === 'hub' ? 'spoke' : 'hub';
        $connections = SourceHub_Database::get_connections(array('mode' => $connection_mode, 'status' => null));

        include SOURCEHUB_PLUGIN_DIR . 'admin/views/connections.php';
    }
    
    /**
     * Render add spoke site page
     */
    public function render_add_spoke() {
        // Check admin permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // Only available in hub mode
        $mode = sourcehub()->get_mode();
        if ($mode !== 'hub') {
            wp_redirect(admin_url('admin.php?page=sourcehub-connections'));
            exit;
        }
        
        include SOURCEHUB_PLUGIN_DIR . 'admin/views/add-spoke.php';
    }

    /**
     * Render settings page
     */
    public function render_settings() {
        // Check admin permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $mode = sourcehub()->get_mode();
        $ai_models = SourceHub_AI_Rewriter::get_available_models();
        $ai_tones = SourceHub_AI_Rewriter::get_available_tones();
        $length_adjustments = SourceHub_AI_Rewriter::get_length_adjustments();

        // Get current settings
        $settings = array(
            'mode' => $mode,
            'openai_api_key' => get_option('sourcehub_openai_api_key', ''),
            'openai_model' => get_option('sourcehub_openai_model', 'gpt-4'),
            'ai_max_words' => get_option('sourcehub_ai_max_words', 2000),
            'default_author' => get_option('sourcehub_default_author', 1),
            'attribution_text' => get_option('sourcehub_attribution_text', 'Originally published on {hub_name}'),
            'log_retention_days' => get_option('sourcehub_log_retention_days', 30),
            'enable_debug_logging' => get_option('sourcehub_enable_debug_logging', false),
            'auto_publish' => get_option('sourcehub_auto_publish', true),
            'preserve_author' => get_option('sourcehub_preserve_author', true),
            'sync_featured_image' => get_option('sourcehub_sync_featured_image', true),
            'sync_categories' => get_option('sourcehub_sync_categories', true),
            'sync_tags' => get_option('sourcehub_sync_tags', true)
        );

        include SOURCEHUB_PLUGIN_DIR . 'admin/views/settings.php';
    }

    /**
     * Render logs page
     */
    public function render_logs() {
        // Check permissions
        if (!current_user_can('edit_posts')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $per_page = 20;
        
        // Get filters from GET parameters
        $status_filter = isset($_GET['log_level']) ? sanitize_text_field($_GET['log_level']) : '';
        $action_filter = isset($_GET['log_action']) ? sanitize_text_field($_GET['log_action']) : '';
        $date_filter = isset($_GET['log_date']) ? sanitize_text_field($_GET['log_date']) : '';
        $search_filter = isset($_GET['log_search']) ? sanitize_text_field($_GET['log_search']) : '';

        $args = array(
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'order' => 'DESC'
        );

        if ($status_filter) {
            $args['status'] = $status_filter;
        }
        
        if ($action_filter) {
            $args['action'] = $action_filter;
        }
        
        if ($date_filter) {
            $args['date'] = $date_filter;
        }
        
        if ($search_filter) {
            $args['search'] = $search_filter;
        }

        // Start profiling
        $start_time = microtime(true);
        
        $time_before_logs = microtime(true);
        $logs = SourceHub_Logger::get_formatted_logs($args);
        $logs_time = microtime(true) - $time_before_logs;
        
        $time_before_count = microtime(true);
        $total_logs = SourceHub_Database::count_logs($args);
        $count_time = microtime(true) - $time_before_count;
        
        $total_pages = ceil($total_logs / $per_page);

        $time_before_stats = microtime(true);
        $stats = SourceHub_Logger::get_stats(array('days' => 7));
        $stats_time = microtime(true) - $time_before_stats;

        // Log performance metrics
        $total_time = microtime(true) - $start_time;
        error_log(sprintf('[SourceHub Performance] Logs Page - Total: %.2fs | Logs Query: %.2fs | Count: %.2fs | Stats: %.2fs | Total Logs: %d', 
            $total_time, $logs_time, $count_time, $stats_time, $total_logs));

        // Set variables for the template
        $current_page = $page;
        $mode = sourcehub()->get_mode();

        include SOURCEHUB_PLUGIN_DIR . 'admin/views/logs.php';
    }

    /**
     * Save settings via AJAX
     */
    public function save_settings() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'sourcehub'));
        }

        $settings = isset($_POST['settings']) ? $_POST['settings'] : array();

        // Sanitize and save settings
        $this->save_setting('sourcehub_mode', sanitize_text_field($settings['mode'] ?? 'hub'));
        $this->save_setting('sourcehub_openai_api_key', sanitize_text_field($settings['openai_api_key'] ?? ''));
        $this->save_setting('sourcehub_openai_model', sanitize_text_field($settings['openai_model'] ?? 'gpt-4'));
        $this->save_setting('sourcehub_ai_max_words', intval($settings['ai_max_words'] ?? 2000));
        $this->save_setting('sourcehub_default_author', intval($settings['default_author'] ?? 1));
        $this->save_setting('sourcehub_attribution_text', sanitize_textarea_field($settings['attribution_text'] ?? ''));
        $this->save_setting('sourcehub_log_retention_days', intval($settings['log_retention_days'] ?? 30));
        $this->save_setting('sourcehub_enable_debug_logging', !empty($settings['enable_debug_logging']));
        $this->save_setting('sourcehub_auto_publish', !empty($settings['auto_publish']));
        $this->save_setting('sourcehub_preserve_author', !empty($settings['preserve_author']));
        $this->save_setting('sourcehub_sync_featured_image', !empty($settings['sync_featured_image']));
        $this->save_setting('sourcehub_sync_categories', !empty($settings['sync_categories']));
        $this->save_setting('sourcehub_sync_tags', !empty($settings['sync_tags']));

        SourceHub_Logger::success(
            'Settings updated successfully',
            $settings,
            null,
            null,
            'settings_update'
        );

        wp_send_json_success(array(
            'message' => __('Settings saved successfully!', 'sourcehub')
        ));
    }

    /**
     * Test API connection via AJAX
     */
    public function test_api_connection() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'sourcehub'));
        }

        $api_key = sanitize_text_field($_POST['api_key'] ?? '');
        
        if (empty($api_key)) {
            wp_send_json_error(array(
                'message' => __('API key is required', 'sourcehub')
            ));
        }

        // Temporarily set the API key for testing
        $original_key = get_option('sourcehub_openai_api_key');
        update_option('sourcehub_openai_api_key', $api_key);

        $ai_rewriter = new SourceHub_AI_Rewriter();
        $result = $ai_rewriter->test_api_connection();

        // Restore original key
        update_option('sourcehub_openai_api_key', $original_key);

        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }

    /**
     * Regenerate spoke API key via AJAX
     */
    public function regenerate_spoke_key() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'sourcehub'));
        }

        // Generate new API key
        $new_api_key = wp_generate_password(32, false);
        
        // Save the new API key
        update_option('sourcehub_spoke_api_key', $new_api_key);
        
        // Log the regeneration
        SourceHub_Logger::info(
            'Spoke API key regenerated',
            array('new_key_length' => strlen($new_api_key)),
            null,
            null,
            'api_key_regenerated'
        );

        wp_send_json_success(array(
            'message' => __('API key regenerated successfully!', 'sourcehub'),
            'api_key' => $new_api_key
        ));
    }

    /**
     * Save individual setting
     *
     * @param string $option_name Option name
     * @param mixed $value Option value
     */
    private function save_setting($option_name, $value) {
        update_option($option_name, $value);
    }

    /**
     * Display admin notices
     */
    public function admin_notices() {
        $screen = get_current_screen();
        
        if (!$screen || strpos($screen->id, 'sourcehub') === false) {
            return;
        }

        // Check database tables
        global $wpdb;
        $connections_table = $wpdb->prefix . 'sourcehub_connections';
        $logs_table = $wpdb->prefix . 'sourcehub_logs';
        $queue_table = $wpdb->prefix . 'sourcehub_queue';
        
        $missing_tables = array();
        if ($wpdb->get_var("SHOW TABLES LIKE '$connections_table'") != $connections_table) {
            $missing_tables[] = 'connections';
        }
        if ($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") != $logs_table) {
            $missing_tables[] = 'logs';
        }
        if ($wpdb->get_var("SHOW TABLES LIKE '$queue_table'") != $queue_table) {
            $missing_tables[] = 'queue';
        }
        
        if (!empty($missing_tables)) {
            echo '<div class="notice notice-error"><p>';
            echo sprintf(
                __('SourceHub database tables are missing (%s). Please deactivate and reactivate the plugin to fix this issue.', 'sourcehub'),
                implode(', ', $missing_tables)
            );
            echo '</p></div>';
        }

        // Check if Yoast SEO is active
        if (!SourceHub_Yoast_Integration::is_yoast_active()) {
            echo '<div class="notice notice-warning"><p>';
            echo sprintf(
                __('SourceHub works best with Yoast SEO plugin. <a href="%s" target="_blank">Install Yoast SEO</a> for full SEO metadata synchronization.', 'sourcehub'),
                admin_url('plugin-install.php?s=yoast+seo&tab=search&type=term')
            );
            echo '</p></div>';
        }

        // Check if mode is set
        $mode = sourcehub()->get_mode();
        if (empty($mode) || !in_array($mode, array('hub', 'spoke'))) {
            echo '<div class="notice notice-error"><p>';
            echo sprintf(
                __('Please configure SourceHub mode in <a href="%s">Settings</a>.', 'sourcehub'),
                admin_url('admin.php?page=sourcehub-settings')
            );
            echo '</p></div>';
        }

        // Check for AI configuration if needed (only if tables exist)
        if ($mode === 'hub' && empty($missing_tables)) {
            try {
                $connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
                $has_ai_enabled = false;
                
                foreach ($connections as $connection) {
                    $ai_settings = json_decode($connection->ai_settings, true);
                    if (!empty($ai_settings['enabled'])) {
                        $has_ai_enabled = true;
                        break;
                    }
                }

                if ($has_ai_enabled && !get_option('sourcehub_openai_api_key')) {
                    echo '<div class="notice notice-warning"><p>';
                    echo sprintf(
                        __('AI rewriting is enabled but OpenAI API key is not configured. <a href="%s">Configure API key</a> in Settings.', 'sourcehub'),
                        admin_url('admin.php?page=sourcehub-settings')
                    );
                    echo '</p></div>';
                }
            } catch (Exception $e) {
                // Silently handle database errors here
            }
        }
    }

    /**
     * Get connection status badge HTML
     *
     * @param object $connection Connection object
     * @return string HTML for status badge
     */
    public static function get_status_badge($connection) {
        $status = $connection->status;
        $class = '';
        $text = '';

        switch ($status) {
            case 'active':
                $class = 'success';
                $text = __('Active', 'sourcehub');
                break;
            case 'inactive':
                $class = 'secondary';
                $text = __('Inactive', 'sourcehub');
                break;
            case 'error':
                $class = 'danger';
                $text = __('Error', 'sourcehub');
                break;
            default:
                $class = 'secondary';
                $text = ucfirst($status);
        }

        return sprintf('<span class="badge badge-%s">%s</span>', $class, $text);
    }

    /**
     * Get log level badge HTML
     *
     * @param string $level Log level
     * @return string HTML for level badge
     */
    public static function get_log_level_badge($level) {
        $class = '';
        
        switch (strtoupper($level)) {
            case 'SUCCESS':
                $class = 'success';
                break;
            case 'ERROR':
                $class = 'danger';
                break;
            case 'WARNING':
                $class = 'warning';
                break;
            case 'INFO':
                $class = 'info';
                break;
            default:
                $class = 'secondary';
        }

        return sprintf('<span class="badge badge-%s">%s</span>', $class, strtoupper($level));
    }

    /**
     * Format file size
     *
     * @param int $size Size in bytes
     * @return string Formatted size
     */
    public static function format_file_size($size) {
        $units = array('B', 'KB', 'MB', 'GB');
        $unit_index = 0;
        
        while ($size >= 1024 && $unit_index < count($units) - 1) {
            $size /= 1024;
            $unit_index++;
        }
        
        return round($size, 2) . ' ' . $units[$unit_index];
    }

    /**
     * Format datetime for display
     *
     * @param string $datetime Datetime string
     * @return string Formatted date and time
     */
    public static function time_ago($datetime) {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return __('Never', 'sourcehub');
        }

        // Calculate time difference
        $time_diff = current_time('timestamp') - strtotime($datetime);

        // If less than 1 minute, show "Just now"
        if ($time_diff < 60) {
            return __('Just now', 'sourcehub');
        }

        // Otherwise show formatted date/time
        return date_i18n('n/j/y g:i A', strtotime($datetime));
    }

    /**
     * Get connection data for editing via AJAX
     */
    public function get_connection() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'sourcehub'));
        }

        $connection_id = intval($_POST['connection_id']);
        
        if (!$connection_id) {
            wp_send_json_error(array(
                'message' => __('Invalid connection ID', 'sourcehub')
            ));
        }

        try {
            // Check if database class exists
            if (!class_exists('SourceHub_Database')) {
                wp_send_json_error(array(
                    'message' => __('Database class not available', 'sourcehub')
                ));
                return;
            }

            $connection = SourceHub_Database::get_connection($connection_id);
            
            if (!$connection) {
                wp_send_json_error(array(
                    'message' => __('Connection not found', 'sourcehub')
                ));
                return;
            }

            wp_send_json_success($connection);

        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Failed to load connection: ', 'sourcehub') . $e->getMessage()
            ));
        }
    }

    /**
     * Check syndication status via AJAX
     */
    public function check_sync_status() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'sourcehub')));
        }

        $post_id = intval($_POST['post_id']);
        
        if (!$post_id) {
            wp_send_json_error(array('message' => __('Invalid post ID', 'sourcehub')));
        }

        // Get overall sync status from transient
        $overall_status = get_transient('sourcehub_sync_status_' . $post_id);
        
        // Get per-spoke status from post meta
        $per_spoke_status = get_post_meta($post_id, '_sourcehub_sync_status', true);
        if (!is_array($per_spoke_status)) {
            $per_spoke_status = array();
        }
        
        // Get syndicated spokes
        $syndicated_spokes = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
        if (!is_array($syndicated_spokes)) {
            $syndicated_spokes = array();
        }
        
        // Determine overall status
        $status = 'none';
        if ($overall_status && isset($overall_status['status'])) {
            $status = $overall_status['status'];
        } elseif (!empty($syndicated_spokes)) {
            $status = 'completed';
        }

        // Return current status with per-spoke details
        wp_send_json_success(array(
            'status' => $status,
            'spokes' => $syndicated_spokes,
            'count' => count($syndicated_spokes),
            'per_spoke_status' => $per_spoke_status,
            'started' => isset($overall_status['started']) ? $overall_status['started'] : null,
            'completed' => isset($overall_status['completed']) ? $overall_status['completed'] : null,
            'error' => isset($overall_status['error']) ? $overall_status['error'] : null
        ));
    }

    /**
     * Render Bug Tracker page
     */
    public function render_bug_tracker() {
        // Check permissions
        if (!current_user_can('edit_posts')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        include SOURCEHUB_PLUGIN_DIR . 'admin/views/bug-tracker.php';
    }

    /**
     * Render Smart Links Guide page
     */
    public function render_smart_links_guide() {
        // Check permissions
        if (!current_user_can('edit_posts')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $documentation = SourceHub_Shortcodes::get_documentation();
        include SOURCEHUB_PLUGIN_DIR . 'admin/views/smart-links-guide.php';
    }

    /**
     * Clear all logs via AJAX
     */
    public function clear_logs() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => __('Permission denied', 'sourcehub')
            ));
            return;
        }

        try {
            SourceHub_Logger::clear_logs();
            
            wp_send_json_success(array(
                'message' => __('All logs have been cleared', 'sourcehub')
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Failed to clear logs: ', 'sourcehub') . $e->getMessage()
            ));
        }
    }

    /**
     * Check for timed-out processing jobs via AJAX
     */
    public function ajax_check_timeouts() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => __('Permission denied', 'sourcehub')
            ));
            return;
        }

        // Only available in hub mode
        if (sourcehub()->get_mode() !== 'hub') {
            wp_send_json_error(array(
                'message' => __('This feature is only available in hub mode', 'sourcehub')
            ));
            return;
        }

        try {
            // Count how many will be found before running
            global $wpdb;
            $posts_with_status = $wpdb->get_results(
                "SELECT post_id, meta_value 
                FROM {$wpdb->postmeta} 
                WHERE meta_key = '_sourcehub_sync_status'"
            );
            
            $timeout_count = 0;
            $timeout_seconds = 5 * 60;
            $now = current_time('timestamp');
            
            foreach ($posts_with_status as $row) {
                $sync_status = maybe_unserialize($row->meta_value);
                if (!is_array($sync_status)) continue;
                
                foreach ($sync_status as $status_data) {
                    if (isset($status_data['status']) && $status_data['status'] === 'processing') {
                        if (isset($status_data['started_at'])) {
                            $started_timestamp = strtotime($status_data['started_at']);
                            $elapsed = $now - $started_timestamp;
                            if ($elapsed > $timeout_seconds) {
                                $timeout_count++;
                            }
                        }
                    }
                }
            }
            
            // Run the timeout check using the global sourcehub instance
            sourcehub()->hub_manager->check_processing_timeouts();
            
            wp_send_json_success(array(
                'message' => sprintf(__('Checked for timed-out jobs. Found: %d', 'sourcehub'), $timeout_count),
                'found' => $timeout_count
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => $e->getMessage()
            ));
        }
    }
    
    /**
     * Delete individual log via AJAX
     */
    public function delete_log() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array(
                'message' => __('Permission denied', 'sourcehub')
            ));
            return;
        }

        $log_id = isset($_POST['log_id']) ? intval($_POST['log_id']) : 0;

        if (!$log_id) {
            wp_send_json_error(array(
                'message' => __('Invalid log ID', 'sourcehub')
            ));
            return;
        }

        try {
            SourceHub_Logger::delete_log($log_id);
            
            wp_send_json_success(array(
                'message' => __('Log entry deleted', 'sourcehub')
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Failed to delete log: ', 'sourcehub') . $e->getMessage()
            ));
        }
    }

    /**
     * Get logs via AJAX
     */
    public function ajax_get_logs() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array(
                'message' => __('Permission denied', 'sourcehub')
            ));
            return;
        }

        try {
            $page = max(1, intval($_POST['page']));
            $per_page = min(100, max(1, intval($_POST['per_page'])));
            
            $args = array(
                'limit' => $per_page,
                'offset' => ($page - 1) * $per_page,
                'order' => 'DESC'
            );

            // Add filters if provided
            if (!empty($_POST['level'])) {
                $args['status'] = sanitize_text_field($_POST['level']);
            }
            if (!empty($_POST['action'])) {
                $args['action'] = sanitize_text_field($_POST['action']);
            }

            $logs = SourceHub_Logger::get_formatted_logs($args);
            $total = SourceHub_Database::count_logs($args);

            wp_send_json_success(array(
                'logs' => $logs,
                'pagination' => array(
                    'page' => $page,
                    'per_page' => $per_page,
                    'total' => $total,
                    'total_pages' => ceil($total / $per_page)
                )
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Failed to load logs: ', 'sourcehub') . $e->getMessage()
            ));
        }
    }

    /**
     * Export logs via AJAX
     */
    public function export_logs() {
        check_ajax_referer('sourcehub_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied', 'sourcehub'));
        }

        try {
            // Get filters - map 'level' to 'status' for database query
            $filters = array(
                'limit' => 1000, // Limit export to 1000 most recent logs
                'order' => 'DESC'
            );
            
            if (!empty($_GET['level'])) {
                $filters['status'] = sanitize_text_field($_GET['level']);
            }
            if (!empty($_GET['log_action_filter'])) {
                $filters['action'] = sanitize_text_field($_GET['log_action_filter']);
            }
            if (!empty($_GET['date'])) {
                $filters['date'] = sanitize_text_field($_GET['date']);
            }

            // Get logs
            $logs = SourceHub_Logger::get_formatted_logs($filters);
            
            // Debug: Log the count
            error_log('SourceHub Export: Retrieved ' . count($logs) . ' logs with filters: ' . print_r($filters, true));
            
            if (empty($logs)) {
                wp_die('No logs found to export. Try adjusting your filters or check if logs exist in the database.');
            }

            // Generate CSV
            $filename = 'sourcehub-logs-' . date('Y-m-d-His') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, array('Date', 'Status', 'Action', 'Message', 'Connection', 'Details'));
            
            // Add log rows
            foreach ($logs as $log) {
                // Handle details/data field - could be in either property
                $details = '';
                if (!empty($log->details)) {
                    $details = is_string($log->details) ? $log->details : json_encode($log->details);
                } elseif (!empty($log->data)) {
                    $details = is_string($log->data) ? $log->data : json_encode($log->data);
                }
                
                fputcsv($output, array(
                    !empty($log->created_at) ? $log->created_at : '',
                    !empty($log->status) ? $log->status : '',
                    !empty($log->action) ? $log->action : '',
                    !empty($log->message) ? $log->message : '',
                    !empty($log->connection_name) ? $log->connection_name : '-',
                    $details
                ));
            }
            
            fclose($output);
            exit;

        } catch (Exception $e) {
            wp_die(__('Failed to export logs: ', 'sourcehub') . $e->getMessage());
        }
    }

    /**
     * Add mode badge to admin bar
     */
    public function add_admin_bar_badge($wp_admin_bar) {
        $mode = sourcehub()->get_mode();
        
        if (empty($mode)) {
            return;
        }

        $badge_text = $mode === 'hub' ? __('Hub Site', 'sourcehub') : __('Spoke Site', 'sourcehub');
        $badge_class = $mode === 'hub' ? 'sourcehub-admin-bar-hub' : 'sourcehub-admin-bar-spoke';

        $wp_admin_bar->add_node(array(
            'id'     => 'sourcehub-mode-badge',
            'parent' => 'top-secondary',
            'title'  => '<span class="' . $badge_class . '">' . $badge_text . '</span>',
            'href'   => admin_url('admin.php?page=sourcehub'),
            'meta'   => array(
                'class' => 'sourcehub-admin-bar-item'
            )
        ));
    }
}
