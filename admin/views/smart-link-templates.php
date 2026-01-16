<?php
/**
 * Smart Link Templates Admin Page
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get existing templates
$templates = get_option('sourcehub_smart_link_templates', array());
?>

<div class="wrap">
    <h1><?php _e('Smart Link Templates', 'sourcehub'); ?></h1>
    <p><?php _e('Create reusable Smart Link templates that can be quickly inserted when creating Smart Links in your content.', 'sourcehub'); ?></p>

    <div class="sourcehub-smart-link-templates-container">
        <!-- Add New Template Form -->
        <div class="sourcehub-template-form-card">
            <h2><?php _e('Add New Template', 'sourcehub'); ?></h2>
            <form id="sourcehub-add-template-form">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="template-link-text"><?php _e('Link Text', 'sourcehub'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="template-link-text" 
                                   name="link_text" 
                                   class="regular-text" 
                                   placeholder="<?php esc_attr_e('e.g., Contact Us', 'sourcehub'); ?>" 
                                   required>
                            <p class="description"><?php _e('The text that will appear in the dropdown and as the link text.', 'sourcehub'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="template-path"><?php _e('Path', 'sourcehub'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="template-path" 
                                   name="path" 
                                   class="regular-text" 
                                   placeholder="<?php esc_attr_e('e.g., /contact', 'sourcehub'); ?>" 
                                   required>
                            <p class="description"><?php _e('The URL path that will be appended to each spoke site URL.', 'sourcehub'); ?></p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php _e('Add Template', 'sourcehub'); ?>
                    </button>
                </p>
            </form>
        </div>

        <!-- Existing Templates List -->
        <div class="sourcehub-templates-list-card">
            <h2><?php _e('Saved Templates', 'sourcehub'); ?></h2>
            
            <?php if (empty($templates)): ?>
                <p class="no-templates-message"><?php _e('No templates created yet. Add your first template above!', 'sourcehub'); ?></p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Link Text', 'sourcehub'); ?></th>
                            <th><?php _e('Path', 'sourcehub'); ?></th>
                            <th><?php _e('Created', 'sourcehub'); ?></th>
                            <th><?php _e('Actions', 'sourcehub'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="templates-list">
                        <?php foreach ($templates as $template_id => $template): ?>
                            <tr data-template-id="<?php echo esc_attr($template_id); ?>">
                                <td><strong><?php echo esc_html($template['link_text']); ?></strong></td>
                                <td><code><?php echo esc_html($template['path']); ?></code></td>
                                <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($template['created_at']))); ?></td>
                                <td>
                                    <button type="button" 
                                            class="button button-small delete-template-btn" 
                                            data-template-id="<?php echo esc_attr($template_id); ?>">
                                        <?php _e('Delete', 'sourcehub'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.sourcehub-smart-link-templates-container {
    max-width: 1200px;
    margin-top: 20px;
}

.sourcehub-template-form-card,
.sourcehub-templates-list-card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    padding: 20px;
    margin-bottom: 20px;
}

.sourcehub-template-form-card h2,
.sourcehub-templates-list-card h2 {
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

.no-templates-message {
    padding: 20px;
    text-align: center;
    color: #666;
    font-style: italic;
}

#templates-list code {
    background: #f0f0f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 13px;
}

.delete-template-btn {
    color: #b32d2e;
}

.delete-template-btn:hover {
    color: #dc3232;
    border-color: #dc3232;
}
</style>

<script>
jQuery(document).ready(function($) {
    var nonce = '<?php echo wp_create_nonce('sourcehub_smart_link_template'); ?>';

    // Add new template
    $('#sourcehub-add-template-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var linkText = $('#template-link-text').val().trim();
        var path = $('#template-path').val().trim();
        
        if (!linkText || !path) {
            alert('<?php _e('Please fill in both fields.', 'sourcehub'); ?>');
            return;
        }
        
        $submitBtn.prop('disabled', true).text('<?php _e('Adding...', 'sourcehub'); ?>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sourcehub_save_smart_link_template',
                nonce: nonce,
                link_text: linkText,
                path: path
            },
            success: function(response) {
                if (response.success) {
                    // Reload page to show new template
                    location.reload();
                } else {
                    alert('<?php _e('Error:', 'sourcehub'); ?> ' + response.data.message);
                    $submitBtn.prop('disabled', false).text('<?php _e('Add Template', 'sourcehub'); ?>');
                }
            },
            error: function() {
                alert('<?php _e('Connection error. Please try again.', 'sourcehub'); ?>');
                $submitBtn.prop('disabled', false).text('<?php _e('Add Template', 'sourcehub'); ?>');
            }
        });
    });
    
    // Delete template
    $('.delete-template-btn').on('click', function() {
        if (!confirm('<?php _e('Are you sure you want to delete this template?', 'sourcehub'); ?>')) {
            return;
        }
        
        var $btn = $(this);
        var templateId = $btn.data('template-id');
        var $row = $btn.closest('tr');
        
        $btn.prop('disabled', true).text('<?php _e('Deleting...', 'sourcehub'); ?>');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'sourcehub_delete_smart_link_template',
                nonce: nonce,
                template_id: templateId
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if table is now empty
                        if ($('#templates-list tr').length === 0) {
                            location.reload();
                        }
                    });
                } else {
                    alert('<?php _e('Error:', 'sourcehub'); ?> ' + response.data.message);
                    $btn.prop('disabled', false).text('<?php _e('Delete', 'sourcehub'); ?>');
                }
            },
            error: function() {
                alert('<?php _e('Connection error. Please try again.', 'sourcehub'); ?>');
                $btn.prop('disabled', false).text('<?php _e('Delete', 'sourcehub'); ?>');
            }
        });
    });
});
</script>
