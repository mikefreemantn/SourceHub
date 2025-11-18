<?php
/**
 * Newspaper Theme Integration
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Newspaper Integration Class
 */
class SourceHub_Newspaper_Integration {

    /**
     * Initialize hooks
     */
    public static function init() {
        // Auto-set template when post is saved with video format
        add_action('save_post', array(__CLASS__, 'auto_set_video_template_on_save'), 10, 2);
    }

    /**
     * Automatically set Newspaper template to style 2 when video format is selected
     *
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     */
    public static function auto_set_video_template_on_save($post_id, $post) {
        // Avoid autosaves and revisions
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (wp_is_post_revision($post_id)) {
            return;
        }
        
        // Only for posts
        if ($post->post_type !== 'post') {
            return;
        }

        // Only proceed if Newspaper theme is active
        if (!self::is_newspaper_active()) {
            return;
        }

        // Get the post format
        $format = get_post_format($post_id);
        
        error_log(sprintf('SourceHub Newspaper: Checking format for post %d: %s', $post_id, $format ? $format : 'standard'));

        // Only set template for video format
        if ($format !== 'video') {
            return;
        }

        // Set the template to style 2
        // Newspaper stores this as an array with 'td_post_template' key
        $template_settings = array(
            'td_post_template' => 'single_template_2'
        );

        update_post_meta($post_id, 'td_post_theme_settings', $template_settings);
        
        error_log(sprintf('SourceHub Newspaper: Auto-set template to style 2 for video post %d', $post_id));
    }

    /**
     * Newspaper meta fields to sync
     *
     * @var array
     */
    private static $newspaper_fields = array(
        // WordPress core template
        '_wp_page_template',
        
        // Post settings
        '_td_post_settings',
        '_td_featured_image',
        '_td_post_theme_settings',
        'td_post_theme_settings', // Newspaper stores this WITHOUT underscore prefix!
        'td_post_video', // Newspaper stores this WITHOUT underscore prefix!
        'td_post_audio', // Newspaper stores this WITHOUT underscore prefix!
        '_td_post_gallery',
        '_td_post_quote',
        '_td_post_review',
        
        // Layout and template settings
        '_td_layout',
        '_td_sidebar_position',
        '_td_page_layout',
        '_td_full_page_layout',
        '_td_post_template',
        '_td_single_template',
        '_td_article_template',
        '_td_post_style',
        '_td_post_layout',
        '_td_single_post_template',
        '_td_module_template',
        
        // Featured image settings
        '_td_featured_image_disable',
        '_td_featured_image_url',
        '_td_featured_image_alt',
        
        // Post format specific
        '_td_video_playlist',
        '_td_audio_playlist',
        '_td_gallery_images_ids',
        '_td_quote_on_blocks',
        '_td_review_key_ups',
        '_td_review_key_downs',
        '_td_review_overall',
        
        // Social and sharing
        '_td_social_networks',
        '_td_post_subtitle',
        '_td_post_source_name',
        '_td_post_source_url',
        
        // Advanced settings
        '_td_post_smart_list',
        '_td_post_views',
        '_td_post_content_positions',
        '_td_lock_content',
        '_td_exclude_from_display',
        
        // Custom CSS and scripts
        '_td_post_css',
        '_td_post_js',
        
        // SEO specific (if not using Yoast)
        '_td_post_title',
        '_td_post_description',
        '_td_post_keywords'
    );

    /**
     * Check if Newspaper theme is active
     *
     * @return bool
     */
    public static function is_newspaper_active() {
        $theme = wp_get_theme();
        return ($theme->get('Name') === 'Newspaper' || $theme->get('Template') === 'Newspaper');
    }

