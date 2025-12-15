# SourceHub Publication Storage Reference

## Overview
This document explains how SourceHub stores publication (spoke site) information in the WordPress database. Use this reference when building external tools that need to integrate with SourceHub's publication system.

---

## Database Structure

### Table: `wp_sourcehub_connections`
Publications (spoke sites) are stored in the `wp_sourcehub_connections` table.

**Table Schema:**
```sql
CREATE TABLE wp_sourcehub_connections (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,              -- Publication display name (e.g., "Pub 1")
    url varchar(255) NOT NULL,               -- Full site URL
    api_key varchar(255) NOT NULL,           -- API authentication key
    mode enum('hub','spoke') NOT NULL DEFAULT 'spoke',
    status enum('active','inactive','error') NOT NULL DEFAULT 'active',
    last_sync datetime DEFAULT NULL,
    sync_settings longtext,                  -- JSON encoded settings
    ai_settings longtext,                    -- JSON encoded AI settings
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY url (url)
)
```

---

## Key Fields

### `id` (mediumint)
- **Primary identifier** for each publication
- Auto-incrementing integer
- Use this ID to reference publications in your tool

### `name` (varchar 255)
- **Human-readable publication name** (e.g., "Pub 1", "Chicago Tribune", "Daily News")
- This is what users see in the UI
- Searchable field for your tool

### `url` (varchar 255)
- Full WordPress site URL (e.g., "https://pub1.example.com")
- Unique constraint - no duplicate URLs allowed
- Does not include trailing slash

### `mode` (enum)
- Values: `'hub'` or `'spoke'`
- For publications, this will always be `'spoke'`
- Hub site itself can have mode = `'hub'`

### `status` (enum)
- Values: `'active'`, `'inactive'`, `'error'`
- Only `'active'` publications receive syndicated content
- Your tool should filter by status if needed

---

## How Posts Reference Publications

### Post Meta: `_sourcehub_selected_spokes`
Each post stores which publications it's assigned to using WordPress post meta.

**Meta Key:** `_sourcehub_selected_spokes`
**Value:** Serialized PHP array of connection IDs

**Example:**
```php
// Post ID 123 is assigned to publications with IDs 1, 3, and 5
get_post_meta(123, '_sourcehub_selected_spokes', true);
// Returns: array(1, 3, 5)
```

### Post Meta: `_sourcehub_syndicated_spokes`
Tracks which publications have already received the post.

**Meta Key:** `_sourcehub_syndicated_spokes`
**Value:** Serialized PHP array of connection IDs

---

## Database Queries for Your Tool

### Get All Publications
```php
global $wpdb;
$table = $wpdb->prefix . 'sourcehub_connections';

// Get all active spoke publications
$publications = $wpdb->get_results("
    SELECT id, name, url, status, last_sync
    FROM $table
    WHERE mode = 'spoke'
    AND status = 'active'
    ORDER BY name ASC
");
```

### Search Publications by Name
```php
global $wpdb;
$table = $wpdb->prefix . 'sourcehub_connections';
$search_term = 'Pub 1';

// Find publication by name
$publication = $wpdb->get_row($wpdb->prepare("
    SELECT id, name, url, status
    FROM $table
    WHERE name = %s
    AND mode = 'spoke'
", $search_term));

// Access the ID
$publication_id = $publication->id;
```

### Get Publication by ID
```php
global $wpdb;
$table = $wpdb->prefix . 'sourcehub_connections';
$publication_id = 1;

$publication = $wpdb->get_row($wpdb->prepare("
    SELECT *
    FROM $table
    WHERE id = %d
", $publication_id));
```

### Get All Posts Assigned to a Publication
```php
global $wpdb;
$publication_id = 1;

// Query posts that have this publication in their selected spokes
$posts = $wpdb->get_results($wpdb->prepare("
    SELECT p.ID, p.post_title, p.post_status, pm.meta_value as selected_spokes
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE pm.meta_key = '_sourcehub_selected_spokes'
    AND p.post_type = 'post'
    AND p.post_status = 'publish'
"));

// Filter posts that include this publication ID
$filtered_posts = array_filter($posts, function($post) use ($publication_id) {
    $selected_spokes = maybe_unserialize($post->selected_spokes);
    return is_array($selected_spokes) && in_array($publication_id, $selected_spokes);
});
```

---

## Using SourceHub's Built-in Methods

SourceHub provides database helper methods in `SourceHub_Database` class:

### Get All Connections
```php
// Get all active spoke connections
$publications = SourceHub_Database::get_connections(array(
    'mode' => 'spoke',
    'status' => 'active'
));

// Returns array of objects with all fields
foreach ($publications as $pub) {
    echo $pub->id . ': ' . $pub->name;
}
```

### Get Single Connection by ID
```php
$publication = SourceHub_Database::get_connection($publication_id);
echo $publication->name; // "Pub 1"
```

### Get Connection by URL
```php
$publication = SourceHub_Database::get_connection_by_url('https://pub1.example.com');
echo $publication->id;
```

---

## Integration Workflow Example

### Scenario: Your tool needs to find "Pub 1" and reference it

```php
// Step 1: Search for publication by name
global $wpdb;
$table = $wpdb->prefix . 'sourcehub_connections';
$search_name = 'Pub 1';

$publication = $wpdb->get_row($wpdb->prepare("
    SELECT id, name, url, status
    FROM $table
    WHERE name = %s
    AND mode = 'spoke'
    AND status = 'active'
", $search_name));

if ($publication) {
    // Step 2: Store the ID for later use
    $pub_id = $publication->id;
    
    // Step 3: Use this ID to assign posts, query data, etc.
    // For example, assign a post to this publication:
    $post_id = 123;
    $current_spokes = get_post_meta($post_id, '_sourcehub_selected_spokes', true);
    if (!is_array($current_spokes)) {
        $current_spokes = array();
    }
    
    // Add this publication if not already assigned
    if (!in_array($pub_id, $current_spokes)) {
        $current_spokes[] = $pub_id;
        update_post_meta($post_id, '_sourcehub_selected_spokes', $current_spokes);
    }
} else {
    // Publication not found
    echo "Publication '$search_name' not found";
}
```

---

## Important Notes

1. **Always use `id` field** - Never rely on name alone as it can change
2. **Check `status = 'active'`** - Inactive publications shouldn't receive content
3. **Filter by `mode = 'spoke'`** - Hub connections have mode='hub'
4. **Post meta is serialized** - Use `maybe_unserialize()` when reading `_sourcehub_selected_spokes`
5. **Table prefix** - Always use `$wpdb->prefix` for table names (default: `wp_`)
6. **URL format** - URLs are stored without trailing slashes

---

## Quick Reference

| What You Need | How to Get It |
|---------------|---------------|
| Publication ID from name | Query `wp_sourcehub_connections` WHERE `name = 'Pub 1'` |
| Publication name from ID | Query `wp_sourcehub_connections` WHERE `id = 1` |
| Posts assigned to publication | Query `wp_postmeta` WHERE `meta_key = '_sourcehub_selected_spokes'` and ID in array |
| All active publications | Query `wp_sourcehub_connections` WHERE `mode = 'spoke'` AND `status = 'active'` |

---

## Contact & Support

For questions about integrating with SourceHub's publication system, refer to:
- Main plugin file: `sourcehub.php`
- Database class: `includes/class-sourcehub-database.php`
- Hub manager: `includes/class-sourcehub-hub-manager.php`
