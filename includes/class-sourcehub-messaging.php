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
        
        // Heartbeat API integration
        add_filter('heartbeat_received', array($this, 'heartbeat_received'), 10, 2);
        add_filter('heartbeat_settings', array($this, 'heartbeat_settings'));
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
        
        // Add attachment data if present
        foreach ($messages as &$message) {
            if ($message->attachment_id) {
                $message->attachment = $this->get_attachment_data($message->attachment_id);
            }
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
                 AND m2.is_read = 0) as unread_count
            FROM $table m
            LEFT JOIN {$wpdb->users} u ON u.ID = CASE WHEN m.from_user_id = %d THEN m.to_user_id ELSE m.from_user_id END
            WHERE (m.from_user_id = %d OR m.to_user_id = %d)
            AND m.group_id IS NULL
            GROUP BY user_id",
            $user_id, $user_id, $user_id, $user_id, $user_id, $user_id
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
                 AND mr.id IS NULL) as unread_count
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
}
