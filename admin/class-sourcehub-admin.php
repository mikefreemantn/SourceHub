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
    public function init() {
        // Prevent multiple initializations
        if (self::$initialized) {
            return;
        }
        self::$initialized = true;

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
        add_action('wp_ajax_sourcehub_save_settings', array($this, 'save_settings'));
        add_action('wp_ajax_sourcehub_test_api', array($this, 'test_api_connection'));
        add_action('wp_ajax_sourcehub_regenerate_spoke_key', array($this, 'regenerate_spoke_key'));
        add_action('wp_ajax_sourcehub_clear_logs', array($this, 'clear_logs'));
        add_action('wp_ajax_sourcehub_get_connection', array($this, 'get_connection'));
        add_action('admin_notices', array($this, 'admin_notices'));
        add_filter('admin_footer_text', array($this, 'admin_footer_text'));
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
        } elseif ($mode === 'spoke') {
            // Hub Connections - Admin only
            add_submenu_page(
                'sourcehub',
                __('Hub Connections', 'sourcehub'),
                __('Hub Connections', 'sourcehub'),
                $admin_capability,
                'sourcehub-connections',
                array($this, 'render_connections')
            );
        }

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
                array('jquery'),
                SOURCEHUB_VERSION,
                true
            );
            
            wp_enqueue_style(
                'sourcehub-tinymce-shortcodes',
                SOURCEHUB_PLUGIN_URL . 'assets/css/tinymce-shortcodes.css',
                array(),
                SOURCEHUB_VERSION
            );
            
            // Localize script for TinyMCE buttons
            wp_localize_script('sourcehub-tinymce-shortcodes', 'sourcehub_admin', array(
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

        // Enqueue admin scripts
        wp_enqueue_script(
            'sourcehub-admin',
            SOURCEHUB_PLUGIN_URL . 'admin/js/admin.js',
            array('jquery', 'wp-util'),
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
                $connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
            } else {
                $connections = SourceHub_Database::get_connections(array('mode' => 'hub'));
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
        $connections = SourceHub_Database::get_connections(array('mode' => $connection_mode));

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
        $status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

        $args = array(
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'order' => 'DESC'
        );

        if ($status_filter) {
            $args['status'] = $status_filter;
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
     * Format time ago
     *
     * @param string $datetime Datetime string
     * @return string Human readable time ago
     */
    public static function time_ago($datetime) {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return __('Never', 'sourcehub');
        }

        $time = time() - strtotime($datetime);

        if ($time < 60) {
            return __('Just now', 'sourcehub');
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return sprintf(_n('%d minute ago', '%d minutes ago', $minutes, 'sourcehub'), $minutes);
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return sprintf(_n('%d hour ago', '%d hours ago', $hours, 'sourcehub'), $hours);
        } else {
            $days = floor($time / 86400);
            return sprintf(_n('%d day ago', '%d days ago', $days, 'sourcehub'), $days);
        }
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
}
