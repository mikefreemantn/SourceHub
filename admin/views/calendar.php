<?php
/**
 * Content Calendar View
 *
 * @package SourceHub
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap sourcehub-calendar-wrap">
    <h1 class="wp-heading-inline">
        <?php _e('Content Calendar', 'sourcehub'); ?>
        <span class="title-count theme-count"><?php echo count($spoke_connections); ?> <?php _e('Spoke Sites', 'sourcehub'); ?></span>
    </h1>

    <hr class="wp-header-end">

    <!-- Calendar Filters -->
    <div class="sourcehub-calendar-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label for="calendar-view-select"><?php _e('View:', 'sourcehub'); ?></label>
                <select id="calendar-view-select" class="calendar-filter">
                    <option value="dayGridMonth"><?php _e('Month', 'sourcehub'); ?></option>
                    <option value="listDay"><?php _e('List', 'sourcehub'); ?></option>
                </select>
            </div>

            <div class="filter-group" id="list-date-picker-group" style="display: none;">
                <label for="list-date-picker"><?php _e('List Date:', 'sourcehub'); ?></label>
                <input type="date" id="list-date-picker" class="calendar-filter" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="filter-group">
                <label for="post-status-filter"><?php _e('Status:', 'sourcehub'); ?></label>
                <select id="post-status-filter" class="calendar-filter" multiple>
                    <option value="publish" selected><?php _e('Published', 'sourcehub'); ?></option>
                    <option value="future" selected><?php _e('Scheduled', 'sourcehub'); ?></option>
                    <option value="draft" selected><?php _e('Draft', 'sourcehub'); ?></option>
                    <option value="pending"><?php _e('Pending Review', 'sourcehub'); ?></option>
                    <option value="private"><?php _e('Private', 'sourcehub'); ?></option>
                </select>
            </div>

            <div class="filter-group">
                <label for="post-type-filter"><?php _e('Post Type:', 'sourcehub'); ?></label>
                <select id="post-type-filter" class="calendar-filter" multiple>
                    <?php foreach ($post_types as $post_type): ?>
                        <option value="<?php echo esc_attr($post_type->name); ?>" <?php selected($post_type->name, 'post'); ?>>
                            <?php echo esc_html($post_type->label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="category-filter"><?php _e('Category:', 'sourcehub'); ?></label>
                <select id="category-filter" class="calendar-filter" multiple>
                    <option value=""><?php _e('All Categories', 'sourcehub'); ?></option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo esc_attr($category->term_id); ?>">
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="spoke-filter"><?php _e('Spoke Sites:', 'sourcehub'); ?></label>
                <select id="spoke-filter" class="calendar-filter" multiple>
                    <option value=""><?php _e('All Spoke Sites', 'sourcehub'); ?></option>
                    <?php foreach ($spoke_connections as $connection): ?>
                        <option value="<?php echo esc_attr($connection->id); ?>">
                            <?php echo esc_html($connection->name); ?>
                            <?php if ($connection->status !== 'active'): ?>
                                <span class="inactive">(<?php echo esc_html(ucfirst($connection->status)); ?>)</span>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-actions">
                <button type="button" id="apply-filters" class="button button-primary">
                    <?php _e('Apply Filters', 'sourcehub'); ?>
                </button>
                <button type="button" id="reset-filters" class="button">
                    <?php _e('Reset', 'sourcehub'); ?>
                </button>
                <button type="button" id="refresh-calendar" class="button">
                    <span class="dashicons dashicons-update"></span>
                    <?php _e('Refresh', 'sourcehub'); ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="sourcehub-calendar-legend">
        <div style="margin-bottom: 10px;">
            <strong><?php _e('Post Status:', 'sourcehub'); ?></strong>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #28a745;"></span>
            <span class="legend-label"><?php _e('Published', 'sourcehub'); ?></span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #007cba;"></span>
            <span class="legend-label"><?php _e('Scheduled', 'sourcehub'); ?></span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #6c757d;"></span>
            <span class="legend-label"><?php _e('Draft', 'sourcehub'); ?></span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #dc3545;"></span>
            <span class="legend-label"><?php _e('Private', 'sourcehub'); ?></span>
        </div>
        
        <div style="margin: 15px 0 10px 0; padding-top: 10px; border-top: 1px solid #ddd;">
            <strong><?php _e('Spoke Syndication:', 'sourcehub'); ?></strong>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #00a32a;"></span>
            <span class="legend-label">✓ <?php _e('Syndicated', 'sourcehub'); ?></span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #007cba;"></span>
            <span class="legend-label">📅 <?php _e('Will Sync on Publish', 'sourcehub'); ?></span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #dba617;"></span>
            <span class="legend-label">⏳ <?php _e('Pending Syndication', 'sourcehub'); ?></span>
        </div>
    </div>

    <!-- Debug Info (hidden by default, can be shown with ?debug=1) -->
    <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
    <div class="sourcehub-debug-info" style="background: #f0f0f1; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
        <h3><?php _e('Debug Information', 'sourcehub'); ?></h3>
        <p><strong><?php _e('Mode:', 'sourcehub'); ?></strong> <?php echo esc_html(sourcehub()->get_mode()); ?></p>
        <p><strong><?php _e('Spoke Connections:', 'sourcehub'); ?></strong> <?php echo count($spoke_connections); ?></p>
        <p><strong><?php _e('Categories:', 'sourcehub'); ?></strong> <?php echo count($categories); ?></p>
        <p><strong><?php _e('Post Types:', 'sourcehub'); ?></strong> <?php echo count($post_types); ?></p>
        
        <?php
        // Quick test query
        $test_posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => array('publish', 'future', 'draft'),
            'posts_per_page' => 5,
            'meta_query' => array(
                array(
                    'key' => '_sourcehub_selected_spokes',
                    'compare' => 'EXISTS'
                )
            )
        ));
        ?>
        <p><strong><?php _e('Posts with Syndication:', 'sourcehub'); ?></strong> <?php echo count($test_posts); ?></p>
        
        <?php if (!empty($test_posts)): ?>
            <details>
                <summary><?php _e('Sample Posts', 'sourcehub'); ?></summary>
                <ul>
                    <?php foreach ($test_posts as $test_post): ?>
                        <li>
                            <strong><?php echo esc_html($test_post->post_title); ?></strong> 
                            (<?php echo esc_html($test_post->post_status); ?>, <?php echo esc_html($test_post->post_date); ?>)
                            - Spokes: <?php 
                                $spokes = get_post_meta($test_post->ID, '_sourcehub_selected_spokes', true);
                                echo is_array($spokes) ? implode(', ', $spokes) : 'None';
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </details>
        <?php endif; ?>
        
        <button type="button" id="test-ajax" class="button button-secondary">
            <?php _e('Test AJAX Call', 'sourcehub'); ?>
        </button>
        <div id="ajax-result" style="margin-top: 10px;"></div>
    </div>
    <?php endif; ?>

    <!-- Calendar Container -->
    <div id="sourcehub-calendar" class="sourcehub-calendar-container">
        <div class="calendar-loading">
            <div class="spinner is-active"></div>
            <p><?php _e('Loading calendar...', 'sourcehub'); ?></p>
        </div>
        <div class="calendar-fallback" style="display: none;">
            <p><?php _e('Calendar could not be loaded. Please refresh the page or check your internet connection.', 'sourcehub'); ?></p>
            <button type="button" class="button button-primary" onclick="location.reload();">
                <?php _e('Refresh Page', 'sourcehub'); ?>
            </button>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div id="event-details-modal" class="sourcehub-modal" style="display: none;">
        <div class="sourcehub-modal-content">
            <div class="sourcehub-modal-header">
                <h2 id="modal-title"></h2>
                <button type="button" class="sourcehub-modal-close">&times;</button>
            </div>
            <div class="sourcehub-modal-body">
                <div class="event-details">
                    <div class="detail-row">
                        <strong><?php _e('Status:', 'sourcehub'); ?></strong>
                        <span id="modal-status" class="status-badge"></span>
                    </div>
                    <div class="detail-row">
                        <strong><?php _e('Author:', 'sourcehub'); ?></strong>
                        <span id="modal-author"></span>
                    </div>
                    <div class="detail-row">
                        <strong><?php _e('Categories:', 'sourcehub'); ?></strong>
                        <span id="modal-categories"></span>
                    </div>
                    <div class="detail-row">
                        <strong><?php _e('Publish Date:', 'sourcehub'); ?></strong>
                        <span id="modal-date"></span>
                    </div>
                    <div class="detail-row">
                        <strong><?php _e('Excerpt:', 'sourcehub'); ?></strong>
                        <p id="modal-excerpt"></p>
                    </div>
                    <div class="detail-row">
                        <strong><?php _e('Spoke Sites:', 'sourcehub'); ?></strong>
                        <div id="modal-spokes" class="spoke-list"></div>
                    </div>
                </div>
            </div>
            <div class="sourcehub-modal-footer">
                <a href="#" id="modal-edit-link" class="button button-primary">
                    <?php _e('Edit Post', 'sourcehub'); ?>
                </a>
                <button type="button" class="button sourcehub-modal-close">
                    <?php _e('Close', 'sourcehub'); ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="sourcehub-calendar-stats">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="total-posts">-</div>
                <div class="stat-label"><?php _e('Total Posts', 'sourcehub'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="scheduled-posts">-</div>
                <div class="stat-label"><?php _e('Scheduled', 'sourcehub'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="draft-posts">-</div>
                <div class="stat-label"><?php _e('Drafts', 'sourcehub'); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="syndicated-posts">-</div>
                <div class="stat-label"><?php _e('With Syndication', 'sourcehub'); ?></div>
            </div>
        </div>
    </div>
</div>
