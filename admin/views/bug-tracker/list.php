<?php
/**
 * Bug Tracker - List View
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get filters from request
$status_filter = $_GET['status_filter'] ?? '';
$priority_filter = $_GET['priority_filter'] ?? '';
$category_filter = $_GET['category_filter'] ?? '';
$search = $_GET['search'] ?? '';

// Build filters array
$filters = array();
if ($status_filter) $filters['status'] = $status_filter;
if ($priority_filter) $filters['priority'] = $priority_filter;
if ($category_filter) $filters['category'] = $category_filter;
if ($search) $filters['search'] = $search;

// Get bugs
$bugs = SourceHub_Bug_Tracker::get_bugs($filters);
$categories = SourceHub_Bug_Tracker::get_categories();
?>

<?php if (isset($_GET['archived'])): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo esc_html__('Bug archived successfully! It has been removed from the main list.', 'sourcehub'); ?></p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['unarchived'])): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo esc_html__('Bug unarchived successfully! It has been restored to the main list.', 'sourcehub'); ?></p>
    </div>
<?php endif; ?>

<!-- Stats Dashboard -->
<div class="sourcehub-bug-stats">
    <div class="sourcehub-stat-card">
        <h3><?php echo esc_html__('Total Bugs', 'sourcehub'); ?></h3>
        <div class="stat-number"><?php echo esc_html($stats['total']); ?></div>
    </div>
    <div class="sourcehub-stat-card">
        <h3><?php echo esc_html__('Open', 'sourcehub'); ?></h3>
        <div class="stat-number" style="color: #dc3545;"><?php echo esc_html($stats['open']); ?></div>
    </div>
    <div class="sourcehub-stat-card">
        <h3><?php echo esc_html__('In Progress', 'sourcehub'); ?></h3>
        <div class="stat-number" style="color: #ffc107;"><?php echo esc_html($stats['in_progress']); ?></div>
    </div>
    <div class="sourcehub-stat-card">
        <h3><?php echo esc_html__('Resolved', 'sourcehub'); ?></h3>
        <div class="stat-number" style="color: #28a745;"><?php echo esc_html($stats['resolved']); ?></div>
    </div>
</div>

<!-- Filters -->
<div class="sourcehub-filters">
    <select id="status-filter" name="status_filter">
        <option value=""><?php echo esc_html__('All Statuses', 'sourcehub'); ?></option>
        <option value="open" <?php selected($status_filter, 'open'); ?>><?php echo esc_html__('Open', 'sourcehub'); ?></option>
        <option value="in_progress" <?php selected($status_filter, 'in_progress'); ?>><?php echo esc_html__('In Progress', 'sourcehub'); ?></option>
        <option value="resolved" <?php selected($status_filter, 'resolved'); ?>><?php echo esc_html__('Resolved', 'sourcehub'); ?></option>
        <option value="closed" <?php selected($status_filter, 'closed'); ?>><?php echo esc_html__('Closed', 'sourcehub'); ?></option>
    </select>

    <select id="priority-filter" name="priority_filter">
        <option value=""><?php echo esc_html__('All Priorities', 'sourcehub'); ?></option>
        <option value="critical" <?php selected($priority_filter, 'critical'); ?>><?php echo esc_html__('Critical', 'sourcehub'); ?></option>
        <option value="high" <?php selected($priority_filter, 'high'); ?>><?php echo esc_html__('High', 'sourcehub'); ?></option>
        <option value="medium" <?php selected($priority_filter, 'medium'); ?>><?php echo esc_html__('Medium', 'sourcehub'); ?></option>
        <option value="low" <?php selected($priority_filter, 'low'); ?>><?php echo esc_html__('Low', 'sourcehub'); ?></option>
    </select>

    <select id="category-filter" name="category_filter">
        <option value=""><?php echo esc_html__('All Categories', 'sourcehub'); ?></option>
        <?php foreach ($categories as $key => $label): ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($category_filter, $key); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" id="search-bugs" name="search" placeholder="<?php echo esc_attr__('Search bugs...', 'sourcehub'); ?>" value="<?php echo esc_attr($search); ?>">

    <button type="button" class="button" onclick="applyBugFilters()">
        <?php echo esc_html__('Filter', 'sourcehub'); ?>
    </button>

    <?php if (!empty($filters)): ?>
        <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs'); ?>" class="button">
            <?php echo esc_html__('Clear Filters', 'sourcehub'); ?>
        </a>
    <?php endif; ?>

    <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs&action=fixed'); ?>" class="button" style="margin-left: auto;">
        <?php echo esc_html__('View Fixed Bugs', 'sourcehub'); ?>
    </a>
    
    <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs&action=archived'); ?>" class="button">
        <?php echo esc_html__('View Archived', 'sourcehub'); ?>
    </a>
</div>

<!-- Bugs Table -->
<table class="wp-list-table widefat fixed striped">
    <thead>
        <tr>
            <th style="width: 50px;"><?php echo esc_html__('ID', 'sourcehub'); ?></th>
            <th><?php echo esc_html__('Title', 'sourcehub'); ?></th>
            <th style="width: 120px;"><?php echo esc_html__('Status', 'sourcehub'); ?></th>
            <th style="width: 100px;"><?php echo esc_html__('Priority', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Category', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Reporter', 'sourcehub'); ?></th>
            <th style="width: 150px;"><?php echo esc_html__('Created', 'sourcehub'); ?></th>
            <th style="width: 120px;"><?php echo esc_html__('Actions', 'sourcehub'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($bugs)): ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <p style="color: #666; font-size: 16px;">
                        <?php echo esc_html__('No bugs found.', 'sourcehub'); ?>
                    </p>
                    <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs&action=submit'); ?>" class="button button-primary">
                        <?php echo esc_html__('Submit Your First Bug', 'sourcehub'); ?>
                    </a>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($bugs as $bug): ?>
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
                        <?php echo esc_html($bug->reporter_name); ?>
                        <?php if ($bug->reporter_email): ?>
                            <br><small style="color: #666;"><?php echo esc_html($bug->reporter_email); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo esc_html(date('M j, Y', strtotime($bug->created_at))); ?>
                        <br><small style="color: #666;"><?php echo esc_html(date('g:i a', strtotime($bug->created_at))); ?></small>
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

<script>
function applyBugFilters() {
    const status = document.getElementById('status-filter').value;
    const priority = document.getElementById('priority-filter').value;
    const category = document.getElementById('category-filter').value;
    const search = document.getElementById('search-bugs').value;
    
    let url = '<?php echo admin_url('admin.php?page=sourcehub-bugs'); ?>';
    const params = [];
    
    if (status) params.push('status_filter=' + status);
    if (priority) params.push('priority_filter=' + priority);
    if (category) params.push('category_filter=' + category);
    if (search) params.push('search=' + encodeURIComponent(search));
    
    if (params.length > 0) {
        url += '&' + params.join('&');
    }
    
    window.location.href = url;
}

// Allow Enter key to trigger filter
document.getElementById('search-bugs').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyBugFilters();
    }
});
</script>