    /**
     * Get Newspaper meta data for a post
     *
     * @param int $post_id Post ID
     * @return array
     */
    public static function get_post_meta($post_id) {
        $meta_data = array();

        // Check if Newspaper theme is active
        if (!self::is_newspaper_active()) {
            error_log('SourceHub Newspaper: Newspaper theme is not active on hub site');
            return $meta_data;
        }

        error_log('SourceHub Newspaper: Getting meta for post ID: ' . $post_id);

        // Debug: Get ALL meta fields for this post to see what Newspaper fields exist
        $all_meta = get_post_meta($post_id);
        $newspaper_meta_found = array();
        foreach ($all_meta as $key => $value) {
            if (strpos($key, '_td_') === 0 || strpos($key, 'td_') === 0) {
                $newspaper_meta_found[$key] = is_array($value) ? $value[0] : $value;
            }
        }
        error_log('SourceHub Newspaper: ALL Newspaper meta found: ' . json_encode($newspaper_meta_found));
        
        // Specifically check for video field
        $video_meta = get_post_meta($post_id, '_td_post_video', true);
        error_log('SourceHub Newspaper: _td_post_video value: ' . ($video_meta ? json_encode($video_meta) : 'EMPTY'));
        
        // Specifically check for post template fields
        $template_fields = array('_td_post_template', '_td_single_template', '_td_article_template', '_td_post_settings', '_td_post_style', '_td_post_layout', 'td_post_theme_settings');
        foreach ($template_fields as $template_field) {
            $template_value = get_post_meta($post_id, $template_field, true);
            error_log('SourceHub Newspaper: Template field ' . $template_field . ' = ' . (is_array($template_value) ? json_encode($template_value) : ($template_value ? $template_value : 'EMPTY')));
        }
        
        // Check if template is stored inside _td_post_settings
        $post_settings = get_post_meta($post_id, '_td_post_settings', true);
        if (is_array($post_settings)) {
            error_log('SourceHub Newspaper: _td_post_settings contents: ' . json_encode($post_settings));
            // Look for template-related keys
            foreach ($post_settings as $key => $value) {
                if (strpos($key, 'template') !== false || strpos($key, 'style') !== false || strpos($key, 'layout') !== false) {
                    error_log('SourceHub Newspaper: Found template-related setting: ' . $key . ' = ' . $value);
                }
            }
        }

        foreach (self::$newspaper_fields as $field) {
            $value = get_post_meta($post_id, $field, true);
            error_log('SourceHub Newspaper: Field ' . $field . ' = ' . ($value ? (is_array($value) ? json_encode($value) : $value) : 'EMPTY'));
            if (!empty($value)) {
                $meta_data[$field] = $value;
            }
        }

        error_log('SourceHub Newspaper: Collected meta data: ' . json_encode($meta_data));
        return $meta_data;
    }

