<?php
/**
 * Add Spoke Site view template
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
        <?php echo __('Add Spoke Site', 'sourcehub'); ?>
        <span class="mode-badge mode-hub">
            <?php echo esc_html('Hub'); ?> Mode
        </span>
    </h1>
    
    <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="page-title-action">
        <?php echo __('Back to Connections', 'sourcehub'); ?>
    </a>

    <div class="sourcehub-add-spoke">
        <div class="card">
            <form id="add-spoke-form" class="sourcehub-form" method="post">
                <?php wp_nonce_field('sourcehub_add_spoke', 'sourcehub_nonce'); ?>
                
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
                            <?php echo __('Enter the spoke site\'s API key (found in their SourceHub settings when in Spoke mode)', 'sourcehub'); ?>
                        </div>
                    </div>
                </div>

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
                            <input type="checkbox" name="ai_enabled" id="ai_enabled">
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

                <div class="form-actions">
                    <button type="submit" class="button button-primary">
                        <?php echo __('Add Spoke Site', 'sourcehub'); ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>" class="button">
                        <?php echo __('Cancel', 'sourcehub'); ?>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Show/hide AI settings based on checkbox
    $('#ai_enabled').on('change', function() {
        if (this.checked) {
            $('.ai-settings').show();
        } else {
            $('.ai-settings').hide();
        }
    });
    
    // Handle form submission
    $('#add-spoke-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $button = $form.find('button[type="submit"]');
        var originalText = $button.text();
        
        $button.text('<?php echo esc_js(__('Adding...', 'sourcehub')); ?>').prop('disabled', true);
        
        // Collect form data
        var formData = {
            name: $('#connection_name').val(),
            url: $('#connection_url').val(),
            api_key: $('#connection_api_key').val(),
            mode: 'spoke'
        };
        
        // Collect sync settings
        var syncSettings = {
            auto_publish: $('input[name="sync_auto_publish"]').is(':checked'),
            featured_image: $('input[name="sync_featured_image"]').is(':checked'),
            categories: $('input[name="sync_categories"]').is(':checked'),
            tags: $('input[name="sync_tags"]').is(':checked')
        };
        
        // Collect AI settings
        var aiEnabled = $('input[name="ai_enabled"]').is(':checked');
        var aiSettings = {
            enabled: aiEnabled
        };
        
        if (aiEnabled) {
            aiSettings.rewrite_title = $('input[name="ai_rewrite_title"]').is(':checked');
            aiSettings.rewrite_content = $('input[name="ai_rewrite_content"]').is(':checked');
            aiSettings.rewrite_excerpt = $('input[name="ai_rewrite_excerpt"]').is(':checked');
            aiSettings.tone = $('#ai_tone').val();
            aiSettings.target_audience = $('#ai_target_audience').val();
            aiSettings.regional_focus = $('#ai_regional_focus').val();
        }
        
        formData.sync_settings = syncSettings;
        formData.ai_settings = aiSettings;
        
        // Send AJAX request
        $.ajax({
            url: sourcehub_admin.rest_url + 'connections',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', sourcehub_admin.nonce);
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    var notice = '<div class="notice notice-success is-dismissible"><p>' + response.message + '</p></div>';
                    $('.wrap > h1').after(notice);
                    
                    // Redirect to connections page after a delay
                    setTimeout(function() {
                        window.location.href = '<?php echo admin_url('admin.php?page=sourcehub-connections'); ?>';
                    }, 1000);
                } else {
                    // Show error message
                    var notice = '<div class="notice notice-error is-dismissible"><p>' + (response.message || '<?php echo esc_js(__('An error occurred', 'sourcehub')); ?>') + '</p></div>';
                    $('.wrap > h1').after(notice);
                    $button.text(originalText).prop('disabled', false);
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : '<?php echo esc_js(__('An error occurred', 'sourcehub')); ?>';
                
                var notice = '<div class="notice notice-error is-dismissible"><p>' + message + '</p></div>';
                $('.wrap > h1').after(notice);
                $button.text(originalText).prop('disabled', false);
            }
        });
    });
});
</script>
