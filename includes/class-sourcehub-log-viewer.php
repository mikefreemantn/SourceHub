<?php
/**
 * Activity Log Viewer
 * Provides URL access to view activity logs
 */

class SourceHub_Log_Viewer {
    
    /**
     * Initialize the log viewer
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'handle_log_view_request'));
        add_action('admin_notices', array(__CLASS__, 'show_token_notice'));
    }
    
    /**
     * Generate a secure random token
     */
    private static function generate_token() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Show token in admin notices for easy access
     */
    public static function show_token_notice() {
        // Only show on SourceHub pages
        $screen = get_current_screen();
        if (!$screen || strpos($screen->id, 'sourcehub') === false) {
            return;
        }
        
        // Only show to admins
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $token = get_option('sourcehub_log_viewer_token');
        if (empty($token)) {
            $token = self::generate_token();
            update_option('sourcehub_log_viewer_token', $token);
        }
        
        $log_url = add_query_arg(array(
            'sourcehub_view_log' => '1',
            'token' => $token
        ), home_url());
        
        ?>
        <div class="notice notice-info" style="padding: 12px; border-left-color: #2271b1;">
            <p style="margin: 0;">
                <strong>📊 Activity Log Viewer:</strong> 
                <a href="<?php echo esc_url($log_url); ?>" target="_blank" style="text-decoration: none;">
                    <?php echo esc_html($log_url); ?>
                </a>
                <button type="button" class="button button-small" onclick="navigator.clipboard.writeText('<?php echo esc_js($log_url); ?>'); this.textContent='Copied!';" style="margin-left: 10px;">
                    Copy URL
                </button>
            </p>
        </div>
        <?php
    }
    
    /**
     * Handle log view requests
     */
    public static function handle_log_view_request() {
        // Check if this is a log view request
        if (!isset($_GET['sourcehub_view_log'])) {
            return;
        }
        
        // Security check - either logged in as admin OR valid token
        $has_valid_token = false;
        if (isset($_GET['token'])) {
            $provided_token = sanitize_text_field($_GET['token']);
            $stored_token = get_option('sourcehub_log_viewer_token');
            
            // If no token exists, generate one
            if (empty($stored_token)) {
                $stored_token = self::generate_token();
                update_option('sourcehub_log_viewer_token', $stored_token);
            }
            
            $has_valid_token = hash_equals($stored_token, $provided_token);
        }
        
        if (!$has_valid_token && !current_user_can('manage_options')) {
            wp_die('Unauthorized access. Use ?sourcehub_view_log=1&token=YOUR_TOKEN', 'Access Denied', array('response' => 403));
        }
        
        // Get the log file to view
        $log_type = isset($_GET['log']) ? sanitize_text_field($_GET['log']) : 'current';
        $format = isset($_GET['format']) ? sanitize_text_field($_GET['format']) : 'html';
        
        // Get site domain for log filename
        $site_domain = str_replace(array('http://', 'https://'), '', home_url());
        $site_domain = preg_replace('/[^a-zA-Z0-9.-]/', '', $site_domain);
        
        // Determine which log file to show
        $log_dir = SOURCEHUB_PLUGIN_DIR . 'logs/';
        
        if ($log_type === 'current') {
            // Show the current version's log
            $log_file = $log_dir . $site_domain . '.' . SOURCEHUB_VERSION . '.csv';
        } else {
            // Show a specific version's log
            $version = preg_replace('/[^0-9.]/', '', $log_type);
            $log_file = $log_dir . $site_domain . '.' . $version . '.csv';
        }
        
        // Check if log file exists
        if (!file_exists($log_file)) {
            wp_die('Log file not found: ' . basename($log_file), 'Log Not Found', array('response' => 404));
        }
        
        // Output based on format
        if ($format === 'csv') {
            self::output_csv($log_file);
        } elseif ($format === 'json') {
            self::output_json($log_file);
        } else {
            self::output_html($log_file);
        }
        
        exit;
    }
    
