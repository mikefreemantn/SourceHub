<?php
/**
 * Activity Log Viewer
 * Provides REST API access to view activity logs
 */

class SourceHub_Log_Viewer {
    
    /**
     * Initialize the log viewer
     */
    public static function init() {
        add_action('rest_api_init', array(__CLASS__, 'register_rest_routes'));
        add_action('admin_notices', array(__CLASS__, 'show_token_notice'));
    }
    
    /**
     * Register REST API routes
     */
    public static function register_rest_routes() {
        register_rest_route('sourcehub/v1', '/logs', array(
            'methods' => 'GET',
            'callback' => array(__CLASS__, 'get_logs'),
            'permission_callback' => array(__CLASS__, 'check_permission'),
        ));
    }
    
    /**
     * Check permission for REST API access
     */
    public static function check_permission($request) {
        // Check for valid token
        $token = $request->get_param('token');
        if (!empty($token)) {
            $stored_token = get_option('sourcehub_log_viewer_token');
            if (empty($stored_token)) {
                $stored_token = self::generate_token();
                update_option('sourcehub_log_viewer_token', $stored_token);
            }
            
            if (hash_equals($stored_token, $token)) {
                return true;
            }
        }
        
        // Fallback to admin check
        return current_user_can('manage_options');
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
        
        // Build URL with proper parameters
        $log_url = add_query_arg(
            array(
                'token' => $token,
                'limit' => 50
            ),
            rest_url('sourcehub/v1/logs')
        );
        
        ?>
        <div class="notice notice-info" style="padding: 12px; border-left-color: #2271b1;">
            <p style="margin: 0;">
                <strong>📊 Activity Log API:</strong> 
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
     * Get logs via REST API
     */
    public static function get_logs($request) {
        $version = $request->get_param('version') ?: SOURCEHUB_VERSION;
        $limit = $request->get_param('limit') ?: 100;
        $limit = max(1, min(1000, intval($limit)));
        
        // Find log file using glob pattern (works regardless of domain)
        $log_dir = SOURCEHUB_PLUGIN_DIR . 'logs/';
        $pattern = $log_dir . '*.' . $version . '.csv';
        $log_files = glob($pattern);
        
        // Check if log file exists
        if (empty($log_files)) {
            return new WP_Error('log_not_found', 'Log file not found for version: ' . $version, array('status' => 404));
        }
        
        // Use the first (and should be only) matching file
        $log_file = $log_files[0];
        
        // Read and parse log file
        $lines = file($log_file);
        $entries = array();
        
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
        
        return array(
            'success' => true,
            'version' => $version,
            'count' => count($entries),
            'limit' => $limit,
            'entries' => $entries
        );
    }
}
