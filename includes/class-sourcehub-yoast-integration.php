<?php
/**
 * Yoast SEO integration
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Yoast Integration Class
 */
class SourceHub_Yoast_Integration {

    /**
     * Yoast meta fields to sync
     *
     * @var array
     */
    private static $yoast_fields = array(
        '_yoast_wpseo_title',
        '_yoast_wpseo_metadesc',
        '_yoast_wpseo_focuskw',
        '_yoast_wpseo_focuskeywords', // Alternative field name
        '_yoast_wpseo_canonical',
        '_yoast_wpseo_twitter-title',
        '_yoast_wpseo_twitter-description',
        '_yoast_wpseo_opengraph-title',
        '_yoast_wpseo_opengraph-description'
    );

    /**
     * Get Yoast meta data for a post
     *
     * @param int $post_id Post ID
     * @return array
     */
    public static function get_post_meta($post_id) {
        $meta_data = array();

        // Check if Yoast is active
        if (!self::is_yoast_active()) {
            error_log('SourceHub Yoast: Yoast SEO is not active on hub site');
            return $meta_data;
        }

        error_log('SourceHub Yoast: Getting meta for post ID: ' . $post_id);

        // Debug: Get ALL meta fields for this post to see what Yoast fields exist
        $all_meta = get_post_meta($post_id);
        $yoast_meta_found = array();
        foreach ($all_meta as $key => $value) {
            if (strpos($key, '_yoast_wpseo_') === 0) {
                $yoast_meta_found[$key] = $value[0];
            }
        }
        error_log('SourceHub Yoast: ALL Yoast meta found: ' . json_encode($yoast_meta_found));

        foreach (self::$yoast_fields as $field) {
            $value = get_post_meta($post_id, $field, true);
            error_log('SourceHub Yoast: Field ' . $field . ' = ' . ($value ? $value : 'EMPTY'));
            if (!empty($value)) {
                $meta_data[$field] = $value;
            }
        }

        error_log('SourceHub Yoast: Collected meta data: ' . json_encode($meta_data));
        return $meta_data;
    }

    /**
     * Set Yoast meta data for a post
     *
     * @param int $post_id Post ID
     * @param array $meta_data Yoast meta data
     * @param bool $allow_override Whether to allow overriding existing values
     * @return bool
     */
    public static function set_post_meta($post_id, $meta_data, $allow_override = true) {
        // Check if Yoast is active
        if (!self::is_yoast_active()) {
            error_log('SourceHub Yoast: Yoast SEO is not active on spoke site');
            return false;
        }

        if (!is_array($meta_data)) {
            error_log('SourceHub Yoast: Meta data is not an array');
            return false;
        }

        error_log('SourceHub Yoast: Setting meta for post ID: ' . $post_id . ' with data: ' . json_encode($meta_data));
        
        // Log what Yoast data was received from hub
        if (!empty($meta_data)) {
            SourceHub_Logger::info(
                sprintf('Received Yoast data from hub (%d fields)', count($meta_data)),
                array(
                    'yoast_fields' => array_keys($meta_data),
                    'has_metadesc' => isset($meta_data['_yoast_wpseo_metadesc']),
                    'has_focuskw' => isset($meta_data['_yoast_wpseo_focuskw'])
                ),
                $post_id,
                null,
                'yoast_receive'
            );
        } else {
            SourceHub_Logger::warning(
                'Received empty Yoast data from hub',
                array('post_id' => $post_id),
                $post_id,
                null,
                'yoast_receive'
            );
        }

        $updated_fields = array();

        foreach (self::$yoast_fields as $field) {
            if (!isset($meta_data[$field])) {
                error_log('SourceHub Yoast: Field ' . $field . ' not found in meta data');
                continue;
            }

            error_log('SourceHub Yoast: Processing field ' . $field . ' with value: ' . $meta_data[$field]);

            // Check if we should override existing values
            if (!$allow_override) {
                $existing_value = get_post_meta($post_id, $field, true);
                if (!empty($existing_value)) {
                    error_log('SourceHub Yoast: Skipping ' . $field . ' - existing value found and override disabled');
                    continue;
                }
            }

            $value = sanitize_text_field($meta_data[$field]);
            
            // Special handling for certain fields
            switch ($field) {
                case '_yoast_wpseo_metadesc':
                case '_yoast_wpseo_twitter-description':
                case '_yoast_wpseo_opengraph-description':
                    $value = sanitize_textarea_field($meta_data[$field]);
                    break;
                    
                case '_yoast_wpseo_canonical':
                    $value = esc_url_raw($meta_data[$field]);
                    break;
            }

            $update_result = update_post_meta($post_id, $field, $value);
            error_log('SourceHub Yoast: Update result for ' . $field . ': ' . ($update_result ? 'SUCCESS' : 'FAILED'));
            
            if ($update_result) {
                $updated_fields[] = $field;
            }
        }

        // Log the update
        if (!empty($updated_fields)) {
            SourceHub_Logger::info(
                sprintf(__('Updated Yoast SEO meta fields: %s', 'sourcehub'), implode(', ', $updated_fields)),
                array('fields' => $updated_fields),
                $post_id,
                null,
                'yoast_update'
            );
        }

        return !empty($updated_fields);
    }