    /**
     * Output log as CSV download
     */
    private static function output_csv($log_file) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . basename($log_file) . '"');
        readfile($log_file);
    }
    
    /**
     * Output log as JSON for programmatic access
     */
    private static function output_json($log_file) {
        $lines = file($log_file);
        $entries = array();
        
        // Get limit parameter
        $limit = isset($_GET['limit']) ? max(1, min(1000, intval($_GET['limit']))) : 100;
        
        // Skip header row and get last N entries
        $data_lines = array_slice($lines, 1);
        $data_lines = array_reverse($data_lines); // Most recent first
        $data_lines = array_slice($data_lines, 0, $limit);
        
        foreach ($data_lines as $line) {
            $data = str_getcsv($line);
            if (count($data) < 6) continue;
            
            list($date, $status, $action, $message, $connection, $details) = $data;
            
            $entries[] = array(
                'date' => $date,
                'status' => $status,
                'action' => $action,
                'message' => $message,
                'connection' => $connection,
                'details' => json_decode($details, true)
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'count' => count($entries),
            'limit' => $limit,
            'entries' => $entries
        ), JSON_PRETTY_PRINT);
    }
    
    /**
     * Output log as HTML table
     */
    private static function output_html($log_file) {
        $lines = file($log_file);
        $total_lines = count($lines);
        
        // Get pagination parameters
        $per_page = isset($_GET['per_page']) ? max(10, min(1000, intval($_GET['per_page']))) : 100;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        
        // Calculate pagination
        $total_pages = ceil(($total_lines - 1) / $per_page); // -1 for header
        $offset = ($page - 1) * $per_page + 1; // +1 to skip header
        $lines_to_show = array_slice($lines, $offset, $per_page);
        
        // Get available log files
        $log_dir = SOURCEHUB_PLUGIN_DIR . 'logs/';
        $site_domain = str_replace(array('http://', 'https://'), '', home_url());
        $site_domain = preg_replace('/[^a-zA-Z0-9.-]/', '', $site_domain);
        $available_logs = glob($log_dir . $site_domain . '.*.csv');
        
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>SourceHub Activity Log</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                    margin: 20px;
                    background: #f0f0f1;
                }
                .container {
                    max-width: 100%;
                    background: white;
                    padding: 20px;
                    border-radius: 4px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                }
                h1 {
                    margin-top: 0;
                    color: #1d2327;
                }
                .controls {
                    margin-bottom: 20px;
                    padding: 15px;
                    background: #f6f7f7;
                    border-radius: 4px;
                }
                .controls select, .controls input {
                    padding: 5px 10px;
                    margin-right: 10px;
                }
                .controls button {
                    padding: 6px 12px;
                    background: #2271b1;
                    color: white;
                    border: none;
                    border-radius: 3px;
                    cursor: pointer;
                }
                .controls button:hover {
                    background: #135e96;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 13px;
                }
                th {
                    background: #f6f7f7;
                    padding: 10px;
                    text-align: left;
                    border-bottom: 2px solid #c3c4c7;
                    position: sticky;
                    top: 0;
                }
                td {
                    padding: 8px 10px;
                    border-bottom: 1px solid #e0e0e0;
                    vertical-align: top;
                }
                tr:hover {
                    background: #f6f7f7;
                }
                .status-SUCCESS { color: #00a32a; font-weight: 600; }
                .status-ERROR { color: #d63638; font-weight: 600; }
                .status-WARNING { color: #dba617; font-weight: 600; }
                .status-INFO { color: #2271b1; }
                .details {
                    font-family: monospace;
                    font-size: 11px;
                    max-width: 400px;
                    overflow-x: auto;
                    background: #f6f7f7;
                    padding: 4px 8px;
                    border-radius: 3px;
                }
                .pagination {
                    margin-top: 20px;
                    padding: 15px;
                    background: #f6f7f7;
                    border-radius: 4px;
                    text-align: center;
                }
                .pagination a {
                    padding: 5px 10px;
                    margin: 0 2px;
                    background: white;
                    border: 1px solid #c3c4c7;
                    text-decoration: none;
                    color: #2271b1;
                    border-radius: 3px;
                }
                .pagination a:hover {
                    background: #f6f7f7;
                }
                .pagination .current {
                    padding: 5px 10px;
                    margin: 0 2px;
                    background: #2271b1;
                    color: white;
                    border-radius: 3px;
                }
                .stats {
                    margin-bottom: 15px;
                    color: #50575e;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>📊 SourceHub Activity Log</h1>
                
                <div class="controls">
                    <form method="get" style="display: inline;">
                        <input type="hidden" name="sourcehub_view_log" value="1">
                        
                        <label>Log Version:</label>
                        <select name="log" onchange="this.form.submit()">
                            <?php
                            foreach ($available_logs as $log_path) {
                                $basename = basename($log_path);
                                preg_match('/\.(\d+\.\d+\.\d+\.\d+)\.csv$/', $basename, $matches);
                                $version = $matches[1] ?? 'unknown';
                                $selected = (isset($_GET['log']) && $_GET['log'] === $version) || 
                                           (!isset($_GET['log']) && $version === SOURCEHUB_VERSION) ? 'selected' : '';
                                echo "<option value=\"{$version}\" {$selected}>v{$version}</option>";
                            }
                            ?>
                        </select>
                        
                        <label>Per Page:</label>
                        <select name="per_page" onchange="this.form.submit()">
                            <option value="50" <?php selected($per_page, 50); ?>>50</option>
                            <option value="100" <?php selected($per_page, 100); ?>>100</option>
                            <option value="250" <?php selected($per_page, 250); ?>>250</option>
                            <option value="500" <?php selected($per_page, 500); ?>>500</option>
                        </select>
                        
                        <button type="submit">Refresh</button>
                    </form>
                    
                    <a href="?sourcehub_view_log=1&log=<?php echo isset($_GET['log']) ? esc_attr($_GET['log']) : 'current'; ?>&format=csv" style="margin-left: 10px;">
                        <button type="button">Download CSV</button>
                    </a>
                </div>
                
                <div class="stats">
                    Showing entries <?php echo number_format($offset); ?> - <?php echo number_format(min($offset + $per_page - 1, $total_lines - 1)); ?> 
                    of <?php echo number_format($total_lines - 1); ?> total
                    (Page <?php echo $page; ?> of <?php echo $total_pages; ?>)
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th style="width: 150px;">Date</th>
                            <th style="width: 80px;">Status</th>
                            <th style="width: 120px;">Action</th>
                            <th>Message</th>
                            <th style="width: 120px;">Connection</th>
                            <th style="width: 200px;">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($lines_to_show as $line) {
                            $data = str_getcsv($line);
                            if (count($data) < 6) continue;
                            
                            list($date, $status, $action, $message, $connection, $details) = $data;
                            
                            echo '<tr>';
                            echo '<td>' . esc_html($date) . '</td>';
                            echo '<td class="status-' . esc_attr($status) . '">' . esc_html($status) . '</td>';
                            echo '<td>' . esc_html($action) . '</td>';
                            echo '<td>' . esc_html($message) . '</td>';
                            echo '<td>' . esc_html($connection) . '</td>';
                            echo '<td><div class="details">' . esc_html($details) . '</div></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php
                    $base_url = '?sourcehub_view_log=1&log=' . (isset($_GET['log']) ? urlencode($_GET['log']) : 'current') . '&per_page=' . $per_page;
                    
                    if ($page > 1) {
                        echo '<a href="' . $base_url . '&page=1">« First</a>';
                        echo '<a href="' . $base_url . '&page=' . ($page - 1) . '">‹ Prev</a>';
                    }
                    
                    $start = max(1, $page - 5);
                    $end = min($total_pages, $page + 5);
                    
                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $page) {
                            echo '<span class="current">' . $i . '</span>';
                        } else {
                            echo '<a href="' . $base_url . '&page=' . $i . '">' . $i . '</a>';
                        }
                    }
                    
                    if ($page < $total_pages) {
                        echo '<a href="' . $base_url . '&page=' . ($page + 1) . '">Next ›</a>';
                        echo '<a href="' . $base_url . '&page=' . $total_pages . '">Last »</a>';
                    }
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }
}
