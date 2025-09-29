# SourceHub Plugin - Important Implementation Notes

## 🚀 Project Status
- **Current Phase**: Initial Development
- **Plugin Name**: SourceHub (Hub & Spoke Publisher)
- **Target**: WordPress 5.0+ with Yoast SEO integration

## 🏗️ Core Architecture

### Plugin Structure
```
sourcehub/
├── sourcehub.php (main plugin file)
├── includes/
│   ├── class-sourcehub.php (main plugin class)
│   ├── class-hub-manager.php (hub functionality)
│   ├── class-spoke-manager.php (spoke functionality)
│   ├── class-api-handler.php (REST API endpoints)
│   ├── class-yoast-integration.php (SEO metadata handling)
│   └── class-ai-rewriter.php (optional AI features)
├── admin/
│   ├── class-admin.php (admin interface)
│   ├── partials/ (admin templates)
│   └── js/ & css/ (admin assets)
└── public/
    ├── class-public.php (frontend functionality)
    └── js/ & css/ (public assets)
```

## 🔑 Critical WordPress Hooks & Functions

### Essential Hooks
- `init` - Register REST API endpoints
- `add_meta_boxes` - Add hub post editor meta box
- `save_post` - Trigger content syndication
- `admin_menu` - Add admin pages
- `wp_enqueue_scripts` - Load assets

### Key WordPress Functions
- `register_rest_route()` - Create spoke API endpoints
- `update_post_meta()` - Store Yoast SEO data
- `wp_remote_post()` - Send data from hub to spokes
- `wp_insert_post()` - Create posts on spoke sites
- `add_settings_section()` - Plugin configuration

## 🔐 Security Implementation

### API Authentication
- Generate unique API keys per spoke connection
- Use WordPress nonces for form submissions
- Validate and sanitize all incoming data
- Rate limiting on API endpoints

### Data Validation
```php
// Example validation pattern
$post_data = array(
    'title' => sanitize_text_field($_POST['title']),
    'content' => wp_kses_post($_POST['content']),
    'hub_id' => absint($_POST['hub_id'])
);
```

## 📊 Database Schema

### Custom Tables
- `wp_sourcehub_connections` - Store spoke connections
- `wp_sourcehub_logs` - Syndication activity logs
- `wp_sourcehub_queue` - Failed/retry queue

### Post Meta Fields
- `_sourcehub_hub_id` - Original hub post ID
- `_sourcehub_origin_url` - Hub site URL
- `_sourcehub_last_sync` - Last sync timestamp
- `_sourcehub_canonical_override` - Custom canonical URL

## 🎯 Yoast SEO Integration

### Required Meta Fields to Transfer
1. `_yoast_wpseo_title` - SEO title
2. `_yoast_wpseo_metadesc` - Meta description
3. `_yoast_wpseo_focuskw` - Focus keyword
4. `_yoast_wpseo_canonical` - Canonical URL
5. `_yoast_wpseo_twitter-title` - Twitter title
6. `_yoast_wpseo_twitter-description` - Twitter description
7. `_yoast_wpseo_opengraph-title` - OpenGraph title
8. `_yoast_wpseo_opengraph-description` - OpenGraph description

### Implementation Pattern
```php
// Hub: Collect Yoast data
$yoast_data = array();
foreach ($yoast_fields as $field) {
    $yoast_data[$field] = get_post_meta($post_id, $field, true);
}

// Spoke: Apply Yoast data
foreach ($yoast_data as $field => $value) {
    update_post_meta($new_post_id, $field, $value);
}
```

## 🔄 REST API Endpoints

### Spoke Endpoints
- `POST /wp-json/sourcehub/v1/receive-post` - Receive new post
- `PUT /wp-json/sourcehub/v1/update-post` - Update existing post
- `GET /wp-json/sourcehub/v1/status` - Health check

### Hub Endpoints (Internal)
- Admin AJAX endpoints for spoke management
- Settings API for configuration

## 🤖 AI Integration Notes

### OpenAI API Integration
- Store API keys securely (encrypted in database)
- Implement retry logic for API failures
- Add content length limits
- Cache rewritten content to avoid duplicate API calls

### Rewriting Rules Per Spoke
- Tone adjustments (formal, casual, regional)
- Length modifications (expand, condense)
- Localization (currency, dates, references)

## 🚨 Error Handling & Logging

### Log Categories
- `SUCCESS` - Successful syndication
- `ERROR` - Failed syndication attempts
- `WARNING` - Partial failures or retries
- `INFO` - General activity

### Retry Mechanism
- Queue failed requests for retry
- Exponential backoff strategy
- Maximum retry attempts (3-5)
- Admin notification for persistent failures

## 🎛️ Admin Interface Requirements

### Hub Mode Admin Pages
1. **Spoke Connections** - Manage connected sites
2. **Syndication Logs** - View push history
3. **Settings** - Plugin configuration
4. **AI Settings** - OpenAI configuration

### Spoke Mode Admin Pages
1. **Connection Status** - Hub connection info
2. **Received Content** - Syndicated posts log
3. **Settings** - Local configuration

## 🔧 Development Priorities

### Phase 1: Core Functionality
1. Plugin activation/deactivation
2. Mode selection (Hub/Spoke)
3. Basic post syndication
4. REST API endpoints

### Phase 2: Advanced Features
1. Yoast SEO integration
2. Post syncing/updates
3. Admin interface
4. Logging system

### Phase 3: Premium Features
1. AI rewriting integration
2. Advanced canonical management
3. Bulk operations
4. Analytics dashboard

## 💡 Remember These WordPress Best Practices
- Always escape output: `esc_html()`, `esc_attr()`, `esc_url()`
- Sanitize input: `sanitize_text_field()`, `wp_kses_post()`
- Use WordPress coding standards
- Implement proper capability checks: `current_user_can()`
- Follow plugin header requirements
- Use WordPress transients for caching
- Implement proper uninstall hooks

---
*Last Updated: 2025-06-19*