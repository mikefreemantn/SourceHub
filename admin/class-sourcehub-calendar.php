<?php
/**
 * Content Calendar for SourceHub plugin
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SourceHub Calendar Class
 */
class SourceHub_Calendar {

    /**
     * Initialize calendar functionality
     */
    public function init() {
        // Only available in hub mode
        if (!sourcehub()->is_hub()) {
            return;
        }

        add_action('admin_menu', array($this, 'add_calendar_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_calendar_scripts'));
        add_action('wp_ajax_sourcehub_get_calendar_events', array($this, 'get_calendar_events'));
    }

    /**
     * Add calendar menu page
     */
    public function add_calendar_menu() {
        add_menu_page(
            __('Content Calendar', 'sourcehub'),
            __('Content Calendar', 'sourcehub'),
            'edit_posts',
            'sourcehub-calendar',
            array($this, 'render_calendar'),
            'dashicons-calendar-alt',
            25
        );
    }

    /**
     * Enqueue calendar scripts and styles
     *
     * @param string $hook Current admin page hook
     */
    public function enqueue_calendar_scripts($hook) {
        if ($hook !== 'toplevel_page_sourcehub-calendar') {
            return;
        }

        // Enqueue FullCalendar library - try multiple CDNs for reliability
        wp_enqueue_script(
            'fullcalendar',
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js',
            array(),
            '6.1.8',
            true
        );
        
        // Add fallback script
        wp_add_inline_script('fullcalendar', '
            if (typeof FullCalendar === "undefined") {
                console.error("FullCalendar failed to load from CDN");
                document.addEventListener("DOMContentLoaded", function() {
                    jQuery(".calendar-loading").hide();
                    jQuery(".calendar-fallback").show();
                });
            } else {
                console.log("FullCalendar loaded successfully");
            }
        ');

        // Enqueue calendar styles
        wp_enqueue_style(
            'sourcehub-calendar',
            SOURCEHUB_PLUGIN_URL . 'admin/css/calendar.css',
            array(),
            SOURCEHUB_VERSION
        );

        // Enqueue calendar scripts
        wp_enqueue_script(
            'sourcehub-calendar',
            SOURCEHUB_PLUGIN_URL . 'admin/js/calendar.js',
            array('jquery', 'fullcalendar'),
            SOURCEHUB_VERSION,
            true
        );

        // Localize script with data
        wp_localize_script('sourcehub-calendar', 'sourcehub_calendar', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sourcehub_calendar_nonce'),
            'edit_post_url' => admin_url('post.php?action=edit&post='),
            'strings' => array(
                'loading' => __('Loading calendar...', 'sourcehub'),
                'no_events' => __('No scheduled content found', 'sourcehub'),
                'error' => __('Error loading calendar data', 'sourcehub'),
                'post_status' => array(
                    'publish' => __('Published', 'sourcehub'),
                    'future' => __('Scheduled', 'sourcehub'),
                    'draft' => __('Draft', 'sourcehub'),
                    'pending' => __('Pending Review', 'sourcehub'),
                    'private' => __('Private', 'sourcehub')
                )
            )
        ));
    }

    /**
     * Render calendar page
     */
    public function render_calendar() {
        // Prevent double rendering
        static $rendered = false;
        if ($rendered) {
            return;
        }
        $rendered = true;
        
        // Get spoke connections for filtering
        $spoke_connections = SourceHub_Database::get_connections(array('mode' => 'spoke'));
        
        // Get categories for filtering
        $categories = get_categories(array(
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC'
        ));

        // Get post types
        $post_types = get_post_types(array('public' => true), 'objects');
        unset($post_types['attachment']); // Remove attachments

        include SOURCEHUB_PLUGIN_DIR . 'admin/views/calendar.php';
    }

