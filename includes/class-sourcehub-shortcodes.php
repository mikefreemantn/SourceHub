<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Shortcodes Class
 * Handles shortcode functionality for Classic Editor Smart Links
 */
class SourceHub_Shortcodes {

    /**
     * Initialize shortcodes
     */
    public static function init() {
        add_shortcode('smart-link', array(__CLASS__, 'smart_link_shortcode'));
        add_shortcode('custom-smart-link', array(__CLASS__, 'custom_smart_link_shortcode'));
        
        // Process shortcodes before syndication
        add_filter('sourcehub_before_syndication', array(__CLASS__, 'process_shortcodes_for_syndication'), 10, 1);
        
        // Add TinyMCE buttons for Classic Editor
        if (is_admin()) {
            add_action('init', array(__CLASS__, 'add_tinymce_buttons'));
            add_action('wp_ajax_sourcehub_get_spoke_connections', array(__CLASS__, 'ajax_get_spoke_connections'));
        }
    }

    /**
     * Handle [smart-link] shortcode
     * Usage: [smart-link path="/contact"]Contact Us[/smart-link]
     *
     * @param array $atts Shortcode attributes
     * @param string $content Shortcode content
     * @return string
     */
    public static function smart_link_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'path' => '',
            'class' => 'sourcehub-smart-link'
        ), $atts, 'smart-link');

        // Validate required attributes
        if (empty($atts['path']) || empty($content)) {
            return $content; // Return original content if invalid
        }

        // Sanitize attributes
        $path = esc_attr($atts['path']);
        $class = esc_attr($atts['class']);
        $link_text = wp_kses_post($content);

        // Create the smart link span (same format as Gutenberg blocks)
        return sprintf(
            '<span class="%s" data-smart-url="%s">%s</span>',
            $class,
            $path,
            $link_text
        );
    }

    /**
     * Handle [custom-smart-link] shortcode
     * Usage: [custom-smart-link urls='{"spoke1":"http://site1.com/about","spoke2":"http://site2.com/about"}']About Us[/custom-smart-link]
     *
     * @param array $atts Shortcode attributes
     * @param string $content Shortcode content
     * @return string
     */
    public static function custom_smart_link_shortcode($atts, $content = '') {
        $atts = shortcode_atts(array(
            'urls' => '',
            'class' => 'sourcehub-custom-smart-link'
        ), $atts, 'custom-smart-link');

        // Validate required attributes
        if (empty($atts['urls']) || empty($content)) {
            return $content; // Return original content if invalid
        }

        // Validate JSON
        $urls_data = json_decode($atts['urls'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $content; // Return original content if invalid JSON
        }

        // Sanitize attributes
        $urls_json = esc_attr($atts['urls']);
        $class = esc_attr($atts['class']);
        $link_text = wp_kses_post($content);

        // Create the custom smart link span (same format as Gutenberg blocks)
        return sprintf(
            '<span class="%s" data-custom-urls="%s">%s</span>',
            $class,
            htmlspecialchars($urls_json, ENT_QUOTES, 'UTF-8'),
            $link_text
        );
    }

    /**
     * Process shortcodes before syndication
     * This ensures shortcodes are converted to spans before Smart Links processing
     *
     * @param string $content Post content
     * @return string Processed content
     */
    public static function process_shortcodes_for_syndication($content) {
        // Process shortcodes to convert them to the span format
        return do_shortcode($content);
    }

    /**
     * Get shortcode documentation for admin
     *
     * @return array Documentation array
     */
    public static function get_documentation() {
        return array(
            'smart-link' => array(
                'title' => 'Smart Link Shortcode',
                'description' => 'Creates a smart link that adapts to each spoke site\'s URL structure.',
                'syntax' => '[smart-link path="/contact"]Contact Us[/smart-link]',
                'attributes' => array(
                    'path' => 'Required. The path that will be adapted for each spoke site (e.g., "/contact", "/about")',
                    'class' => 'Optional. Custom CSS class (default: "sourcehub-smart-link")'
                ),
                'examples' => array(
                    '[smart-link path="/contact"]Contact Us[/smart-link]',
                    '[smart-link path="/services/web-design"]Web Design Services[/smart-link]',
                    '[smart-link path="/blog/latest-news"]Latest News[/smart-link]'
                )
            ),
            'custom-smart-link' => array(
                'title' => 'Custom Smart Link Shortcode',
                'description' => 'Creates a smart link with custom URLs for each spoke site.',
                'syntax' => '[custom-smart-link urls=\'{"spoke1":"http://site1.com/about","spoke2":"http://site2.com/about"}\']About Us[/custom-smart-link]',
                'attributes' => array(
                    'urls' => 'Required. JSON object mapping spoke connection names to custom URLs',
                    'class' => 'Optional. Custom CSS class (default: "sourcehub-custom-smart-link")'
                ),
                'examples' => array(
                    '[custom-smart-link urls=\'{"Spoke 1":"https://example1.com/special-page","Spoke 2":"https://example2.com/different-page"}\']Special Link[/custom-smart-link]',
                    '[custom-smart-link urls=\'{"Main Site":"https://main.com/products","Blog Site":"https://blog.com/reviews"}\']Product Reviews[/custom-smart-link]'
                )
            )
        );
    }

    /**
     * Add shortcode buttons to Classic Editor (TinyMCE)
     * This adds helpful buttons to make shortcode insertion easier
     */
    public static function add_tinymce_buttons() {
        // Only add buttons if user can edit posts and we're in admin
        if (!current_user_can('edit_posts') || !is_admin()) {
            return;
        }

        // Only add for hub mode
        $mode = sourcehub()->get_mode();
        if ($mode !== 'hub') {
            return;
        }

        // Add filter to register our TinyMCE plugin
        add_filter('mce_external_plugins', array(__CLASS__, 'add_tinymce_plugin'));
        add_filter('mce_buttons', array(__CLASS__, 'register_tinymce_buttons'));
    }

    /**
     * Register TinyMCE plugin
     *
     * @param array $plugin_array
     * @return array
     */
    public static function add_tinymce_plugin($plugin_array) {
        $plugin_array['sourcehub_shortcodes'] = SOURCEHUB_PLUGIN_URL . 'assets/js/tinymce-shortcodes.js?v=' . time();
        return $plugin_array;
    }

    /**
     * Register TinyMCE buttons
     *
     * @param array $buttons
     * @return array
     */
    public static function register_tinymce_buttons($buttons) {
        array_push($buttons, 'sourcehub_smart_link', 'sourcehub_custom_smart_link');
        return $buttons;
    }

    /**
     * AJAX handler to get spoke connections for custom smart link modal
     */
    public static function ajax_get_spoke_connections() {
        // Check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'sourcehub_admin_nonce')) {
            wp_send_json_error(array('message' => 'Invalid nonce'));
            return;
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Insufficient permissions'));
            return;
        }

        try {
            // Get spoke connections (only for hub mode)
            $mode = sourcehub()->get_mode();
            if ($mode !== 'hub') {
                wp_send_json_error(array('message' => 'Smart links only available in hub mode'));
                return;
            }

            $connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
            
            // Format connections for the modal
            $formatted_connections = array();
            foreach ($connections as $connection) {
                $formatted_connections[] = array(
                    'id' => $connection->id,
                    'name' => $connection->name,
                    'url' => $connection->url
                );
            }

            wp_send_json_success($formatted_connections);

        } catch (Exception $e) {
            wp_send_json_error(array('message' => 'Failed to load connections: ' . $e->getMessage()));
        }
    }
}

// Initialize shortcodes
SourceHub_Shortcodes::init();
