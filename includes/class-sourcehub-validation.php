<?php

/**
 * SourceHub Post Validation
 *
 * Handles pre-publish validation to ensure posts meet SourceHub requirements
 * before they can be saved or published.
 *
 * @package SourceHub
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class SourceHub_Validation {

    /**
     * Track if validation has been initialized
     */
    private static $initialized = false;

    /**
     * Initialize validation hooks
     */
    public static function init() {
        // Prevent multiple initializations
        if (self::$initialized) {
            return;
        }
        self::$initialized = true;
        
        // Check if this is a spoke site (spoke sites don't need validation)
        $is_spoke = self::is_spoke_site();
        
        // Skip validation entirely on spoke sites
        if ($is_spoke) {
            error_log('SourceHub Validation: Skipping validation - site is in spoke mode');
            return;
        }
        
        error_log('SourceHub Validation: Initializing validation hooks - site is in hub mode');
        
        // Safe validation approach - halt syndication if validation fails
        add_action('transition_post_status', array(__CLASS__, 'check_post_validation'), 10, 3);
        add_action('admin_notices', array(__CLASS__, 'show_validation_notices'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_validation_scripts'));
        add_action('wp_ajax_sourcehub_validate_post', array(__CLASS__, 'ajax_validate_post'));
        
        // Hook into SourceHub syndication process to validate before syncing
        add_filter('sourcehub_should_syndicate_post', array(__CLASS__, 'should_syndicate_post'), 10, 2);
    }

    /**
     * Validate post before saving
     *
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     * @param bool $update Whether this is an update
     */
    public static function validate_post_before_save($post_id, $post, $update) {
        // Skip auto-saves and revisions
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        // Only validate posts (not pages or other post types)
        if ($post->post_type !== 'post') {
            return;
        }

        // Only validate if user is trying to publish or update a published post
        if (!in_array($post->post_status, array('publish', 'future'))) {
            return;
        }

        // Only validate in admin interface, not during programmatic saves
        if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
            return;
        }

        // Skip validation if this is a syndication process
        if (defined('SOURCEHUB_SYNCING') && SOURCEHUB_SYNCING) {
            return;
        }

        $validation_errors = self::get_validation_errors($post_id);
        
        if (!empty($validation_errors)) {
            // Store validation errors in session/transient for display
            set_transient('sourcehub_validation_errors_' . $post_id, $validation_errors, 300);
            
            // Only prevent publication if this is a user-initiated save from admin
            if (isset($_POST['publish']) || isset($_POST['save'])) {
                // Prevent publication by changing status to draft
                remove_action('save_post', array(__CLASS__, 'validate_post_before_save'), 1);
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_status' => 'draft'
                ));
                add_action('save_post', array(__CLASS__, 'validate_post_before_save'), 1, 3);
                
                // Set admin notice flag
                set_transient('sourcehub_validation_failed_' . get_current_user_id(), $post_id, 300);
            }
        }
    }

    /**
     * Check post validation on status transition (safe, non-blocking)
     *
     * @param string $new_status New post status
     * @param string $old_status Old post status  
     * @param WP_Post $post Post object
     */
    public static function check_post_validation($new_status, $old_status, $post) {
        // Only check when transitioning to publish
        if ($new_status !== 'publish') {
            return;
        }

        // Only validate posts (not pages or other post types)
        if ($post->post_type !== 'post') {
            return;
        }

        // Only validate in admin interface
        if (!is_admin()) {
            return;
        }

        // Skip if this is an auto-save or revision
        if (wp_is_post_autosave($post->ID) || wp_is_post_revision($post->ID)) {
            return;
        }

        $validation_errors = self::get_validation_errors($post->ID);
        
        if (!empty($validation_errors)) {
            // Store validation errors for display as warning (not blocking)
            set_transient('sourcehub_validation_warning_' . $post->ID, $validation_errors, 300);
            set_transient('sourcehub_validation_warning_user_' . get_current_user_id(), $post->ID, 300);
        }
    }

    /**
     * Determine if a post should be syndicated based on validation
     *
     * @param bool $should_syndicate Current syndication decision
     * @param int $post_id Post ID
     * @return bool Whether post should be syndicated
     */
    public static function should_syndicate_post($should_syndicate, $post_id) {
        // If already decided not to syndicate, respect that
        if (!$should_syndicate) {
            return false;
        }

        // Check if post passes validation
        $validation_errors = self::get_validation_errors($post_id);
        
        if (!empty($validation_errors)) {
            // Log syndication halt
            error_log('SourceHub Validation: Halting syndication for post ' . $post_id . ' due to validation errors: ' . implode(', ', $validation_errors));
            
            // Store syndication halt reason
            set_transient('sourcehub_syndication_halted_' . $post_id, $validation_errors, 300);
            set_transient('sourcehub_syndication_halted_user_' . get_current_user_id(), $post_id, 300);
            
            return false; // Halt syndication
        }

        return true; // Allow syndication
    }

    /**
     * Get validation errors for a post
     *
     * @param int $post_id Post ID
     * @return array Array of validation error messages
     */
    public static function get_validation_errors($post_id) {
        $errors = array();

        // Check if SourceHub spoke is selected
        $selected_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
        if (empty($selected_spokes) || !is_array($selected_spokes) || count($selected_spokes) === 0) {
            $errors[] = 'select at least one SourceHub Spoke site to syndicate to';
        }

        // Check if featured image is set
        if (!has_post_thumbnail($post_id)) {
            $errors[] = 'set a featured image for the post';
        }

        // Check if post has categories assigned (excluding "Uncategorized")
        $categories = wp_get_post_categories($post_id);
        $uncategorized_id = get_option('default_category');
        
        // Filter out "Uncategorized" category
        $meaningful_categories = array_filter($categories, function($cat_id) use ($uncategorized_id) {
            return $cat_id != $uncategorized_id;
        });
        
        if (empty($meaningful_categories)) {
            $errors[] = 'select at least one category for the post (other than "Uncategorized")';
        }

        // Check if post template is selected
        $has_template = false;
        
        // Check WordPress page template
        $page_template = get_page_template_slug($post_id);
        if (!empty($page_template) && $page_template !== 'default') {
            $has_template = true;
        }
        
        // Check Newspaper theme templates
        if (!$has_template) {
            $newspaper_templates = array(
                '_td_post_template',
                '_td_single_template', 
                '_td_article_template',
                'td_post_theme_settings'
            );
            
            foreach ($newspaper_templates as $template_field) {
                $template_value = get_post_meta($post_id, $template_field, true);
                if (!empty($template_value)) {
                    $has_template = true;
                    break;
                }
            }
        }
        
        if (!$has_template) {
            $errors[] = 'select a post template to use';
        }

        return $errors;
    }

    /**
     * Show validation notices in admin
     */
    public static function show_validation_notices() {
        $user_id = get_current_user_id();
        
        // Check for blocking validation errors (old system)
        $failed_post_id = get_transient('sourcehub_validation_failed_' . $user_id);
        if ($failed_post_id) {
            $errors = get_transient('sourcehub_validation_errors_' . $failed_post_id);
            if (!empty($errors)) {
                $error_list = implode(', ', $errors);
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p><strong>SourceHub Validation Failed:</strong> You need to ' . esc_html($error_list) . ' before publishing this post.</p>';
                echo '</div>';
                delete_transient('sourcehub_validation_failed_' . $user_id);
                delete_transient('sourcehub_validation_errors_' . $failed_post_id);
            }
        }
        
        // Check for syndication halt notices
        $halted_post_id = get_transient('sourcehub_syndication_halted_user_' . $user_id);
        if ($halted_post_id) {
            $halt_reasons = get_transient('sourcehub_syndication_halted_' . $halted_post_id);
            if (!empty($halt_reasons)) {
                $reason_list = implode(', ', $halt_reasons);
                $post_title = get_the_title($halted_post_id);
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p><strong>SourceHub Syndication Halted:</strong> The post "' . esc_html($post_title) . '" was published but <strong>NOT syndicated</strong> because you need to ' . esc_html($reason_list) . '. Fix these issues and update the post to syndicate.</p>';
                echo '</div>';
                delete_transient('sourcehub_syndication_halted_user_' . $user_id);
                delete_transient('sourcehub_syndication_halted_' . $halted_post_id);
            }
        }
        
        // Check for warning validation notices (fallback)
        $warning_post_id = get_transient('sourcehub_validation_warning_user_' . $user_id);
        if ($warning_post_id) {
            $warnings = get_transient('sourcehub_validation_warning_' . $warning_post_id);
            if (!empty($warnings)) {
                $warning_list = implode(', ', $warnings);
                $post_title = get_the_title($warning_post_id);
                echo '<div class="notice notice-warning is-dismissible">';
                echo '<p><strong>SourceHub Validation Warning:</strong> The post "' . esc_html($post_title) . '" was published but may not syndicate properly. You should ' . esc_html($warning_list) . ' for optimal syndication.</p>';
                echo '</div>';
                delete_transient('sourcehub_validation_warning_user_' . $user_id);
                delete_transient('sourcehub_validation_warning_' . $warning_post_id);
            }
        }
    }

    /**
     * Enqueue validation JavaScript
     */
    public static function enqueue_validation_scripts($hook) {
        // Only load on post edit screens
        if (!in_array($hook, array('post.php', 'post-new.php'))) {
            return;
        }

        // Only for posts
        global $post;
        if (!$post || $post->post_type !== 'post') {
            return;
        }

        wp_enqueue_script(
            'sourcehub-validation',
            SOURCEHUB_PLUGIN_URL . 'assets/js/validation.js',
            array('jquery'),
            SOURCEHUB_VERSION,
            true
        );

        wp_localize_script('sourcehub-validation', 'sourcehub_validation', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sourcehub_validation_nonce'),
            'post_id' => $post->ID
        ));
    }

    /**
     * AJAX handler for real-time validation
     */
    public static function ajax_validate_post() {
        check_ajax_referer('sourcehub_validation_nonce', 'nonce');

        $post_id = intval($_POST['post_id']);
        
        if (!$post_id || !current_user_can('edit_post', $post_id)) {
            wp_die('Unauthorized');
        }

        // Get current form data if provided, otherwise use saved data
        $form_data = isset($_POST['form_data']) ? $_POST['form_data'] : array();
        
        $errors = self::get_validation_errors_with_form_data($post_id, $form_data);
        
        wp_send_json_success(array(
            'valid' => empty($errors),
            'errors' => $errors
        ));
    }

    /**
     * Get validation errors with current form data (for real-time validation)
     *
     * @param int $post_id Post ID
     * @param array $form_data Current form data
     * @return array Array of validation error messages
     */
    public static function get_validation_errors_with_form_data($post_id, $form_data = array()) {
        $errors = array();

        // Check if SourceHub spoke is selected (from form or saved data)
        $selected_spokes = !empty($form_data['sourcehub_selected_spokes']) 
            ? $form_data['sourcehub_selected_spokes'] 
            : get_post_meta($post_id, '_sourcehub_selected_spokes', true);
            
        if (empty($selected_spokes) || !is_array($selected_spokes) || count($selected_spokes) === 0) {
            $errors[] = 'select at least one SourceHub Spoke site to syndicate to';
        }

        // Check if featured image is set (from form or saved data)
        $has_featured_image = !empty($form_data['has_featured_image']) 
            ? $form_data['has_featured_image'] === 'true'
            : has_post_thumbnail($post_id);
            
        if (!$has_featured_image) {
            $errors[] = 'set a featured image for the post';
        }

        // Check if post has categories assigned (from form or saved data, excluding "Uncategorized")
        $categories = !empty($form_data['post_categories']) 
            ? $form_data['post_categories'] 
            : wp_get_post_categories($post_id);
            
        $uncategorized_id = get_option('default_category');
        
        // Filter out "Uncategorized" category
        $meaningful_categories = array_filter($categories, function($cat_id) use ($uncategorized_id) {
            return $cat_id != $uncategorized_id;
        });
        
        if (empty($meaningful_categories)) {
            $errors[] = 'select at least one category for the post (other than "Uncategorized")';
        }

        // Check if post template is selected (use saved data for now)
        $has_template = false;
        
        // Check WordPress page template
        $page_template = get_page_template_slug($post_id);
        if (!empty($page_template) && $page_template !== 'default') {
            $has_template = true;
        }
        
        // Check Newspaper theme templates
        if (!$has_template) {
            $newspaper_templates = array(
                '_td_post_template',
                '_td_single_template', 
                '_td_article_template',
                'td_post_theme_settings'
            );
            
            foreach ($newspaper_templates as $template_field) {
                $template_value = get_post_meta($post_id, $template_field, true);
                if (!empty($template_value)) {
                    $has_template = true;
                    break;
                }
            }
        }
        
        if (!$has_template) {
            $errors[] = 'select a post template to use';
        }

        return $errors;
    }

    /**
     * Check if a post passes SourceHub validation
     *
     * @param int $post_id Post ID
     * @return bool True if valid, false otherwise
     */
    public static function is_post_valid($post_id) {
        $errors = self::get_validation_errors($post_id);
        return empty($errors);
    }

    /**
     * Check if current site is in spoke mode
     *
     * @return bool True if spoke site, false if hub site
     */
    public static function is_spoke_site() {
        // Use the actual mode setting from SourceHub
        if (function_exists('sourcehub')) {
            $mode = sourcehub()->get_mode();
            return ($mode === 'spoke');
        }
        
        // Fallback: Check if this site has a spoke API key (indicates it's a spoke)
        $spoke_api_key = get_option('sourcehub_spoke_api_key');
        return !empty($spoke_api_key);
    }
}

// Initialize the validation system
SourceHub_Validation::init();
