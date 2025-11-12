<?php
/**
 * Dashboard view template
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Ensure variables are set with defaults
$mode = isset($mode) ? $mode : 'hub';
$connections = isset($connections) ? $connections : array();
$active_connections = isset($active_connections) ? $active_connections : array();
$stats = isset($stats) ? $stats : array('total_logs' => 0, 'success_rate' => 0);
$recent_logs = isset($recent_logs) ? $recent_logs : array();
?>

<div class="wrap sourcehub-admin">
    <div class="sourcehub-dashboard-header">
        <img src="<?php echo SOURCEHUB_PLUGIN_URL . 'assets/source_hub_logo.png'; ?>" alt="SourceHub" class="sourcehub-logo" />
        <span class="mode-badge mode-<?php echo esc_attr($mode); ?>">
            <?php echo esc_html(ucfirst($mode)); ?> Mode
        </span>
    </div>

    <div class="sourcehub-dashboard">
        <!-- Stats Overview -->
        <div class="stats-grid">
            <?php if ($mode === 'hub'): ?>
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="dashicons dashicons-networking"></span>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo count($connections); ?></h3>
                        <p><?php echo __('Spoke Sites', 'sourcehub'); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="dashicons dashicons-yes-alt"></span>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo count($active_connections); ?></h3>
                        <p><?php echo __('Active Connections', 'sourcehub'); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="dashicons dashicons-chart-line"></span>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo isset($stats['total_logs']) ? $stats['total_logs'] : 0; ?></h3>
                        <p><?php echo __('Activities (30 days)', 'sourcehub'); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="dashicons dashicons-admin-post"></span>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo isset($spoke_stats['total_syndicated']) ? $spoke_stats['total_syndicated'] : 0; ?></h3>
                        <p><?php echo __('Syndicated Posts', 'sourcehub'); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="dashicons dashicons-calendar-alt"></span>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo isset($spoke_stats['syndicated_this_month']) ? $spoke_stats['syndicated_this_month'] : 0; ?></h3>
                        <p><?php echo __('Posts This Month', 'sourcehub'); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <span class="dashicons dashicons-update"></span>
                    </div>
                    <div class="stat-content">
                        <h3>
                            <?php 
                            if (isset($spoke_stats['last_sync']) && $spoke_stats['last_sync']) {
                                echo SourceHub_Admin::time_ago($spoke_stats['last_sync']);
                            } else {
                                echo __('Never', 'sourcehub');
                            }
                            ?>
                        </h3>
                        <p><?php echo __('Last Sync', 'sourcehub'); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-content">
            <div class="dashboard-left">
                <!-- Quick Actions -->
                <div class="dashboard-widget quick-actions-widget">
                    <h2><?php echo __('Quick Actions', 'sourcehub'); ?></h2>
                    <div class="quick-actions-grid">
                        <?php if ($mode === 'hub'): ?>
                            <a href="<?php echo admin_url('post-new.php'); ?>" class="quick-action-card primary">
                                <span class="dashicons dashicons-edit"></span>
                                <span class="action-label"><?php echo __('Create New Post', 'sourcehub'); ?></span>
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-add-spoke'); ?>" class="quick-action-card">
                                <span class="dashicons dashicons-plus-alt"></span>
                                <span class="action-label"><?php echo __('Add Spoke Site', 'sourcehub'); ?></span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo admin_url('edit.php'); ?>" class="quick-action-card primary">
                                <span class="dashicons dashicons-admin-post"></span>
                                <span class="action-label"><?php echo __('View Posts', 'sourcehub'); ?></span>
                            </a>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="quick-action-card">
                                <span class="dashicons dashicons-networking"></span>
                                <span class="action-label"><?php echo __('Hub Connection', 'sourcehub'); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-settings'); ?>" class="quick-action-card">
                            <span class="dashicons dashicons-admin-settings"></span>
                            <span class="action-label"><?php echo __('Settings', 'sourcehub'); ?></span>
                        </a>
                        
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-logs'); ?>" class="quick-action-card">
                            <span class="dashicons dashicons-list-view"></span>
                            <span class="action-label"><?php echo __('Activity Logs', 'sourcehub'); ?></span>
                        </a>
                    </div>
                </div>

                <!-- Connections Overview (Hub Mode Only) -->
                <?php if ($mode === 'hub'): ?>
                <div class="dashboard-widget">
                    <h2>
                        <?php echo __('Spoke Connections', 'sourcehub'); ?>
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="button button-small">
                            <?php echo __('Manage All', 'sourcehub'); ?>
                        </a>
                    </h2>
                    
                    
                    <?php if (empty($connections)): ?>
                        <div class="empty-state">
                            <span class="dashicons dashicons-networking"></span>
                            <h3><?php echo __('No connections yet', 'sourcehub'); ?></h3>
                            <p><?php echo __('Add spoke sites to start syndicating your content.', 'sourcehub'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-add-spoke'); ?>" class="button button-primary">
                                <?php echo __('Add Spoke Site', 'sourcehub'); ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="connections-list">
                            <?php foreach (array_slice($connections, 0, 5) as $connection): ?>
                                <div class="connection-item">
                                    <div class="connection-info">
                                        <h4><?php echo esc_html($connection->name); ?></h4>
                                        <p><?php echo esc_html($connection->url); ?></p>
                                    </div>
                                    <div class="connection-status">
                                        <?php echo SourceHub_Admin::get_status_badge($connection); ?>
                                        <small><?php echo SourceHub_Admin::time_ago($connection->last_sync); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if (count($connections) > 5): ?>
                                <div class="connection-item more-connections">
                                    <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>">
                                        <?php echo sprintf(__('View all %d connections', 'sourcehub'), count($connections)); ?>
                                    </a>
                                </div>
                                
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Content Schedule (Hub Mode Only) -->
                <div class="dashboard-widget">
                    <h2>
                        <?php echo __('Content Schedule', 'sourcehub'); ?>
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-calendar'); ?>" class="button button-small">
                            <?php echo __('View Calendar', 'sourcehub'); ?>
                        </a>
                    </h2>
                    <?php
                    $scheduled_posts = get_posts(array(
                        'post_type' => 'post',
                        'posts_per_page' => 10,
                        'post_status' => array('publish', 'future'),
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if (!empty($scheduled_posts)):
                    ?>
                    <div class="content-schedule-list">
                        <?php foreach ($scheduled_posts as $post): 
                            setup_postdata($post);
                            $thumbnail = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                            $categories = get_the_category($post->ID);
                            $category_names = !empty($categories) ? implode(', ', wp_list_pluck($categories, 'name')) : __('Uncategorized', 'sourcehub');
                            $selected_spokes = get_post_meta($post->ID, '_sourcehub_selected_spokes', true);
                            $spoke_count = is_array($selected_spokes) ? count($selected_spokes) : 0;
                        ?>
                        <div class="schedule-item">
                            <?php if ($thumbnail): ?>
                            <div class="schedule-thumbnail">
                                <a href="<?php echo get_edit_post_link($post->ID); ?>">
                                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="schedule-content">
                                <div class="schedule-header">
                                    <h4><a href="<?php echo get_edit_post_link($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h4>
                                    <span class="schedule-status <?php echo $post->post_status; ?>">
                                        <?php echo $post->post_status === 'future' ? __('Scheduled', 'sourcehub') : __('Published', 'sourcehub'); ?>
                                    </span>
                                </div>
                                <p class="schedule-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 15); ?></p>
                                <div class="schedule-meta">
                                    <span class="schedule-category"><?php echo esc_html($category_names); ?></span>
                                    <span class="schedule-time"><?php echo get_the_date('n/j/y g:i A', $post->ID); ?></span>
                                    <?php if ($spoke_count > 0): ?>
                                    <span class="schedule-spokes"><?php echo sprintf(_n('%d spoke', '%d spokes', $spoke_count, 'sourcehub'), $spoke_count); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state small">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <p><?php echo __('No scheduled content', 'sourcehub'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Syndicated Content (Spoke Mode Only) -->
                <?php if ($mode === 'spoke'): 
                    $syndicated_posts = get_posts(array(
                        'post_type' => 'post',
                        'posts_per_page' => 10,
                        'post_status' => 'publish',
                        'meta_key' => '_sourcehub_hub_id',
                        'meta_compare' => 'EXISTS',
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if (!empty($syndicated_posts)):
                ?>
                <div class="dashboard-widget">
                    <h2><?php echo __('Syndicated Content', 'sourcehub'); ?></h2>
                    <div class="syndicated-posts-list">
                        <?php foreach ($syndicated_posts as $post): 
                            setup_postdata($post);
                            $thumbnail = get_the_post_thumbnail($post->ID, 'thumbnail');
                            $categories = get_the_category($post->ID);
                            $category_names = !empty($categories) ? implode(', ', wp_list_pluck($categories, 'name')) : __('Uncategorized', 'sourcehub');
                        ?>
                        <div class="syndicated-post-item">
                            <?php if ($thumbnail): ?>
                            <div class="syndicated-post-thumbnail">
                                <a href="<?php echo get_edit_post_link($post->ID); ?>">
                                    <?php echo $thumbnail; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="syndicated-post-content">
                                <h4><a href="<?php echo get_edit_post_link($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h4>
                                <p class="syndicated-post-excerpt"><?php echo wp_trim_words(get_the_excerpt($post->ID), 20); ?></p>
                                <div class="syndicated-post-meta">
                                    <span class="syndicated-post-category"><?php echo esc_html($category_names); ?></span>
                                    <span class="syndicated-post-time"><?php echo SourceHub_Admin::time_ago(get_the_date('Y-m-d H:i:s', $post->ID)); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </div>
                <?php endif; endif; ?>
            </div>

            <div class="dashboard-right">
                <!-- System Status -->
                <div class="dashboard-widget">
                    <h2><?php echo __('System Status', 'sourcehub'); ?></h2>
                    <div class="system-status">
                        <div class="status-item">
                            <span class="status-label"><?php echo __('Plugin Version', 'sourcehub'); ?></span>
                            <span class="status-value"><?php echo SOURCEHUB_VERSION; ?></span>
                        </div>
                        
                        <div class="status-item">
                            <span class="status-label"><?php echo __('WordPress Version', 'sourcehub'); ?></span>
                            <span class="status-value"><?php echo get_bloginfo('version'); ?></span>
                        </div>
                        
                        <div class="status-item">
                            <span class="status-label"><?php echo __('Yoast SEO', 'sourcehub'); ?></span>
                            <span class="status-value">
                                <?php if (SourceHub_Yoast_Integration::is_yoast_active()): ?>
                                    <span class="status-good">
                                        <span class="dashicons dashicons-yes"></span>
                                        <?php echo SourceHub_Yoast_Integration::get_yoast_version(); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-warning">
                                        <span class="dashicons dashicons-warning"></span>
                                        <?php echo __('Not installed', 'sourcehub'); ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="status-item">
                            <span class="status-label"><?php echo __('AI Integration', 'sourcehub'); ?></span>
                            <span class="status-value">
                                <?php if (get_option('sourcehub_openai_api_key')): ?>
                                    <span class="status-good">
                                        <span class="dashicons dashicons-yes"></span>
                                        <?php echo __('Configured', 'sourcehub'); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-neutral">
                                        <span class="dashicons dashicons-minus"></span>
                                        <?php echo __('Not configured', 'sourcehub'); ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-widget">
                    <h2>
                        <?php echo __('Recent Activity', 'sourcehub'); ?>
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-logs'); ?>" class="button button-small">
                            <?php echo __('View All', 'sourcehub'); ?>
                        </a>
                    </h2>
                    
                    <?php if (empty($recent_logs)): ?>
                        <div class="empty-state small">
                            <span class="dashicons dashicons-clock"></span>
                            <p><?php echo __('No recent activity', 'sourcehub'); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="activity-list">
                            <?php foreach ($recent_logs as $log): ?>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <?php echo SourceHub_Admin::get_log_level_badge($log->status); ?>
                                    </div>
                                    <div class="activity-content">
                                        <p><?php echo esc_html($log->message); ?></p>
                                        <small>
                                            <?php echo SourceHub_Admin::time_ago($log->created_at); ?>
                                            <?php if (!empty($log->action)): ?>
                                                • <?php echo esc_html($log->action); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Activity Overview -->
                <div class="dashboard-widget">
                    <h2><?php echo __('Activity Overview', 'sourcehub'); ?></h2>
                    <div class="stats-chart">
                        <div class="chart-item">
                            <div class="chart-bar">
                                <?php 
                                $success_width = isset($stats['success_percentage']) ? $stats['success_percentage'] : 0;
                                $success_count = isset($stats['success_count']) ? $stats['success_count'] : 0;
                                if ($success_count > 0 && $success_width < 2) $success_width = 2;
                                ?>
                                <div class="chart-fill success" style="width: <?php echo $success_width; ?>%"></div>
                            </div>
                            <div class="chart-label">
                                <span class="chart-color success"></span>
                                <?php echo sprintf(__('Success (%d)', 'sourcehub'), $success_count); ?>
                            </div>
                        </div>
                        
                        <div class="chart-item">
                            <div class="chart-bar">
                                <?php 
                                $error_width = isset($stats['error_percentage']) ? $stats['error_percentage'] : 0;
                                $error_count = isset($stats['error_count']) ? $stats['error_count'] : 0;
                                if ($error_count > 0 && $error_width < 2) $error_width = 2;
                                ?>
                                <div class="chart-fill error" style="width: <?php echo $error_width; ?>%"></div>
                            </div>
                            <div class="chart-label">
                                <span class="chart-color error"></span>
                                <?php echo sprintf(__('Errors (%d)', 'sourcehub'), $error_count); ?>
                            </div>
                        </div>
                        
                        <div class="chart-item">
                            <div class="chart-bar">
                                <?php 
                                $warning_width = isset($stats['warning_percentage']) ? $stats['warning_percentage'] : 0;
                                $warning_count = isset($stats['warning_count']) ? $stats['warning_count'] : 0;
                                if ($warning_count > 0 && $warning_width < 2) $warning_width = 2;
                                ?>
                                <div class="chart-fill warning" style="width: <?php echo $warning_width; ?>%"></div>
                            </div>
                            <div class="chart-label">
                                <span class="chart-color warning"></span>
                                <?php echo sprintf(__('Warnings (%d)', 'sourcehub'), $warning_count); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
