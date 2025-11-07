<?php
/**
 * Database management class
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Database Class
 */
class SourceHub_Database {

    /**
     * Initialize database
     */
    public static function init() {
        // Check if tables exist and create if needed
        if (get_option('sourcehub_db_version') !== SOURCEHUB_VERSION) {
            self::create_tables();
            update_option('sourcehub_db_version', SOURCEHUB_VERSION);
        }
    }

    /**
     * Create database tables
     */
    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Connections table
        $connections_table = $wpdb->prefix . 'sourcehub_connections';
        $connections_sql = "CREATE TABLE $connections_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            url varchar(255) NOT NULL,
            api_key varchar(255) NOT NULL,
            mode enum('hub','spoke') NOT NULL DEFAULT 'spoke',
            status enum('active','inactive','error') NOT NULL DEFAULT 'active',
            last_sync datetime DEFAULT NULL,
            sync_settings longtext,
            ai_settings longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY url (url)
        ) $charset_collate;";

        // Logs table
        $logs_table = $wpdb->prefix . 'sourcehub_logs';
        $logs_sql = "CREATE TABLE $logs_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) DEFAULT NULL,
            connection_id mediumint(9) DEFAULT NULL,
            action varchar(50) NOT NULL,
            status enum('SUCCESS','ERROR','WARNING','INFO') NOT NULL,
            message text,
            data longtext,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY connection_id (connection_id),
            KEY status (status),
            KEY created_at (created_at),
            KEY status_created (status, created_at),
            KEY action_created (action, created_at)
        ) $charset_collate;";

        // Queue table for failed/retry operations
        $queue_table = $wpdb->prefix . 'sourcehub_queue';
        $queue_sql = "CREATE TABLE $queue_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id bigint(20) NOT NULL,
            connection_id mediumint(9) NOT NULL,
            action varchar(50) NOT NULL,
            payload longtext NOT NULL,
            attempts tinyint(3) NOT NULL DEFAULT 0,
            max_attempts tinyint(3) NOT NULL DEFAULT 3,
            next_attempt datetime DEFAULT NULL,
            status enum('pending','processing','failed','completed') NOT NULL DEFAULT 'pending',
            error_message text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY connection_id (connection_id),
            KEY status (status),
            KEY next_attempt (next_attempt)
        ) $charset_collate;";
        
        // Sync jobs table for async processing
        $sync_jobs_table = $wpdb->prefix . 'sourcehub_sync_jobs';
        $sync_jobs_sql = "CREATE TABLE $sync_jobs_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            job_id varchar(64) NOT NULL,
            hub_post_id bigint(20) NOT NULL,
            hub_url varchar(255) NOT NULL,
            action enum('create','update') NOT NULL,
            payload longtext NOT NULL,
            status enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
            result longtext,
            error_message text,
            started_at datetime DEFAULT NULL,
            completed_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY job_id (job_id),
            KEY hub_post_id (hub_post_id),
            KEY status (status),
            KEY created_at (created_at)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $results = array();
        $results['connections'] = dbDelta($connections_sql);
        $results['logs'] = dbDelta($logs_sql);
        $results['queue'] = dbDelta($queue_sql);
        $results['sync_jobs'] = dbDelta($sync_jobs_sql);
        
        // Check for errors
        if ($wpdb->last_error) {
            error_log('SourceHub Database Creation Error: ' . $wpdb->last_error);
        }
        
        // Verify tables were created
        $tables_created = array();
        $tables_created['connections'] = ($wpdb->get_var("SHOW TABLES LIKE '$connections_table'") == $connections_table);
        $tables_created['logs'] = ($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") == $logs_table);
        $tables_created['queue'] = ($wpdb->get_var("SHOW TABLES LIKE '$queue_table'") == $queue_table);
        $tables_created['sync_jobs'] = ($wpdb->get_var("SHOW TABLES LIKE '$sync_jobs_table'") == $sync_jobs_table);
        
        foreach ($tables_created as $table => $created) {
            if (!$created) {
                error_log("SourceHub: Failed to create $table table");
            }
        }
        
        return $tables_created;
    }

    /**
     * Get connections
     *
     * @param array $args Query arguments
     * @return array
     */
    public static function get_connections($args = array()) {
        global $wpdb;

        $defaults = array(
            'status' => 'active',
            'mode' => null,
            'limit' => 50,
            'offset' => 0
        );

        $args = wp_parse_args($args, $defaults);

        $table = $wpdb->prefix . 'sourcehub_connections';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            return array();
        }
        
        $where = array('1=1');
        $values = array();

        if (!empty($args['status'])) {
            $where[] = 'status = %s';
            $values[] = $args['status'];
        }

        if (!empty($args['mode'])) {
            $where[] = 'mode = %s';
            $values[] = $args['mode'];
        }

        $where_clause = implode(' AND ', $where);
        $limit_clause = '';

        if ($args['limit'] > 0) {
            $limit_clause = $wpdb->prepare(' LIMIT %d OFFSET %d', $args['limit'], $args['offset']);
        }

        $sql = "SELECT * FROM $table WHERE $where_clause ORDER BY name ASC$limit_clause";

        if (!empty($values)) {
            $sql = $wpdb->prepare($sql, $values);
        }

        $results = $wpdb->get_results($sql);
        
        if ($wpdb->last_error) {
            error_log('SourceHub Database Error: ' . $wpdb->last_error);
            return array();
        }
        
        return $results ? $results : array();
    }

    /**
     * Get connection by ID
     *
     * @param int $id Connection ID
     * @return object|null
     */
    public static function get_connection($id) {
        global $wpdb;

        $table = $wpdb->prefix . 'sourcehub_connections';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $id
        ));
    }

    /**
     * Get connection by URL
     *
     * @param string $url Connection URL
     * @return object|null
     */
    public static function get_connection_by_url($url) {
        global $wpdb;

        $table = $wpdb->prefix . 'sourcehub_connections';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE url = %s",
            $url
        ));
    }

    /**
     * Add connection
     *
     * @param array $data Connection data
     * @return int|false Connection ID or false on failure
     */
    public static function add_connection($data) {
        global $wpdb;

        $table = $wpdb->prefix . 'sourcehub_connections';

        $defaults = array(
            'name' => '',
            'url' => '',
            'api_key' => wp_generate_password(32, false),
            'mode' => 'spoke',
            'status' => 'active',
            'sync_settings' => json_encode(array()),
            'ai_settings' => json_encode(array())
        );

        $data = wp_parse_args($data, $defaults);

        $result = $wpdb->insert($table, $data);

        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Update connection
     *
     * @param int $id Connection ID
     * @param array $data Connection data
     * @return bool
     */
    public static function update_connection($id, $data) {
        global $wpdb;

        $table = $wpdb->prefix . 'sourcehub_connections';

        return $wpdb->update(
            $table,
            $data,
            array('id' => $id),
            null,
            array('%d')
        ) !== false;
    }

    /**
     * Delete connection
     *
     * @param int $id Connection ID
     * @return bool
     */
    public static function delete_connection($id) {
        global $wpdb;

        $table = $wpdb->prefix . 'sourcehub_connections';

        return $wpdb->delete(
            $table,
            array('id' => $id),
            array('%d')
        ) !== false;
    }

    /**
     * Get logs
     *
     * @param array $args Query arguments
     * @return array
     */
    public static function get_logs($args = array()) {
        global $wpdb;

        $defaults = array(
            'post_id' => null,
            'connection_id' => null,
            'status' => null,
            'action' => null,
            'search' => null,
            'limit' => 50,
            'offset' => 0,
            'order' => 'DESC'
        );

        $args = wp_parse_args($args, $defaults);

        $logs_table = $wpdb->prefix . 'sourcehub_logs';
        $connections_table = $wpdb->prefix . 'sourcehub_connections';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$logs_table'") != $logs_table) {
            return array();
        }
        
        $where = array('1=1');
        $values = array();

        if (!empty($args['post_id'])) {
            $where[] = 'l.post_id = %d';
            $values[] = $args['post_id'];
        }

        if (!empty($args['connection_id'])) {
            $where[] = 'l.connection_id = %d';
            $values[] = $args['connection_id'];
        }

        if (!empty($args['status'])) {
            $where[] = 'l.status = %s';
            $values[] = $args['status'];
        }

        if (!empty($args['action'])) {
            $where[] = 'l.action = %s';
            $values[] = $args['action'];
        }
        
        if (!empty($args['search'])) {
            $where[] = '(l.message LIKE %s OR l.data LIKE %s OR c.name LIKE %s)';
            $search_term = '%' . $wpdb->esc_like($args['search']) . '%';
            $values[] = $search_term;
            $values[] = $search_term;
            $values[] = $search_term;
        }
        
        if (!empty($args['date'])) {
            $where[] = 'DATE(l.created_at) = %s';
            $values[] = $args['date'];
        }

        $where_clause = implode(' AND ', $where);
        $order = in_array(strtoupper($args['order']), array('ASC', 'DESC')) ? $args['order'] : 'DESC';
        $limit_clause = '';

        if ($args['limit'] > 0) {
            $limit_clause = $wpdb->prepare(' LIMIT %d OFFSET %d', $args['limit'], $args['offset']);
        }

        // Use LEFT JOIN to fetch connection data in a single query (eliminates N+1 problem)
        $sql = "SELECT l.*, 
                       c.name as connection_name, 
                       c.url as connection_url,
                       c.mode as connection_mode
                FROM $logs_table l
                LEFT JOIN $connections_table c ON l.connection_id = c.id
                WHERE $where_clause 
                ORDER BY l.created_at $order$limit_clause";

        if (!empty($values)) {
            $sql = $wpdb->prepare($sql, $values);
        }
        
        // Temporary debugging
        error_log('SourceHub get_logs SQL: ' . $sql);
        error_log('SourceHub get_logs args: ' . print_r($args, true));

        $results = $wpdb->get_results($sql);
        
        if ($wpdb->last_error) {
            error_log('SourceHub Database Error: ' . $wpdb->last_error);
            return array();
        }
        
        error_log('SourceHub get_logs results count: ' . count($results));
        
        return $results ? $results : array();
    }

    /**
     * Add log entry
     *
     * @param array $data Log data
     * @return int|false Log ID or false on failure
     */
    public static function add_log($data) {
        global $wpdb;

        $table = $wpdb->prefix . 'sourcehub_logs';

        $defaults = array(
            'post_id' => null,
            'connection_id' => null,
            'action' => '',
            'status' => 'INFO',
            'message' => '',
            'data' => null
        );

        $data = wp_parse_args($data, $defaults);

        if (is_array($data['data']) || is_object($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }

        $result = $wpdb->insert($table, $data);

        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Clean old logs
     *
     * @param int $days Number of days to keep
     * @return int Number of deleted rows
     */
    public static function clean_old_logs($days = 30) {
        global $wpdb;

        $table = $wpdb->prefix . 'sourcehub_logs';

        return $wpdb->query($wpdb->prepare(
            "DELETE FROM $table WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
            $days
        ));
    }

    /**
     * Count logs
     *
     * @param array $args Query arguments
     * @return int Number of logs
     */
    public static function count_logs($args = array()) {
        global $wpdb;

        $logs_table = $wpdb->prefix . 'sourcehub_logs';
        $connections_table = $wpdb->prefix . 'sourcehub_connections';
        $where_clauses = array();
        $where_values = array();

        // Status filter
        if (!empty($args['status'])) {
            $where_clauses[] = 'l.status = %s';
            $where_values[] = $args['status'];
        }

        // Post ID filter
        if (!empty($args['post_id'])) {
            $where_clauses[] = 'l.post_id = %d';
            $where_values[] = $args['post_id'];
        }

        // Connection ID filter
        if (!empty($args['connection_id'])) {
            $where_clauses[] = 'l.connection_id = %d';
            $where_values[] = $args['connection_id'];
        }

        // Action filter
        if (!empty($args['action'])) {
            $where_clauses[] = 'l.action = %s';
            $where_values[] = $args['action'];
        }
        
        // Search filter
        if (!empty($args['search'])) {
            $where_clauses[] = '(l.message LIKE %s OR l.data LIKE %s OR c.name LIKE %s)';
            $search_term = '%' . $wpdb->esc_like($args['search']) . '%';
            $where_values[] = $search_term;
            $where_values[] = $search_term;
            $where_values[] = $search_term;
        }
        
        // Date filter
        if (!empty($args['date'])) {
            $where_clauses[] = 'DATE(l.created_at) = %s';
            $where_values[] = $args['date'];
        }

        $where_sql = '';
        if (!empty($where_clauses)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
        }

        // Always use LEFT JOIN to maintain consistent table aliases
        $sql = "SELECT COUNT(*) FROM $logs_table l 
                LEFT JOIN $connections_table c ON l.connection_id = c.id 
                $where_sql";

        if (!empty($where_values)) {
            $sql = $wpdb->prepare($sql, $where_values);
        }

        return (int) $wpdb->get_var($sql);
    }
}