    /**
     * Generate canonical URL for syndicated content
     *
     * @param int $post_id Post ID
     * @param string $hub_url Hub site URL
     * @param int $hub_post_id Original hub post ID
     * @return string
     */
    public static function generate_canonical_url($post_id, $hub_url, $hub_post_id) {
        // Check if custom canonical is already set
        $existing_canonical = get_post_meta($post_id, '_yoast_wpseo_canonical', true);
        $custom_canonical = get_post_meta($post_id, '_sourcehub_canonical_override', true);

        if (!empty($custom_canonical)) {
            return $custom_canonical;
        }

        // Generate canonical URL pointing to hub
        $hub_post_url = trailingslashit($hub_url) . '?p=' . $hub_post_id;
        
        // Try to get the actual permalink from hub if possible
        $hub_permalink = self::get_hub_permalink($hub_url, $hub_post_id);
        if ($hub_permalink) {
            $hub_post_url = $hub_permalink;
        }

        return $hub_post_url;
    }

    /**
     * Set canonical URL for syndicated content
     *
     * @param int $post_id Post ID
     * @param string $canonical_url Canonical URL
     * @return bool
     */
    public static function set_canonical_url($post_id, $canonical_url) {
        if (!self::is_yoast_active()) {
            return false;
        }

        $canonical_url = esc_url_raw($canonical_url);
        
        return update_post_meta($post_id, '_yoast_wpseo_canonical', $canonical_url);
    }

    /**
     * Get hub permalink for a post
     *
     * @param string $hub_url Hub site URL
     * @param int $hub_post_id Hub post ID
     * @return string|false
     */
    private static function get_hub_permalink($hub_url, $hub_post_id) {
        $api_url = trailingslashit($hub_url) . 'wp-json/wp/v2/posts/' . $hub_post_id;
        
        $response = wp_remote_get($api_url, array(
            'timeout' => 10
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            return false;
        }

        $post_data = json_decode(wp_remote_retrieve_body($response), true);
        
        return isset($post_data['link']) ? $post_data['link'] : false;
    }

    /**
     * Check if Yoast SEO is active
     *
     * @return bool
     */
    public static function is_yoast_active() {
        return defined('WPSEO_VERSION') || class_exists('WPSEO_Options');
    }

    /**
     * Get Yoast SEO version
     *
     * @return string|false
     */
    public static function get_yoast_version() {
        if (defined('WPSEO_VERSION')) {
            return WPSEO_VERSION;
        }

        return false;
    }

    /**
     * Validate Yoast meta data
     *
     * @param array $meta_data Meta data to validate
     * @return array Validation results
     */
    public static function validate_meta_data($meta_data) {
        $validation = array(
            'valid' => true,
            'errors' => array(),
            'warnings' => array()
        );

        if (!is_array($meta_data)) {
            $validation['valid'] = false;
            $validation['errors'][] = __('Meta data must be an array', 'sourcehub');
            return $validation;
        }

        foreach ($meta_data as $field => $value) {
            // Check if field is supported
            if (!in_array($field, self::$yoast_fields)) {
                $validation['warnings'][] = sprintf(__('Unknown Yoast field: %s', 'sourcehub'), $field);
                continue;
            }

            // Validate field values
            switch ($field) {
                case '_yoast_wpseo_title':
                case '_yoast_wpseo_twitter-title':
                case '_yoast_wpseo_opengraph-title':
                    if (strlen($value) > 60) {
                        $validation['warnings'][] = sprintf(__('Title field %s is longer than recommended (60 characters)', 'sourcehub'), $field);
                    }
                    break;

                case '_yoast_wpseo_metadesc':
                case '_yoast_wpseo_twitter-description':
                case '_yoast_wpseo_opengraph-description':
                    if (strlen($value) > 160) {
                        $validation['warnings'][] = sprintf(__('Description field %s is longer than recommended (160 characters)', 'sourcehub'), $field);
                    }
                    break;

                case '_yoast_wpseo_canonical':
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $validation['valid'] = false;
                        $validation['errors'][] = sprintf(__('Invalid URL in canonical field: %s', 'sourcehub'), $value);
                    }
                    break;

                case '_yoast_wpseo_focuskw':
                    if (strlen($value) > 100) {
                        $validation['warnings'][] = __('Focus keyword is longer than recommended (100 characters)', 'sourcehub');
                    }
                    break;
            }
        }

        return $validation;
    }

