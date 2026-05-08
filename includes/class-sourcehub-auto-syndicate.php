<?php
/**
 * Auto-syndication trigger handler
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Auto Syndicate Class
 * Handles automatic syndication when custom field trigger is detected
 */
class SourceHub_Auto_Syndicate {

    /**
     * Initialize auto-syndication hooks
     */
    public static function init() {
        error_log('SourceHub Auto-Syndicate: Class initialized');
        // Use both updated_post_meta and added_post_meta to catch the trigger AFTER the custom field is saved
        add_action('updated_post_meta', array(__CLASS__, 'check_meta_update'), 10, 4);
        add_action('added_post_meta', array(__CLASS__, 'check_meta_update'), 10, 4);
        // Also use save_post as fallback with low priority
        add_action('save_post', array(__CLASS__, 'check_on_save'), 9999, 2);
        // Register delayed syndication action
        add_action('sourcehub_auto_syndicate_delayed', array(__CLASS__, 'execute_delayed_syndication'));
    }
    
    /**
     * Check for trigger on save_post (fallback method)
     *
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     */
    public static function check_on_save($post_id, $post) {
        // Skip autosaves and revisions
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }
        
        // Check for trigger field (all sites)
        $trigger = get_post_meta($post_id, 'sourcehub_auto_syndicate', true);
        
        if ($trigger === 'true' || $trigger === '1' || $trigger === 1) {
            error_log('SourceHub Auto-Syndicate: Trigger detected via save_post for post ' . $post_id . ' with value: ' . var_export($trigger, true));
            self::check_trigger($post_id, $post);
            return;
        }
        
        // Check for trigger field (specific sites)
        $specific_ids = get_post_meta($post_id, 'sourcehub_auto_syndicate_to_id', true);
        
