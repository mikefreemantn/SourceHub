<?php
/**
 * Bug Tracker Manager
 *
 * Handles bug submission, tracking, and management
 *
 * @package SourceHub
 */

class SourceHub_Bug_Tracker {

    /**
     * Zapier webhook URL
     */
    private static $zapier_webhook = 'https://hooks.zapier.com/hooks/catch/4353386/uimt1jx/';

    /**
     * Create bug tracker tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Bugs table
        $bugs_table = $wpdb->prefix . 'sourcehub_bugs';
        $bugs_sql = "CREATE TABLE IF NOT EXISTS $bugs_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description longtext NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'open',
            priority varchar(20) NOT NULL DEFAULT 'medium',
            category varchar(50) NOT NULL,
            reporter_name varchar(100) NOT NULL,
            reporter_email varchar(100) NOT NULL,
            reporter_url varchar(255) DEFAULT NULL,
            attachments longtext DEFAULT NULL,
            subscribers longtext DEFAULT NULL,
            plugin_version varchar(20) DEFAULT NULL,
            wordpress_version varchar(20) DEFAULT NULL,
            php_version varchar(20) DEFAULT NULL,
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            resolved_at datetime DEFAULT NULL,
            resolved_version varchar(20) DEFAULT NULL,
            PRIMARY KEY (id),
            KEY status (status),
            KEY priority (priority),
            KEY category (category),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Bug notes table
        $notes_table = $wpdb->prefix . 'sourcehub_bug_notes';
        $notes_sql = "CREATE TABLE IF NOT EXISTS $notes_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            bug_id bigint(20) NOT NULL,
            note longtext NOT NULL,
            images longtext DEFAULT NULL,
            mentions longtext DEFAULT NULL,
            author_id bigint(20) NOT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY (id),
            KEY bug_id (bug_id),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($bugs_sql);
        dbDelta($notes_sql);
        
        // Manually add attachments column if it doesn't exist (dbDelta doesn't always add new columns)
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $bugs_table LIKE 'attachments'");
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE $bugs_table ADD COLUMN attachments longtext DEFAULT NULL AFTER reporter_url");
            error_log('SourceHub Bug Tracker: Added attachments column to bugs table');
        }
        
        // Add subscribers column if it doesn't exist
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $bugs_table LIKE 'subscribers'");
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE $bugs_table ADD COLUMN subscribers longtext DEFAULT NULL AFTER attachments");
            error_log('SourceHub Bug Tracker: Added subscribers column to bugs table');
        }
        
        // Add images column to notes table if it doesn't exist
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $notes_table LIKE 'images'");
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE $notes_table ADD COLUMN images longtext DEFAULT NULL AFTER note");
            error_log('SourceHub Bug Tracker: Added images column to bug notes table');
        }
        
        // Add mentions column to notes table if it doesn't exist
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $notes_table LIKE 'mentions'");
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE $notes_table ADD COLUMN mentions longtext DEFAULT NULL AFTER images");
            error_log('SourceHub Bug Tracker: Added mentions column to bug notes table');
        }
        
        // Log table creation
        error_log('SourceHub Bug Tracker: Tables created/verified');
    }

    /**
     * Submit a new bug
     *
     * @param array $data Bug data
     * @return int|false Bug ID on success, false on failure
     */
    public static function submit_bug($data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        
        // Ensure tables exist
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            self::create_tables();
        }
        
        // Prepare bug data (wp_unslash removes WordPress magic quotes)
        $bug_data = array(
            'title' => sanitize_text_field(wp_unslash($data['title'])),
            'description' => wp_kses_post(wp_unslash($data['description'])),
            'status' => 'open',
            'priority' => sanitize_text_field(wp_unslash($data['priority'] ?? 'medium')),
            'category' => sanitize_text_field(wp_unslash($data['category'])),
            'reporter_name' => sanitize_text_field(wp_unslash($data['reporter_name'])),
            'reporter_email' => sanitize_email(wp_unslash($data['reporter_email'])),
            'reporter_url' => esc_url_raw(wp_unslash($data['reporter_url'] ?? '')),
            'plugin_version' => SOURCEHUB_VERSION,
            'wordpress_version' => get_bloginfo('version'),
            'php_version' => PHP_VERSION,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        );
        