    /**
     * Get meta field labels for display
     *
     * @return array
     */
    public static function get_field_labels() {
        return array(
            '_yoast_wpseo_title' => __('SEO Title', 'sourcehub'),
            '_yoast_wpseo_metadesc' => __('Meta Description', 'sourcehub'),
            '_yoast_wpseo_focuskw' => __('Focus Keyword', 'sourcehub'),
            '_yoast_wpseo_canonical' => __('Canonical URL', 'sourcehub'),
            '_yoast_wpseo_twitter-title' => __('Twitter Title', 'sourcehub'),
            '_yoast_wpseo_twitter-description' => __('Twitter Description', 'sourcehub'),
            '_yoast_wpseo_opengraph-title' => __('OpenGraph Title', 'sourcehub'),
            '_yoast_wpseo_opengraph-description' => __('OpenGraph Description', 'sourcehub')
        );
    }

    /**
     * Get supported Yoast fields
     *
     * @return array
     */
    public static function get_supported_fields() {
        return self::$yoast_fields;
    }

    /**
     * Check compatibility with current Yoast version
     *
     * @return array
     */
    public static function check_compatibility() {
        $compatibility = array(
            'compatible' => true,
            'version' => self::get_yoast_version(),
            'messages' => array()
        );

        if (!self::is_yoast_active()) {
            $compatibility['compatible'] = false;
            $compatibility['messages'][] = __('Yoast SEO plugin is not active', 'sourcehub');
            return $compatibility;
        }

        $version = self::get_yoast_version();
        if ($version && version_compare($version, '14.0', '<')) {
            $compatibility['messages'][] = sprintf(
                __('Yoast SEO version %s detected. Some features may not work properly. Please update to version 14.0 or higher.', 'sourcehub'),
                $version
            );
        }

        return $compatibility;
    }

    /**
     * Initialize Yoast integration hooks
     */
    public static function init() {
        // Add hooks for canonical URL management
        add_action('wp_head', array(__CLASS__, 'maybe_override_canonical'), 1);
        
        // Add filter to modify Yoast canonical output
        add_filter('wpseo_canonical', array(__CLASS__, 'filter_canonical_url'), 10, 2);
    }

    /**
     * Maybe override canonical URL for syndicated content
     */
    public static function maybe_override_canonical() {
        if (!is_singular()) {
            return;
        }

        global $post;
        
        $hub_id = get_post_meta($post->ID, '_sourcehub_hub_id', true);
        $hub_url = get_post_meta($post->ID, '_sourcehub_origin_url', true);
        
        if ($hub_id && $hub_url) {
            $canonical_url = self::generate_canonical_url($post->ID, $hub_url, $hub_id);
            self::set_canonical_url($post->ID, $canonical_url);
        }
    }

    /**
     * Filter Yoast canonical URL
     *
     * @param string $canonical Current canonical URL
     * @param WP_Post $post Current post object
     * @return string
     */
    public static function filter_canonical_url($canonical, $post = null) {
        if (!$post) {
            global $post;
        }

        if (!$post) {
            return $canonical;
        }

        $custom_canonical = get_post_meta($post->ID, '_sourcehub_canonical_override', true);
        if (!empty($custom_canonical)) {
            return $custom_canonical;
        }

        return $canonical;
    }
}
