<?php
/**
 * SourceHub Messaging System
 * 
 * Handles real-time messaging between hub users with support for:
 * - Direct messages (1-on-1)
 * - Group messages
 * - File attachments
 * - Read receipts
 * - Online status
 *
 * @package SourceHub
 * @since 2.1.0
 */

class SourceHub_Messaging {
    
    /**
     * Initialize messaging system
     */
    public function __construct() {
        add_action('wp_ajax_sourcehub_send_message', array($this, 'ajax_send_message'));
        add_action('wp_ajax_sourcehub_get_messages', array($this, 'ajax_get_messages'));
        add_action('wp_ajax_sourcehub_mark_read', array($this, 'ajax_mark_read'));
        add_action('wp_ajax_sourcehub_create_group', array($this, 'ajax_create_group'));
        add_action('wp_ajax_sourcehub_get_groups', array($this, 'ajax_get_groups'));
        add_action('wp_ajax_sourcehub_update_group_name', array($this, 'ajax_update_group_name'));
        add_action('wp_ajax_sourcehub_add_group_member', array($this, 'ajax_add_group_member'));
        add_action('wp_ajax_sourcehub_remove_group_member', array($this, 'ajax_remove_group_member'));
        add_action('wp_ajax_sourcehub_get_online_users', array($this, 'ajax_get_online_users'));
        add_action('wp_ajax_sourcehub_get_conversations', array($this, 'ajax_get_conversations'));
        add_action('wp_ajax_sourcehub_get_group_members', array($this, 'ajax_get_group_members'));
        add_action('wp_ajax_sourcehub_leave_group', array($this, 'ajax_leave_group'));
        add_action('wp_ajax_sourcehub_fetch_og_tags', array($this, 'ajax_fetch_og_tags'));
        add_action('wp_ajax_sourcehub_add_reaction', array($this, 'ajax_add_reaction'));
        add_action('wp_ajax_sourcehub_remove_reaction', array($this, 'ajax_remove_reaction'));
        add_action('wp_ajax_sourcehub_delete_message', array($this, 'ajax_delete_message'));
        add_action('wp_ajax_sourcehub_get_unread_count', array($this, 'ajax_get_unread_count'));
        
        // Heartbeat API integration
        add_filter('heartbeat_received', array($this, 'heartbeat_received'), 10, 2);
        add_filter('heartbeat_settings', array($this, 'heartbeat_settings'));
        
        // Email digest for unread messages
        add_action('sourcehub_check_message_digests', array($this, 'check_and_send_digests'));
        add_filter('cron_schedules', array($this, 'add_cron_schedules'));
        
        // Clear old cron event if it exists with wrong schedule
        $timestamp = wp_next_scheduled('sourcehub_check_message_digests');
        if ($timestamp) {
            $cron = _get_cron_array();
            if (isset($cron[$timestamp]['sourcehub_check_message_digests'])) {
                foreach ($cron[$timestamp]['sourcehub_check_message_digests'] as $key => $event) {
                    if (isset($event['schedule']) && $event['schedule'] !== 'sourcehub_2min') {
                        wp_unschedule_event($timestamp, 'sourcehub_check_message_digests');
                        break;
                    }
                }
            }
        }
        
        // Register new cron event
        if (!wp_next_scheduled('sourcehub_check_message_digests')) {
            wp_schedule_event(time(), 'sourcehub_2min', 'sourcehub_check_message_digests');
        }
        
        // Add admin action to manually trigger digest (for testing)
        add_action('admin_init', array($this, 'maybe_trigger_digest_manually'));
    }
    
    /**
     * Add custom cron schedules
     */
    public function add_cron_schedules($schedules) {
        $schedules['sourcehub_2min'] = array(
            'interval' => 120, // 2 minutes in seconds
            'display'  => __('Every 2 Minutes (SourceHub Testing)')
        );
        return $schedules;
    }
    
    /**
     * Create database tables for messaging system
     * Called on plugin activation
     */
    public static function create_tables() {
        global $wpdb;
        
        // Tables are now created in SourceHub_Database::create_tables()
        // This method is kept for backwards compatibility but does nothing
        
        // Group members table
        $table_members = $wpdb->prefix . 'sourcehub_group_members';
        $sql_members = "CREATE TABLE $table_members (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            group_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            joined_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY group_id (group_id),
            KEY user_id (user_id),
            UNIQUE KEY group_user (group_id, user_id)
        ) $charset_collate;";
        
        // Messages table
        $table_messages = $wpdb->prefix . 'sourcehub_messages';
        $sql_messages = "CREATE TABLE $table_messages (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            from_user_id bigint(20) NOT NULL,
            to_user_id bigint(20) DEFAULT NULL,
            group_id bigint(20) DEFAULT NULL,
            message text NOT NULL,
            attachment_id bigint(20) DEFAULT NULL,
            is_read tinyint(1) DEFAULT 0,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY from_user_id (from_user_id),
            KEY to_user_id (to_user_id),
            KEY group_id (group_id),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Message reads table (for group messages)
        $table_reads = $wpdb->prefix . 'sourcehub_message_reads';
        $sql_reads = "CREATE TABLE $table_reads (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            message_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            read_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY message_id (message_id),
            KEY user_id (user_id),
            UNIQUE KEY message_user (message_id, user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_groups);
        dbDelta($sql_members);
        dbDelta($sql_messages);
        dbDelta($sql_reads);
        
