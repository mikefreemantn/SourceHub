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
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-networking"></span>
                </div>
                <div class="stat-content">
                    <h3><?php echo count($connections); ?></h3>
                    <p><?php echo $mode === 'hub' ? __('Spoke Sites', 'sourcehub') : __('Hub Sites', 'sourcehub'); ?></p>
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
        </div>

        <div class="dashboard-content">
            <div class="dashboard-left">
                <!-- Quick Actions -->
                <div class="dashboard-widget">
                    <h2><?php echo __('Quick Actions', 'sourcehub'); ?></h2>
                    <div class="quick-actions">
                        <?php if ($mode === 'hub'): ?>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="button button-primary">
                                <span class="dashicons dashicons-plus-alt"></span>
                                <?php echo __('Add Spoke Site', 'sourcehub'); ?>
                            </a>
                            <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-secondary">
                                <span class="dashicons dashicons-edit"></span>
                                <?php echo __('Create New Post', 'sourcehub'); ?>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="button button-primary">
                                <span class="dashicons dashicons-plus-alt"></span>
                                <?php echo __('Connect to Hub', 'sourcehub'); ?>
                            </a>
                            <a href="<?php echo admin_url('edit.php'); ?>" class="button button-secondary">
                                <span class="dashicons dashicons-list-view"></span>
                                <?php echo __('View Received Posts', 'sourcehub'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-settings'); ?>" class="button button-secondary">
                            <span class="dashicons dashicons-admin-settings"></span>
                            <?php echo __('Settings', 'sourcehub'); ?>
                        </a>
                        
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-logs'); ?>" class="button button-secondary">
                            <span class="dashicons dashicons-list-view"></span>
                            <?php echo __('View Logs', 'sourcehub'); ?>
                        </a>
                    </div>
                </div>

                <!-- Connections Overview -->
                <div class="dashboard-widget">
                    <h2>
                        <?php echo $mode === 'hub' ? __('Spoke Connections', 'sourcehub') : __('Hub Connections', 'sourcehub'); ?>
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="button button-small">
                            <?php echo __('Manage All', 'sourcehub'); ?>
                        </a>
                    </h2>
                    
                    <?php if (empty($connections)): ?>
                        <div class="empty-state">
                            <span class="dashicons dashicons-networking"></span>
                            <h3><?php echo __('No connections yet', 'sourcehub'); ?></h3>
                            <p>
                                <?php if ($mode === 'hub'): ?>
                                    <?php echo __('Add spoke sites to start syndicating your content.', 'sourcehub'); ?>
                                <?php else: ?>
                                    <?php echo __('Connect to a hub site to receive syndicated content.', 'sourcehub'); ?>
                                <?php endif; ?>
                            </p>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="button button-primary">
                                <?php echo $mode === 'hub' ? __('Add Spoke Site', 'sourcehub') : __('Connect to Hub', 'sourcehub'); ?>
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

                <!-- Stats Chart -->
                <div class="dashboard-widget">
                    <h2><?php echo __('Activity Overview', 'sourcehub'); ?></h2>
                    <div class="stats-chart">
                        <div class="chart-item">
                            <div class="chart-bar">
                                <div class="chart-fill success" style="width: <?php echo isset($stats['success_percentage']) ? $stats['success_percentage'] : 0; ?>%"></div>
                            </div>
                            <div class="chart-label">
                                <span class="chart-color success"></span>
                                <?php echo sprintf(__('Success (%d)', 'sourcehub'), isset($stats['success_count']) ? $stats['success_count'] : 0); ?>
                            </div>
                        </div>
                        
                        <div class="chart-item">
                            <div class="chart-bar">
                                <div class="chart-fill error" style="width: <?php echo isset($stats['error_percentage']) ? $stats['error_percentage'] : 0; ?>%"></div>
                            </div>
                            <div class="chart-label">
                                <span class="chart-color error"></span>
                                <?php echo sprintf(__('Errors (%d)', 'sourcehub'), isset($stats['error_count']) ? $stats['error_count'] : 0); ?>
                            </div>
                        </div>
                        
                        <div class="chart-item">
                            <div class="chart-bar">
                                <div class="chart-fill warning" style="width: <?php echo isset($stats['warning_percentage']) ? $stats['warning_percentage'] : 0; ?>%"></div>
                            </div>
                            <div class="chart-label">
                                <span class="chart-color warning"></span>
                                <?php echo sprintf(__('Warnings (%d)', 'sourcehub'), isset($stats['warning_count']) ? $stats['warning_count'] : 0); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
