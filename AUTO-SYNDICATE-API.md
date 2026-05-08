# SourceHub Auto-Syndication API

## Overview

SourceHub now supports automatic syndication to all active spoke sites via a custom field trigger. This allows external plugins to programmatically trigger syndication without manual intervention.

## How It Works

When a post is saved with the custom field `sourcehub_auto_syndicate` set to `true`, SourceHub will automatically:

1. Detect the trigger field
2. Schedule syndication with a 30-second delay
3. After the delay, get all active spoke site connections
4. Syndicate the post to all active spokes
5. Mark the trigger as processed

**Why the delay?** The 30-second delay gives WordPress time to:
- Fully index the post
- Process and attach featured images
- Complete Yoast SEO meta generation
- Finalize all post metadata

This prevents timing issues where syndication might occur before all post data is ready.

## Usage in Your Plugin

### Basic Example (All Sites)

```php
// Create or update a post
$post_id = wp_insert_post(array(
    'post_title'   => 'My Auto-Generated Post',
    'post_content' => 'This post was created by my plugin.',
    'post_status'  => 'publish',
    'post_type'    => 'post'
));

// Trigger auto-syndication to all active spoke sites
update_post_meta($post_id, 'sourcehub_auto_syndicate', 'true');
```

### Targeted Syndication (Specific Sites)

```php
// Create or update a post
$post_id = wp_insert_post(array(
    'post_title'   => 'Regional News Article',
    'post_content' => 'This post should only go to specific sites.',
    'post_status'  => 'publish',
    'post_type'    => 'post'
));

// Set featured image
set_post_thumbnail($post_id, $image_id);

// Trigger auto-syndication to ONLY spoke sites with IDs 11, 14, and 16
update_post_meta($post_id, 'sourcehub_auto_syndicate_to_id', '11,14,16');
```

### Advanced Example with Full Control

```php
// Create post with all metadata
$post_id = wp_insert_post(array(
    'post_title'   => 'AI Generated Article',
    'post_content' => $generated_content,
    'post_excerpt' => $generated_excerpt,
    'post_status'  => 'publish',
    'post_type'    => 'post',
    'post_author'  => 1
));

// Set featured image
set_post_thumbnail($post_id, $image_id);

// Set categories
wp_set_post_categories($post_id, array(5, 12));

// Set tags
wp_set_post_tags($post_id, 'AI, automation, news');

// Add Yoast SEO meta (if Yoast is installed)
update_post_meta($post_id, '_yoast_wpseo_metadesc', $meta_description);
update_post_meta($post_id, '_yoast_wpseo_focuskw', $focus_keyword);

// Trigger auto-syndication
update_post_meta($post_id, 'sourcehub_auto_syndicate', 'true');
```

## Custom Field Reference

### Trigger Field (All Sites)

**Field Name:** `sourcehub_auto_syndicate`  
**Accepted Values:** `'true'`, `'1'`, or `1`  
**Action:** Triggers syndication to **all active spoke sites**

### Trigger Field (Specific Sites)

**Field Name:** `sourcehub_auto_syndicate_to_id`  
**Accepted Values:** Comma-separated connection IDs (e.g., `'11,14,16'`)  
**Action:** Triggers syndication to **only the specified spoke sites**  
**Notes:**
- Connection IDs must be valid and active
- Invalid or inactive IDs are automatically filtered out
- Use this when you want to target specific spoke sites instead of all sites
- To find connection IDs: Go to SourceHub → Connections in WordPress admin
- IDs are shown in the connections list or in the URL when editing a connection

### Processing Timestamp

**Field Name:** `sourcehub_auto_syndicate_processed`  
**Value:** MySQL datetime when syndication was triggered  
**Purpose:** Track when auto-syndication occurred

## Requirements

- Post must have `post_status` of `publish`
- At least one active spoke site connection must exist
- The trigger field must be set AFTER the post is published

## Behavior

### First Syndication
If the post has never been syndicated before:
- Creates new posts on all active spoke sites
- Sets `_sourcehub_selected_spokes` meta with all connection IDs
- Sets `_sourcehub_syndicated_spokes` meta after successful syndication

### Update Existing
If the post was previously syndicated:
- Updates existing posts on all active spoke sites
- Preserves spoke-specific customizations (if configured)

### Logging
All auto-syndication actions are logged with:
```
SourceHub Auto-Syndicate: Triggering syndication for post {ID} to {N} spoke sites
```

## Example: Complete Integration

```php
/**
 * Example: AI Content Generator Plugin Integration
 */
class My_AI_Content_Generator {
    
    public function generate_and_publish() {
        // Generate content using your AI service
        $content = $this->generate_ai_content();
        
        // Create the post
        $post_id = wp_insert_post(array(
            'post_title'   => $content['title'],
            'post_content' => $content['body'],
            'post_excerpt' => $content['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'post'
        ));
        
        if (is_wp_error($post_id)) {
            error_log('Failed to create post: ' . $post_id->get_error_message());
            return false;
        }
        
        // Add metadata
        if (!empty($content['featured_image'])) {
            set_post_thumbnail($post_id, $content['featured_image']);
        }
        
        if (!empty($content['categories'])) {
            wp_set_post_categories($post_id, $content['categories']);
        }
        
        // Trigger SourceHub auto-syndication
        update_post_meta($post_id, 'sourcehub_auto_syndicate', 'true');
        
        return $post_id;
    }
}
```

## Troubleshooting

### Post Not Syndicating

1. **Check post status:** Must be `publish`
2. **Check active connections:** At least one spoke must be active
3. **Wait for delay:** Syndication occurs 30 seconds after trigger is set
4. **Check error logs:** Look for `SourceHub Auto-Syndicate:` entries
5. **Verify trigger field:** Ensure `sourcehub_auto_syndicate` is set to `'true'`
6. **Check wp-cron:** Ensure WordPress cron is running (required for delayed execution)

### Duplicate Syndications

The trigger is designed to process once. If you need to re-trigger:
- Delete the `sourcehub_auto_syndicate_processed` meta
- Set `sourcehub_auto_syndicate` to `'true'` again

## Best Practices

1. **Set trigger LAST:** Add all post metadata before setting the trigger
2. **Check for SourceHub:** Verify SourceHub is active before triggering
3. **Handle errors:** Check for WP_Error when creating posts
4. **Log actions:** Add your own logging for debugging

## Example: Check if SourceHub is Active

```php
if (class_exists('SourceHub_Auto_Syndicate')) {
    // SourceHub is active, safe to use auto-syndication
    update_post_meta($post_id, 'sourcehub_auto_syndicate', 'true');
} else {
    // SourceHub not active, handle accordingly
    error_log('SourceHub not active - post not syndicated');
}
```

## Support

For issues or questions about auto-syndication, check:
- SourceHub Activity Logs (SourceHub → Activity Logs)
- WordPress debug.log for error messages
- Post Logs (SourceHub → Post Logs) for syndication status
