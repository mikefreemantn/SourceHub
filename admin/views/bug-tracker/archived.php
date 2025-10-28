<?php
/**
 * Bug Tracker - Archived Bugs View
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get archived bugs
$archived_bugs = SourceHub_Bug_Tracker::get_bugs(array(
    'status' => 'archived',
    'include_archived' => true,
    'orderby' => 'updated_at',
    'order' => 'DESC'
));

$categories = SourceHub_Bug_Tracker::get_categories();
?>

<div style="margin-bottom: 20px;">
    <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs'); ?>" class="button">
        <?php echo esc_html__('← Back to All Bugs', 'sourcehub'); ?>
    </a>
</div>

<div style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 20px; margin-bottom: 20px;">
    <h2 style="margin-top: 0;">📦 <?php echo esc_html__('Archived Bugs', 'sourcehub'); ?></h2>
    <p style="color: #666;">
        <?php echo esc_html__('These bugs have been archived and removed from the main list. They are kept for historical records.', 'sourcehub'); ?>
    </p>
</div>

<!-- Archived Bugs Table -->
<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th style="width: 50px;"><?php echo esc_html__('ID', 'sourcehub'); ?></th>
            <th><?php echo esc_html__('Title', 'sourcehub'); ?></th>
            <th style="width: 100px;"><?php echo esc_html__('Priority', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Category', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Reporter', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Archived', 'sourcehub'); ?></th>
            <th style="width: 120px;"><?php echo esc_html__('Actions', 'sourcehub'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($archived_bugs)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <p style="color: #666; font-size: 16px;">
                        <?php echo esc_html__('No archived bugs.', 'sourcehub'); ?>
                    </p>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($archived_bugs as $bug): ?>
                <tr style="opacity: 0.7;">
                    <td><?php echo esc_html($bug->id); ?></td>
                    <td>
                        <strong>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs&action=view&bug_id=' . $bug->id); ?>">
                                <?php echo esc_html($bug->title); ?>
                            </a>
                        </strong>
                    </td>
                    <td><?php echo SourceHub_Bug_Tracker::get_priority_badge($bug->priority); ?></td>
                    <td><?php echo esc_html($categories[$bug->category] ?? $bug->category); ?></td>
                    <td>
                        <?php echo esc_html($bug->reporter_name); ?>
                        <?php if ($bug->reporter_email): ?>
                            <br><small style="color: #666;"><?php echo esc_html($bug->reporter_email); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo esc_html(date('M j, Y', strtotime($bug->updated_at))); ?>
                        <br><small style="color: #666;"><?php echo esc_html(date('g:i a', strtotime($bug->updated_at))); ?></small>
                    </td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs&action=view&bug_id=' . $bug->id); ?>" class="button button-small">
                            <?php echo esc_html__('View', 'sourcehub'); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if (!empty($archived_bugs)): ?>
    <div style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 4px;">
        <strong><?php echo esc_html__('Total Archived:', 'sourcehub'); ?></strong>
        <?php echo count($archived_bugs); ?> <?php echo esc_html__('bugs', 'sourcehub'); ?>
    </div>
<?php endif; ?>
