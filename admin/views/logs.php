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
        <div class="logs-filters">
            <select id="log-level-filter" class="form-select">
                <option value=""><?php echo __('All Levels', 'sourcehub'); ?></option>
                <option value="success"><?php echo __('Success', 'sourcehub'); ?></option>
                <option value="error"><?php echo __('Error', 'sourcehub'); ?></option>
                <option value="warning"><?php echo __('Warning', 'sourcehub'); ?></option>
                <option value="info"><?php echo __('Info', 'sourcehub'); ?></option>
            </select>

            <select id="log-action-filter" class="form-select">
                <option value=""><?php echo __('All Actions', 'sourcehub'); ?></option>
                <option value="sync"><?php echo __('Sync', 'sourcehub'); ?></option>
                <option value="connection"><?php echo __('Connection', 'sourcehub'); ?></option>
                <option value="ai_rewrite"><?php echo __('AI Rewrite', 'sourcehub'); ?></option>
                <option value="api"><?php echo __('API', 'sourcehub'); ?></option>
            </select>

            <input type="date" id="log-date-filter" class="form-input">

            <button type="button" id="apply-filters" class="button button-secondary">
                <?php echo __('Apply Filters', 'sourcehub'); ?>
            </button>

            <button type="button" id="clear-filters" class="button button-secondary">
                <?php echo __('Clear', 'sourcehub'); ?>
            </button>
        </div>

        <div class="logs-actions">
            <button type="button" id="refresh-logs" class="button button-secondary">
                <span class="dashicons dashicons-update"></span>
                <?php echo __('Refresh', 'sourcehub'); ?>
            </button>

            <button type="button" id="export-logs" class="button button-secondary">
                <span class="dashicons dashicons-download"></span>
                <?php echo __('Export', 'sourcehub'); ?>
            </button>

            <button type="button" id="clear-logs" class="button button-link-delete">
                <span class="dashicons dashicons-trash"></span>
                <?php echo __('Clear All', 'sourcehub'); ?>
            </button>
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

            <?php if ($total_pages > 1): ?>
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
                            <a href="#" class="button" data-page="1"><?php echo __('First', 'sourcehub'); ?></a>
                            <a href="#" class="button" data-page="<?php echo $current_page - 1; ?>"><?php echo __('Previous', 'sourcehub'); ?></a>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="#" class="button <?php echo $i === $current_page ? 'button-primary' : ''; ?>" data-page="<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <a href="#" class="button" data-page="<?php echo $current_page + 1; ?>"><?php echo __('Next', 'sourcehub'); ?></a>
                            <a href="#" class="button" data-page="<?php echo $total_pages; ?>"><?php echo __('Last', 'sourcehub'); ?></a>
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

.stat-card.error {
    border-left: 4px solid #d63638;
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
</style>

<script>
jQuery(document).ready(function($) {
    var currentPage = 1;
    var currentFilters = {};

    // Toggle log details
    $('.toggle-details').on('click', function() {
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
    $('.delete-log').on('click', function() {
        if (!confirm('<?php echo esc_js(__('Are you sure you want to delete this log entry?', 'sourcehub')); ?>')) {
            return;
        }

        var logId = $(this).data('log-id');
        var $row = $(this).closest('tr');

        $.post(sourcehub_admin.ajax_url, {
            action: 'sourcehub_delete_log',
            log_id: logId,
            nonce: sourcehub_admin.nonce
        }, function(response) {
            if (response.success) {
                $row.fadeOut(300, function() {
                    $(this).remove();
                });
                SourceHubAdmin.showNotice('success', response.data.message);
            } else {
                SourceHubAdmin.showNotice('error', response.data.message);
            }
        });
    });

    // Apply filters
    $('#apply-filters').on('click', function() {
        currentFilters = {
            level: $('#log-level-filter').val(),
            action: $('#log-action-filter').val(),
            date: $('#log-date-filter').val()
        };
        currentPage = 1;
        loadLogs();
    });

    // Clear filters
    $('#clear-filters').on('click', function() {
        $('#log-level-filter').val('');
        $('#log-action-filter').val('');
        $('#log-date-filter').val('');
        currentFilters = {};
        currentPage = 1;
        loadLogs();
    });

    // Refresh logs
    $('#refresh-logs').on('click', function() {
        loadLogs();
    });

    // Pagination
    $(document).on('click', '.pagination-links .button', function(e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('page'));
        loadLogs();
    });

    // Export logs
    $('#export-logs').on('click', function() {
        var params = new URLSearchParams(currentFilters);
        params.append('action', 'sourcehub_export_logs');
        params.append('nonce', sourcehub_admin.nonce);
        
        window.open(sourcehub_admin.ajax_url + '?' + params.toString());
    });

    // Clear all logs
    $('#clear-logs').on('click', function() {
        if (!confirm('<?php echo esc_js(__('Are you sure you want to clear all logs? This action cannot be undone.', 'sourcehub')); ?>')) {
            return;
        }

        $.post(sourcehub_admin.ajax_url, {
            action: 'sourcehub_clear_logs',
            nonce: sourcehub_admin.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                SourceHubAdmin.showNotice('error', response.data.message);
            }
        });
    });

    // Load logs via AJAX
    function loadLogs() {
        var $table = $('#sourcehub-logs-table tbody');
        var $loading = $('<tr><td colspan="6" style="text-align: center; padding: 40px;"><span class="spinner is-active"></span> Loading...</td></tr>');
        
        $table.html($loading);

        var data = $.extend({}, currentFilters, {
            page: currentPage,
            per_page: 20
        });

        $.get(sourcehub_admin.rest_url + 'logs', data)
            .done(function(response) {
                if (response.logs && response.logs.length > 0) {
                    var html = '';
                    response.logs.forEach(function(log) {
                        html += buildLogRow(log);
                    });
                    $table.html(html);
                    updatePagination(response.pagination);
                } else {
                    $table.html('<tr><td colspan="6" style="text-align: center; padding: 40px;">No logs found</td></tr>');
                }
            })
            .fail(function() {
                $table.html('<tr><td colspan="6" style="text-align: center; padding: 40px; color: #d63638;">Error loading logs</td></tr>');
            });
    }

    function buildLogRow(log) {
        // This would mirror the PHP template logic
        // Implementation depends on the exact log structure returned by the API
        return '<tr>...</tr>'; // Simplified for brevity
    }

    function updatePagination(pagination) {
        // Update pagination based on response
        // Implementation would update the pagination HTML
    }
});
</script>