    /**
     * Get calendar events via AJAX
     */
    public function get_calendar_events() {
        check_ajax_referer('sourcehub_calendar_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(__('Insufficient permissions', 'sourcehub'));
        }

        try {
            $start_date = sanitize_text_field($_POST['start'] ?? '');
            $end_date = sanitize_text_field($_POST['end'] ?? '');
            $filters = isset($_POST['filters']) ? $_POST['filters'] : array();

            // Sanitize filters
            $spoke_filter = isset($filters['spoke']) ? array_map('intval', (array)$filters['spoke']) : array();
            $status_filter = isset($filters['status']) ? array_map('sanitize_text_field', (array)$filters['status']) : array();
            $category_filter = isset($filters['category']) ? array_map('intval', (array)$filters['category']) : array();
            $post_type_filter = isset($filters['post_type']) ? array_map('sanitize_text_field', (array)$filters['post_type']) : array('post');

            // Remove empty values
            $spoke_filter = array_filter($spoke_filter);
            $status_filter = array_filter($status_filter);
            $category_filter = array_filter($category_filter);
            $post_type_filter = array_filter($post_type_filter);

            error_log('SourceHub Calendar: Loading events with filters - ' . json_encode(array(
                'start' => $start_date,
                'end' => $end_date,
                'spoke' => $spoke_filter,
                'status' => $status_filter,
                'category' => $category_filter,
                'post_type' => $post_type_filter
            )));

            // Debug: Log raw filter data
            error_log('SourceHub Calendar: Raw spoke filter data - ' . json_encode($_POST['filters']['spoke'] ?? 'not set'));

            $events = $this->get_calendar_data($start_date, $end_date, array(
                'spoke' => $spoke_filter,
                'status' => $status_filter,
                'category' => $category_filter,
                'post_type' => $post_type_filter
            ));

            error_log('SourceHub Calendar: Found ' . count($events) . ' events');
            
            // Debug: Log first event for inspection
            if (!empty($events)) {
                error_log('SourceHub Calendar: First event data - ' . json_encode($events[0]));
            }

            wp_send_json_success($events);

        } catch (Exception $e) {
            error_log('SourceHub Calendar Error: ' . $e->getMessage());
            wp_send_json_error('Error loading calendar data: ' . $e->getMessage());
        }
    }

