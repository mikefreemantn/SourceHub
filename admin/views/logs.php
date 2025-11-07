<?php
/**
 * Logs view template
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap sourcehub-admin">
    <h1 class="wp-heading-inline">
        <?php echo esc_html__('Activity Logs', 'sourcehub'); ?>
        <span class="mode-badge mode-<?php echo esc_attr($mode); ?>">
            <?php echo esc_html(ucfirst($mode)); ?> Mode
        </span>
    </h1>

    <div class="logs-header">
        <form method="get" action="" class="logs-filters">
            <input type="hidden" name="page" value="sourcehub-logs">
            
            <select name="log_level" class="form-select">
                <option value=""><?php echo __('All Levels', 'sourcehub'); ?></option>
                <option value="SUCCESS" <?php selected(isset($_GET['log_level']) ? $_GET['log_level'] : '', 'SUCCESS'); ?>><?php echo __('Success', 'sourcehub'); ?></option>
                <option value="ERROR" <?php selected(isset($_GET['log_level']) ? $_GET['log_level'] : '', 'ERROR'); ?>><?php echo __('Error', 'sourcehub'); ?></option>
                <option value="WARNING" <?php selected(isset($_GET['log_level']) ? $_GET['log_level'] : '', 'WARNING'); ?>><?php echo __('Warning', 'sourcehub'); ?></option>
                <option value="INFO" <?php selected(isset($_GET['log_level']) ? $_GET['log_level'] : '', 'INFO'); ?>><?php echo __('Info', 'sourcehub'); ?></option>
            </select>

            <select name="log_action" class="form-select">
                <option value=""><?php echo __('All Actions', 'sourcehub'); ?></option>
                <option value="sync" <?php selected(isset($_GET['log_action']) ? $_GET['log_action'] : '', 'sync'); ?>><?php echo __('Sync', 'sourcehub'); ?></option>
                <option value="connection" <?php selected(isset($_GET['log_action']) ? $_GET['log_action'] : '', 'connection'); ?>><?php echo __('Connection', 'sourcehub'); ?></option>
                <option value="ai_rewrite" <?php selected(isset($_GET['log_action']) ? $_GET['log_action'] : '', 'ai_rewrite'); ?>><?php echo __('AI Rewrite', 'sourcehub'); ?></option>
                <option value="api" <?php selected(isset($_GET['log_action']) ? $_GET['log_action'] : '', 'api'); ?>><?php echo __('API', 'sourcehub'); ?></option>
            </select>

            <input type="date" name="log_date" class="form-input" value="<?php echo isset($_GET['log_date']) ? esc_attr($_GET['log_date']) : ''; ?>">
            
            <input type="text" name="log_search" class="form-input" placeholder="<?php echo __('Search logs...', 'sourcehub'); ?>" value="<?php echo isset($_GET['log_search']) ? esc_attr($_GET['log_search']) : ''; ?>">

            <button type="submit" class="button button-secondary">
                <?php echo __('Apply Filters', 'sourcehub'); ?>
            </button>

            <a href="<?php echo admin_url('admin.php?page=sourcehub-logs'); ?>" class="button button-secondary">
                <?php echo __('Clear', 'sourcehub'); ?>
            </a>
        </form>

        <div class="logs-actions">
            <a href="<?php echo admin_url('admin.php?page=sourcehub-logs' . (isset($_GET['log_level']) ? '&log_level=' . urlencode($_GET['log_level']) : '') . (isset($_GET['log_action']) ? '&log_action=' . urlencode($_GET['log_action']) : '') . (isset($_GET['log_date']) ? '&log_date=' . urlencode($_GET['log_date']) : '') . (isset($_GET['log_search']) ? '&log_search=' . urlencode($_GET['log_search']) : '')); ?>" class="button button-secondary">
                <span class="dashicons dashicons-update"></span>
                <?php echo __('Refresh', 'sourcehub'); ?>
            </a>

            <a href="<?php 
                $export_args = array('action' => 'sourcehub_export_logs');
                if (isset($_GET['log_level']) && $_GET['log_level']) $export_args['level'] = $_GET['log_level'];
                if (isset($_GET['log_action']) && $_GET['log_action']) $export_args['log_action_filter'] = $_GET['log_action'];
                if (isset($_GET['log_date']) && $_GET['log_date']) $export_args['date'] = $_GET['log_date'];
                if (isset($_GET['log_search']) && $_GET['log_search']) $export_args['search'] = $_GET['log_search'];
                echo wp_nonce_url(add_query_arg($export_args, admin_url('admin-ajax.php')), 'sourcehub_admin_nonce', 'nonce'); 
            ?>" class="button button-secondary">
                <span class="dashicons dashicons-download"></span>
                <?php echo __('Export', 'sourcehub'); ?>
            </a>

            <button type="button" id="clear-logs" class="button button-link-delete">
                <span class="dashicons dashicons-trash"></span>
                <?php echo __('Clear All', 'sourcehub'); ?>
            </button>
            
            <?php if ($mode === 'hub'): ?>
            <button type="button" id="check-timeouts" class="button button-secondary">
                <span class="dashicons dashicons-clock"></span>
                <?php echo __('Check Timeouts', 'sourcehub'); ?>
            </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="logs-stats">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($stats['total']); ?></div>
                <div class="stat-label"><?php echo __('Total Logs', 'sourcehub'); ?></div>
            </div>
            <div class="stat-card success">
                <div class="stat-number"><?php echo number_format($stats['success']); ?></div>
                <div class="stat-label"><?php echo __('Success', 'sourcehub'); ?></div>
            </div>
            <div class="stat-card error">
                <div class="stat-number"><?php echo number_format($stats['error']); ?></div>
                <div class="stat-label"><?php echo __('Errors', 'sourcehub'); ?></div>
            </div>
            <div class="stat-card warning">
                <div class="stat-number"><?php echo number_format($stats['warning']); ?></div>
                <div class="stat-label"><?php echo __('Warnings', 'sourcehub'); ?></div>
            </div>
        </div>
    </div>

    <div class="sourcehub-logs">
        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <span class="dashicons dashicons-admin-page"></span>
                <h3><?php echo __('No logs yet', 'sourcehub'); ?></h3>
                <p><?php echo __('Activity logs will appear here as you use SourceHub features.', 'sourcehub'); ?></p>
            </div>
        <?php else: ?>
            <table class="sourcehub-table" id="sourcehub-logs-table">
                <thead>
                    <tr>
                        <th class="column-status"><?php echo __('Status', 'sourcehub'); ?></th>
                        <th class="column-message"><?php echo __('Message', 'sourcehub'); ?></th>
                        <th class="column-action"><?php echo __('Action', 'sourcehub'); ?></th>
                        <th class="column-connection"><?php echo __('Connection', 'sourcehub'); ?></th>
                        <th class="column-date"><?php echo __('Date', 'sourcehub'); ?></th>
                        <th class="column-details"><?php echo __('Details', 'sourcehub'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr data-log-id="<?php echo $log->id; ?>" data-level="<?php echo esc_attr($log->status); ?>" data-action="<?php echo esc_attr($log->action); ?>">
                            <td class="column-status">
                                <?php echo SourceHub_Admin::get_log_level_badge($log->status); ?>
                            </td>
                            <td class="column-message">
                                <div class="log-message">
                                    <?php echo esc_html($log->message); ?>
                                    <?php if (!empty($log->details)): ?>
                                        <button type="button" class="button-link toggle-details" data-log-id="<?php echo $log->id; ?>">
                                            <?php echo __('Show Details', 'sourcehub'); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($log->details)): ?>
                                    <div class="log-details" id="details-<?php echo $log->id; ?>" style="display: none;">
                                        <pre><?php echo esc_html($log->details); ?></pre>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="column-action">
                                <?php if (!empty($log->action)): ?>
                                    <span class="action-badge"><?php echo esc_html(ucfirst($log->action)); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="column-connection">
                                <?php if (!empty($log->connection_id)): ?>
                                    <?php
                                    // Connection data is now pre-fetched via JOIN (no additional query needed)
                                    if (!empty($log->connection_name)) {
                                        echo '<a href="' . admin_url('admin.php?page=sourcehub-connections') . '" title="' . esc_attr($log->connection_url) . '">';
                                        echo esc_html($log->connection_name);
                                        echo '</a>';
                                    } else {
                                        echo '<span class="text-muted">' . __('Deleted', 'sourcehub') . '</span>';
                                    }
                                    ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="column-date">
                                <time datetime="<?php echo esc_attr($log->created_at); ?>" title="<?php echo esc_attr(date('Y-m-d H:i:s', strtotime($log->created_at))); ?>">
                                    <?php echo SourceHub_Admin::time_ago($log->created_at); ?>
                                </time>
                            </td>
                            <td class="column-details">
                                <div class="row-actions">
                                    <?php if (!empty($log->details)): ?>
                                        <span class="view">
                                            <button type="button" class="button-link view-log-details" data-log-id="<?php echo $log->id; ?>">
                                                <?php echo __('View', 'sourcehub'); ?>
                                            </button>
                                        </span>
                                    <?php endif; ?>
                                    <span class="delete">
                                        <button type="button" class="button-link delete-log" data-log-id="<?php echo $log->id; ?>">
                                            <?php echo __('Delete', 'sourcehub'); ?>
                                        </button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($total_pages > 1): 
                // Build query string with filters
                $query_args = array('page' => 'sourcehub-logs');
                if (isset($_GET['log_level']) && $_GET['log_level']) $query_args['log_level'] = $_GET['log_level'];
                if (isset($_GET['log_action']) && $_GET['log_action']) $query_args['log_action'] = $_GET['log_action'];
                if (isset($_GET['log_date']) && $_GET['log_date']) $query_args['log_date'] = $_GET['log_date'];
                if (isset($_GET['log_search']) && $_GET['log_search']) $query_args['log_search'] = $_GET['log_search'];
            ?>
                <div class="logs-pagination">
                    <div class="pagination-info">
                        <?php
                        $start = (($current_page - 1) * $per_page) + 1;
                        $end = min($current_page * $per_page, $total_logs);
                        printf(__('Showing %d-%d of %d logs', 'sourcehub'), $start, $end, $total_logs);
                        ?>
                    </div>
                    <div class="pagination-links">
                        <?php if ($current_page > 1): ?>
                            <a href="<?php echo add_query_arg(array_merge($query_args, array('paged' => 1)), admin_url('admin.php')); ?>" class="button"><?php echo __('First', 'sourcehub'); ?></a>
                            <a href="<?php echo add_query_arg(array_merge($query_args, array('paged' => $current_page - 1)), admin_url('admin.php')); ?>" class="button"><?php echo __('Previous', 'sourcehub'); ?></a>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="<?php echo add_query_arg(array_merge($query_args, array('paged' => $i)), admin_url('admin.php')); ?>" class="button <?php echo $i === $current_page ? 'button-primary' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?php echo add_query_arg(array_merge($query_args, array('paged' => $current_page + 1)), admin_url('admin.php')); ?>" class="button"><?php echo __('Next', 'sourcehub'); ?></a>
                            <a href="<?php echo add_query_arg(array_merge($query_args, array('paged' => $total_pages)), admin_url('admin.php')); ?>" class="button"><?php echo __('Last', 'sourcehub'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Log Details Modal -->
<div id="log-details-modal" class="sourcehub-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><?php echo __('Log Details', 'sourcehub'); ?></h2>
            <button type="button" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div id="log-details-content"></div>
        </div>
    </div>
</div>

<style>
.logs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.logs-filters {
    display: flex;
    gap: 10px;
    align-items: center;
}

.logs-filters .form-select,
.logs-filters .form-input {
    min-width: 120px;
}

.logs-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.logs-stats {
    margin: 20px 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.stat-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    text-align: center;
}

.stat-card.success {
    border-left: 4px solid #00a32a;
}

.stat-card.error .stat-number {
    color: #dc3232;
}

.stat-card.warning {
    border-left: 4px solid #dba617;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 14px;
}

.column-status {
    width: 80px;
}

.column-action {
    width: 100px;
}

.column-connection {
    width: 150px;
}

.column-date {
    width: 120px;
}

.column-details {
    width: 100px;
}

.log-details {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    margin-top: 10px;
}

.log-details pre {
    margin: 0;
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: 12px;
    max-height: 200px;
    overflow-y: auto;
}

.action-badge {
    background: #f0f0f1;
    color: #3c434a;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 12px;
    text-transform: uppercase;
}

.logs-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.pagination-links {
    display: flex;
    gap: 5px;
}

.pagination-links .button {
    min-width: 40px;
    text-align: center;
}

.toggle-details {
    font-size: 12px;
    margin-left: 10px;
    color: #0073aa;
}

.row-actions {
    visibility: hidden;
}

tr:hover .row-actions {
    visibility: visible;
}

.row-actions span {
    margin-right: 10px;
}

.button-link {
    background: none;
    border: none;
    color: #0073aa;
    cursor: pointer;
    text-decoration: underline;
    padding: 0;
    font-size: inherit;
}

.button-link:hover {
    color: #005177;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.status-success {
    background: #d4edda;
    color: #155724;
}

.status-badge.status-error {
    background: #f8d7da;
    color: #721c24;
}

.status-badge.status-warning {
    background: #fff3cd;
    color: #856404;
}

.status-badge.status-info {
    background: #d1ecf1;
    color: #0c5460;
}

@media (max-width: 768px) {
    .logs-header {
        flex-direction: column;
        gap: 15px;
    }

    .logs-filters {
        flex-wrap: wrap;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .sourcehub-table {
        font-size: 14px;
    }

    .column-connection,
    .column-details {
        display: none;
    }
}

.dashicons.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
jQuery(document).ready(function($) {
    var currentPage = 1;
    var currentFilters = {};

    // Bind event handlers for log rows (called initially and after AJAX loads)
    function bindLogEventHandlers() {
        // Toggle log details
        $('.toggle-details').off('click').on('click', function() {
        var logId = $(this).data('log-id');
        var $details = $('#details-' + logId);
        var $button = $(this);

        if ($details.is(':visible')) {
            $details.hide();
            $button.text('<?php echo esc_js(__('Show Details', 'sourcehub')); ?>');
        } else {
            $details.show();
            $button.text('<?php echo esc_js(__('Hide Details', 'sourcehub')); ?>');
        }
    });

    // View log details in modal
    $('.view-log-details').on('click', function() {
        var logId = $(this).data('log-id');
        var $details = $('#details-' + logId + ' pre');
        
        if ($details.length) {
            $('#log-details-content').html('<pre>' + $details.html() + '</pre>');
            $('#log-details-modal').addClass('active');
            $('body').addClass('modal-open');
        }
    });

        // Delete individual log
        $('.delete-log').off('click').on('click', function() {
            if (!confirm('<?php echo esc_js(__('Are you sure you want to delete this log entry?', 'sourcehub')); ?>')) {
                return;
            }

            var logId = $(this).data('log-id');
            var $row = $(this).closest('tr');

            $.post(sourcehub_admin.ajax_url, {
                action: 'sourcehub_delete_log',
                log_id: logId,
                nonce: sourcehub_admin.ajax_nonce
            }, function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                    });
                    if (typeof SourceHubAdmin !== 'undefined') {
                        SourceHubAdmin.showNotice('success', response.data.message);
                    }
                } else {
                    if (typeof SourceHubAdmin !== 'undefined') {
                        SourceHubAdmin.showNotice('error', response.data.message);
                    }
                }
            });
        });
    }
    
    // Call on page load to bind initial handlers
    bindLogEventHandlers();

    // Clear all logs (only AJAX action we keep)
    $('#clear-logs').on('click', function() {
        if (!confirm('<?php echo esc_js(__('Are you sure you want to clear all logs? This action cannot be undone.', 'sourcehub')); ?>')) {
            return;
        }

        $.post(sourcehub_admin.ajax_url, {
            action: 'sourcehub_clear_logs',
            nonce: sourcehub_admin.ajax_nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                SourceHubAdmin.showNotice('error', response.data.message);
            }
        });
    });
    
    // Check for timed-out processing jobs
    $('#check-timeouts').on('click', function() {
        var $btn = $(this);
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="dashicons dashicons-update spin"></span> Checking...');
        
        $.post(sourcehub_admin.ajax_url, {
            action: 'sourcehub_check_timeouts',
            nonce: sourcehub_admin.ajax_nonce
        }, function(response) {
            if (response.success) {
                var message = response.data.found > 0 
                    ? '<?php echo esc_js(__('Found and marked %d timed-out job(s) as failed.', 'sourcehub')); ?>'.replace('%d', response.data.found)
                    : '<?php echo esc_js(__('No timed-out jobs found.', 'sourcehub')); ?>';
                SourceHubAdmin.showNotice('success', message);
                if (response.data.found > 0) {
                    setTimeout(function() { location.reload(); }, 1500);
                }
            } else {
                SourceHubAdmin.showNotice('error', response.data.message || '<?php echo esc_js(__('Failed to check timeouts.', 'sourcehub')); ?>');
            }
            $btn.prop('disabled', false).html(originalText);
        }).fail(function() {
            SourceHubAdmin.showNotice('error', '<?php echo esc_js(__('Failed to check timeouts.', 'sourcehub')); ?>');
            $btn.prop('disabled', false).html(originalText);
        });
    });
    
    function escapeHtml(text) {
        if (!text) return '';
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function updatePagination(pagination) {
        // For now, just reload the page to show updated pagination
        // A full implementation would rebuild the pagination HTML
        if (pagination && pagination.total_pages > 1) {
            console.log('Pagination:', pagination);
        }
    }
});
</script>
