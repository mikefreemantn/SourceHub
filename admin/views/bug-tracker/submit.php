<?php
/**
 * Bug Tracker - Submit Bug Form
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$categories = SourceHub_Bug_Tracker::get_categories();
$current_user = wp_get_current_user();
?>

<div class="sourcehub-bug-form">
    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=sourcehub-bugs&action=submit')); ?>" enctype="multipart/form-data">
        <?php wp_nonce_field('submit_bug', 'sourcehub_bug_nonce'); ?>

        <div class="form-field">
            <label for="title">
                <?php echo esc_html__('Bug Title', 'sourcehub'); ?> <span style="color: red;">*</span>
            </label>
            <input type="text" id="title" name="title" required placeholder="<?php echo esc_attr__('Brief description of the issue', 'sourcehub'); ?>">
            <p class="description"><?php echo esc_html__('A short, descriptive title for the bug', 'sourcehub'); ?></p>
        </div>

        <div class="form-field">
            <label for="description">
                <?php echo esc_html__('Description', 'sourcehub'); ?> <span style="color: red;">*</span>
            </label>
            <textarea id="description" name="description" required placeholder="<?php echo esc_attr__('Detailed description of the bug...', 'sourcehub'); ?>"></textarea>
            <p class="description">
                <?php echo esc_html__('Please include:', 'sourcehub'); ?>
            </p>
            <ul style="margin-left: 20px; color: #666;">
                <li><?php echo esc_html__('Steps to reproduce the issue', 'sourcehub'); ?></li>
                <li><?php echo esc_html__('Expected behavior', 'sourcehub'); ?></li>
                <li><?php echo esc_html__('Actual behavior', 'sourcehub'); ?></li>
                <li><?php echo esc_html__('Any error messages', 'sourcehub'); ?></li>
            </ul>
        </div>

        <div class="form-field">
            <label for="screenshots">
                <?php echo esc_html__('Screenshots', 'sourcehub'); ?>
            </label>
            <input type="file" id="screenshots" name="screenshots[]" multiple accept="image/*" style="margin-bottom: 10px;">
            <p class="description">
                <strong><?php echo esc_html__('💡 Tip:', 'sourcehub'); ?></strong> 
                <?php echo esc_html__('You can select multiple images at once. Hold Ctrl (Windows) or Cmd (Mac) while clicking to select multiple files.', 'sourcehub'); ?>
            </p>
            <div id="screenshot-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-top: 10px;"></div>
        </div>

        <script>
        document.getElementById('screenshots').addEventListener('change', function(e) {
            const preview = document.getElementById('screenshot-preview');
            preview.innerHTML = '';
            
            const files = e.target.files;
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.cssText = 'border: 2px solid #ddd; border-radius: 4px; padding: 5px; text-align: center;';
                        div.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: auto; display: block; margin-bottom: 5px;"><small style="color: #666;">' + file.name + '</small>';
                        preview.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                }
            }
        });
        </script>

        <div class="form-field">
            <label for="category">
                <?php echo esc_html__('Category', 'sourcehub'); ?> <span style="color: red;">*</span>
            </label>
            <select id="category" name="category" required>
                <option value=""><?php echo esc_html__('Select a category', 'sourcehub'); ?></option>
                <?php foreach ($categories as $key => $label): ?>
                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php echo esc_html__('Which area of the plugin is affected?', 'sourcehub'); ?></p>
        </div>

        <div class="form-field">
            <label for="priority">
                <?php echo esc_html__('Priority', 'sourcehub'); ?> <span style="color: red;">*</span>
            </label>
            <select id="priority" name="priority" required>
                <option value="low"><?php echo esc_html__('Low - Minor issue, workaround available', 'sourcehub'); ?></option>
                <option value="medium" selected><?php echo esc_html__('Medium - Affects functionality but not critical', 'sourcehub'); ?></option>
                <option value="high"><?php echo esc_html__('High - Major functionality broken', 'sourcehub'); ?></option>
                <option value="critical"><?php echo esc_html__('Critical - Site breaking, no workaround', 'sourcehub'); ?></option>
            </select>
            <p class="description"><?php echo esc_html__('How severe is this issue?', 'sourcehub'); ?></p>
        </div>

        <div class="form-field">
            <label for="reporter_name">
                <?php echo esc_html__('Your Name', 'sourcehub'); ?> <span style="color: red;">*</span>
            </label>
            <input type="text" id="reporter_name" name="reporter_name" required value="<?php echo esc_attr($current_user->display_name); ?>">
        </div>

        <div class="form-field">
            <label for="reporter_email">
                <?php echo esc_html__('Your Email', 'sourcehub'); ?> <span style="color: red;">*</span>
            </label>
            <input type="email" id="reporter_email" name="reporter_email" required value="<?php echo esc_attr($current_user->user_email); ?>">
            <p class="description"><?php echo esc_html__('We may contact you for more information', 'sourcehub'); ?></p>
        </div>

        <div class="form-field">
            <label for="reporter_url">
                <?php echo esc_html__('Your Site URL', 'sourcehub'); ?>
            </label>
            <input type="url" id="reporter_url" name="reporter_url" value="<?php echo esc_attr(get_site_url()); ?>" placeholder="https://example.com">
            <p class="description"><?php echo esc_html__('Optional - helps us understand your setup', 'sourcehub'); ?></p>
        </div>

        <div style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin: 20px 0;">
            <h4 style="margin-top: 0;"><?php echo esc_html__('System Information', 'sourcehub'); ?></h4>
            <p style="margin: 5px 0; color: #666;">
                <strong><?php echo esc_html__('Plugin Version:', 'sourcehub'); ?></strong> <?php echo esc_html(SOURCEHUB_VERSION); ?>
            </p>
            <p style="margin: 5px 0; color: #666;">
                <strong><?php echo esc_html__('WordPress Version:', 'sourcehub'); ?></strong> <?php echo esc_html(get_bloginfo('version')); ?>
            </p>
            <p style="margin: 5px 0; color: #666;">
                <strong><?php echo esc_html__('PHP Version:', 'sourcehub'); ?></strong> <?php echo esc_html(PHP_VERSION); ?>
            </p>
            <p style="margin: 5px 0; color: #999; font-size: 12px;">
                <?php echo esc_html__('This information will be automatically included with your bug report', 'sourcehub'); ?>
            </p>
        </div>

        <div class="form-field">
            <button type="submit" name="submit_bug" class="button button-primary button-large">
                <?php echo esc_html__('Submit Bug Report', 'sourcehub'); ?>
            </button>
            <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs'); ?>" class="button button-large">
                <?php echo esc_html__('Cancel', 'sourcehub'); ?>
            </a>
        </div>
    </form>
</div>
