<?php
/**
 * Bug Tracker Admin View
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current action
$action = $_GET['action'] ?? 'list';
$bug_id = isset($_GET['bug_id']) ? intval($_GET['bug_id']) : 0;

// Get stats for dashboard
$stats = SourceHub_Bug_Tracker::get_stats();
?>

<div class="wrap sourcehub-admin">
    <h1>
        <?php echo esc_html__('Bug Tracker', 'sourcehub'); ?>
        <?php if ($action === 'list'): ?>
            <a href="<?php echo admin_url('admin.php?page=sourcehub-bugs&action=submit'); ?>" class="page-title-action">
                <?php echo esc_html__('Submit Bug', 'sourcehub'); ?>
            </a>
        <?php endif; ?>
    </h1>

    <?php
    // Show action-specific view
    switch ($action) {
        case 'submit':
            include __DIR__ . '/bug-tracker/submit.php';
            break;
        case 'view':
            include __DIR__ . '/bug-tracker/view.php';
            break;
        case 'fixed':
            include __DIR__ . '/bug-tracker/fixed.php';
            break;
        case 'archived':
            include __DIR__ . '/bug-tracker/archived.php';
            break;
        case 'list':
        default:
            include __DIR__ . '/bug-tracker/list.php';
            break;
    }
    ?>
</div>

<style>
.sourcehub-bug-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.sourcehub-stat-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    text-align: center;
}

.sourcehub-stat-card h3 {
    margin: 0 0 10px 0;
    font-size: 14px;
    color: #666;
    text-transform: uppercase;
    font-weight: 600;
}

.sourcehub-stat-card .stat-number {
    font-size: 36px;
    font-weight: bold;
    color: #2271b1;
}

.sourcehub-bug-form {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    max-width: 800px;
}

.sourcehub-bug-form .form-field {
    margin-bottom: 20px;
}

.sourcehub-bug-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.sourcehub-bug-form input[type="text"],
.sourcehub-bug-form input[type="email"],
.sourcehub-bug-form input[type="url"],
.sourcehub-bug-form select,
.sourcehub-bug-form textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.sourcehub-bug-form textarea {
    min-height: 150px;
    font-family: monospace;
}

.sourcehub-bug-detail {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
}

.sourcehub-bug-header {
    border-bottom: 1px solid #ddd;
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.sourcehub-bug-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 20px 0;
    padding: 15px;
    background: #f5f5f5;
    border-radius: 4px;
}

.sourcehub-bug-meta-item {
    display: flex;
    flex-direction: column;
}

.sourcehub-bug-meta-item label {
    font-size: 11px;
    color: #666;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 5px;
}

.sourcehub-bug-notes {
    margin-top: 30px;
    border-top: 1px solid #ddd;
    padding-top: 20px;
}

.sourcehub-note {
    background: #f9f9f9;
    border-left: 3px solid #2271b1;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.sourcehub-note-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 12px;
    color: #666;
}

.sourcehub-note-author {
    font-weight: 600;
}

.sourcehub-note-content {
    color: #333;
}

.sourcehub-filters {
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.sourcehub-filters select,
.sourcehub-filters input[type="text"] {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.sourcehub-filters .button {
    margin-left: auto;
}

.sourcehub-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    color: white;
}
</style>