    /**
     * Set Newspaper meta data for a post
     *
     * @param int $post_id Post ID
     * @param array $meta_data Newspaper meta data
     * @param bool $allow_override Whether to allow overriding existing values
     * @return bool
     */
    public static function set_post_meta($post_id, $meta_data, $allow_override = true) {
        // Check if Newspaper theme is active
        if (!self::is_newspaper_active()) {
            error_log('SourceHub Newspaper: Newspaper theme is not active on spoke site');
            return false;
        }

        if (!is_array($meta_data)) {
            error_log('SourceHub Newspaper: Meta data is not an array');
            return false;
        }

        error_log('SourceHub Newspaper: Setting meta for post ID: ' . $post_id . ' with data: ' . json_encode($meta_data));

        $updated_fields = array();

        foreach (self::$newspaper_fields as $field) {
            if (!isset($meta_data[$field])) {
                error_log('SourceHub Newspaper: Field ' . $field . ' not found in meta data');
                continue;
            }

            error_log('SourceHub Newspaper: Processing field ' . $field . ' with value: ' . (is_array($meta_data[$field]) ? json_encode($meta_data[$field]) : $meta_data[$field]));

            // Check if we should override existing values
            if (!$allow_override) {
                $existing_value = get_post_meta($post_id, $field, true);
                if (!empty($existing_value)) {
                    error_log('SourceHub Newspaper: Skipping ' . $field . ' - existing value found and override disabled');
                    continue;
                }
            }

            $value = $meta_data[$field];
            
            // Handle different field types
            switch ($field) {
                case '_td_post_settings':
                case '_td_post_theme_settings':
                case 'td_post_theme_settings': // Add the non-underscore version!
                case '_td_gallery_images_ids':
                case '_td_video_playlist':
                case '_td_audio_playlist':
                case '_td_review_key_ups':
                case '_td_review_key_downs':
                case '_td_social_networks':
                case '_td_post_smart_list':
                case '_td_post_content_positions':
                    // These are typically arrays or serialized data
                    if (is_string($value)) {
                        // Try to unserialize if it's a serialized string
                        $unserialized = @unserialize($value);
                        if ($unserialized !== false) {
                            $value = $unserialized;
                        }
                    }
                    break;
                    
                case '_td_featured_image_url':
                case '_td_post_source_url':
                    // URLs should be properly escaped
                    $value = esc_url_raw($value);
                    break;
                    
                case '_td_post_css':
                case '_td_post_js':
                    // CSS and JS should be handled carefully
                    $value = wp_strip_all_tags($value);
                    break;
                    
                default:
                    // Default sanitization for text fields
                    if (is_string($value)) {
                        $value = sanitize_text_field($value);
                    }
                    break;
            }

            $update_result = update_post_meta($post_id, $field, $value);
            error_log('SourceHub Newspaper: Update result for ' . $field . ': ' . ($update_result ? 'SUCCESS' : 'FAILED'));
            
            if ($update_result) {
                $updated_fields[] = $field;
            }
        }

        // Log the update
        if (!empty($updated_fields)) {
            SourceHub_Logger::info(
                sprintf(__('Updated Newspaper theme meta fields: %s', 'sourcehub'), implode(', ', $updated_fields)),
                array('fields' => $updated_fields),
                $post_id,
                null,
                'newspaper_update'
            );
        }

        return !empty($updated_fields);
    }

    /**
     * Get all Newspaper meta fields for debugging
     *
     * @param int $post_id Post ID
     * @return array All Newspaper-related meta fields
     */
    public static function get_all_newspaper_meta($post_id) {
        $all_meta = get_post_meta($post_id);
        $newspaper_meta = array();
        
        foreach ($all_meta as $key => $value) {
            if (strpos($key, '_td_') === 0) {
                $newspaper_meta[$key] = $value[0];
            }
        }
        
        return $newspaper_meta;
    }

    /**
     * Check if a specific Newspaper feature is enabled
     *
     * @param int $post_id Post ID
     * @param string $feature Feature to check
     * @return bool
     */
    public static function is_feature_enabled($post_id, $feature) {
        $post_settings = get_post_meta($post_id, '_td_post_settings', true);
        
        if (!is_array($post_settings)) {
            return false;
        }
        
        return isset($post_settings[$feature]) && $post_settings[$feature] === 'yes';
    }

    /**
     * Get supported Newspaper fields list
     *
     * @return array
     */
    public static function get_supported_fields() {
        return self::$newspaper_fields;
    }

    /**
     * Debug function to check what Newspaper fields exist on a post
     * Call this manually to debug a specific post
     *
     * @param int $post_id Post ID to check
     */
    public static function debug_post_fields($post_id) {
        error_log('=== NEWSPAPER DEBUG FOR POST ' . $post_id . ' ===');
        
        $all_meta = get_post_meta($post_id);
        foreach ($all_meta as $key => $value) {
            if (strpos($key, '_td_') === 0) {
                $val = is_array($value) ? $value[0] : $value;
                error_log('NEWSPAPER FIELD: ' . $key . ' = ' . (is_array($val) ? json_encode($val) : $val));
            }
        }
        
        error_log('=== END NEWSPAPER DEBUG ===');
    }
}
