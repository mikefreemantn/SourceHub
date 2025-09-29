<?php
/**
 * Logging functionality
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Logger Class
 */
class SourceHub_Logger {

    /**
     * Log levels
     */
    const SUCCESS = 'SUCCESS';
    const ERROR = 'ERROR';
    const WARNING = 'WARNING';
    const INFO = 'INFO';

    /**
     * Log a message
     *
     * @param string $message Log message
     * @param string $level Log level
     * @param array $data Additional data
     * @param int $post_id Related post ID
     * @param int $connection_id Related connection ID
     * @param string $action Action being performed
     */
    public static function log($message, $level = self::INFO, $data = array(), $post_id = null, $connection_id = null, $action = '') {
        $log_data = array(
            'message' => $message,
            'status' => $level,
            'data' => $data,
            'post_id' => $post_id,
            'connection_id' => $connection_id,
            'action' => $action
        );

        SourceHub_Database::add_log($log_data);

        // Also log to WordPress debug log if enabled
        if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            $debug_message = sprintf(
                '[SourceHub] [%s] %s',
                $level,
                $message
            );

            if (!empty($data)) {
                $debug_message .= ' | Data: ' . json_encode($data);
            }

            error_log($debug_message);
        }
    }

    /**
     * Log success message
     *
     * @param string $message Log message
     * @param array $data Additional data
     * @param int $post_id Related post ID
     * @param int $connection_id Related connection ID
     * @param string $action Action being performed
     */
    public static function success($message, $data = array(), $post_id = null, $connection_id = null, $action = '') {
        self::log($message, self::SUCCESS, $data, $post_id, $connection_id, $action);
    }

    /**
     * Log error message
     *
     * @param string $message Log message
     * @param array $data Additional data
     * @param int $post_id Related post ID
     * @param int $connection_id Related connection ID
     * @param string $action Action being performed
     */
    public static function error($message, $data = array(), $post_id = null, $connection_id = null, $action = '') {
        self::log($message, self::ERROR, $data, $post_id, $connection_id, $action);
    }

    /**
     * Log warning message
     *
     * @param string $message Log message
     * @param array $data Additional data
     * @param int $post_id Related post ID
     * @param int $connection_id Related connection ID
     * @param string $action Action being performed
     */
    public static function warning($message, $data = array(), $post_id = null, $connection_id = null, $action = '') {
        self::log($message, self::WARNING, $data, $post_id, $connection_id, $action);
    }

    /**
     * Log info message
     *
     * @param string $message Log message
     * @param array $data Additional data
     * @param int $post_id Related post ID
     * @param int $connection_id Related connection ID
     * @param string $action Action being performed
     */
    public static function info($message, $data = array(), $post_id = null, $connection_id = null, $action = '') {
        self::log($message, self::INFO, $data, $post_id, $connection_id, $action);
    }

    /**
     * Get formatted logs for display
     *
     * @param array $args Query arguments
     * @return array
     */
    public static function get_formatted_logs($args = array()) {
        try {
            $logs = SourceHub_Database::get_logs($args);
            $formatted = array();

            if (!is_array($logs)) {
                return array();
            }

            foreach ($logs as $log) {
                $formatted[] = array(
                    'id' => $log->id,
                    'post_id' => $log->post_id,
                    'connection_id' => $log->connection_id,
                    'action' => $log->action,
                    'status' => $log->status,
                    'message' => $log->message,
                    'data' => !empty($log->data) ? json_decode($log->data, true) : array(),
                    'created_at' => $log->created_at,
                    'formatted_date' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($log->created_at)),
                    'status_class' => self::get_status_class($log->status),
                    'status_icon' => self::get_status_icon($log->status)
                );
            }

            return $formatted;
        } catch (Exception $e) {
            error_log('SourceHub Logger Error: ' . $e->getMessage());
            return array();
        }
    }

    /**
     * Get CSS class for status
     *
     * @param string $status Log status
     * @return string
     */
    private static function get_status_class($status) {
        switch ($status) {
            case self::SUCCESS:
                return 'success';
            case self::ERROR:
                return 'error';
            case self::WARNING:
                return 'warning';
            case self::INFO:
            default:
                return 'info';
        }
    }

    /**
     * Get icon for status
     *
     * @param string $status Log status
     * @return string
     */
    private static function get_status_icon($status) {
        switch ($status) {
            case self::SUCCESS:
                return 'dashicons-yes-alt';
            case self::ERROR:
                return 'dashicons-dismiss';
            case self::WARNING:
                return 'dashicons-warning';
            case self::INFO:
            default:
                return 'dashicons-info';
        }
    }

    /**
     * Get log statistics
     *
     * @param array $args Query arguments
     * @return array
     */
    public static function get_stats($args = array()) {
        global $wpdb;

        $defaults = array(
            'days' => 30,
            'connection_id' => null
        );

        $args = wp_parse_args($args, $defaults);

        $table = $wpdb->prefix . 'sourcehub_logs';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            return array(
                'total_logs' => 0,
                'success_rate' => 0,
                'success_count' => 0,
                'error_count' => 0,
                'warning_count' => 0,
                'success_percentage' => 0,
                'error_percentage' => 0,
                'warning_percentage' => 0
            );
        }
        
        $where = array('created_at >= DATE_SUB(NOW(), INTERVAL %d DAY)');
        $values = array($args['days']);

        if (!empty($args['connection_id'])) {
            $where[] = 'connection_id = %d';
            $values[] = $args['connection_id'];
        }

        $where_clause = implode(' AND ', $where);

        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM $table 
                WHERE $where_clause 
                GROUP BY status";

        $results = $wpdb->get_results($wpdb->prepare($sql, $values));
        
        if ($wpdb->last_error) {
            error_log('SourceHub Logger Error: ' . $wpdb->last_error);
            return array(
                'total_logs' => 0,
                'success_rate' => 0,
                'success_count' => 0,
                'error_count' => 0,
                'warning_count' => 0,
                'success_percentage' => 0,
                'error_percentage' => 0,
                'warning_percentage' => 0
            );
        }

        $stats = array(
            'total' => 0,
            'success' => 0,
            'error' => 0,
            'warning' => 0,
            'info' => 0
        );

        if ($results) {
            foreach ($results as $result) {
                $stats['total'] += $result->count;
                $stats[strtolower($result->status)] = $result->count;
            }
        }
        
        // Calculate percentages and success rate
        $total = $stats['total'];
        $success_rate = $total > 0 ? ($stats['success'] / $total) * 100 : 0;
        
        return array(
            'total_logs' => $total,
            'success_rate' => $success_rate,
            'success_count' => $stats['success'],
            'error_count' => $stats['error'],
            'warning_count' => $stats['warning'],
            'success_percentage' => $total > 0 ? ($stats['success'] / $total) * 100 : 0,
            'error_percentage' => $total > 0 ? ($stats['error'] / $total) * 100 : 0,
            'warning_percentage' => $total > 0 ? ($stats['warning'] / $total) * 100 : 0
        );
    }
}
