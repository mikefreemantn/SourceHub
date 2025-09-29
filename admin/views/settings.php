<?php
/**
 * Settings view template
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
        <?php echo esc_html__('SourceHub Settings', 'sourcehub'); ?>
        <span class="mode-badge mode-<?php echo esc_attr($mode); ?>">
            <?php echo esc_html(ucfirst($mode)); ?> Mode
        </span>
    </h1>

    <form id="sourcehub-settings-form" class="sourcehub-form">
        <?php wp_nonce_field('sourcehub_admin_nonce', 'sourcehub_nonce'); ?>
        <div class="nav-tab-wrapper">
            <a href="#general-settings" class="nav-tab nav-tab-active"><?php echo __('General', 'sourcehub'); ?></a>
            <a href="#ai-settings" class="nav-tab"><?php echo __('AI Integration', 'sourcehub'); ?></a>
            <a href="#sync-settings" class="nav-tab"><?php echo __('Sync Options', 'sourcehub'); ?></a>
            <a href="#advanced-settings" class="nav-tab"><?php echo __('Advanced', 'sourcehub'); ?></a>
        </div>

        <!-- General Settings -->
        <div id="general-settings" class="tab-content">
            <h2><?php echo __('General Settings', 'sourcehub'); ?></h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="sourcehub_mode" class="form-label"><?php echo __('Plugin Mode', 'sourcehub'); ?></label>
                    <select id="sourcehub_mode" name="sourcehub_mode" class="form-select">
                        <option value="hub" <?php selected($settings['mode'], 'hub'); ?>><?php echo __('Hub (Content Creator)', 'sourcehub'); ?></option>
                        <option value="spoke" <?php selected($settings['mode'], 'spoke'); ?>><?php echo __('Spoke (Content Receiver)', 'sourcehub'); ?></option>
                    </select>
                    <div class="form-help">
                        <?php echo __('Hub sites create and syndicate content. Spoke sites receive and publish syndicated content.', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <?php if ($settings['mode'] === 'spoke'): ?>
            <div class="form-row spoke-api-key-section">
                <div class="form-group">
                    <label class="form-label"><?php echo __('Spoke API Key', 'sourcehub'); ?></label>
                    <div class="api-key-display">
                        <input type="text" id="spoke-api-key" value="<?php echo esc_attr(get_option('sourcehub_spoke_api_key', '')); ?>" readonly class="form-input" style="font-family: monospace;">
                        <button type="button" class="button button-secondary" id="copy-spoke-key" style="margin-left: 10px;">
                            <?php echo __('Copy', 'sourcehub'); ?>
                        </button>
                        <button type="button" class="button button-secondary" id="regenerate-spoke-key" style="margin-left: 5px;">
                            <?php echo __('Regenerate', 'sourcehub'); ?>
                        </button>
                    </div>
                    <div class="form-help">
                        <?php echo __('Provide this API key to hub sites when they add this site as a spoke connection.', 'sourcehub'); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="sourcehub_default_author" class="form-label"><?php echo __('Default Author', 'sourcehub'); ?></label>
                    <?php
                    wp_dropdown_users(array(
                        'id' => 'sourcehub_default_author',
                        'name' => 'sourcehub_default_author',
                        'selected' => $settings['default_author'],
                        'class' => 'form-select',
                        'show_option_none' => __('Select an author', 'sourcehub')
                    ));
                    ?>
                    <div class="form-help">
                        <?php echo __('Default author for syndicated posts when original author doesn\'t exist on spoke site', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sourcehub_attribution_text" class="form-label"><?php echo __('Attribution Text', 'sourcehub'); ?></label>
                    <textarea id="sourcehub_attribution_text" name="sourcehub_attribution_text" class="form-textarea" rows="3"><?php echo esc_textarea($settings['attribution_text']); ?></textarea>
                    <div class="form-help">
                        <?php echo __('Text added to syndicated posts. Use {hub_name} and {hub_url} as placeholders.', 'sourcehub'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Settings -->
        <div id="ai-settings" class="tab-content" style="display: none;">
            <h2><?php echo __('AI Integration Settings', 'sourcehub'); ?></h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="sourcehub_openai_api_key" class="form-label"><?php echo __('OpenAI API Key', 'sourcehub'); ?></label>
                    <input type="password" id="sourcehub_openai_api_key" name="sourcehub_openai_api_key" value="<?php echo esc_attr($settings['openai_api_key']); ?>" class="form-input">
                    <div class="form-help">
                        <?php echo sprintf(__('Get your API key from <a href="%s" target="_blank">OpenAI Platform</a>', 'sourcehub'), 'https://platform.openai.com/api-keys'); ?>
                    </div>
                    <button type="button" id="test-api-connection" class="button button-secondary" style="margin-top: 10px;">
                        <?php echo __('Test Connection', 'sourcehub'); ?>
                    </button>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sourcehub_openai_model" class="form-label"><?php echo __('AI Model', 'sourcehub'); ?></label>
                    <select id="sourcehub_openai_model" name="sourcehub_openai_model" class="form-select">
                        <?php foreach ($ai_models as $model_id => $model_name): ?>
                            <option value="<?php echo esc_attr($model_id); ?>" <?php selected($settings['openai_model'], $model_id); ?>>
                                <?php echo esc_html($model_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-help">
                        <?php echo __('GPT-4 provides the best quality. GPT-4o Mini is the most cost-effective option. GPT-3.5 Turbo is faster and cheaper than GPT-4.', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="sourcehub_ai_max_words" class="form-label"><?php echo __('Maximum Words for AI Processing', 'sourcehub'); ?></label>
                    <input type="number" id="sourcehub_ai_max_words" name="sourcehub_ai_max_words" value="<?php echo esc_attr($settings['ai_max_words']); ?>" class="form-input" min="100" max="10000">
                    <div class="form-help">
                        <?php echo __('Posts longer than this will not be processed by AI to control costs', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="ai-cost-estimator" style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-top: 20px;">
                <h4><?php echo __('Cost Estimation', 'sourcehub'); ?></h4>
                <p><?php echo __('Estimated costs for AI rewriting (per 1000 words):', 'sourcehub'); ?></p>
                <ul>
                    <li><strong>GPT-4:</strong> ~$0.06 - $0.12</li>
                    <li><strong>GPT-3.5 Turbo:</strong> ~$0.002 - $0.004</li>
                </ul>
                <p><small><?php echo __('Actual costs may vary based on content complexity and rewriting requirements.', 'sourcehub'); ?></small></p>
            </div>
        </div>

        <!-- Sync Settings -->
        <div id="sync-settings" class="tab-content" style="display: none;">
            <h2><?php echo __('Synchronization Options', 'sourcehub'); ?></h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="sourcehub_auto_publish" <?php checked($settings['auto_publish']); ?>>
                        <?php echo __('Auto-publish syndicated posts', 'sourcehub'); ?>
                    </label>
                    <div class="form-help">
                        <?php echo __('Automatically publish posts when received from hub (spoke mode only)', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="sourcehub_preserve_author" <?php checked($settings['preserve_author']); ?>>
                        <?php echo __('Preserve original author', 'sourcehub'); ?>
                    </label>
                    <div class="form-help">
                        <?php echo __('Try to match original author by username or email. Falls back to default author if not found.', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="sourcehub_sync_featured_image" <?php checked($settings['sync_featured_image']); ?>>
                        <?php echo __('Sync featured images', 'sourcehub'); ?>
                    </label>
                    <div class="form-help">
                        <?php echo __('Download and set featured images from the hub site', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="sourcehub_sync_categories" <?php checked($settings['sync_categories']); ?>>
                        <?php echo __('Sync categories', 'sourcehub'); ?>
                    </label>
                    <div class="form-help">
                        <?php echo __('Create and assign categories from the hub site', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="sourcehub_sync_tags" <?php checked($settings['sync_tags']); ?>>
                        <?php echo __('Sync tags', 'sourcehub'); ?>
                    </label>
                    <div class="form-help">
                        <?php echo __('Create and assign tags from the hub site', 'sourcehub'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Settings -->
        <div id="advanced-settings" class="tab-content" style="display: none;">
            <h2><?php echo __('Advanced Settings', 'sourcehub'); ?></h2>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="sourcehub_log_retention_days" class="form-label"><?php echo __('Log Retention (Days)', 'sourcehub'); ?></label>
                    <input type="number" id="sourcehub_log_retention_days" name="sourcehub_log_retention_days" value="<?php echo esc_attr($settings['log_retention_days']); ?>" class="form-input" min="1" max="365">
                    <div class="form-help">
                        <?php echo __('Number of days to keep activity logs. Older logs will be automatically deleted.', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="sourcehub_enable_debug_logging" <?php checked($settings['enable_debug_logging']); ?>>
                        <?php echo __('Enable debug logging', 'sourcehub'); ?>
                    </label>
                    <div class="form-help">
                        <?php echo __('Write detailed logs to WordPress debug.log file (requires WP_DEBUG_LOG to be enabled)', 'sourcehub'); ?>
                    </div>
                </div>
            </div>

            <div class="system-info" style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-top: 20px;">
                <h4><?php echo __('System Information', 'sourcehub'); ?></h4>
                <table class="system-info-table">
                    <tr>
                        <td><strong><?php echo __('Plugin Version:', 'sourcehub'); ?></strong></td>
                        <td><?php echo SOURCEHUB_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo __('WordPress Version:', 'sourcehub'); ?></strong></td>
                        <td><?php echo get_bloginfo('version'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo __('PHP Version:', 'sourcehub'); ?></strong></td>
                        <td><?php echo PHP_VERSION; ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php echo __('Yoast SEO:', 'sourcehub'); ?></strong></td>
                        <td>
                            <?php if (SourceHub_Yoast_Integration::is_yoast_active()): ?>
                                <span class="status-good">
                                    <?php echo sprintf(__('Active (v%s)', 'sourcehub'), SourceHub_Yoast_Integration::get_yoast_version()); ?>
                                </span>
                            <?php else: ?>
                                <span class="status-warning"><?php echo __('Not installed', 'sourcehub'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php echo __('REST API:', 'sourcehub'); ?></strong></td>
                        <td>
                            <span class="status-good"><?php echo rest_url('sourcehub/v1/'); ?></span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="danger-zone" style="border: 1px solid #d63638; border-radius: 4px; padding: 15px; margin-top: 20px;">
                <h4 style="color: #d63638; margin-top: 0;"><?php echo __('Danger Zone', 'sourcehub'); ?></h4>
                <p><?php echo __('These actions cannot be undone. Please proceed with caution.', 'sourcehub'); ?></p>
                
                <button type="button" class="button button-secondary" id="clear-logs">
                    <?php echo __('Clear All Logs', 'sourcehub'); ?>
                </button>
                
                <button type="button" class="button button-secondary" id="reset-connections">
                    <?php echo __('Reset All Connections', 'sourcehub'); ?>
                </button>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary">
                <?php echo __('Save Settings', 'sourcehub'); ?>
            </button>
            <button type="button" class="button button-secondary" id="reset-to-defaults">
                <?php echo __('Reset to Defaults', 'sourcehub'); ?>
            </button>
        </div>
    </form>
</div>

<style>
.system-info-table {
    width: 100%;
    border-collapse: collapse;
}

.system-info-table td {
    padding: 8px 0;
    border-bottom: 1px solid #ddd;
}

.system-info-table td:first-child {
    width: 200px;
}

.status-good {
    color: #00a32a;
}

.status-warning {
    color: #dba617;
}

.nav-tab-wrapper {
    margin-bottom: 20px;
}

.tab-content {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 0 8px 8px 8px;
    padding: 20px;
    margin-top: -1px;
}

.danger-zone .button {
    margin-right: 10px;
}

.spoke-api-key-section {
    background: #f8f9fa;
    border: 1px solid #e1e5e9;
    border-radius: 6px;
    padding: 20px;
    margin: 15px 0;
}

.api-key-display {
    display: flex;
    align-items: center;
    gap: 10px;
}

.api-key-display input[readonly] {
    background: #fff;
    color: #2c3e50;
    font-weight: 500;
    border: 1px solid #ddd;
}

.spoke-api-key-section .form-help {
    margin-top: 10px;
    color: #666;
    font-style: italic;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Clear logs functionality
    $('#clear-logs').on('click', function() {
        if (confirm('<?php echo esc_js(__('Are you sure you want to clear all logs? This action cannot be undone.', 'sourcehub')); ?>')) {
            // AJAX call to clear logs
            $.post(sourcehub_admin.ajax_url, {
                action: 'sourcehub_clear_logs',
                nonce: sourcehub_admin.nonce
            }, function(response) {
                if (response.success) {
                    SourceHubAdmin.showNotice('success', response.data.message);
                } else {
                    SourceHubAdmin.showNotice('error', response.data.message);
                }
            });
        }
    });

    // Reset connections functionality
    $('#reset-connections').on('click', function() {
        if (confirm('<?php echo esc_js(__('Are you sure you want to reset all connections? This will delete all connection data and cannot be undone.', 'sourcehub')); ?>')) {
            // AJAX call to reset connections
            $.post(sourcehub_admin.ajax_url, {
                action: 'sourcehub_reset_connections',
                nonce: sourcehub_admin.nonce
            }, function(response) {
                if (response.success) {
                    SourceHubAdmin.showNotice('success', response.data.message);
                } else {
                    SourceHubAdmin.showNotice('error', response.data.message);
                }
            });
        }
    });

    // Reset to defaults
    $('#reset-to-defaults').on('click', function() {
        if (confirm('<?php echo esc_js(__('Are you sure you want to reset all settings to their default values?', 'sourcehub')); ?>')) {
            // Reset form to default values
            $('#sourcehub-settings-form')[0].reset();
            SourceHubAdmin.showNotice('info', '<?php echo esc_js(__('Form reset to defaults. Click "Save Settings" to apply changes.', 'sourcehub')); ?>');
        }
    });

    // Handle mode changes to show/hide spoke API key section
    $('#sourcehub_mode').on('change', function() {
        var mode = $(this).val();
        if (mode === 'spoke') {
            $('.spoke-api-key-section').show();
        } else {
            $('.spoke-api-key-section').hide();
        }
    });
    
    // Initialize spoke API key section visibility
    $('#sourcehub_mode').trigger('change');

    // Copy spoke API key
    $('#copy-spoke-key').on('click', function() {
        var $input = $('#spoke-api-key');
        $input.select();
        document.execCommand('copy');
        
        var $button = $(this);
        var originalText = $button.text();
        $button.text('<?php echo esc_js(__('Copied!', 'sourcehub')); ?>');
        
        setTimeout(function() {
            $button.text(originalText);
        }, 2000);
    });
    
    // Regenerate spoke API key
    $('#regenerate-spoke-key').on('click', function() {
        if (!confirm('<?php echo esc_js(__('Are you sure you want to regenerate the API key? This will invalidate the current key and hub sites will need to be updated.', 'sourcehub')); ?>')) {
            return;
        }
        
        var $button = $(this);
        var originalText = $button.text();
        $button.text('<?php echo esc_js(__('Generating...', 'sourcehub')); ?>').prop('disabled', true);
        
        $.ajax({
            url: sourcehub_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'sourcehub_regenerate_spoke_key',
                nonce: sourcehub_admin.ajax_nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#spoke-api-key').val(response.data.api_key);
                    SourceHubAdmin.showNotice('success', response.data.message);
                } else {
                    SourceHubAdmin.showNotice('error', response.data.message);
                }
            },
            error: function() {
                SourceHubAdmin.showNotice('error', '<?php echo esc_js(__('Failed to regenerate API key', 'sourcehub')); ?>');
            },
            complete: function() {
                $button.text(originalText).prop('disabled', false);
            }
        });
    });

    // Show/hide API key
    $('#sourcehub_openai_api_key').after('<button type="button" class="button button-small" id="toggle-api-key" style="margin-left: 10px;">Show</button>');
    
    $('#toggle-api-key').on('click', function() {
        var $input = $('#sourcehub_openai_api_key');
        var $button = $(this);
        
        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $button.text('Hide');
        } else {
            $input.attr('type', 'password');
            $button.text('Show');
        }
    });
});
</script>
