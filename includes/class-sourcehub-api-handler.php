<?php
/**
 * API handler for REST endpoints
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub API Handler Class
 */
class SourceHub_API_Handler {

    /**
     * Initialize API handler
     */
    public function init() {
        add_action('rest_api_init', array($this, 'register_routes'));
        add_filter('rest_pre_dispatch', array($this, 'handle_cors'), 10, 3);
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {
        // Hub-specific routes
        if (sourcehub()->is_hub()) {
            $this->register_hub_routes();
        }

        // Spoke-specific routes
        if (sourcehub()->is_spoke()) {
            $this->register_spoke_routes();
        }

        // Common routes
        $this->register_common_routes();
    }

    /**
     * Register hub-specific routes
     */
    private function register_hub_routes() {
        register_rest_route('sourcehub/v1', '/connections', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_connections'),
            'permission_callback' => array($this, 'check_admin_permission')
        ));

        register_rest_route('sourcehub/v1', '/connections', array(
            'methods' => 'POST',
            'callback' => array($this, 'create_connection'),
            'permission_callback' => array($this, 'check_admin_permission'),
            'args' => array(
                'name' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                'url' => array(
                    'required' => true,
                    'type' => 'string',
                    'validate_callback' => array($this, 'validate_url')
                ),
                'api_key' => array(
                    'required' => true,
                    'type' => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                )
            )
        ));

        register_rest_route('sourcehub/v1', '/connections/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_connection'),
            'permission_callback' => array($this, 'check_admin_permission')
        ));

        register_rest_route('sourcehub/v1', '/connections/(?P<id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array($this, 'update_connection'),
            'permission_callback' => array($this, 'check_admin_permission')
        ));

        register_rest_route('sourcehub/v1', '/connections/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array($this, 'delete_connection'),
            'permission_callback' => array($this, 'check_admin_permission')
        ));

        register_rest_route('sourcehub/v1', '/connections/(?P<id>\d+)/test', array(
            'methods' => 'POST',
            'callback' => array($this, 'test_connection'),
            'permission_callback' => array($this, 'check_admin_permission')
        ));
    }

    /**
     * Register spoke-specific routes
     */
    private function register_spoke_routes() {
        // These are handled by the Spoke Manager
        // Just ensuring they're available
    }

    /**
     * Register common routes
     */
    private function register_common_routes() {
        register_rest_route('sourcehub/v1', '/info', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_info'),
            'permission_callback' => '__return_true'
        ));

        // Logs route disabled - using AJAX instead to avoid CORS issues
        /*
        register_rest_route('sourcehub/v1', '/logs', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_logs'),
            'permission_callback' => array($this, 'check_admin_permission'),
            'args' => array(
                'page' => array(
                    'default' => 1,
                    'type' => 'integer',
                    'minimum' => 1
                ),
                'per_page' => array(
                    'default' => 20,
                    'type' => 'integer',
                    'minimum' => 1,
                    'maximum' => 100
                ),
                'status' => array(
                    'type' => 'string',
                    'enum' => array('SUCCESS', 'ERROR', 'WARNING', 'INFO')
                )
            )
        ));
        */
    }

    /**
     * Handle CORS for API requests
     *
     * @param mixed $result
     * @param WP_REST_Server $server
     * @param WP_REST_Request $request
     * @return mixed
     */
    public function handle_cors($result, $server, $request) {
        $route = $request->get_route();
        
        if (strpos($route, '/sourcehub/') === 0) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-SourceHub-API-Key, X-WP-Nonce, X-Requested-With');
            header('Access-Control-Allow-Credentials: true');
            
            if ($request->get_method() === 'OPTIONS') {
                exit;
            }
        }

        return $result;
    }

    /**
     * Check admin permission
     *
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_admin_permission($request) {
        return current_user_can('manage_options');
    }

    /**
     * Check if user has permission to view logs
     * Allows editors and above to view activity logs
     *
     * @param WP_REST_Request $request
     * @return bool
     */
    public function check_logs_permission($request) {
        return current_user_can('edit_posts');
    }

    /**
     * Validate URL
     *
     * @param string $value
     * @param WP_REST_Request $request
     * @param string $param
     * @return bool
     */
    public function validate_url($value, $request, $param) {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Get connections
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_connections($request) {
        $args = array(
            'limit' => $request->get_param('per_page') ?: 50,
            'offset' => (($request->get_param('page') ?: 1) - 1) * ($request->get_param('per_page') ?: 50)
        );

        if ($request->get_param('status')) {
            $args['status'] = $request->get_param('status');
        }

        $connections = SourceHub_Database::get_connections($args);
        
        // Format connections for API response
        $formatted_connections = array_map(function($connection) {
            return array(
                'id' => (int) $connection->id,
                'name' => $connection->name,
                'url' => $connection->url,
                'mode' => $connection->mode,
                'status' => $connection->status,
                'last_sync' => $connection->last_sync,
                'sync_settings' => json_decode($connection->sync_settings, true),
                'ai_settings' => json_decode($connection->ai_settings, true),
                'created_at' => $connection->created_at,
                'updated_at' => $connection->updated_at
            );
        }, $connections);

        return new WP_REST_Response(array(
            'connections' => $formatted_connections,
            'total' => count($connections)
        ), 200);
    }

    /**
     * Get connection
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_connection($request) {
        $connection_id = (int) $request->get_param('id');
        $connection = SourceHub_Database::get_connection($connection_id);

        if (!$connection) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Connection not found', 'sourcehub')
            ), 404);
        }

        return new WP_REST_Response(array(
            'success' => true,
            'connection' => array(
                'id' => (int) $connection->id,
                'name' => $connection->name,
                'url' => $connection->url,
                'api_key' => $connection->api_key,
                'mode' => $connection->mode,
                'status' => $connection->status,
                'last_sync' => $connection->last_sync,
                'sync_settings' => $connection->sync_settings,
                'ai_settings' => $connection->ai_settings,
                'created_at' => $connection->created_at,
                'updated_at' => $connection->updated_at
            )
        ), 200);
    }

    /**
     * Create connection
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function create_connection($request) {
        $data = array(
            'name' => sanitize_text_field($request->get_param('name')),
            'url' => esc_url_raw($request->get_param('url')),
            'api_key' => sanitize_text_field($request->get_param('api_key')),
            'mode' => sanitize_text_field($request->get_param('mode') ?: 'spoke'),
            'status' => 'active',
            'sync_settings' => json_encode($request->get_param('sync_settings') ?: array()),
            'ai_settings' => json_encode($request->get_param('ai_settings') ?: array())
        );

        // Check if connection already exists
        $existing = SourceHub_Database::get_connection_by_url($data['url']);
        if ($existing) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Connection with this URL already exists', 'sourcehub')
            ), 409);
        }

        $connection_id = SourceHub_Database::add_connection($data);
        
        if (!$connection_id) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Failed to create connection', 'sourcehub')
            ), 500);
        }

        $connection = SourceHub_Database::get_connection($connection_id);

        SourceHub_Logger::success(
            sprintf('Created new connection: %s (%s)', $data['name'], $data['url']),
            $data,
            null,
            $connection_id,
            'create_connection'
        );

        return new WP_REST_Response(array(
            'success' => true,
            'message' => __('Connection created successfully', 'sourcehub'),
            'connection' => array(
                'id' => (int) $connection->id,
                'name' => $connection->name,
                'url' => $connection->url,
                'api_key' => $connection->api_key,
                'mode' => $connection->mode,
                'status' => $connection->status
            )
        ), 201);
    }

    /**
     * Update connection
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function update_connection($request) {
        $connection_id = (int) $request->get_param('id');
        $connection = SourceHub_Database::get_connection($connection_id);

        if (!$connection) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Connection not found', 'sourcehub')
            ), 404);
        }

        $data = array();
        
        if ($request->get_param('name')) {
            $data['name'] = sanitize_text_field($request->get_param('name'));
        }
        
        if ($request->get_param('url')) {
            $data['url'] = esc_url_raw($request->get_param('url'));
        }
        
        if ($request->get_param('api_key')) {
            $data['api_key'] = sanitize_text_field($request->get_param('api_key'));
        }
        
        if ($request->get_param('status')) {
            $data['status'] = sanitize_text_field($request->get_param('status'));
        }
        
        if ($request->get_param('sync_settings')) {
            $data['sync_settings'] = json_encode($request->get_param('sync_settings'));
        }
        
        if ($request->get_param('ai_settings')) {
            $data['ai_settings'] = json_encode($request->get_param('ai_settings'));
        }

        $result = SourceHub_Database::update_connection($connection_id, $data);
        
        if (!$result) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Failed to update connection', 'sourcehub')
            ), 500);
        }

        SourceHub_Logger::success(
            sprintf('Updated connection: %s', $connection->name),
            $data,
            null,
            $connection_id,
            'update_connection'
        );

        return new WP_REST_Response(array(
            'success' => true,
            'message' => __('Connection updated successfully', 'sourcehub')
        ), 200);
    }

    /**
     * Delete connection
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function delete_connection($request) {
        $connection_id = (int) $request->get_param('id');
        $connection = SourceHub_Database::get_connection($connection_id);

        if (!$connection) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Connection not found', 'sourcehub')
            ), 404);
        }

        $result = SourceHub_Database::delete_connection($connection_id);
        
        if (!$result) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Failed to delete connection', 'sourcehub')
            ), 500);
        }

        SourceHub_Logger::info(
            sprintf('Deleted connection: %s', $connection->name),
            array('connection_id' => $connection_id),
            null,
            $connection_id,
            'delete_connection'
        );

        return new WP_REST_Response(array(
            'success' => true,
            'message' => __('Connection deleted successfully', 'sourcehub')
        ), 200);
    }

    /**
     * Test connection
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function test_connection($request) {
        $connection_id = (int) $request->get_param('id');
        $connection = SourceHub_Database::get_connection($connection_id);

        // Get API key from request (for testing before save)
        $api_key = $request->get_param('api_key');
        
        // Also check JSON body if not in params
        if (empty($api_key)) {
            $json_params = $request->get_json_params();
            if (!empty($json_params['api_key'])) {
                $api_key = $json_params['api_key'];
            }
        }
        
        // Get URL from request (for testing before save) or from connection
        $test_url = null;
        $json_params = $request->get_json_params();
        if (!empty($json_params['url'])) {
            $test_url = $json_params['url'];
        }
        
        // If we have a valid connection, use its data as fallback
        if ($connection) {
            if (empty($api_key)) {
                $api_key = $connection->api_key;
            }
            if (empty($test_url)) {
                $test_url = $connection->url;
            }
        }
        
        // Validate we have required data
        if (empty($api_key) || empty($test_url)) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('API key and URL are required for testing', 'sourcehub')
            ), 400);
        }
        
        error_log('SourceHub: Testing connection to ' . $test_url . ' with API key: ' . substr($api_key, 0, 8) . '...');

        // Add cache-busting parameter to prevent cached responses
        $url = trailingslashit($test_url) . 'wp-json/sourcehub/v1/status?_=' . time();
        
        // Start timing
        $start_time = microtime(true);
        
        $response = wp_remote_get($url, array(
            'timeout' => 30, // Match syndication timeout
            'sslverify' => false, // Allow testing with self-signed certs
            'headers' => array(
                'X-SourceHub-API-Key' => $api_key,
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            )
        ));
        
        // Calculate response time
        $response_time = round((microtime(true) - $start_time) * 1000); // Convert to milliseconds

        if (is_wp_error($response)) {
            $message = sprintf(__('Connection failed: %s', 'sourcehub'), $response->get_error_message());
            
            SourceHub_Logger::error(
                $message,
                array('url' => $url, 'error' => $response->get_error_message(), 'response_time_ms' => $response_time),
                null,
                $connection_id,
                'test_connection'
            );

            return new WP_REST_Response(array(
                'success' => false,
                'message' => $message,
                'response_time' => $response_time
            ), 500);
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        
        if ($response_code === 200) {
            $data = json_decode($response_body, true);
            
            $connection_name = $connection ? $connection->name : $test_url;
            SourceHub_Logger::success(
                sprintf('Connection test successful: %s (%dms)', $connection_name, $response_time),
                array_merge($data, array('response_time_ms' => $response_time)),
                null,
                $connection_id,
                'test_connection'
            );

            return new WP_REST_Response(array(
                'success' => true,
                'message' => __('Connection test successful', 'sourcehub'),
                'spoke_info' => $data,
                'response_time' => $response_time
            ), 200);
        } else {
            $message = sprintf(__('Connection test failed with HTTP %d', 'sourcehub'), $response_code);
            
            SourceHub_Logger::error(
                $message,
                array('response_code' => $response_code, 'response_body' => $response_body),
                null,
                $connection_id,
                'test_connection'
            );

            return new WP_REST_Response(array(
                'success' => false,
                'message' => $message,
                'response_code' => $response_code
            ), 500);
        }
    }

    /**
     * Get plugin info
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_info($request) {
        $mode = sourcehub()->get_mode();
        $stats = SourceHub_Logger::get_stats(array('days' => 30));

        $info = array(
            'version' => SOURCEHUB_VERSION,
            'mode' => $mode,
            'site_url' => home_url(),
            'wordpress_version' => get_bloginfo('version'),
            'php_version' => PHP_VERSION,
            'yoast_active' => SourceHub_Yoast_Integration::is_yoast_active(),
            'yoast_version' => SourceHub_Yoast_Integration::get_yoast_version(),
            'stats' => $stats
        );

        if ($mode === 'hub') {
            $connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
            $info['spoke_count'] = count($connections);
            $info['active_spokes'] = count(array_filter($connections, function($conn) {
                return $conn->status === 'active';
            }));
        }

        if ($mode === 'spoke') {
            $hub_connections = SourceHub_Database::get_connections(array('mode' => 'hub'));
            $info['hub_count'] = count($hub_connections);
        }

        return new WP_REST_Response($info, 200);
    }

    /**
     * Get logs
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_logs($request) {
        try {
            $page = max(1, (int) $request->get_param('page'));
            $per_page = min(100, max(1, (int) $request->get_param('per_page')));
            $status = $request->get_param('level'); // JavaScript sends 'level'
            $action = $request->get_param('action');

            $args = array(
                'limit' => $per_page ?: 20,
                'offset' => ($page - 1) * ($per_page ?: 20),
                'order' => 'DESC'
            );

            if ($status) {
                $args['status'] = sanitize_text_field($status);
            }
            
            if ($action) {
                $args['action'] = sanitize_text_field($action);
            }

            $logs = SourceHub_Logger::get_formatted_logs($args);
            
            // Get total count for pagination (cached for 1 minute)
            $cache_key = 'sourcehub_logs_count_' . md5(serialize($args));
            $total = get_transient($cache_key);
            
            if ($total === false) {
                $total = SourceHub_Database::count_logs($args);
                set_transient($cache_key, $total, 60); // Cache for 1 minute
            }

            return new WP_REST_Response(array(
                'logs' => $logs,
                'pagination' => array(
                    'page' => $page,
                    'per_page' => $per_page,
                    'total' => $total,
                    'total_pages' => ceil($total / $per_page)
                )
            ), 200);
            
        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'error' => $e->getMessage(),
                'logs' => array(),
                'pagination' => array(
                    'page' => 1,
                    'per_page' => 20,
                    'total' => 0,
                    'total_pages' => 0
                )
            ), 500);
        }
    }
}