        error_log('SourceHub: Messaging tables created successfully');
    }
    
    /**
     * Send a message (direct or group)
     */
    public function send_message($from_user_id, $message, $to_user_id = null, $group_id = null, $attachment_id = null) {
        global $wpdb;
        
        // Validate: must have either to_user_id OR group_id, not both
        if ((!$to_user_id && !$group_id) || ($to_user_id && $group_id)) {
            return new WP_Error('invalid_recipient', 'Must specify either to_user_id or group_id');
        }
        
        // Validate group membership if sending to group
        if ($group_id && !$this->is_group_member($from_user_id, $group_id)) {
            return new WP_Error('not_member', 'You are not a member of this group');
        }
        
        $table = $wpdb->prefix . 'sourcehub_messages';
        
        $result = $wpdb->insert(
            $table,
            array(
                'from_user_id' => $from_user_id,
                'to_user_id' => $to_user_id,
                'group_id' => $group_id,
                'message' => $message,
                'attachment_id' => $attachment_id,
                'created_at' => current_time('mysql')
            ),
            array('%d', '%d', '%d', '%s', '%d', '%s')
        );
        
        if ($result === false) {
            return new WP_Error('db_error', 'Failed to send message');
        }
        
        $message_id = $wpdb->insert_id;
        
        // Log message sent
        SourceHub_Logger::info(
            sprintf('Message sent to %s', $group_id ? "group $group_id" : "user $to_user_id"),
            array(
                'message_id' => $message_id,
                'from_user_id' => $from_user_id,
                'to_user_id' => $to_user_id,
                'group_id' => $group_id
            ),
            null,
            null,
            'message_sent'
        );
        
        return $message_id;
    }
    
    /**
     * Get messages for a user (unread or conversation)
     */
    public function get_messages($user_id, $conversation_user_id = null, $group_id = null, $unread_only = false, $limit = 50, $offset = 0) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_messages';
        
        if ($conversation_user_id) {
            // Get direct message conversation
            $sql = $wpdb->prepare(
                "SELECT m.*, u.display_name as from_user_name 
                FROM $table m
                LEFT JOIN {$wpdb->users} u ON m.from_user_id = u.ID
                WHERE ((m.from_user_id = %d AND m.to_user_id = %d) 
                   OR (m.from_user_id = %d AND m.to_user_id = %d))
                ORDER BY m.created_at DESC
                LIMIT %d OFFSET %d",
                $user_id, $conversation_user_id,
                $conversation_user_id, $user_id,
                $limit, $offset
            );
        } elseif ($group_id) {
            // Get group messages
            $sql = $wpdb->prepare(
                "SELECT m.*, u.display_name as from_user_name,
                    (SELECT read_at FROM {$wpdb->prefix}sourcehub_message_reads 
                     WHERE message_id = m.id AND user_id = %d) as read_at
                FROM $table m
                LEFT JOIN {$wpdb->users} u ON m.from_user_id = u.ID
                WHERE m.group_id = %d
                ORDER BY m.created_at DESC
                LIMIT %d OFFSET %d",
                $user_id, $group_id, $limit, $offset
            );
        } else {
            // Get all unread messages for user (both direct and group)
            $members_table = $wpdb->prefix . 'sourcehub_group_members';
            $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
            $groups_table = $wpdb->prefix . 'sourcehub_groups';
            
            $sql = $wpdb->prepare(
                "SELECT m.*, u.display_name as from_user_name, g.name as group_name
                FROM $table m
                LEFT JOIN {$wpdb->users} u ON m.from_user_id = u.ID
                LEFT JOIN $reads_table r ON m.id = r.message_id AND r.user_id = %d
                LEFT JOIN $groups_table g ON m.group_id = g.id
                WHERE (
                    (m.to_user_id = %d AND m.is_read = 0)
                    OR 
                    (m.group_id IS NOT NULL 
                     AND m.group_id IN (SELECT group_id FROM $members_table WHERE user_id = %d)
                     AND r.read_at IS NULL
                     AND m.from_user_id != %d)
                )
                ORDER BY m.created_at DESC
                LIMIT %d",
                $user_id, $user_id, $user_id, $user_id, $limit
            );
            
            error_log('SourceHub get_messages SQL: ' . $sql);
        }
        
        $messages = $wpdb->get_results($sql);
        error_log('SourceHub get_messages: Found ' . count($messages) . ' messages for user ' . $user_id);
        
        // Add attachment data and reactions if present
        foreach ($messages as &$message) {
            if ($message->attachment_id) {
                $message->attachment = $this->get_attachment_data($message->attachment_id);
            }
            // Add reactions for this message
            $message->reactions = $this->get_message_reactions($message->id);
        }
        
        return $messages;
    }
    
    /**
     * Get unread message count for user
     */
    public function get_unread_count($user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_messages';
        
        // Direct messages
        $direct_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table 
            WHERE to_user_id = %d AND is_read = 0",
            $user_id
        ));
        
        // Group messages (not read by this user)
        $groups_table = $wpdb->prefix . 'sourcehub_group_members';
        $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
        
        $group_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT m.id) 
            FROM $table m
            INNER JOIN $groups_table gm ON m.group_id = gm.group_id
            LEFT JOIN $reads_table mr ON m.id = mr.message_id AND mr.user_id = %d
            WHERE gm.user_id = %d 
            AND m.from_user_id != %d
            AND mr.id IS NULL",
            $user_id, $user_id, $user_id
        ));
        
        return intval($direct_count) + intval($group_count);
    }
    
    /**
     * Mark message(s) as read
     */
    public function mark_read($user_id, $message_id = null, $conversation_user_id = null, $group_id = null) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_messages';
        
        if ($message_id) {
            // Mark single message as read
            $message = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table WHERE id = %d",
                $message_id
            ));
            
            if (!$message) {
                return false;
            }
            
            if ($message->to_user_id) {
                // Direct message
                $wpdb->update(
                    $table,
                    array('is_read' => 1),
                    array('id' => $message_id, 'to_user_id' => $user_id),
                    array('%d'),
                    array('%d', '%d')
                );
            } else {
                // Group message
                $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
                $wpdb->insert(
                    $reads_table,
                    array(
                        'message_id' => $message_id,
                        'user_id' => $user_id,
                        'read_at' => current_time('mysql')
                    ),
                    array('%d', '%d', '%s')
                );
            }
        } elseif ($conversation_user_id) {
            // Mark all messages in conversation as read
            $wpdb->query($wpdb->prepare(
                "UPDATE $table SET is_read = 1 
                WHERE to_user_id = %d AND from_user_id = %d AND is_read = 0",
                $user_id, $conversation_user_id
            ));
        } elseif ($group_id) {
            // Mark all group messages as read
            $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
            
            // Get unread messages in this group
            $unread_messages = $wpdb->get_col($wpdb->prepare(
                "SELECT m.id FROM $table m
                LEFT JOIN $reads_table mr ON m.id = mr.message_id AND mr.user_id = %d
                WHERE m.group_id = %d AND m.from_user_id != %d AND mr.id IS NULL",
                $user_id, $group_id, $user_id
            ));
            
            foreach ($unread_messages as $msg_id) {
                $wpdb->insert(
                    $reads_table,
                    array(
                        'message_id' => $msg_id,
                        'user_id' => $user_id,
                        'read_at' => current_time('mysql')
                    ),
                    array('%d', '%d', '%s')
                );
            }
        }
        
        return true;
    }
    
    /**
     * Create a message group
     */
    public function create_group($name, $description, $created_by, $member_ids = array()) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_groups';
        
        // Note: description field not used as table doesn't have this column
        $result = $wpdb->insert(
            $table,
            array(
                'name' => $name,
                'created_by' => $created_by,
                'created_at' => current_time('mysql')
            ),
            array('%s', '%d', '%s')
        );
        
        if ($result === false) {
            return new WP_Error('db_error', 'Failed to create group');
        }
        
        $group_id = $wpdb->insert_id;
        
        // Add creator as member
        $this->add_group_member($group_id, $created_by);
        
        // Add additional members
        foreach ($member_ids as $user_id) {
            if ($user_id != $created_by) {
                $this->add_group_member($group_id, $user_id);
            }
        }
        
        SourceHub_Logger::info(
            sprintf('Message group created: %s', $name),
            array('group_id' => $group_id, 'created_by' => $created_by),
            null,
            null,
            'group_created'
        );
        
        return $group_id;
    }
    
    /**
     * Get groups for a user
     */
    public function get_user_groups($user_id) {
        global $wpdb;
        
        $groups_table = $wpdb->prefix . 'sourcehub_groups';
        $members_table = $wpdb->prefix . 'sourcehub_group_members';
        $messages_table = $wpdb->prefix . 'sourcehub_messages';
        $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
        
        $sql = $wpdb->prepare(
            "SELECT g.*, u.display_name as created_by_name,
                (SELECT COUNT(*) FROM $members_table WHERE group_id = g.id) as member_count,
                (SELECT COUNT(DISTINCT m.id) 
                 FROM $messages_table m
                 LEFT JOIN $reads_table mr ON m.id = mr.message_id AND mr.user_id = %d
                 WHERE m.group_id = g.id 
                 AND m.from_user_id != %d
                 AND mr.id IS NULL) as unread_count
            FROM $groups_table g
            INNER JOIN $members_table gm ON g.id = gm.group_id
            LEFT JOIN {$wpdb->users} u ON g.created_by = u.ID
            WHERE gm.user_id = %d
            ORDER BY g.name ASC",
            $user_id, $user_id, $user_id
        );
        
        return $wpdb->get_results($sql);
    }
    
    /**
     * Get group members
     */
    public function get_group_members($group_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_group_members';
        
        $sql = $wpdb->prepare(
            "SELECT gm.*, u.display_name, u.user_email
            FROM $table gm
            LEFT JOIN {$wpdb->users} u ON gm.user_id = u.ID
            WHERE gm.group_id = %d
            ORDER BY u.display_name ASC",
            $group_id
        );
        
        return $wpdb->get_results($sql);
    }
    
    /**
     * Add member to group
     */
    public function add_group_member($group_id, $user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_group_members';
        
        $wpdb->insert(
            $table,
            array(
                'group_id' => $group_id,
                'user_id' => $user_id,
                'joined_at' => current_time('mysql')
            ),
            array('%d', '%d', '%s')
        );
        
        return $wpdb->insert_id;
    }
    
    /**
     * Remove member from group
     */
    public function remove_group_member($group_id, $user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_group_members';
        
        return $wpdb->delete(
            $table,
            array('group_id' => $group_id, 'user_id' => $user_id),
            array('%d', '%d')
        );
    }
    
    /**
     * Check if user is member of group
     */
    public function is_group_member($user_id, $group_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_group_members';
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE group_id = %d AND user_id = %d",
            $group_id, $user_id
        ));
        
        return $count > 0;
    }
    
    /**
     * Get all users with their online status
     */
    public function get_online_users() {
        global $wpdb;
        
        // Get all users with their last activity (if any)
        $sql = "SELECT DISTINCT u.ID, u.display_name, u.user_email, um.meta_value as last_activity
            FROM {$wpdb->users} u
            LEFT JOIN {$wpdb->usermeta} um ON u.ID = um.user_id AND um.meta_key = 'sourcehub_last_activity'
            WHERE u.ID != " . get_current_user_id() . "
            ORDER BY u.display_name ASC";
        
        $users = $wpdb->get_results($sql);
        
        // Add online status and avatar to each user
        $cutoff = date('Y-m-d H:i:s', strtotime('-5 minutes', current_time('timestamp')));
        foreach ($users as $user) {
            $user->is_online = ($user->last_activity && $user->last_activity > $cutoff);
            $user->avatar_url = get_avatar_url($user->ID, array('size' => 40));
        }
        
        return $users;
    }
    
    /**
     * Update user's last activity timestamp
     */
    public function update_user_activity($user_id) {
        update_user_meta($user_id, 'sourcehub_last_activity', current_time('mysql'));
    }
    
    /**
     * Add reaction to message
     */
    public function add_reaction($message_id, $user_id, $reaction_type) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_message_reactions';
        
        // Check if user already reacted with this type
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE message_id = %d AND user_id = %d AND reaction_type = %s",
            $message_id, $user_id, $reaction_type
        ));
        
        if ($existing) {
            return true; // Already exists
        }
        
        $result = $wpdb->insert(
            $table,
            array(
                'message_id' => $message_id,
                'user_id' => $user_id,
                'reaction_type' => $reaction_type,
                'created_at' => current_time('mysql')
            ),
            array('%d', '%d', '%s', '%s')
        );
        
        return $result !== false;
    }
    
    /**
     * Remove reaction from message
     */
    public function remove_reaction($message_id, $user_id, $reaction_type) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_message_reactions';
        
        $result = $wpdb->delete(
            $table,
            array(
                'message_id' => $message_id,
                'user_id' => $user_id,
                'reaction_type' => $reaction_type
            ),
            array('%d', '%d', '%s')
        );
        
        return $result !== false;
    }
    
    /**
     * Get all reactions for a message
     */
    public function get_message_reactions($message_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_message_reactions';
        
        $reactions = $wpdb->get_results($wpdb->prepare(
            "SELECT r.reaction_type, r.user_id, u.display_name, COUNT(*) as count
             FROM $table r
             LEFT JOIN {$wpdb->users} u ON r.user_id = u.ID
             WHERE r.message_id = %d
             GROUP BY r.reaction_type
             ORDER BY r.created_at ASC",
            $message_id
        ));
        
        // Format reactions with user lists
        $formatted = array();
        foreach ($reactions as $reaction) {
            if (!isset($formatted[$reaction->reaction_type])) {
                $formatted[$reaction->reaction_type] = array(
                    'count' => 0,
                    'users' => array()
                );
            }
            
            // Get all users who reacted with this type
            $users = $wpdb->get_results($wpdb->prepare(
                "SELECT r.user_id, u.display_name
                 FROM $table r
                 LEFT JOIN {$wpdb->users} u ON r.user_id = u.ID
                 WHERE r.message_id = %d AND r.reaction_type = %s",
                $message_id, $reaction->reaction_type
            ));
            
            $formatted[$reaction->reaction_type] = array(
                'count' => count($users),
                'users' => $users
            );
        }
        
        return $formatted;
    }
    
    /**
     * Delete a message
     */
    public function delete_message($message_id) {
        global $wpdb;
        
        $messages_table = $wpdb->prefix . 'sourcehub_messages';
        $reactions_table = $wpdb->prefix . 'sourcehub_message_reactions';
        $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
        
        // Delete reactions
        $wpdb->delete($reactions_table, array('message_id' => $message_id), array('%d'));
        
        // Delete read receipts
        $wpdb->delete($reads_table, array('message_id' => $message_id), array('%d'));
        
        // Delete the message
        $result = $wpdb->delete($messages_table, array('id' => $message_id), array('%d'));
        
        return $result !== false;
    }
    
    /**
     * Update group name
     */
    public function update_group_name($group_id, $name) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_groups';
        
        $result = $wpdb->update(
            $table,
            array('name' => $name),
            array('id' => $group_id),
            array('%s'),
            array('%d')
        );
        
        return $result !== false;
    }
    
    /**
     * Leave group
     */
    public function leave_group($user_id, $group_id) {
        global $wpdb;
        
        $members_table = $wpdb->prefix . 'sourcehub_group_members';
        
        $result = $wpdb->delete(
            $members_table,
            array(
                'group_id' => $group_id,
                'user_id' => $user_id
            ),
            array('%d', '%d')
        );
        
        return $result !== false;
    }
    
    /**
     * Get attachment data
     */
    private function get_attachment_data($attachment_id) {
        $attachment = get_post($attachment_id);
        
        if (!$attachment) {
            return null;
        }
        
        return array(
            'id' => $attachment_id,
            'url' => wp_get_attachment_url($attachment_id),
            'filename' => basename(get_attached_file($attachment_id)),
            'type' => get_post_mime_type($attachment_id),
            'thumbnail' => wp_get_attachment_image_url($attachment_id, 'thumbnail')
        );
    }
    
    /**
     * Fetch Open Graph tags from URL
     */
    public function fetch_og_tags($url) {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        
        // Fetch the page content
        $response = wp_remote_get($url, array(
            'timeout' => 10,
            'user-agent' => 'Mozilla/5.0 (compatible; SourceHub/1.0)'
        ));
        
        if (is_wp_error($response)) {
            return null;
        }
        
        $html = wp_remote_retrieve_body($response);
        
        if (empty($html)) {
            return null;
        }
        
        // Parse OG tags
        $og_data = array(
            'url' => $url,
            'title' => null,
            'description' => null,
            'image' => null,
            'site_name' => null
        );
        
        // Extract og:title
        if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\'](.*?)["\']/i', $html, $matches)) {
            $og_data['title'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        
        // Extract og:description
        if (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\'](.*?)["\']/i', $html, $matches)) {
            $og_data['description'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        
        // Extract og:image
        if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $matches)) {
            $og_data['image'] = $matches[1];
        }
        
        // Extract og:site_name
        if (preg_match('/<meta[^>]+property=["\']og:site_name["\'][^>]+content=["\'](.*?)["\']/i', $html, $matches)) {
            $og_data['site_name'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        
        // Fallback to regular title if no og:title
        if (empty($og_data['title']) && preg_match('/<title>(.*?)<\/title>/i', $html, $matches)) {
            $og_data['title'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        
        // Fallback to meta description if no og:description
        if (empty($og_data['description']) && preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\']/i', $html, $matches)) {
            $og_data['description'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
        
        return $og_data;
    }
    
    /**
     * AJAX: Send message
     */
    public function ajax_send_message() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $message = sanitize_textarea_field(wp_unslash($_POST['message']));
        $to_user_id = isset($_POST['to_user_id']) ? intval($_POST['to_user_id']) : null;
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : null;
        $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : null;
        
        $result = $this->send_message($current_user_id, $message, $to_user_id, $group_id, $attachment_id);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        wp_send_json_success(array('message_id' => $result));
    }
    
    /**
     * AJAX: Get messages
     */
    public function ajax_get_messages() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $conversation_user_id = isset($_POST['conversation_user_id']) ? intval($_POST['conversation_user_id']) : null;
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : null;
        $unread_only = isset($_POST['unread_only']) ? boolval($_POST['unread_only']) : false;
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
        
        $messages = $this->get_messages($current_user_id, $conversation_user_id, $group_id, $unread_only, 50, $offset);
        
        wp_send_json_success(array('messages' => $messages));
    }
    
    /**
     * AJAX: Mark as read
     */
    public function ajax_mark_read() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : null;
        $conversation_user_id = isset($_POST['conversation_user_id']) ? intval($_POST['conversation_user_id']) : null;
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : null;
        
        $this->mark_read($current_user_id, $message_id, $conversation_user_id, $group_id);
        
        // Get updated unread count
        $unread_count = $this->get_unread_count($current_user_id);
        
        wp_send_json_success(array('unread_count' => $unread_count));
    }
    
    /**
     * AJAX: Create group
     */
    public function ajax_create_group() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $name = sanitize_text_field($_POST['name']);
        $description = sanitize_textarea_field($_POST['description']);
        $member_ids = isset($_POST['member_ids']) ? array_map('intval', $_POST['member_ids']) : array();
        
        $result = $this->create_group($name, $description, $current_user_id, $member_ids);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        wp_send_json_success(array('group_id' => $result));
    }
    
    /**
     * AJAX: Get groups
     */
    public function ajax_get_groups() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $groups = $this->get_user_groups($current_user_id);
        
        wp_send_json_success(array('groups' => $groups));
    }
    
    /**
     * AJAX: Update group name
     */
    public function ajax_update_group_name() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        
        if (!$group_id || !$name) {
            wp_send_json_error(array('message' => 'Invalid group ID or name'));
        }
        
        $result = $this->update_group_name($group_id, $name);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Group name updated'));
        } else {
            wp_send_json_error(array('message' => 'Failed to update group name'));
        }
    }
    
    /**
     * AJAX: Add group member
     */
    public function ajax_add_group_member() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $group_id = intval($_POST['group_id']);
        $user_id = intval($_POST['user_id']);
        
        $this->add_group_member($group_id, $user_id);
        
        wp_send_json_success();
    }
    
    /**
     * AJAX: Remove group member
     */
    public function ajax_remove_group_member() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        error_log('SourceHub: Removing member - Group ID: ' . $group_id . ', User ID: ' . $user_id);
        
        if (!$group_id || !$user_id) {
            error_log('SourceHub: Invalid group_id or user_id');
            wp_send_json_error(array('message' => 'Invalid group or user ID'));
        }
        
        $result = $this->remove_group_member($group_id, $user_id);
        
        error_log('SourceHub: Remove member result: ' . ($result ? 'success' : 'failed'));
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Member removed successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to remove member from database'));
        }
    }
    
    /**
     * AJAX: Get online users
     */
    public function ajax_get_online_users() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $users = $this->get_online_users();
        
        wp_send_json_success(array('users' => $users));
    }
    
    /**
     * Get all recent conversations and groups for a user (unified inbox)
     */
    public function get_conversations($user_id, $limit = 20) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_messages';
        $groups_table = $wpdb->prefix . 'sourcehub_groups';
        $members_table = $wpdb->prefix . 'sourcehub_group_members';
        $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
        
        // Get direct conversations
        $conversations_sql = $wpdb->prepare(
            "SELECT 
                'conversation' as type,
                CASE 
                    WHEN m.from_user_id = %d THEN m.to_user_id
                    ELSE m.from_user_id
                END as user_id,
                NULL as group_id,
                u.display_name as name,
                MAX(m.created_at) as last_activity,
                (SELECT COUNT(*) FROM $table m2 
                 WHERE m2.to_user_id = %d 
                 AND m2.from_user_id = CASE WHEN m.from_user_id = %d THEN m.to_user_id ELSE m.from_user_id END
                 AND m2.is_read = 0) as unread_count,
                (SELECT m3.message FROM $table m3
                 WHERE (m3.from_user_id = %d AND m3.to_user_id = CASE WHEN m.from_user_id = %d THEN m.to_user_id ELSE m.from_user_id END)
                    OR (m3.to_user_id = %d AND m3.from_user_id = CASE WHEN m.from_user_id = %d THEN m.to_user_id ELSE m.from_user_id END)
                 AND m3.group_id IS NULL
                 ORDER BY m3.created_at DESC
                 LIMIT 1) as last_message
            FROM $table m
            LEFT JOIN {$wpdb->users} u ON u.ID = CASE WHEN m.from_user_id = %d THEN m.to_user_id ELSE m.from_user_id END
            WHERE (m.from_user_id = %d OR m.to_user_id = %d)
            AND m.group_id IS NULL
            GROUP BY user_id",
            $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id
        );
        
        // Get groups
        $groups_sql = $wpdb->prepare(
            "SELECT 
                'group' as type,
                NULL as user_id,
                g.id as group_id,
                g.name,
                MAX(m.created_at) as last_activity,
                (SELECT COUNT(DISTINCT m2.id) 
                 FROM $table m2
                 LEFT JOIN $reads_table mr ON m2.id = mr.message_id AND mr.user_id = %d
                 WHERE m2.group_id = g.id 
                 AND m2.from_user_id != %d
                 AND mr.id IS NULL) as unread_count,
                (SELECT m3.message FROM $table m3
                 WHERE m3.group_id = g.id
                 ORDER BY m3.created_at DESC
                 LIMIT 1) as last_message
            FROM $groups_table g
            INNER JOIN $members_table gm ON g.id = gm.group_id
            LEFT JOIN $table m ON m.group_id = g.id
            WHERE gm.user_id = %d
            GROUP BY g.id",
            $user_id, $user_id, $user_id
        );
        
        // Combine and sort
        $combined_sql = "($conversations_sql) UNION ALL ($groups_sql) ORDER BY last_activity DESC LIMIT $limit";
        
        $items = $wpdb->get_results($combined_sql);
        
        // Add avatar URLs for conversations
        foreach ($items as &$item) {
            if ($item->type === 'conversation') {
                $item->avatar_url = get_avatar_url($item->user_id);
            }
        }
        
        return $items;
    }
    
    /**
     * AJAX: Get conversations
     */
    public function ajax_get_conversations() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $conversations = $this->get_conversations($current_user_id);
        
        wp_send_json_success(array('conversations' => $conversations));
    }
    
    /**
     * AJAX: Get group members
     */
    public function ajax_get_group_members() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        
        if (!$group_id) {
            wp_send_json_error(array('message' => 'Invalid group ID'));
        }
        
        // Verify user is a member of the group
        if (!$this->is_group_member($current_user_id, $group_id)) {
            wp_send_json_error(array('message' => 'Not authorized'));
        }
        
        $members = $this->get_group_members($group_id);
        
        wp_send_json_success(array('members' => $members));
    }
    
    /**
     * AJAX: Leave group
     */
    public function ajax_leave_group() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
        
        if (!$group_id) {
            wp_send_json_error(array('message' => 'Invalid group ID'));
        }
        
        $result = $this->leave_group($current_user_id, $group_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Left group successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to leave group'));
        }
    }
    
    /**
     * AJAX: Fetch OG tags from URL
     */
    public function ajax_fetch_og_tags() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
        
        if (empty($url)) {
            wp_send_json_error(array('message' => 'Invalid URL'));
        }
        
        $og_data = $this->fetch_og_tags($url);
        
        if ($og_data) {
            wp_send_json_success(array('og_data' => $og_data));
        } else {
            wp_send_json_error(array('message' => 'Failed to fetch OG tags'));
        }
    }
    
    /**
     * AJAX: Add reaction to message
     */
    public function ajax_add_reaction() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
        $reaction_type = isset($_POST['reaction_type']) ? sanitize_text_field($_POST['reaction_type']) : '';
        
        if (!$message_id || !$reaction_type) {
            wp_send_json_error(array('message' => 'Invalid parameters'));
        }
        
        $result = $this->add_reaction($message_id, $current_user_id, $reaction_type);
        
        if ($result) {
            $reactions = $this->get_message_reactions($message_id);
            wp_send_json_success(array('reactions' => $reactions));
        } else {
            wp_send_json_error(array('message' => 'Failed to add reaction'));
        }
    }
    
    /**
     * AJAX: Remove reaction from message
     */
    public function ajax_remove_reaction() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
        $reaction_type = isset($_POST['reaction_type']) ? sanitize_text_field($_POST['reaction_type']) : '';
        
        if (!$message_id || !$reaction_type) {
            wp_send_json_error(array('message' => 'Invalid parameters'));
        }
        
        $result = $this->remove_reaction($message_id, $current_user_id, $reaction_type);
        
        if ($result) {
            $reactions = $this->get_message_reactions($message_id);
            wp_send_json_success(array('reactions' => $reactions));
        } else {
            wp_send_json_error(array('message' => 'Failed to remove reaction'));
        }
    }
    
    /**
     * AJAX: Delete message
     */
    public function ajax_delete_message() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
        
        if (!$message_id) {
            wp_send_json_error(array('message' => 'Invalid message ID'));
        }
        
        $result = $this->delete_message($message_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Message deleted'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete message'));
        }
    }
    
    /**
     * AJAX: Get unread count
     */
    public function ajax_get_unread_count() {
        check_ajax_referer('sourcehub_messaging_nonce', 'nonce');
        
        $current_user_id = get_current_user_id();
        if (!$current_user_id) {
            wp_send_json_error(array('message' => 'Not logged in'));
        }
        
        $unread_count = $this->get_unread_count($current_user_id);
        
        wp_send_json_success(array('unread_count' => $unread_count));
    }
    
    /**
     * Heartbeat API: Receive and process
     */
    public function heartbeat_received($response, $data) {
        $current_user_id = get_current_user_id();
        
        if (!$current_user_id) {
            return $response;
        }
        
        // Update user activity
        $this->update_user_activity($current_user_id);
        
        // Check for new messages
        if (isset($data['sourcehub_check_messages'])) {
            $unread_count = $this->get_unread_count($current_user_id);
            $new_messages = $this->get_messages($current_user_id, null, null, false, 10);
            
            error_log('SourceHub Heartbeat: User ' . $current_user_id . ' - Unread count: ' . $unread_count . ', New messages: ' . count($new_messages));
            if (!empty($new_messages)) {
                error_log('SourceHub Heartbeat: First message: ' . print_r($new_messages[0], true));
            }
            
            $response['sourcehub_messages'] = array(
                'unread_count' => $unread_count,
                'new_messages' => $new_messages
            );
        }
        
        return $response;
    }
    
    /**
     * Heartbeat API: Settings
     */
    public function heartbeat_settings($settings) {
        // Set heartbeat to 15 seconds for messaging
        $settings['interval'] = 15;
        return $settings;
    }
    
    /**
     * Manual trigger for digest (testing only)
     */
    public function maybe_trigger_digest_manually() {
        if (isset($_GET['sourcehub_trigger_digest']) && current_user_can('manage_options')) {
            $this->check_and_send_digests();
            wp_die('Digest check completed. Check Mailpit for emails.');
        }
    }
    
    /**
     * Check for unread messages and send email digests
     * Runs every 2 minutes via cron
     */
    public function check_and_send_digests() {
        error_log('SourceHub Digest: check_and_send_digests() called');
        global $wpdb;
        
        $messages_table = $wpdb->prefix . 'sourcehub_messages';
        $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
        
        // Find messages that are:
        // 1. Older than 2 minutes (for testing - change to 1 hour for production)
        // 2. Not read by the recipient
        // 3. Recipient hasn't received a digest for this message yet
        $two_minutes_ago = gmdate('Y-m-d H:i:s', strtotime('-2 minutes'));
        
        // Get all users with unread messages older than 2 minutes
        error_log('SourceHub Digest: Checking for messages older than ' . $two_minutes_ago);
        $users_with_unread = $wpdb->get_results($wpdb->prepare(
            "SELECT DISTINCT 
                CASE 
                    WHEN m.group_id IS NOT NULL THEN gm.user_id
                    ELSE m.to_user_id 
                END as user_id
            FROM $messages_table m
            LEFT JOIN {$wpdb->prefix}sourcehub_group_members gm ON m.group_id = gm.group_id
            LEFT JOIN $reads_table r ON m.id = r.message_id AND (
                CASE 
                    WHEN m.group_id IS NOT NULL THEN r.user_id = gm.user_id
                    ELSE r.user_id = m.to_user_id
                END
            )
            WHERE m.created_at < %s
            AND r.id IS NULL
            AND m.from_user_id != CASE 
                WHEN m.group_id IS NOT NULL THEN gm.user_id
                ELSE m.to_user_id 
            END",
            $two_minutes_ago
        ));
        
        error_log('SourceHub Digest: Found ' . count($users_with_unread) . ' users with unread messages');
        
        foreach ($users_with_unread as $row) {
            $user_id = $row->user_id;
            
            // Get timestamp of last digest sent
            $last_digest = get_user_meta($user_id, 'sourcehub_last_digest_sent', true);
            
            // Only get messages that are:
            // 1. Older than 2 minutes (or 1 hour in production)
            // 2. Created AFTER the last digest was sent (to avoid re-sending same messages)
            $since_timestamp = $last_digest ? date('Y-m-d H:i:s', $last_digest) : '2000-01-01 00:00:00';
            
            // Check if user has email digest enabled (default to enabled if not set)
            $email_digest_enabled = get_user_meta($user_id, 'sourcehub_email_digest', true);
            if ($email_digest_enabled === '') {
                $email_digest_enabled = '1'; // Default to enabled
            }
            
            if ($email_digest_enabled !== '1') {
                error_log("SourceHub Digest: User $user_id has email digest disabled, skipping");
                continue;
            }
            
            // Get unread messages that haven't been sent in a digest yet
            error_log("SourceHub Digest: Checking user $user_id, last digest: " . ($last_digest ? date('Y-m-d H:i:s', $last_digest) : 'never'));
            $result = $this->get_unread_messages_for_digest($user_id, $two_minutes_ago, $since_timestamp);
            
            error_log("SourceHub Digest: User $user_id has " . count($result['messages']) . " messages to send (total count: {$result['total_count']})");
            
            if (!empty($result['messages']) && $result['total_count'] > 0) {
                error_log("SourceHub Digest: Sending email to user $user_id");
                $this->send_digest_email($user_id, $result['messages'], $result['total_count']);
                update_user_meta($user_id, 'sourcehub_last_digest_sent', time());
                error_log("SourceHub Digest: Email sent to user $user_id");
            }
        }
    }
    
    /**
     * Get unread messages for a user (for digest email)
     */
    private function get_unread_messages_for_digest($user_id, $older_than, $since_timestamp) {
        global $wpdb;
        
        $messages_table = $wpdb->prefix . 'sourcehub_messages';
        $reads_table = $wpdb->prefix . 'sourcehub_message_reads';
        $groups_table = $wpdb->prefix . 'sourcehub_groups';
        
        // First get the total count - split into direct and group messages like get_unread_count does
        $members_table = $wpdb->prefix . 'sourcehub_group_members';
        
        // Direct messages count
        $direct_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*)
            FROM $messages_table
            WHERE to_user_id = %d
            AND group_id IS NULL
            AND is_read = 0
            AND created_at < %s
            AND created_at > %s",
            $user_id,
            $older_than,
            $since_timestamp
        ));
        
        // Group messages count
        $group_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT m.id)
            FROM $messages_table m
            INNER JOIN $members_table gm ON m.group_id = gm.group_id
            LEFT JOIN $reads_table mr ON m.id = mr.message_id AND mr.user_id = %d
            WHERE gm.user_id = %d
            AND m.from_user_id != %d
            AND mr.id IS NULL
            AND m.created_at < %s
            AND m.created_at > %s",
            $user_id,
            $user_id,
            $user_id,
            $older_than,
            $since_timestamp
        ));
        
        $total_count = intval($direct_count) + intval($group_count);
        error_log("SourceHub Digest: User $user_id - direct_count: $direct_count, group_count: $group_count, total_count: $total_count");
        
        // Then get the messages (limited to 20 for email display)
        // Split into direct and group messages like the count query
        $messages = $wpdb->get_results($wpdb->prepare(
            "SELECT m.*, 
                u.display_name as sender_name,
                u.user_email as sender_email,
                g.name as group_name
            FROM $messages_table m
            LEFT JOIN {$wpdb->users} u ON m.from_user_id = u.ID
            LEFT JOIN $groups_table g ON m.group_id = g.id
            LEFT JOIN $members_table gm ON m.group_id = gm.group_id AND gm.user_id = %d
            LEFT JOIN $reads_table r ON m.id = r.message_id AND r.user_id = %d
            WHERE m.created_at < %s
            AND m.created_at > %s
            AND (
                (m.to_user_id = %d AND m.group_id IS NULL AND m.is_read = 0)
                OR (m.group_id IS NOT NULL AND gm.user_id IS NOT NULL AND r.id IS NULL AND m.from_user_id != %d)
            )
            ORDER BY m.created_at DESC
            LIMIT 20",
            $user_id,
            $user_id,
            $older_than,
            $since_timestamp,
            $user_id,
            $user_id
        ));
        
        return array(
            'messages' => $messages,
            'total_count' => (int) $total_count
        );
    }
    
    /**
     * Send digest email to user
     */
    private function send_digest_email($user_id, $messages, $total_count) {
        $user = get_userdata($user_id);
        if (!$user) {
            return;
        }
        
        $site_name = get_bloginfo('name');
        $site_url = get_site_url();
        $message_count = count($messages); // Count of messages shown in this email (max 20)
        
        // Email subject - use total_count (actual unread count)
        $subject = sprintf('[%s] You have %d unread message%s', 
            $site_name, 
            $total_count,
            $total_count > 1 ? 's' : ''
        );
        
        // Build email body
        $body = "<html><body style='font-family: Arial, sans-serif; color: #333;'>";
        $body .= "<h2 style='color: #46b450;'>You have unread messages</h2>";
        $body .= "<p>Hi {$user->display_name},</p>";
        $body .= "<p>You have <strong>{$total_count} unread message" . ($total_count > 1 ? 's' : '') . "</strong> waiting for you" . ($total_count > $message_count ? " (showing {$message_count} most recent)" : "") . ":</p>";
        
        $body .= "<div style='background: #f5f5f5; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
        
        foreach ($messages as $msg) {
            $timestamp = date('M j, Y \a\t g:i A', strtotime($msg->created_at));
            $message_preview = wp_trim_words(strip_tags($msg->message), 20, '...');
            
            $body .= "<div style='background: white; padding: 15px; margin-bottom: 15px; border-left: 3px solid #46b450; border-radius: 3px;'>";
            $body .= "<div style='font-weight: bold; color: #46b450; margin-bottom: 5px;'>";
            $body .= htmlspecialchars($msg->sender_name);
            if ($msg->group_name) {
                $body .= " <span style='color: #666; font-weight: normal;'>in " . htmlspecialchars($msg->group_name) . "</span>";
            }
            $body .= "</div>";
            $body .= "<div style='color: #666; font-size: 12px; margin-bottom: 8px;'>{$timestamp}</div>";
            $body .= "<div style='color: #333;'>" . htmlspecialchars($message_preview) . "</div>";
            $body .= "</div>";
        }
        
        $body .= "</div>";
        
        // Add link to view messages (auto-opens chat panel to inbox)
        $messages_url = admin_url('admin.php?page=sourcehub&open_chat=1');
        $body .= "<p style='margin-top: 30px;'>";
        $body .= "<a href='{$messages_url}' style='background: #46b450; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;'>View Messages</a>";
        $body .= "</p>";
        
        $body .= "<p style='color: #666; font-size: 12px; margin-top: 30px;'>You're receiving this email because you have unread messages on {$site_name}.</p>";
        $body .= "</body></html>";
        
        // Email headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>'
        );
        
        // Send email
        wp_mail($user->user_email, $subject, $body, $headers);
    }
}