        if (!empty($specific_ids)) {
            error_log('SourceHub Auto-Syndicate: Trigger detected via save_post for post ' . $post_id . ' with specific IDs: ' . $specific_ids);
            self::check_trigger($post_id, $post);
        }
    }

    /**
     * Check when post meta is updated
     *
     * @param int $meta_id Meta ID
     * @param int $post_id Post ID
     * @param string $meta_key Meta key
     * @param mixed $meta_value Meta value
     */
    public static function check_meta_update($meta_id, $post_id, $meta_key, $meta_value) {
        // Check for both trigger fields
        if ($meta_key !== 'sourcehub_auto_syndicate' && $meta_key !== 'sourcehub_auto_syndicate_to_id') {
            return;
        }
        
        error_log('SourceHub Auto-Syndicate: Meta field "' . $meta_key . '" updated for post ' . $post_id . ' with value: ' . var_export($meta_value, true));
        
        // Validate based on which field was updated
        if ($meta_key === 'sourcehub_auto_syndicate') {
            if ($meta_value !== 'true' && $meta_value !== '1' && $meta_value !== 1) {
                error_log('SourceHub Auto-Syndicate: Invalid trigger value for sourcehub_auto_syndicate');
                return;
            }
        } elseif ($meta_key === 'sourcehub_auto_syndicate_to_id') {
            if (empty($meta_value)) {
                error_log('SourceHub Auto-Syndicate: Empty value for sourcehub_auto_syndicate_to_id');
                return;
            }
        }
        
        $post = get_post($post_id);
        if (!$post) {
            error_log('SourceHub Auto-Syndicate: Post not found');
            return;
        }
        
        self::check_trigger($post_id, $post);
    }

    /**
     * Check for auto-syndication trigger
     *
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     */
    public static function check_trigger($post_id, $post) {
        error_log('SourceHub Auto-Syndicate: check_trigger called for post ' . $post_id);
        
        // Only process published posts
        if ($post->post_status !== 'publish') {
            error_log('SourceHub Auto-Syndicate: Skipping - post status is ' . $post->post_status);
            return;
        }
        
        error_log('SourceHub Auto-Syndicate: All checks passed, scheduling delayed syndication');

        // Schedule delayed syndication (30 seconds delay to allow post to fully index)
        // This gives WordPress time to process featured images, Yoast meta, etc.
        $delay = 30; // seconds
        $scheduled_time = time() + $delay;
        
        // Check if already scheduled
        $already_scheduled = wp_next_scheduled('sourcehub_auto_syndicate_delayed', array($post_id));
        if ($already_scheduled) {
            error_log('SourceHub Auto-Syndicate: Syndication already scheduled for post ' . $post_id);
            return;
        }
        
        wp_schedule_single_event($scheduled_time, 'sourcehub_auto_syndicate_delayed', array($post_id));
        error_log('SourceHub Auto-Syndicate: Scheduled syndication for post ' . $post_id . ' in ' . $delay . ' seconds');
    }
    
    /**
     * Execute delayed syndication
     *
     * @param int $post_id Post ID
     */
    public static function execute_delayed_syndication($post_id) {
        error_log('SourceHub Auto-Syndicate: Executing delayed syndication for post ' . $post_id);
        
        // Get the post
        $post = get_post($post_id);
        if (!$post || $post->post_status !== 'publish') {
            error_log('SourceHub Auto-Syndicate: Post not found or not published, aborting');
            return;
        }
        
        // Check if specific spoke IDs are provided
        $specific_ids = get_post_meta($post_id, 'sourcehub_auto_syndicate_to_id', true);
        
        if (!empty($specific_ids)) {
            // Parse comma-separated IDs
            $connection_ids = array_map('trim', explode(',', $specific_ids));
            $connection_ids = array_map('intval', $connection_ids);
            $connection_ids = array_filter($connection_ids); // Remove empty values
            
            if (empty($connection_ids)) {
                error_log('SourceHub Auto-Syndicate: Invalid connection IDs provided: ' . $specific_ids);
                return;
            }
            
            // Validate that these connections exist and are active
            $all_connections = SourceHub_Database::get_connections(array('status' => 'active'));
            $valid_ids = array_map(function($conn) {
                return $conn->id;
            }, $all_connections);
            
            // Filter to only valid, active connection IDs
            $connection_ids = array_intersect($connection_ids, $valid_ids);
            // Re-index array to prevent key issues
            $connection_ids = array_values($connection_ids);
            
            if (empty($connection_ids)) {
                error_log('SourceHub Auto-Syndicate: None of the specified connection IDs are valid/active: ' . $specific_ids);
                return;
            }
            
            error_log('SourceHub Auto-Syndicate: Triggering targeted syndication for post ' . $post_id . ' to specific spoke sites: ' . implode(', ', $connection_ids));
        } else {
            // Get all active connections (original behavior)
            $connections = SourceHub_Database::get_connections(array('status' => 'active'));
            
            if (empty($connections)) {
                error_log('SourceHub Auto-Syndicate: No active connections found');
                return;
            }

            $connection_ids = array_map(function($conn) {
                return $conn->id;
            }, $connections);

            error_log('SourceHub Auto-Syndicate: Triggering syndication for post ' . $post_id . ' to all ' . count($connection_ids) . ' active spoke sites');
        }

        // Set selected spokes
        update_post_meta($post_id, '_sourcehub_selected_spokes', $connection_ids);

        // Check if already syndicated
        $syndicated = get_post_meta($post_id, '_sourcehub_syndicated_spokes', true);
        
        if (empty($syndicated)) {
            // First time - create posts
            $hub_manager = new SourceHub_Hub_Manager();
            $hub_manager->syndicate_post($post_id, $connection_ids);
        } else {
            // Update existing posts
            $hub_manager = new SourceHub_Hub_Manager();
            $hub_manager->update_syndicated_post($post_id, $connection_ids);
        }

        // Mark as processed
        update_post_meta($post_id, 'sourcehub_auto_syndicate_processed', current_time('mysql'));
        
        error_log('SourceHub Auto-Syndicate: Syndication completed for post ' . $post_id);
    }
}
