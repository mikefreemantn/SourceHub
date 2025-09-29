Project Documentation: Hub & Spoke Publisher Plugin for WordPress

Project Summary:
The Hub & Spoke Publisher plugin is a WordPress-based content distribution tool built specifically for newspaper publishers and digital media networks. It enables a centralized editorial team (Hub) to write and publish articles, then syndicate them to any number of connected WordPress sites (Spokes). This plugin operates in two modes: Hub and Spoke, selectable via a settings panel.

Objectives:

Allow a single "hub" site to create and syndicate content to multiple "spoke" sites

Allow spoke sites to receive, publish, and optionally modify syndicated content

Integrate fully with Yoast SEO plugin to transfer SEO metadata

Include a syncing mechanism to update spoke content if the hub content is changed

Avoid SEO penalties for duplicate content via canonical tags and optional AI rewriting

Core Features:

Dual-Mode Plugin Architecture

A single plugin installed on all sites

Sites choose "Hub" or "Spoke" mode via plugin settings

Different functionality enabled depending on the selected mode

Hub Mode Functionality

Post editor meta box for selecting which spokes to send content to

Admin screen for managing spoke connections (URL, API key, name, sync rules)

On publish or update, plugin sends post content to selected spokes

Logging of push success/failure per site

Optional AI rewriter per spoke

Canonical tag management per spoke

Spoke Mode Functionality

REST API endpoint to receive incoming post data

Creates or updates post based on hub-provided unique ID or slug

Stores origin ID in post meta for future syncing

Applies post metadata (categories, tags, featured image, author)

Supports optional AI rewriter or local post modifiers

Accepts Yoast metadata and stores in proper meta fields

Sends response to hub after success/failure

Yoast SEO Integration

Captures and transmits the following meta fields from hub to spoke:

_yoast_wpseo_title

_yoast_wpseo_metadesc

_yoast_wpseo_focuskw

_yoast_wpseo_canonical

_yoast_wpseo_twitter-title

_yoast_wpseo_twitter-description

_yoast_wpseo_opengraph-title

_yoast_wpseo_opengraph-description

Plugin must write to these fields on spoke side using update_post_meta

Allow spoke to override metadata after receipt if enabled in settings

Post Syncing Feature

When a post is updated in the hub, any previously pushed spokes are listed

The editor can select which spokes to update

The plugin sends updated payload to spoke

Spoke uses original hub ID to locate and update local post

Optional: toggle to automatically sync on each update

AI Rewriting Option

Optional feature per spoke connection

When enabled, hub sends content through an AI model (e.g., GPT-4) before pushing

Rules configurable per spoke (e.g., change tone, add regional details)

AI rewriter modifies:

Title

Content body

Excerpt

Yoast metadata (if selected)

Rewrites are logged and optionally previewed before sending

Canonical Tag Handling

If AI rewriting is disabled, the plugin should insert a canonical tag on each spoke post

This canonical tag should point to the original post URL on the hub

Use Yoast's canonical override field (_yoast_wpseo_canonical) to implement this

Canonical tags are disabled automatically if rewriting is enabled

Data Payload Example (JSON)

{
  "post_id": 123,
  "title": "Breaking News: Local Pizza Wins Award",
  "slug": "pizza-wins-award",
  "content": "<p>Full article HTML here...</p>",
  "excerpt": "Local pizza gets national recognition...",
  "author": "Mike Freeman",
  "categories": ["Local News", "Food"],
  "tags": ["pizza", "awards"],
  "featured_image_url": "https://hubsite.com/wp-content/uploads/image.jpg",
  "yoast_meta": {
    "_yoast_wpseo_title": "Pizza Wins Local Award - Best in Town",
    "_yoast_wpseo_metadesc": "Find out which pizza shop took top honors...",
    "_yoast_wpseo_canonical": "https://hubsite.com/pizza-wins-award"
  },
  "ai_rewrite": false,
  "sync_type": "new" // or "update"
}

Spoke Response Format

{
  "status": "success",
  "post_url": "https://spokesite.com/pizza-wins-award",
  "post_id": 456,
  "message": "Post created successfully."
}

Admin Settings (Plugin Options Panel)

Mode: [Hub | Spoke]

Hub Settings:

Add/Remove Spoke Sites

Test Connection

API Key per Spoke

AI Rewrite [Yes/No] per Spoke

Canonical Override [Yes/No] per Spoke

Default Sync Behavior [Manual | Automatic]

Spoke Settings:

API Key

Author to assign posts to

Category/tag mapping

Allow Hub to overwrite SEO fields [Yes/No]

Deployment Notes

The plugin must be WordPress 5.0+ compatible

Gutenberg and Classic Editor support

Multisite support optional, but helpful

Plugin should be namespaced and avoid global function collisions

All REST endpoints should be namespaced and secured with API keys

AJAX interfaces should be secured with nonce validation