    /**
     * Get calendar data
     *
     * @param string $start_date Start date
     * @param string $end_date End date
     * @param array $filters Filters to apply
     * @return array Calendar events
     */
    private function get_calendar_data($start_date, $end_date, $filters = array()) {
        global $wpdb;

        // Build query args
        $args = array(
            'post_type' => !empty($filters['post_type']) ? $filters['post_type'] : array('post'),
            'post_status' => array('publish', 'future', 'draft', 'pending', 'private'),
            'posts_per_page' => -1,
            'meta_query' => array(),
            'date_query' => array(),
            'orderby' => 'date',
            'order' => 'ASC'
        );
        
        // Debug the query
        error_log('SourceHub Calendar: Query args - ' . json_encode($args));

        // Add date range filter
        if ($start_date && $end_date) {
            $args['date_query'] = array(
                array(
                    'after' => $start_date,
                    'before' => $end_date,
                    'inclusive' => true,
                    'column' => 'post_date'
                )
            );
        }

        // Add status filter
        if (!empty($filters['status'])) {
            $args['post_status'] = $filters['status'];
        }

        // Add category filter
        if (!empty($filters['category'])) {
            $args['category__in'] = $filters['category'];
        }

        // Note: Spoke filtering is done at PHP level after getting posts for more reliable results

        $posts = get_posts($args);
        $events = array();
        
        error_log('SourceHub Calendar: Found ' . count($posts) . ' posts total');

        foreach ($posts as $post) {
            // Debug each post's date info
            error_log('SourceHub Calendar: Processing post ID=' . $post->ID . ', title="' . $post->post_title . '", post_date="' . $post->post_date . '", post_status="' . $post->post_status . '"');
            $selected_spokes = get_post_meta($post->ID, '_sourcehub_selected_spokes', true);
            $syndicated_spokes = get_post_meta($post->ID, '_sourcehub_syndicated_spokes', true);
            
            if (!is_array($selected_spokes)) {
                $selected_spokes = array();
            }
            if (!is_array($syndicated_spokes)) {
                $syndicated_spokes = array();
            }

            // Apply spoke filter at the PHP level for more reliable filtering
            if (!empty($filters['spoke'])) {
                $has_matching_spoke = false;
                foreach ($filters['spoke'] as $filter_spoke_id) {
                    // Convert both to integers for comparison
                    $filter_spoke_id = intval($filter_spoke_id);
                    $selected_spokes_int = array_map('intval', $selected_spokes);
                    
                    if (in_array($filter_spoke_id, $selected_spokes_int)) {
                        $has_matching_spoke = true;
                        break;
                    }
                }
                
                // Debug logging
                error_log('SourceHub Calendar: Post "' . $post->post_title . '" - Selected spokes: ' . json_encode($selected_spokes) . ', Filter spokes: ' . json_encode($filters['spoke']) . ', Match: ' . ($has_matching_spoke ? 'YES' : 'NO'));
                
                if (!$has_matching_spoke) {
                    continue; // Skip this post if it doesn't match the spoke filter
                }
            }

            // Get spoke connections for this post
            $post_spokes = array();
            if (!empty($selected_spokes)) {
                foreach ($selected_spokes as $spoke_id) {
                    $connection = SourceHub_Database::get_connection($spoke_id);
                    if ($connection) {
                        $post_spokes[] = array(
                            'id' => $connection->id,
                            'name' => $connection->name,
                            'url' => $connection->url,
                            'status' => $connection->status,
                            'syndicated' => in_array($spoke_id, $syndicated_spokes)
                        );
                    }
                }
            }

            // Get categories
            $categories = get_the_category($post->ID);
            $category_names = array();
            foreach ($categories as $category) {
                $category_names[] = $category->name;
            }

            // Determine event color based on post status
            $color = $this->get_status_color($post->post_status);

            // Get the post date (date only, no time) to prevent multi-day spanning
            $formatted_date = get_the_date('Y-m-d', $post->ID);
            
            // Debug logging
            error_log('SourceHub Calendar: Post "' . $post->post_title . '" (ID: ' . $post->ID . ', Status: ' . $post->post_status . ') - Original: ' . $post->post_date . ', Formatted: ' . $formatted_date);

            // Get timestamp for ordering (events on same day will be ordered by time)
            $timestamp = strtotime($post->post_date);
            
            // Create event
            $event = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'start' => $formatted_date,
                'url' => admin_url('post.php?action=edit&post=' . $post->ID),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'allDay' => true, // Display as all-day event to prevent multi-day spanning
                'order' => $timestamp, // Order by timestamp so earlier times appear first
                'extendedProps' => array(
                    'post_id' => $post->ID,
                    'post_status' => $post->post_status,
                    'post_type' => $post->post_type,
                    'categories' => $category_names,
                    'spokes' => $post_spokes,
                    'excerpt' => wp_trim_words($post->post_content, 20),
                    'author' => get_the_author_meta('display_name', $post->post_author),
                    'edit_url' => admin_url('post.php?action=edit&post=' . $post->ID),
                    'original_date' => $post->post_date
                )
            );

            $events[] = $event;
        }

        return $events;
    }

    /**
     * Get color for post status
     *
     * @param string $status Post status
     * @return string Color hex code
     */
    private function get_status_color($status) {
        $colors = array(
            'publish' => '#28a745',    // Green
            'future' => '#007cba',     // Blue
            'draft' => '#6c757d',      // Gray
            'pending' => '#ffc107',    // Yellow
            'private' => '#dc3545',    // Red
            'trash' => '#343a40'       // Dark gray
        );

        return isset($colors[$status]) ? $colors[$status] : '#6c757d';
    }

    /**
     * Get status label
     *
     * @param string $status Post status
     * @return string Status label
     */
    public static function get_status_label($status) {
        $labels = array(
            'publish' => __('Published', 'sourcehub'),
            'future' => __('Scheduled', 'sourcehub'),
            'draft' => __('Draft', 'sourcehub'),
            'pending' => __('Pending Review', 'sourcehub'),
            'private' => __('Private', 'sourcehub'),
            'trash' => __('Trash', 'sourcehub')
        );

        return isset($labels[$status]) ? $labels[$status] : ucfirst($status);
    }
}
