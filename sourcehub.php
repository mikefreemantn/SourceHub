<?php
/**
 * Plugin Name: SourceHub - Hub & Spoke Publisher
 * Plugin URI: https://github.com/mikefreemantn/SourceHub
 * Description: A powerful content syndication plugin that enables centralized editorial teams to distribute content across multiple WordPress sites with full SEO integration.
 * Version: 1.9.2.7
 * Author: Mike Freeman
 * Author URI: https://manovermachine.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sourcehub
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: true
 * 
 * GitHub Plugin URI: https://github.com/mikefreemantn/SourceHub
 * Primary Branch: main
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SOURCEHUB_VERSION', '1.9.2.7');
define('SOURCEHUB_PLUGIN_FILE', __FILE__);
define('SOURCEHUB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SOURCEHUB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SOURCEHUB_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main SourceHub Plugin Class
 */
final class SourceHub {

    /**
     * Plugin instance
     *
     * @var SourceHub
     */
    private static $instance = null;

    /**
     * Hub Manager instance
     *
     * @var SourceHub_Hub_Manager
     */
    public $hub_manager;

    /**
     * Spoke Manager instance
     *
     * @var SourceHub_Spoke_Manager
     */
    public $spoke_manager;

    /**
     * Yoast Integration instance
     *
     * @var SourceHub_Yoast_Integration
     */
    public $yoast_integration;

    /**
     * Admin instance
     *
     * @var SourceHub_Admin
     */
    public $admin;

    /**
     * API Handler instance
     *
     * @var SourceHub_API_Handler
     */
    public $api_handler;

    /**
     * Calendar instance
     *
     * @var SourceHub_Calendar
     */
    public $calendar;

    /**
     * Get plugin instance
     *
     * @return SourceHub
     */
    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->includes();
        $this->init_components();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'), 0);
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Include required files
     */
    private function includes() {
        // Load core classes
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-database.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-logger.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-hub-manager.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-spoke-manager.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-yoast-integration.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-newspaper-integration.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-ai-rewriter.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-api-handler.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-smart-links.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-shortcodes.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-validation.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-gallery-handler.php';
        require_once SOURCEHUB_PLUGIN_DIR . 'includes/class-sourcehub-bug-tracker.php';

        // Load admin classes
        if (is_admin()) {
            require_once SOURCEHUB_PLUGIN_DIR . 'admin/class-sourcehub-admin.php';
            require_once SOURCEHUB_PLUGIN_DIR . 'admin/class-sourcehub-calendar.php';
        }
    }

    /**
     * Initialize plugin components
     */
    private function init_components() {
        $this->hub_manager = new SourceHub_Hub_Manager();
        $this->spoke_manager = new SourceHub_Spoke_Manager();
        $this->yoast_integration = new SourceHub_Yoast_Integration();
        $this->api_handler = new SourceHub_API_Handler();

        // Initialize Bug Tracker
        SourceHub_Bug_Tracker::init();

        // Initialize admin
        if (is_admin()) {
            $this->admin = new SourceHub_Admin();
            $this->calendar = new SourceHub_Calendar();
        }
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize database tables
        SourceHub_Database::init();
        
        // Load components based on mode
        $mode = get_option('sourcehub_mode', 'hub');
        
        if ($mode === 'hub') {
            $this->hub_manager->init();
        } elseif ($mode === 'spoke') {
            $this->spoke_manager->init();
        }
        
        // Initialize the API handler for REST endpoints
        $this->api_handler->init();

        // Admin is initialized via constructor, no init() method needed
        // Calendar init if it exists
        if (is_admin() && isset($this->calendar)) {
            $this->calendar->init();
        }
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain('sourcehub', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables
        SourceHub_Database::create_tables();
        SourceHub_Bug_Tracker::create_tables();
        
        // Verify tables were created
        global $wpdb;
        $connections_table = $wpdb->prefix . 'sourcehub_connections';
        $logs_table = $wpdb->prefix . 'sourcehub_logs';
        $queue_table = $wpdb->prefix . 'sourcehub_queue';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$connections_table'") != $connections_table ||
            $wpdb->get_var("SHOW TABLES LIKE '$logs_table'") != $logs_table ||
            $wpdb->get_var("SHOW TABLES LIKE '$queue_table'") != $queue_table) {
            error_log('SourceHub: Failed to create database tables during activation');
        }
        
        // Set default options
        add_option('sourcehub_mode', 'hub');
        add_option('sourcehub_version', SOURCEHUB_VERSION);
        add_option('sourcehub_db_version', SOURCEHUB_VERSION);
        add_option('sourcehub_api_key', wp_generate_password(32, false));
        
        // Generate spoke API key if it doesn't exist
        if (!get_option('sourcehub_spoke_api_key')) {
            $spoke_api_key = wp_generate_password(32, false);
            add_option('sourcehub_spoke_api_key', $spoke_api_key);
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Log activation (only if tables exist)
        if ($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") == $logs_table) {
            SourceHub_Logger::log('Plugin activated', 'INFO');
        }
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Log deactivation
        SourceHub_Logger::log('Plugin deactivated', 'INFO');
    }

    /**
     * Get plugin mode
     *
     * @return string
     */
    public function get_mode() {
        return get_option('sourcehub_mode', 'hub');
    }

    /**
     * Check if current site is hub
     *
     * @return bool
     */
    public function is_hub() {
        return $this->get_mode() === 'hub';
    }

    /**
     * Check if current site is spoke
     *
     * @return bool
     */
    public function is_spoke() {
        return $this->get_mode() === 'spoke';
    }
}

/**
 * Get main SourceHub instance
 *
 * @return SourceHub
 */
function sourcehub() {
    return SourceHub::instance();
}

// Initialize the plugin
sourcehub();
