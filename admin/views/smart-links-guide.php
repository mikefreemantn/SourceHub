<?php
/**
 * Smart Links Guide Admin View
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Smart Links Guide', 'sourcehub'); ?></h1>
    
    <div class="notice notice-info">
        <p><strong><?php _e('Smart Links', 'sourcehub'); ?></strong> <?php _e('allow you to create links that automatically adapt to each spoke site\'s URL structure when content is syndicated.', 'sourcehub'); ?></p>
    </div>

    <div class="sourcehub-guide-container">
        
        <!-- Gutenberg vs Classic Editor -->
        <div class="card">
            <h2><?php _e('Editor Support', 'sourcehub'); ?></h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Editor', 'sourcehub'); ?></th>
                        <th><?php _e('Method', 'sourcehub'); ?></th>
                        <th><?php _e('Status', 'sourcehub'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Gutenberg (Block Editor)</strong></td>
                        <td>Custom Smart Link blocks</td>
                        <td><span class="dashicons dashicons-yes-alt" style="color: green;"></span> Fully Supported</td>
                    </tr>
                    <tr>
                        <td><strong>Classic Editor</strong></td>
                        <td>Shortcodes (documented below)</td>
                        <td><span class="dashicons dashicons-yes-alt" style="color: green;"></span> Fully Supported</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php foreach ($documentation as $shortcode_name => $doc): ?>
        <div class="card">
            <h2><?php echo esc_html($doc['title']); ?></h2>
            <p><?php echo esc_html($doc['description']); ?></p>
            
            <h3><?php _e('Syntax', 'sourcehub'); ?></h3>
            <code style="background: #f1f1f1; padding: 10px; display: block; margin: 10px 0; border-radius: 4px;">
                <?php echo esc_html($doc['syntax']); ?>
            </code>
            
            <h3><?php _e('Attributes', 'sourcehub'); ?></h3>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Attribute', 'sourcehub'); ?></th>
                        <th><?php _e('Description', 'sourcehub'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doc['attributes'] as $attr => $description): ?>
                    <tr>
                        <td><code><?php echo esc_html($attr); ?></code></td>
                        <td><?php echo esc_html($description); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3><?php _e('Examples', 'sourcehub'); ?></h3>
            <?php foreach ($doc['examples'] as $example): ?>
            <div style="background: #f9f9f9; border-left: 4px solid #0073aa; padding: 10px; margin: 10px 0;">
                <code><?php echo esc_html($example); ?></code>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <!-- How It Works -->
        <div class="card">
            <h2><?php _e('How Smart Links Work', 'sourcehub'); ?></h2>
            <ol>
                <li><strong><?php _e('Create Content', 'sourcehub'); ?></strong> - <?php _e('Use shortcodes in your hub posts (Classic Editor) or Smart Link blocks (Gutenberg)', 'sourcehub'); ?></li>
                <li><strong><?php _e('Syndication Processing', 'sourcehub'); ?></strong> - <?php _e('SourceHub converts shortcodes to Smart Link spans before syndication', 'sourcehub'); ?></li>
                <li><strong><?php _e('URL Adaptation', 'sourcehub'); ?></strong> - <?php _e('Each spoke site receives links adapted to its URL structure', 'sourcehub'); ?></li>
                <li><strong><?php _e('Display', 'sourcehub'); ?></strong> - <?php _e('Visitors see working links that point to the correct pages on each spoke site', 'sourcehub'); ?></li>
            </ol>
        </div>

        <!-- Best Practices -->
        <div class="card">
            <h2><?php _e('Best Practices', 'sourcehub'); ?></h2>
            <ul>
                <li><strong><?php _e('Use descriptive link text', 'sourcehub'); ?></strong> - <?php _e('Make it clear what the link leads to', 'sourcehub'); ?></li>
                <li><strong><?php _e('Test your paths', 'sourcehub'); ?></strong> - <?php _e('Ensure the paths exist on all spoke sites', 'sourcehub'); ?></li>
                <li><strong><?php _e('Use relative paths', 'sourcehub'); ?></strong> - <?php _e('Start paths with "/" for consistency', 'sourcehub'); ?></li>
                <li><strong><?php _e('Custom Smart Links for special cases', 'sourcehub'); ?></strong> - <?php _e('Use when spoke sites have different URL structures', 'sourcehub'); ?></li>
            </ul>
        </div>

        <!-- Troubleshooting -->
        <div class="card">
            <h2><?php _e('Troubleshooting', 'sourcehub'); ?></h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Issue', 'sourcehub'); ?></th>
                        <th><?php _e('Solution', 'sourcehub'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php _e('Shortcode appears as text', 'sourcehub'); ?></td>
                        <td><?php _e('Check syntax - ensure attributes are properly quoted', 'sourcehub'); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Link points to wrong page', 'sourcehub'); ?></td>
                        <td><?php _e('Verify the path exists on the spoke site', 'sourcehub'); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Custom Smart Link not working', 'sourcehub'); ?></td>
                        <td><?php _e('Check JSON syntax and spoke connection names', 'sourcehub'); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Smart Link not processing', 'sourcehub'); ?></td>
                        <td><?php _e('Ensure SourceHub is active on both hub and spoke sites', 'sourcehub'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<style>
.sourcehub-guide-container .card {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.sourcehub-guide-container .card h2 {
    margin-top: 0;
    color: #23282d;
}

.sourcehub-guide-container .card h3 {
    color: #0073aa;
    margin-top: 20px;
}

.sourcehub-guide-container code {
    background: #f1f1f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
}

.sourcehub-guide-container .widefat th {
    background: #f9f9f9;
}
</style>
