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
        
        // Send notifications for errors
        self::send_error_notifications($message, $data, $post_id, $connection_id, $action);
    }
    
    /**
     * Send error notifications via email and webhook
     *
     * @param string $message Error message
     * @param array $data Additional data
     * @param int $post_id Related post ID
     * @param int $connection_id Related connection ID
     * @param string $action Action being performed
     */
    private static function send_error_notifications($message, $data = array(), $post_id = null, $connection_id = null, $action = '') {
        // Get notification settings
        $notification_emails = get_option('sourcehub_post_logs_notification_emails', '');
        $webhook_url = get_option('sourcehub_post_logs_webhook_url', '');
        
        // Prepare error data
        $error_data = array(
            'message' => $message,
            'action' => $action,
            'post_id' => $post_id,
            'connection_id' => $connection_id,
            'data' => $data,
            'timestamp' => current_time('mysql'),
            'site_url' => get_site_url()
        );
        
        // Add post details if available
        if ($post_id) {
            $post = get_post($post_id);
            if ($post) {
                $error_data['post_title'] = $post->post_title;
                $error_data['post_url'] = get_edit_post_link($post_id, 'raw');
            }
        }
        
        // Add connection details if available
        if ($connection_id) {
            $connection = SourceHub_Database::get_connection($connection_id);
            if ($connection) {
                $error_data['connection_name'] = $connection->name;
                $error_data['connection_url'] = $connection->url;
            }
        }
        
        // Send email notifications
        if (!empty($notification_emails)) {
            self::send_email_notification($error_data, $notification_emails);
        }
        
        // Send webhook notification
        if (!empty($webhook_url)) {
            self::send_webhook_notification($error_data, $webhook_url);
        }
    }
    
    /**
     * Send email notification
     *
     * @param array $error_data Error data
     * @param string $emails Email addresses (one per line)
     */
    private static function send_email_notification($error_data, $emails) {
        $email_list = array_filter(array_map('trim', explode("\n", $emails)));
        
        if (empty($email_list)) {
            return;
        }
        
        $subject = sprintf('[SourceHub] Syndication Error: %s', 
            isset($error_data['post_title']) ? $error_data['post_title'] : 'Post #' . $error_data['post_id']
        );
        
        // Build HTML email
        $message = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: #d63638; color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header .icon { font-size: 48px; margin-bottom: 10px; }
        .content { padding: 30px 20px; }
        .error-box { background: #fff3cd; border-left: 4px solid #d63638; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .error-message { color: #721c24; font-weight: 500; margin: 0; }
        .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .info-table td { padding: 12px 8px; border-bottom: 1px solid #e0e0e0; }
        .info-table td:first-child { font-weight: 600; color: #666; width: 140px; }
        .info-table td:last-child { color: #333; }
        .button { display: inline-block; padding: 12px 24px; background: #2271b1; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: 500; margin: 10px 5px; }
        .button:hover { background: #135e96; }
        .button-secondary { background: #50575e; }
        .button-secondary:hover { background: #3c434a; }
        .details { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0; font-family: monospace; font-size: 12px; overflow-x: auto; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #e0e0e0; }
        .footer a { color: #2271b1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">⚠️</div>
            <h1>Syndication Error</h1>
        </div>
        
        <div class="content">
            <div class="error-box">
                <p class="error-message">' . esc_html($error_data['message']) . '</p>
            </div>
            
            <table class="info-table">';
        
        if (isset($error_data['post_title'])) {
            $message .= '
                <tr>
                    <td>Post</td>
                    <td><strong>' . esc_html($error_data['post_title']) . '</strong></td>
                </tr>';
        }
        
        if (isset($error_data['connection_name'])) {
            $message .= '
                <tr>
                    <td>Spoke Site</td>
                    <td>' . esc_html($error_data['connection_name']) . '<br><small style="color: #666;">' . esc_html($error_data['connection_url']) . '</small></td>
                </tr>';
        }
        
        $message .= '
                <tr>
                    <td>Action</td>
                    <td>' . esc_html($error_data['action']) . '</td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td>' . esc_html($error_data['timestamp']) . '</td>
                </tr>
            </table>';
        
        if (!empty($error_data['data'])) {
            $message .= '
            <h3 style="color: #666; font-size: 14px; margin: 20px 0 10px;">Additional Details</h3>
            <div class="details">' . esc_html(print_r($error_data['data'], true)) . '</div>';
        }
        
        $message .= '
            <div style="text-align: center; margin-top: 30px;">';
        
        if (isset($error_data['post_url'])) {
            $message .= '
                <a href="' . esc_url($error_data['post_url']) . '" class="button">Edit Post</a>';
        }
        
        $message .= '
                <a href="' . esc_url(admin_url('admin.php?page=sourcehub-post-logs')) . '" class="button button-secondary">View Post Logs</a>
            </div>
        </div>
        
        <div class="footer">
            <p>This notification was sent by <a href="' . esc_url($error_data['site_url']) . '">SourceHub</a></p>
            <p style="margin: 5px 0 0;">Manage notification settings in <a href="' . esc_url(admin_url('admin.php?page=sourcehub-post-logs')) . '">Post Logs</a></p>
        </div>
    </div>
</body>
</html>';
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        foreach ($email_list as $email) {
            if (is_email($email)) {
                wp_mail($email, $subject, $message, $headers);
            }
        }
    }
    
    /**
     * Send webhook notification
     *
     * @param array $error_data Error data
     * @param string $webhook_url Webhook URL
     */
    private static function send_webhook_notification($error_data, $webhook_url) {
        if (empty($webhook_url) || !filter_var($webhook_url, FILTER_VALIDATE_URL)) {
            return;
        }
        
        $response = wp_remote_post($webhook_url, array(
            'method' => 'POST',
            'timeout' => 10,
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => 'SourceHub/' . SOURCEHUB_VERSION
            ),
            'body' => json_encode($error_data),
            'sslverify' => true
        ));
        
        // Log webhook failures silently to avoid infinite loops
        if (is_wp_error($response)) {
            error_log('SourceHub: Webhook notification failed - ' . $response->get_error_message());
        }
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
                // Add formatted fields to the existing log object
                $log->formatted_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($log->created_at));
                $log->status_class = self::get_status_class($log->status);
                $log->status_icon = self::get_status_icon($log->status);
                $log->details = !empty($log->data) ? $log->data : null;
                
                $formatted[] = $log;
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
            // Keys used by logs.php template
            'total' => $total,
            'success' => $stats['success'],
            'error' => $stats['error'],
            'warning' => $stats['warning'],
            'info' => $stats['info'],
            // Legacy keys for backwards compatibility
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

    /**
     * Clear all logs
     */
    public static function clear_logs() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sourcehub_logs';
        
        $result = $wpdb->query("TRUNCATE TABLE {$table_name}");
        
        if ($result === false) {
            throw new Exception('Failed to clear logs');
        }
        
        return true;
    }

    /**
     * Delete a single log entry
     *
     * @param int $log_id Log ID to delete
     */
    public static function delete_log($log_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sourcehub_logs';
        
        $result = $wpdb->delete(
            $table_name,
            array('id' => $log_id),
            array('%d')
        );
        
        if ($result === false) {
            throw new Exception('Failed to delete log');
        }
        
        return true;
    }
}