        // Handle file uploads (screenshots)
        $attachments = array();
        if (isset($_FILES['screenshots']) && !empty($_FILES['screenshots']['name'][0])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            
            $files = $_FILES['screenshots'];
            $file_count = count($files['name']);
            
            error_log('SourceHub Bug Tracker: Processing ' . $file_count . ' file(s)');
            
            for ($i = 0; $i < $file_count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $file = array(
                        'name'     => $files['name'][$i],
                        'type'     => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error'    => $files['error'][$i],
                        'size'     => $files['size'][$i]
                    );
                    
                    // Upload file
                    $upload = wp_handle_upload($file, array('test_form' => false));
                    
                    if (!isset($upload['error'])) {
                        $attachments[] = array(
                            'url' => $upload['url'],
                            'file' => $upload['file'],
                            'type' => $upload['type']
                        );
                        error_log('SourceHub Bug Tracker: File uploaded successfully - ' . $upload['url']);
                    } else {
                        error_log('SourceHub Bug Tracker: File upload error - ' . $upload['error']);
                    }
                }
            }
        }
        
        // Store attachments as JSON
        if (!empty($attachments)) {
            $bug_data['attachments'] = json_encode($attachments);
        }
        
        // Auto-subscribe the reporter
        $subscribers = array($bug_data['reporter_email']);
        $bug_data['subscribers'] = json_encode($subscribers);
        
        // Insert bug
        $result = $wpdb->insert($table, $bug_data);
        
        if ($result === false) {
            error_log('SourceHub Bug Tracker: Failed to insert bug - ' . $wpdb->last_error);
            return false;
        }
        
        $bug_id = $wpdb->insert_id;
        
        // Send to Zapier webhook
        self::send_to_zapier($bug_id, $bug_data);
        
        // Log submission
        SourceHub_Logger::log(
            'Bug submitted: ' . $bug_data['title'],
            'INFO',
            'bug_tracker',
            array('bug_id' => $bug_id)
        );
        
        return $bug_id;
    }

    /**
     * Get bug by ID
     *
     * @param int $bug_id Bug ID
     * @return object|null Bug object or null
     */
    public static function get_bug($bug_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $bug_id
        ));
    }

    /**
     * Get all bugs with optional filters
     *
     * @param array $filters Filters to apply
     * @return array Array of bug objects
     */
    public static function get_bugs($filters = array()) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        
        // Ensure tables exist
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            self::create_tables();
            return array(); // Return empty array on first load
        }
        
        $where = array('1=1');
        $params = array();
        
        // Exclude archived bugs by default unless specifically requested
        if (!isset($filters['include_archived']) || !$filters['include_archived']) {
            $where[] = 'status != %s';
            $params[] = 'archived';
        }
        
        // Status filter
        if (!empty($filters['status'])) {
            $where[] = 'status = %s';
            $params[] = $filters['status'];
        }
        
        // Priority filter
        if (!empty($filters['priority'])) {
            $where[] = 'priority = %s';
            $params[] = $filters['priority'];
        }
        
        // Category filter
        if (!empty($filters['category'])) {
            $where[] = 'category = %s';
            $params[] = $filters['category'];
        }
        
        // Search filter
        if (!empty($filters['search'])) {
            $where[] = '(title LIKE %s OR description LIKE %s)';
            $search_term = '%' . $wpdb->esc_like($filters['search']) . '%';
            $params[] = $search_term;
            $params[] = $search_term;
        }
        
        // Order by
        $orderby = $filters['orderby'] ?? 'created_at';
        $order = $filters['order'] ?? 'DESC';
        
        $sql = "SELECT * FROM $table WHERE " . implode(' AND ', $where) . " ORDER BY $orderby $order";
        
        if (!empty($params)) {
            $sql = $wpdb->prepare($sql, $params);
        }
        
        return $wpdb->get_results($sql);
    }

    /**
     * Update bug
     *
     * @param int $bug_id Bug ID
     * @param array $data Data to update
     * @return bool Success
     */
    public static function update_bug($bug_id, $data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        
        // Get current bug state for comparison
        $old_bug = self::get_bug($bug_id);
        
        // Add updated_at timestamp
        $data['updated_at'] = current_time('mysql');
        
        // If status is being changed to resolved/closed, set resolved_at
        if (isset($data['status']) && in_array($data['status'], array('resolved', 'closed'))) {
            if ($old_bug && empty($old_bug->resolved_at)) {
                $data['resolved_at'] = current_time('mysql');
                $data['resolved_version'] = SOURCEHUB_VERSION;
            }
        }
        
        $result = $wpdb->update(
            $table,
            $data,
            array('id' => $bug_id)
        );
        
        // Send notifications if status or priority changed
        if ($result !== false && $old_bug) {
            if (isset($data['status']) && $data['status'] !== $old_bug->status) {
                $subject = '[Bug #' . $bug_id . '] Status changed to: ' . ucfirst($data['status']);
                $message = 'The bug status has been updated from "' . ucfirst($old_bug->status) . '" to "' . ucfirst($data['status']) . '".';
                self::notify_subscribers($bug_id, $subject, $message);
            }
            
            if (isset($data['priority']) && $data['priority'] !== $old_bug->priority) {
                $subject = '[Bug #' . $bug_id . '] Priority changed to: ' . ucfirst($data['priority']);
                $message = 'The bug priority has been updated from "' . ucfirst($old_bug->priority) . '" to "' . ucfirst($data['priority']) . '".';
                self::notify_subscribers($bug_id, $subject, $message);
            }
        }
        
        return $result !== false;
    }

    /**
     * Archive bug (soft delete)
     *
     * @param int $bug_id Bug ID
     * @return bool Success
     */
    public static function archive_bug($bug_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        
        // Set status to archived instead of deleting
        $result = $wpdb->update(
            $table,
            array(
                'status' => 'archived',
                'updated_at' => current_time('mysql')
            ),
            array('id' => $bug_id)
        );
        
        // Log the archive
        error_log('SourceHub Bug Tracker: Bug #' . $bug_id . ' archived');
        
        return $result !== false;
    }
    
    /**
     * Delete bug (alias for archive_bug for backward compatibility)
     *
     * @param int $bug_id Bug ID
     * @return bool Success
     */
    public static function delete_bug($bug_id) {
        return self::archive_bug($bug_id);
    }

    /**
     * Unarchive bug (restore to open status)
     *
     * @param int $bug_id Bug ID
     * @return bool Success
     */
    public static function unarchive_bug($bug_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        
        // Restore to open status
        $result = $wpdb->update(
            $table,
            array(
                'status' => 'open',
                'updated_at' => current_time('mysql')
            ),
            array('id' => $bug_id)
        );
        
        // Log the unarchive
        error_log('SourceHub Bug Tracker: Bug #' . $bug_id . ' unarchived');
        
        return $result !== false;
    }

    /**
     * Add note to bug
     *
     * @param int $bug_id Bug ID
     * @param string $note Note content
     * @param int $author_id Author user ID
     * @return int|false Note ID on success, false on failure
     */
    public static function add_note($bug_id, $note, $author_id = null) {
        global $wpdb;
        
        if ($author_id === null) {
            $author_id = get_current_user_id();
        }
        
        $table = $wpdb->prefix . 'sourcehub_bug_notes';
        
        // Parse @mentions from note
        $mentioned_users = self::parse_mentions($note);
        
        // Handle image uploads
        $images = array();
        if (isset($_FILES['note_images']) && !empty($_FILES['note_images']['name'][0])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            
            $files = $_FILES['note_images'];
            $file_count = count($files['name']);
            
            for ($i = 0; $i < $file_count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $file = array(
                        'name'     => $files['name'][$i],
                        'type'     => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error'    => $files['error'][$i],
                        'size'     => $files['size'][$i]
                    );
                    
                    $upload = wp_handle_upload($file, array('test_form' => false));
                    
                    if (!isset($upload['error'])) {
                        $images[] = array(
                            'url' => $upload['url'],
                            'file' => $upload['file'],
                            'type' => $upload['type']
                        );
                    }
                }
            }
        }
        
        $note_data = array(
            'bug_id' => $bug_id,
            'note' => wp_kses_post($note),
            'author_id' => $author_id,
            'created_at' => current_time('mysql')
        );
        
        // Add images if any were uploaded
        if (!empty($images)) {
            $note_data['images'] = json_encode($images);
        }
        
        // Add mentions if any were found
        if (!empty($mentioned_users)) {
            $note_data['mentions'] = json_encode($mentioned_users);
            
            // Subscribe mentioned users to the bug
            foreach ($mentioned_users as $user_id) {
                $user = get_userdata($user_id);
                if ($user && $user->user_email) {
                    self::subscribe($bug_id, $user->user_email);
                }
            }
        }
        
        $result = $wpdb->insert($table, $note_data);
        
        if ($result === false) {
            return false;
        }
        
        $note_id = $wpdb->insert_id;
        
        // Update bug's updated_at timestamp
        self::update_bug($bug_id, array());
        
        // Send notification to subscribers and mentioned users
        $author = get_userdata($author_id);
        $author_name = $author ? $author->display_name : 'Someone';
        
        $bug = self::get_bug($bug_id);
        $subject = $author_name . ' commented on "' . $bug->title . '"';
        $message = $author_name . ' added a comment:' . "\n\n" . wp_strip_all_tags($note);
        
        // Send immediate notification to mentioned users
        if (!empty($mentioned_users)) {
            foreach ($mentioned_users as $user_id) {
                $mentioned_user = get_userdata($user_id);
                if ($mentioned_user && $mentioned_user->user_email) {
                    $mention_subject = $author_name . ' mentioned you in "' . $bug->title . '"';
                    $mention_message = $author_name . ' mentioned you in a comment:' . "\n\n" . wp_strip_all_tags($note);
                    $mention_message .= "\n\nView bug: " . admin_url('admin.php?page=sourcehub-bug-tracker&action=view&id=' . $bug_id);
                    wp_mail($mentioned_user->user_email, $mention_subject, $mention_message);
                }
            }
        }
        self::notify_subscribers($bug_id, $subject, $message, $images);
        
        return $note_id;
    }

    /**
     * Get notes for a bug
     *
     * @param int $bug_id Bug ID
     * @return array Array of note objects
     */
    public static function get_notes($bug_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bug_notes';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE bug_id = %d ORDER BY created_at ASC",
            $bug_id
        ));
    }

    /**
     * Get bug statistics
     *
     * @return array Statistics
     */
    public static function get_stats() {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        
        $stats = array(
            'total' => 0,
            'open' => 0,
            'in_progress' => 0,
            'resolved' => 0,
            'closed' => 0,
            'by_priority' => array(
                'critical' => 0,
                'high' => 0,
                'medium' => 0,
                'low' => 0
            )
        );
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            return $stats;
        }
        
        // Get status counts
        $status_counts = $wpdb->get_results(
            "SELECT status, COUNT(*) as count FROM $table GROUP BY status"
        );
        
        foreach ($status_counts as $row) {
            $stats[$row->status] = (int) $row->count;
            $stats['total'] += (int) $row->count;
        }
        
        // Get priority counts for open bugs
        $priority_counts = $wpdb->get_results(
            "SELECT priority, COUNT(*) as count FROM $table WHERE status IN ('open', 'in_progress') GROUP BY priority"
        );
        
        foreach ($priority_counts as $row) {
            $stats['by_priority'][$row->priority] = (int) $row->count;
        }
        
        return $stats;
    }

    /**
     * Send bug data to Zapier webhook
     *
     * @param int $bug_id Bug ID
     * @param array $bug_data Bug data
     */
    private static function send_to_zapier($bug_id, $bug_data) {
        // Prepare webhook payload
        $payload = array_merge($bug_data, array(
            'bug_id' => $bug_id,
            'admin_url' => admin_url('admin.php?page=sourcehub-bugs&action=view&bug_id=' . $bug_id)
        ));
        
        // Send to Zapier
        $response = wp_remote_post(self::$zapier_webhook, array(
            'body' => json_encode($payload),
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'timeout' => 10
        ));
        
        if (is_wp_error($response)) {
            error_log('SourceHub Bug Tracker: Failed to send to Zapier - ' . $response->get_error_message());
        } else {
            error_log('SourceHub Bug Tracker: Bug sent to Zapier webhook - Bug ID: ' . $bug_id);
        }
    }

    /**
     * Get available categories
     *
     * @return array Categories
     */
    public static function get_categories() {
        return array(
            'syndication' => 'Syndication',
            'calendar' => 'Calendar',
            'ai_rewriting' => 'AI Rewriting',
            'smart_links' => 'Smart Links',
            'yoast_seo' => 'Yoast SEO Integration',
            'newspaper_theme' => 'Newspaper Theme',
            'connections' => 'Connections',
            'validation' => 'Validation',
            'performance' => 'Performance',
            'ui' => 'User Interface',
            'other' => 'Other'
        );
    }

    /**
     * Get status badge HTML
     *
     * @param string $status Status
     * @return string HTML
     */
    public static function get_status_badge($status) {
        $colors = array(
            'open' => '#dc3545',
            'in_progress' => '#ffc107',
            'resolved' => '#28a745',
            'closed' => '#6c757d'
        );
        
        $labels = array(
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed'
        );
        
        $color = $colors[$status] ?? '#6c757d';
        $label = $labels[$status] ?? ucfirst($status);
        
        return sprintf(
            '<span class="sourcehub-badge" style="background-color: %s; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;">%s</span>',
            esc_attr($color),
            esc_html($label)
        );
    }

    /**
     * Get priority badge HTML
     *
     * @param string $priority Priority
     * @return string HTML
     */
    public static function get_priority_badge($priority) {
        $colors = array(
            'critical' => '#dc3545',
            'high' => '#fd7e14',
            'medium' => '#ffc107',
            'low' => '#28a745'
        );
        
        $color = $colors[$priority] ?? '#6c757d';
        
        return sprintf(
            '<span class="sourcehub-badge" style="background-color: %s; color: white; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600;">%s</span>',
            esc_attr($color),
            esc_html(ucfirst($priority))
        );
    }

    /**
     * Subscribe to bug updates
     *
     * @param int $bug_id Bug ID
     * @param string $email Email address
     * @return bool Success
     */
    public static function subscribe($bug_id, $email) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        $bug = self::get_bug($bug_id);
        
        if (!$bug) {
            return false;
        }
        
        $subscribers = !empty($bug->subscribers) ? json_decode($bug->subscribers, true) : array();
        
        // Add email if not already subscribed
        if (!in_array($email, $subscribers)) {
            $subscribers[] = $email;
            
            $wpdb->update(
                $table,
                array('subscribers' => json_encode($subscribers)),
                array('id' => $bug_id)
            );
        }
        
        return true;
    }

    /**
     * Unsubscribe from bug updates
     *
     * @param int $bug_id Bug ID
     * @param string $email Email address
     * @return bool Success
     */
    public static function unsubscribe($bug_id, $email) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'sourcehub_bugs';
        $bug = self::get_bug($bug_id);
        
        if (!$bug) {
            return false;
        }
        
        $subscribers = !empty($bug->subscribers) ? json_decode($bug->subscribers, true) : array();
        
        // Remove email
        $subscribers = array_diff($subscribers, array($email));
        
        $wpdb->update(
            $table,
            array('subscribers' => json_encode(array_values($subscribers))),
            array('id' => $bug_id)
        );
        
        return true;
    }

    /**
     * Check if email is subscribed to bug
     *
     * @param int $bug_id Bug ID
     * @param string $email Email address
     * @return bool Is subscribed
     */
    public static function is_subscribed($bug_id, $email) {
        $bug = self::get_bug($bug_id);
        
        if (!$bug || empty($bug->subscribers)) {
            return false;
        }
        
        $subscribers = json_decode($bug->subscribers, true);
        return in_array($email, $subscribers);
    }

    /**
     * Send email notification to subscribers
     *
     * @param int $bug_id Bug ID
     * @param string $subject Email subject
     * @param string $message Email message
     * @param array $images Optional array of images to include
     */
    public static function notify_subscribers($bug_id, $subject, $message, $images = array()) {
        $bug = self::get_bug($bug_id);
        
        if (!$bug || empty($bug->subscribers)) {
            return;
        }
        
        $subscribers = json_decode($bug->subscribers, true);
        
        if (empty($subscribers)) {
            return;
        }
        
        $bug_url = admin_url('admin.php?page=sourcehub-bugs&action=view&bug_id=' . $bug_id);
        
        // Always send HTML email
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        // Build HTML email
        $full_message = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body style="margin: 0; padding: 0; background: #f5f5f5; font-family: \'Google Sans\', Roboto, Arial, sans-serif;">
            <table width="100%" cellpadding="0" cellspacing="0" style="padding: 24px 0;">
                <tr>
                    <td align="center">
                        <!-- Main Container -->
                        <table width="600" cellpadding="0" cellspacing="0" style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);">
                            <!-- Header -->
                            <tr>
                                <td style="padding: 24px 24px 16px 24px; border-bottom: 1px solid #e8eaed;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <p style="margin: 0; color: #5f6368; font-size: 14px; line-height: 20px;">
                                                    SourceHub Bug Tracker
                                                </p>
                                                <h1 style="margin: 4px 0 0 0; color: #202124; font-size: 24px; font-weight: 400; line-height: 32px;">
                                                    ' . esc_html($bug->title) . '
                                                </h1>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Content -->
                            <tr>
                                <td style="padding: 24px;">
                                    <!-- Message -->
                                    <div style="margin-bottom: 24px;">
                                        <p style="margin: 0 0 16px 0; color: #202124; font-size: 14px; line-height: 20px;">
                                            ' . nl2br(esc_html($message)) . '
                                        </p>
                                    </div>';
        
        // Add images if present
        if (!empty($images)) {
            $full_message .= '
                                    
                                    <!-- Images -->
                                    <div style="margin-bottom: 24px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>';
            
            $image_count = 0;
            foreach ($images as $image) {
                if ($image_count > 0 && $image_count % 2 === 0) {
                    $full_message .= '</tr><tr>';
                }
                $full_message .= '
                                                    <td width="48%" style="padding: 4px;" valign="top">
                                                        <a href="' . esc_url($image['url']) . '" style="display: block; border: 1px solid #dadce0; border-radius: 8px; overflow: hidden;">
                                                            <img src="' . esc_url($image['url']) . '" alt="Attachment" style="width: 100%; height: auto; display: block;">
                                                        </a>
                                                    </td>';
                if ($image_count % 2 === 0) {
                    $full_message .= '<td width="4%"></td>';
                }
                $image_count++;
            }
            
            $full_message .= '
                                                </tr>
                                            </table>
                                        </div>';
        }
        
        // Close HTML email
        $full_message .= '
                                    
                                    <!-- CTA Button -->
                                    <div style="margin: 24px 0;">
                                        <a href="' . esc_url($bug_url) . '" style="display: inline-block; background: #1a73e8; color: white; text-decoration: none; padding: 10px 24px; border-radius: 4px; font-weight: 500; font-size: 14px; line-height: 20px;">
                                            View in Bug Tracker
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td style="padding: 24px; border-top: 1px solid #e8eaed;">
                                    <p style="margin: 0 0 8px 0; color: #5f6368; font-size: 12px; line-height: 16px;">
                                        You\'re receiving this because you\'re subscribed to this bug.
                                    </p>
                                    <p style="margin: 0; color: #5f6368; font-size: 12px; line-height: 16px;">
                                        <a href="' . esc_url($bug_url) . '" style="color: #1a73e8; text-decoration: none;">Unsubscribe</a> · 
                                        <a href="' . esc_url(admin_url('admin.php?page=sourcehub-bugs')) . '" style="color: #1a73e8; text-decoration: none;">View all bugs</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        
                        <!-- Branding -->
                        <table width="600" cellpadding="0" cellspacing="0" style="margin-top: 16px;">
                            <tr>
                                <td style="text-align: center; padding: 16px;">
                                    <p style="margin: 0; color: #5f6368; font-size: 12px; line-height: 16px;">
                                        SourceHub Bug Tracker
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
        
        $sent_count = 0;
        $failed_count = 0;
        
        foreach ($subscribers as $email) {
            $result = wp_mail($email, $subject, $full_message, $headers);
            
            if ($result) {
                $sent_count++;
                error_log('SourceHub Bug Tracker: Email sent successfully to ' . $email);
            } else {
                $failed_count++;
                error_log('SourceHub Bug Tracker: Failed to send email to ' . $email);
            }
        }
        
        error_log('SourceHub Bug Tracker: Email summary for bug #' . $bug_id . ' - Sent: ' . $sent_count . ', Failed: ' . $failed_count);
    }

    /**
     * Parse @mentions from text and return array of user IDs
     *
     * @param string $text Text to parse
     * @return array Array of user IDs
     */
    private static function parse_mentions($text) {
        $mentioned_users = array();
        
        // Match @username patterns
        preg_match_all('/@(\w+)/', $text, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $username) {
                $user = get_user_by('login', $username);
                if ($user) {
                    $mentioned_users[] = $user->ID;
                }
            }
        }
        
        return array_unique($mentioned_users);
    }

    /**
     * Get all WordPress users for mention autocomplete
     *
     * @return array Array of users with id, login, and display_name
     */
    public static function get_users_for_mentions() {
        $users = get_users(array(
            'orderby' => 'display_name',
            'order' => 'ASC'
        ));
        
        $user_list = array();
        foreach ($users as $user) {
            $user_list[] = array(
                'id' => $user->ID,
                'login' => $user->user_login,
                'name' => $user->display_name,
                'email' => $user->user_email
            );
        }
        
        return $user_list;
    }
}
