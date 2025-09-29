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
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-SourceHub-API-Key');
            
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

        if (!$connection) {
            return new WP_REST_Response(array(
                'success' => false,
                'message' => __('Connection not found', 'sourcehub')
            ), 404);
        }

        $url = trailingslashit($connection->url) . 'wp-json/sourcehub/v1/status';
        
        $response = wp_remote_get($url, array(
            'timeout' => 15,
            'headers' => array(
                'X-SourceHub-API-Key' => $connection->api_key
            )
        ));

        if (is_wp_error($response)) {
            $message = sprintf(__('Connection failed: %s', 'sourcehub'), $response->get_error_message());
            
            SourceHub_Logger::error(
                $message,
                array('url' => $url, 'error' => $response->get_error_message()),
                null,
                $connection_id,
                'test_connection'
            );

            return new WP_REST_Response(array(
                'success' => false,
                'message' => $message
            ), 500);
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        
        if ($response_code === 200) {
            $data = json_decode($response_body, true);
            
            SourceHub_Logger::success(
                sprintf('Connection test successful: %s', $connection->name),
                $data,
                null,
                $connection_id,
                'test_connection'
            );

            return new WP_REST_Response(array(
                'success' => true,
                'message' => __('Connection test successful', 'sourcehub'),
                'spoke_info' => $data
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
        $page = (int) $request->get_param('page');
        $per_page = (int) $request->get_param('per_page');
        $status = $request->get_param('status');

        $args = array(
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page,
            'order' => 'DESC'
        );

        if ($status) {
            $args['status'] = $status;
        }

        $logs = SourceHub_Logger::get_formatted_logs($args);

        return new WP_REST_Response(array(
            'logs' => $logs,
            'page' => $page,
            'per_page' => $per_page,
            'total' => count($logs)
        ), 200);
    }
}
