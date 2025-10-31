<?php
/**
 * Connections view template
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
        <?php echo $mode === 'hub' ? __('Spoke Connections', 'sourcehub') : __('Hub Connections', 'sourcehub'); ?>
        <span class="mode-badge mode-<?php echo esc_attr($mode); ?>">
            <?php echo esc_html(ucfirst($mode)); ?> Mode
        </span>
    </h1>

    <?php if ($mode === 'hub'): ?>
        <a href="<?php echo admin_url('admin.php?page=sourcehub-add-spoke'); ?>" class="page-title-action">
            <?php echo __('Add Spoke Site', 'sourcehub'); ?>
        </a>
    <?php else: ?>
        <a href="#" class="page-title-action" data-modal="add-connection-modal">
            <?php echo __('Connect to Hub', 'sourcehub'); ?>
        </a>
    <?php endif; ?>

    <div class="sourcehub-connections">
        <?php if (empty($connections)): ?>
            <div class="empty-state">
                <span class="dashicons dashicons-networking"></span>
                <h3><?php echo __('No connections yet', 'sourcehub'); ?></h3>
                <p>
                    <?php if ($mode === 'hub'): ?>
                        <?php echo __('Add spoke sites to start syndicating your content to multiple locations.', 'sourcehub'); ?>
                    <?php else: ?>
                        <?php echo __('Connect to a hub site to receive and publish syndicated content.', 'sourcehub'); ?>
                    <?php endif; ?>
                </p>
                <?php if ($mode === 'hub'): ?>
                    <a href="<?php echo admin_url('admin.php?page=sourcehub-add-spoke'); ?>" class="button button-primary">
                        <?php echo __('Add Your First Spoke Site', 'sourcehub'); ?>
                    </a>
                <?php else: ?>
                    <a href="#" class="button button-primary" data-modal="add-connection-modal">
                        <?php echo __('Connect to Hub', 'sourcehub'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <table class="sourcehub-table">
                <thead>
                    <tr>
                        <th><?php echo __('Name', 'sourcehub'); ?></th>
                        <th><?php echo __('URL', 'sourcehub'); ?></th>
                        <th><?php echo __('Status', 'sourcehub'); ?></th>
                        <th><?php echo __('API Key', 'sourcehub'); ?></th>
                        <th><?php echo __('Actions', 'sourcehub'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($connections as $connection): ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($connection->name); ?></strong>
                                <?php if (!empty($connection->sync_settings)): ?>
                                    <?php $sync_settings = json_decode($connection->sync_settings, true); ?>
                                    <?php if (!empty($sync_settings['auto_publish'])): ?>
                                        <br><small class="text-muted"><?php echo __('Auto-publish enabled', 'sourcehub'); ?></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($connection->url); ?>" target="_blank" rel="noopener">
                                    <?php echo esc_html($connection->url); ?>
                                    <span class="dashicons dashicons-external"></span>
                                </a>
                            </td>
                            <td class="connection-status">
                                <?php echo SourceHub_Admin::get_status_badge($connection); ?>
                            </td>
                            <td>
                                <code class="api-key" data-tooltip="<?php echo esc_attr__('Click to copy', 'sourcehub'); ?>">
                                    <?php echo esc_html(substr($connection->api_key, 0, 8) . '...'); ?>
                                </code>
                                <button type="button" class="button button-small copy-button" data-copy-target="#api-key-<?php echo $connection->id; ?>">
                                    <?php echo __('Copy', 'sourcehub'); ?>
                                </button>
                                <input type="hidden" id="api-key-<?php echo $connection->id; ?>" value="<?php echo esc_attr($connection->api_key); ?>">
                            </td>
                            <td>
                                <div class="actions">
                                    <button type="button" class="button button-small test-connection" data-connection-id="<?php echo $connection->id; ?>">
                                        <?php echo __('Test', 'sourcehub'); ?>
                                    </button>
                                    <button type="button" class="button button-small" data-modal="edit-connection-modal" data-connection-id="<?php echo $connection->id; ?>">
                                        <?php echo __('Edit', 'sourcehub'); ?>
                                    </button>
                                    <button type="button" class="button button-small button-link-delete delete-connection" data-connection-id="<?php echo $connection->id; ?>">
                                        <?php echo __('Delete', 'sourcehub'); ?>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Add Connection Modal -->
<div id="add-connection-modal" class="sourcehub-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><?php echo $mode === 'hub' ? __('Add Spoke Site', 'sourcehub') : __('Connect to Hub', 'sourcehub'); ?></h2>
            <button type="button" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-connection-form" class="sourcehub-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="connection_name" class="form-label"><?php echo __('Connection Name', 'sourcehub'); ?></label>
                        <input type="text" id="connection_name" name="name" class="form-input" required>
                        <div class="form-help"><?php echo __('A friendly name to identify this connection', 'sourcehub'); ?></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="connection_url" class="form-label"><?php echo __('Site URL', 'sourcehub'); ?></label>
                        <input type="url" id="connection_url" name="url" class="form-input" required placeholder="https://example.com">
                        <div class="form-help"><?php echo __('The full URL of the WordPress site (without trailing slash)', 'sourcehub'); ?></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="connection_api_key" class="form-label"><?php echo __('API Key', 'sourcehub'); ?></label>
                        <input type="text" id="connection_api_key" name="api_key" class="form-input" required placeholder="Enter the site's API key">
                        <div class="form-help">
                            <?php if ($mode === 'hub'): ?>
                                <?php echo __('Enter the spoke site\'s API key (found in their SourceHub settings when in Spoke mode)', 'sourcehub'); ?>
                            <?php else: ?>
                                <?php echo __('Enter the hub site\'s API key to establish connection', 'sourcehub'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($mode === 'hub'): ?>
                    <!-- Sync Settings for Hub Mode -->
                    <h3><?php echo __('Sync Settings', 'sourcehub'); ?></h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="sync_auto_publish" checked>
                                <?php echo __('Auto-publish posts', 'sourcehub'); ?>
                            </label>
                            <div class="form-help"><?php echo __('Automatically publish posts on the spoke site', 'sourcehub'); ?></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="sync_featured_image" checked>
                                <?php echo __('Sync featured images', 'sourcehub'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="sync_categories" checked>
                                <?php echo __('Sync categories', 'sourcehub'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="sync_tags" checked>
                                <?php echo __('Sync tags', 'sourcehub'); ?>
                            </label>
                        </div>
                    </div>

                    <!-- AI Settings -->
                    <h3><?php echo __('AI Rewriting (Optional)', 'sourcehub'); ?></h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="ai_enabled">
                                <?php echo __('Enable AI rewriting for this spoke', 'sourcehub'); ?>
                            </label>
                            <div class="form-help"><?php echo __('Requires OpenAI API key to be configured in settings', 'sourcehub'); ?></div>
                        </div>
                    </div>

                    <div class="ai-settings" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="ai_rewrite_title">
                                    <?php echo __('Rewrite titles', 'sourcehub'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="ai_rewrite_content">
                                    <?php echo __('Rewrite content', 'sourcehub'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="ai_rewrite_excerpt">
                                    <?php echo __('Rewrite excerpts', 'sourcehub'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ai_tone" class="form-label"><?php echo __('Writing Tone', 'sourcehub'); ?></label>
                                <select id="ai_tone" name="ai_tone" class="form-select">
                                    <option value="maintain"><?php echo __('Maintain Original', 'sourcehub'); ?></option>
                                    <option value="professional"><?php echo __('Professional', 'sourcehub'); ?></option>
                                    <option value="casual"><?php echo __('Casual', 'sourcehub'); ?></option>
                                    <option value="formal"><?php echo __('Formal', 'sourcehub'); ?></option>
                                    <option value="friendly"><?php echo __('Friendly', 'sourcehub'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ai_target_audience" class="form-label"><?php echo __('Target Audience', 'sourcehub'); ?></label>
                                <input type="text" id="ai_target_audience" name="ai_target_audience" class="form-input" placeholder="e.g., local residents, business owners">
                                <div class="form-help"><?php echo __('Optional: Specify the target audience for content adaptation', 'sourcehub'); ?></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="ai_regional_focus" class="form-label"><?php echo __('Regional Focus', 'sourcehub'); ?></label>
                                <input type="text" id="ai_regional_focus" name="ai_regional_focus" class="form-input" placeholder="e.g., New York, California">
                                <div class="form-help"><?php echo __('Optional: Add regional context to the content', 'sourcehub'); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="button button-primary">
                        <?php echo $mode === 'hub' ? __('Add Spoke Site', 'sourcehub') : __('Connect to Hub', 'sourcehub'); ?>
                    </button>
                    <button type="button" class="button modal-close"><?php echo __('Cancel', 'sourcehub'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Connection Modal -->
<div id="edit-connection-modal" class="sourcehub-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><?php echo $mode === 'hub' ? __('Edit Spoke Site', 'sourcehub') : __('Edit Hub Connection', 'sourcehub'); ?></h2>
            <button type="button" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-connection-form" class="sourcehub-form">
                <input type="hidden" id="edit-connection-id" name="connection_id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_connection_name" class="form-label"><?php echo __('Connection Name', 'sourcehub'); ?></label>
                        <input type="text" id="edit_connection_name" name="name" class="form-input" required>
                        <div class="form-help"><?php echo __('A friendly name to identify this connection', 'sourcehub'); ?></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_connection_url" class="form-label"><?php echo __('Site URL', 'sourcehub'); ?></label>
                        <input type="url" id="edit_connection_url" name="url" class="form-input" required placeholder="https://example.com">
                        <div class="form-help"><?php echo __('The full URL of the WordPress site (without trailing slash)', 'sourcehub'); ?></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_connection_api_key" class="form-label"><?php echo __('API Key', 'sourcehub'); ?></label>
                        <input type="text" id="edit_connection_api_key" name="api_key" class="form-input" required placeholder="Enter the site's API key">
                        <div class="form-help">
                            <?php if ($mode === 'hub'): ?>
                                <?php echo __('Enter the spoke site\'s API key (found in their SourceHub settings when in Spoke mode)', 'sourcehub'); ?>
                            <?php else: ?>
                                <?php echo __('Enter the hub site\'s API key to establish connection', 'sourcehub'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($mode === 'hub'): ?>
                    <!-- Sync Settings for Hub Mode -->
                    <h3><?php echo __('Sync Settings', 'sourcehub'); ?></h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="edit_sync_auto_publish" name="sync_auto_publish">
                                <?php echo __('Auto-publish syndicated content', 'sourcehub'); ?>
                            </label>
                            <div class="form-help"><?php echo __('Automatically publish content when received from hub', 'sourcehub'); ?></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="edit_sync_categories" name="sync_categories">
                                <?php echo __('Sync categories', 'sourcehub'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="edit_sync_tags" name="sync_tags">
                                <?php echo __('Sync tags', 'sourcehub'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="edit_sync_featured_image" name="sync_featured_image">
                                <?php echo __('Sync featured image', 'sourcehub'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="edit_sync_yoast_meta" name="sync_yoast_meta">
                                <?php echo __('Sync Yoast SEO metadata', 'sourcehub'); ?>
                            </label>
                            <div class="form-help"><?php echo __('Requires Yoast SEO plugin to be active on both sites', 'sourcehub'); ?></div>
                        </div>
                    </div>

                    <!-- AI Settings -->
                    <h3><?php echo __('AI Rewriting', 'sourcehub'); ?></h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="edit_ai_enabled" name="ai_enabled">
                                <?php echo __('Enable AI rewriting for this connection', 'sourcehub'); ?>
                            </label>
                            <div class="form-help"><?php echo __('Requires OpenAI API key to be configured in settings', 'sourcehub'); ?></div>
                        </div>
                    </div>

                    <div class="edit-ai-settings" style="display: none;">
                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="edit_ai_rewrite_title" name="ai_rewrite_title">
                                    <?php echo __('Rewrite title', 'sourcehub'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="edit_ai_rewrite_content" name="ai_rewrite_content">
                                    <?php echo __('Rewrite content', 'sourcehub'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="edit_ai_rewrite_excerpt" name="ai_rewrite_excerpt">
                                    <?php echo __('Rewrite excerpt', 'sourcehub'); ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit_ai_tone" class="form-label"><?php echo __('Writing Tone', 'sourcehub'); ?></label>
                                <select id="edit_ai_tone" name="ai_tone" class="form-select">
                                    <option value="professional"><?php echo __('Professional', 'sourcehub'); ?></option>
                                    <option value="casual"><?php echo __('Casual', 'sourcehub'); ?></option>
                                    <option value="formal"><?php echo __('Formal', 'sourcehub'); ?></option>
                                    <option value="conversational"><?php echo __('Conversational', 'sourcehub'); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit_ai_instructions" class="form-label"><?php echo __('Additional Instructions', 'sourcehub'); ?></label>
                                <textarea id="edit_ai_instructions" name="ai_instructions" class="form-textarea" rows="3" placeholder="<?php echo esc_attr__('Optional: Provide specific instructions for AI rewriting...', 'sourcehub'); ?>"></textarea>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="button button-primary">
                        <?php echo __('Update Connection', 'sourcehub'); ?>
                    </button>
                    <button type="button" class="button modal-close"><?php echo __('Cancel', 'sourcehub'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Show/hide AI settings based on checkbox
    $('input[name="ai_enabled"]').on('change', function() {
        if (this.checked) {
            $('.ai-settings').show();
        } else {
            $('.ai-settings').hide();
        }
    });

    // Show/hide AI settings based on checkbox for edit modal
    $('input[name="ai_enabled"]').on('change', function() {
        if (this.checked) {
            $('.edit-ai-settings').show();
        } else {
            $('.edit-ai-settings').hide();
        }
    });
    
    // Handle edit connection button clicks
    $('button[data-modal="edit-connection-modal"]').on('click', function() {
        var connectionId = $(this).data('connection-id');
        
        // Get connection data via AJAX
        $.ajax({
            url: sourcehub_admin.rest_url + 'connections/' + connectionId,
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', sourcehub_admin.nonce);
            },
            success: function(response) {
                if (response.success && response.connection) {
                    populateEditForm(response.connection);
                    SourceHubAdmin.openModal('edit-connection-modal');
                } else {
                    SourceHubAdmin.showNotice('error', response.message || 'Failed to load connection data');
                }
            },
            error: function() {
                SourceHubAdmin.showNotice('error', 'Failed to load connection data');
            }
        });
    });
    
    // Populate edit form with connection data
    function populateEditForm(connection) {
        $('#edit-connection-id').val(connection.id);
        $('#edit_connection_name').val(connection.name);
        $('#edit_connection_url').val(connection.url);
        $('#edit_connection_api_key').val(connection.api_key);
        
        // Parse and populate sync settings
        var syncSettings = {};
        if (connection.sync_settings) {
            try {
                syncSettings = JSON.parse(connection.sync_settings);
            } catch (e) {
                syncSettings = {};
            }
        }
        
        $('#edit_sync_auto_publish').prop('checked', syncSettings.auto_publish || false);
        $('#edit_sync_categories').prop('checked', syncSettings.categories || false);
        $('#edit_sync_tags').prop('checked', syncSettings.tags || false);
        $('#edit_sync_featured_image').prop('checked', syncSettings.featured_image || false);
        $('#edit_sync_yoast_meta').prop('checked', syncSettings.yoast_meta || false);
        
        // Parse and populate AI settings
        var aiSettings = {};
        if (connection.ai_settings) {
            try {
                aiSettings = JSON.parse(connection.ai_settings);
            } catch (e) {
                aiSettings = {};
            }
        }
        
        $('#edit_ai_enabled').prop('checked', aiSettings.enabled || false);
        $('#edit_ai_rewrite_title').prop('checked', aiSettings.rewrite_title || false);
        $('#edit_ai_rewrite_content').prop('checked', aiSettings.rewrite_content || false);
        $('#edit_ai_rewrite_excerpt').prop('checked', aiSettings.rewrite_excerpt || false);
        $('#edit_ai_tone').val(aiSettings.tone || 'professional');
        $('#edit_ai_instructions').val(aiSettings.instructions || '');
        
        // Show/hide AI settings based on enabled checkbox
        if (aiSettings.enabled) {
            $('.edit-ai-settings').show();
        } else {
            $('.edit-ai-settings').hide();
        }
    }

    // Copy API key functionality
    $('.api-key').on('click', function() {
        var $this = $(this);
        var connectionId = $this.closest('tr').find('.test-connection').data('connection-id');
        var $hiddenInput = $('#api-key-' + connectionId);
        
        if ($hiddenInput.length) {
            var text = $hiddenInput.val();
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    SourceHubAdmin.showNotice('success', 'API key copied to clipboard!');
                });
            } else {
                // Fallback
                var $temp = $('<textarea>');
                $('body').append($temp);
                $temp.val(text).select();
                document.execCommand('copy');
                $temp.remove();
                SourceHubAdmin.showNotice('success', 'API key copied to clipboard!');
            }
        }
    });
});
</script>
