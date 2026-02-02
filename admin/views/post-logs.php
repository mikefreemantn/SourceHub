<?php
/**
 * Post Logs Admin Page
 * Shows posts with syndication errors or stuck in processing
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';
$spoke_filter = isset($_GET['spoke']) ? intval($_GET['spoke']) : 0;

// Query posts with syndication status
global $wpdb;
$posts_with_status = $wpdb->get_results(
    "SELECT p.ID, p.post_title, p.post_date, pm.meta_value as sync_status
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE pm.meta_key = '_sourcehub_sync_status'
    AND p.post_status IN ('publish', 'draft', 'pending')
    ORDER BY p.post_date DESC
    LIMIT 100"
);

// Get all connections for filter dropdown
$connections = SourceHub_Database::get_connections();

// Process and filter posts
$filtered_posts = array();
foreach ($posts_with_status as $post_data) {
    $post_id = $post_data->ID;
    $sync_status = maybe_unserialize($post_data->sync_status);
    
    if (!is_array($sync_status)) {
        continue;
    }
    
    $has_error = false;
    $has_processing = false;
    $error_spokes = array();
    $processing_spokes = array();
    
    foreach ($sync_status as $spoke_id => $status_data) {
        $status = $status_data['status'] ?? 'unknown';
        
        // Apply spoke filter
        if ($spoke_filter > 0 && $spoke_id != $spoke_filter) {
            continue;
        }
        
        if ($status === 'failed') {
            $has_error = true;
            $connection = SourceHub_Database::get_connection($spoke_id);
            $error_spokes[] = array(
                'id' => $spoke_id,
                'name' => $connection ? $connection->name : "Connection $spoke_id",
                'error' => $status_data['error'] ?? 'Unknown error',
                'last_sync' => $status_data['last_sync'] ?? '',
                'retry_count' => $status_data['retry_count'] ?? 0
            );
        } elseif ($status === 'processing') {
            // Check if stuck (older than 10 minutes)
            if (isset($status_data['started_at'])) {
                $started = strtotime($status_data['started_at']);
                $elapsed = time() - $started;
                if ($elapsed > 600) { // 10 minutes
                    $has_processing = true;
                    $connection = SourceHub_Database::get_connection($spoke_id);
                    $processing_spokes[] = array(
                        'id' => $spoke_id,
                        'name' => $connection ? $connection->name : "Connection $spoke_id",
                        'started_at' => $status_data['started_at'],
                        'elapsed_minutes' => round($elapsed / 60),
                        'job_id' => $status_data['job_id'] ?? null
                    );
                }
            }
        }
    }
    
    // Apply status filter
    $include = false;
    if ($status_filter === 'all' && ($has_error || $has_processing)) {
        $include = true;
    } elseif ($status_filter === 'error' && $has_error) {
        $include = true;
    } elseif ($status_filter === 'stuck' && $has_processing) {
        $include = true;
    }
    
    if ($include) {
        $filtered_posts[] = array(
            'id' => $post_id,
            'title' => $post_data->post_title,
            'date' => $post_data->post_date,
            'has_error' => $has_error,
            'has_processing' => $has_processing,
            'error_spokes' => $error_spokes,
            'processing_spokes' => $processing_spokes
        );
    }
}
?>

<div class="wrap">
    <h1><?php _e('Post Syndication Logs', 'sourcehub'); ?></h1>
    <p><?php _e('View posts with syndication errors or stuck in processing status.', 'sourcehub'); ?></p>

    <!-- Filters -->
    <div class="sourcehub-post-logs-filters">
        <form method="get" action="">
            <input type="hidden" name="page" value="sourcehub-post-logs">
            
            <select name="status" id="status-filter">
                <option value="all" <?php selected($status_filter, 'all'); ?>><?php _e('All Issues', 'sourcehub'); ?></option>
                <option value="error" <?php selected($status_filter, 'error'); ?>><?php _e('Errors Only', 'sourcehub'); ?></option>
                <option value="stuck" <?php selected($status_filter, 'stuck'); ?>><?php _e('Stuck/Processing', 'sourcehub'); ?></option>
            </select>
            
            <select name="spoke" id="spoke-filter">
                <option value="0"><?php _e('All Spokes', 'sourcehub'); ?></option>
                <?php foreach ($connections as $connection): ?>
                    <option value="<?php echo esc_attr($connection->id); ?>" <?php selected($spoke_filter, $connection->id); ?>>
                        <?php echo esc_html($connection->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="button"><?php _e('Filter', 'sourcehub'); ?></button>
            <a href="<?php echo admin_url('admin.php?page=sourcehub-post-logs'); ?>" class="button"><?php _e('Clear Filters', 'sourcehub'); ?></a>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="sourcehub-post-logs-summary">
        <p>
            <strong><?php echo count($filtered_posts); ?></strong> 
            <?php echo _n('post with issues found', 'posts with issues found', count($filtered_posts), 'sourcehub'); ?>
        </p>
    </div>

    <!-- Posts Table -->
    <?php if (empty($filtered_posts)): ?>
        <div class="notice notice-success">
            <p><?php _e('No posts with syndication issues found! 🎉', 'sourcehub'); ?></p>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped sourcehub-post-logs-table">
            <thead>
                <tr>
                    <th><?php _e('Post', 'sourcehub'); ?></th>
                    <th><?php _e('Published', 'sourcehub'); ?></th>
                    <th><?php _e('Issues', 'sourcehub'); ?></th>
                    <th><?php _e('Details', 'sourcehub'); ?></th>
                    <th><?php _e('Actions', 'sourcehub'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filtered_posts as $post): ?>
                    <tr>
                        <td>
                            <strong>
                                <a href="<?php echo get_edit_post_link($post['id']); ?>" target="_blank">
                                    <?php echo esc_html($post['title']); ?>
                                </a>
                            </strong>
                            <br>
                            <small>ID: <?php echo $post['id']; ?></small>
                        </td>
                        <td>
                            <?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($post['date'])); ?>
                        </td>
                        <td>
                            <?php if ($post['has_error']): ?>
                                <span class="sourcehub-status-badge error">
                                    <span class="dashicons dashicons-warning"></span>
                                    <?php echo count($post['error_spokes']); ?> <?php _e('Error(s)', 'sourcehub'); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($post['has_processing']): ?>
                                <span class="sourcehub-status-badge processing">
                                    <span class="dashicons dashicons-update"></span>
                                    <?php echo count($post['processing_spokes']); ?> <?php _e('Stuck', 'sourcehub'); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($post['error_spokes'])): ?>
                                <div class="sourcehub-spoke-details">
                                    <strong><?php _e('Failed Spokes:', 'sourcehub'); ?></strong>
                                    <ul>
                                        <?php foreach ($post['error_spokes'] as $spoke): ?>
                                            <li>
                                                <strong><?php echo esc_html($spoke['name']); ?></strong>
                                                <br>
                                                <span class="error-message"><?php echo esc_html($spoke['error']); ?></span>
                                                <?php if ($spoke['retry_count'] > 0): ?>
                                                    <br><small><?php printf(__('Retries: %d', 'sourcehub'), $spoke['retry_count']); ?></small>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($post['processing_spokes'])): ?>
                                <div class="sourcehub-spoke-details">
                                    <strong><?php _e('Stuck Processing:', 'sourcehub'); ?></strong>
                                    <ul>
                                        <?php foreach ($post['processing_spokes'] as $spoke): ?>
                                            <li>
                                                <strong><?php echo esc_html($spoke['name']); ?></strong>
                                                <br>
                                                <small>
                                                    <?php printf(__('Stuck for %d minutes', 'sourcehub'), $spoke['elapsed_minutes']); ?>
                                                    <?php if ($spoke['job_id']): ?>
                                                        <br>Job ID: <code><?php echo esc_html($spoke['job_id']); ?></code>
                                                    <?php endif; ?>
                                                </small>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo get_edit_post_link($post['id']); ?>" class="button button-small" target="_blank">
                                <?php _e('Edit Post', 'sourcehub'); ?>
                            </a>
                            <br><br>
                            <button type="button" 
                                    class="button button-small retry-sync-btn" 
                                    data-post-id="<?php echo esc_attr($post['id']); ?>">
                                <?php _e('Retry Sync', 'sourcehub'); ?>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
.sourcehub-post-logs-filters {
    background: #fff;
    padding: 15px;
    margin: 20px 0;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.sourcehub-post-logs-filters form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.sourcehub-post-logs-filters select {
    min-width: 200px;
}

.sourcehub-post-logs-summary {
    margin: 15px 0;
}

.sourcehub-post-logs-table {
    margin-top: 20px;
}

.sourcehub-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-right: 5px;
}

.sourcehub-status-badge.error {
    background: #f8d7da;
    color: #721c24;
}

.sourcehub-status-badge.processing {
    background: #fff3cd;
    color: #856404;
}

.sourcehub-status-badge .dashicons {
    font-size: 14px;
    width: 14px;
    height: 14px;
}

.sourcehub-spoke-details {
    margin: 5px 0;
}

.sourcehub-spoke-details ul {
    margin: 5px 0 0 0;
    padding-left: 20px;
}

.sourcehub-spoke-details li {
    margin: 8px 0;
    line-height: 1.6;
}

.sourcehub-spoke-details .error-message {
    color: #d63638;
    font-size: 13px;
}

.sourcehub-spoke-details code {
    background: #f0f0f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 11px;
}

.retry-sync-btn {
    margin-top: 5px;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Handle retry sync button
    $('.retry-sync-btn').on('click', function() {
        var $btn = $(this);
        var postId = $btn.data('post-id');
        
        if (!confirm('<?php _e('Retry syndication for this post? This will attempt to sync to all failed/stuck spokes.', 'sourcehub'); ?>')) {
            return;
        }
        
        $btn.prop('disabled', true).text('<?php _e('Retrying...', 'sourcehub'); ?>');
        
        // Trigger re-sync by updating the post
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sourcehub_manual_retry',
                post_id: postId,
                nonce: '<?php echo wp_create_nonce('sourcehub_manual_retry'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('<?php _e('Retry initiated. Check the post status in a few moments.', 'sourcehub'); ?>');
                    location.reload();
                } else {
                    alert('<?php _e('Retry failed:', 'sourcehub'); ?> ' + (response.data.message || 'Unknown error'));
                    $btn.prop('disabled', false).text('<?php _e('Retry Sync', 'sourcehub'); ?>');
                }
            },
            error: function() {
                alert('<?php _e('Connection error. Please try again.', 'sourcehub'); ?>');
                $btn.prop('disabled', false).text('<?php _e('Retry Sync', 'sourcehub'); ?>');
            }
        });
    });
});
</script>
