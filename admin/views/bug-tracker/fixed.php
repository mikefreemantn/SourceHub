<?php
/**
 * Bug Tracker - Fixed Bugs View
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get fixed bugs (resolved and closed)
$filters = array(
    'orderby' => 'resolved_at',
    'order' => 'DESC'
);

$resolved_bugs = SourceHub_Bug_Tracker::get_bugs(array_merge($filters, array('status' => 'resolved')));
$closed_bugs = SourceHub_Bug_Tracker::get_bugs(array_merge($filters, array('status' => 'closed')));

$fixed_bugs = array_merge($resolved_bugs, $closed_bugs);

// Sort by resolved_at
usort($fixed_bugs, function($a, $b) {
    return strtotime($b->resolved_at) - strtotime($a->resolved_at);
});

$categories = SourceHub_Bug_Tracker::get_categories();
?>

<div style="margin-bottom: 20px;">
    <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs'); ?>" class="button">
        <?php echo esc_html__('← Back to All Bugs', 'sourcehub'); ?>
    </a>
</div>

<div style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 20px; margin-bottom: 20px;">
    <h2 style="margin-top: 0;"><?php echo esc_html__('Fixed Bugs Archive', 'sourcehub'); ?></h2>
    <p style="color: #666;">
        <?php echo esc_html__('This is a historical record of all bugs that have been resolved or closed.', 'sourcehub'); ?>
    </p>
</div>

<!-- Fixed Bugs Table -->
<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th style="width: 50px;"><?php echo esc_html__('ID', 'sourcehub'); ?></th>
            <th><?php echo esc_html__('Title', 'sourcehub'); ?></th>
            <th style="width: 120px;"><?php echo esc_html__('Status', 'sourcehub'); ?></th>
            <th style="width: 100px;"><?php echo esc_html__('Priority', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Category', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Resolved', 'sourcehub'); ?></th>
            <th style="width: 100px;"><?php echo esc_html__('Version', 'sourcehub'); ?></th>
            <th style="width: 120px;"><?php echo esc_html__('Actions', 'sourcehub'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($fixed_bugs)): ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <p style="color: #666; font-size: 16px;">
                        <?php echo esc_html__('No fixed bugs yet.', 'sourcehub'); ?>
                    </p>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($fixed_bugs as $bug): ?>
                <tr>
                    <td><?php echo esc_html($bug->id); ?></td>
                    <td>
                        <strong>
                            <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs&action=view&bug_id=' . $bug->id); ?>">
                                <?php echo esc_html($bug->title); ?>
                            </a>
                        </strong>
                    </td>
                    <td><?php echo SourceHub_Bug_Tracker::get_status_badge($bug->status); ?></td>
                    <td><?php echo SourceHub_Bug_Tracker::get_priority_badge($bug->priority); ?></td>
                    <td><?php echo esc_html($categories[$bug->category] ?? $bug->category); ?></td>
                    <td>
                        <?php if ($bug->resolved_at): ?>
                            <?php echo esc_html(date('M j, Y', strtotime($bug->resolved_at))); ?>
                            <br><small style="color: #666;"><?php echo esc_html(date('g:i a', strtotime($bug->resolved_at))); ?></small>
                        <?php else: ?>
                            <span style="color: #999;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($bug->resolved_version): ?>
                            <span style="color: #666;">v<?php echo esc_html($bug->resolved_version); ?></span>
                        <?php else: ?>
                            <span style="color: #999;">—</span>
                        <?php endif; ?>
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

<?php if (!empty($fixed_bugs)): ?>
    <div style="margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 4px;">
        <strong><?php echo esc_html__('Total Fixed:', 'sourcehub'); ?></strong>
        <?php echo count($fixed_bugs); ?> <?php echo esc_html__('bugs', 'sourcehub'); ?>
    </div>
<?php endif; ?>
