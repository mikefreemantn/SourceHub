# Changelog

All notable changes to SourceHub will be documented in this file.

## [1.9.9.6] - 2025-12-02

### Added
- **Action Scheduler Integration**: Replaced unreliable WordPress cron with Action Scheduler
  - Battle-tested library used by WooCommerce (millions of sites)
  - Guaranteed execution in all hosting environments (including WP Engine)
  - Self-executing background task processor
  - Built-in retry logic and failure handling
  - No dependency on site traffic or server cron configuration
  - Works reliably in development, staging, and production environments

### Fixed
- **Delayed Sync Reliability**: Eliminated all cron-related execution failures
  - Action Scheduler ensures delayed sync always executes
  - No more posts stuck in draft status
  - Images, Yoast data, and publish status now sync reliably
  - Removed all wp-cron workarounds (spawn_cron, etc.)

## [1.9.9.5] - 2025-12-02

### Fixed
- **WordPress Cron Execution**: Force cron execution with `spawn_cron()` after scheduling delayed sync
  - WordPress cron only runs when someone visits the site (pseudo-cron)
  - In low-traffic or development environments, scheduled events may never execute
  - Now manually spawn wp-cron.php immediately after scheduling to guarantee execution
  - Added comprehensive diagnostic logging to track cron scheduling and execution

### Added
- **WordPress Cron Diagnostics**: Added comprehensive logging to diagnose cron execution issues
  - Check if `DISABLE_WP_CRON` constant is set
  - Verify event scheduling success/failure
  - Confirm event is in cron queue after scheduling
  - Log when `handle_delayed_sync` is actually called
  - Helps identify why delayed sync events aren't executing

## [1.9.9.4] - 2025-12-02

### Fixed
- **Critical Lock Timing Issue**: Fixed sync lock collision preventing delayed sync from executing
  - Changed from immediate background execution to 2-second scheduled delay
  - Allows CREATE sync locks to be released before UPDATE operations begin
  - Prevents "delayed_sync_locked" errors that left posts in draft status
  - Ensures delayed sync (image + Yoast + publish) executes without lock conflicts
  - Removed AJAX handler in favor of WordPress cron with short delay

## [1.9.9.3] - 2025-12-02

### Fixed
- **Critical Race Condition in Delayed Sync**: Fixed object cache issue preventing delayed sync from triggering
  - Added `wp_cache_delete()` to force fresh read of sync status from database
  - Prevents stale cached data from causing "all creates complete" check to fail
  - Resolves issue where second spoke completion couldn't detect first spoke's success
  - Ensures delayed sync (image + Yoast + publish) triggers reliably when all draft creates complete

## [1.0.2] - 2025-01-28

### Added
- **Smart Links Feature**: Automatic URL adaptation for internal links
  - Smart Links with base URL + path (`/weather` → `spoke1.com/weather`)
  - Custom Smart Links with unique URLs per spoke site
  - Block editor integration with visual formatting tools
  - Keyboard shortcuts (Ctrl/Cmd + L for Smart Links, Ctrl/Cmd + K for Custom Smart Links)

- **Site Wake-Up System**: Intelligent handling of sleeping/inactive sites
  - 3-step wake-up process (health check, ping, verification)
  - Automatic detection of responsive vs sleeping sites
  - Comprehensive logging of wake-up attempts
  - Increased timeout for better reliability (45 seconds)

- **Enhanced AI Rewriting**:
  - GPT-4o Mini model support for cost-effective rewriting
  - Per-post AI override controls
  - Improved quote preservation in AI rewriting
  - Better error handling and logging

### Improved
- **Admin Interface**: Complete Google Material Design overhaul
  - Modern card-based dashboard layout
  - Animated stats cards with hover effects
  - Responsive design for all screen sizes
  - Better visual hierarchy and typography

- **Yoast SEO Integration**: Enhanced debugging and field detection
  - Comprehensive logging of Yoast field processing
  - Support for alternative field names
  - Better error reporting for missing fields

- **Connection Management**: 
  - Edit connection functionality
  - API key management improvements
  - Better connection testing and validation

### Fixed
- **Critical API Authentication Bug**: Fixed spoke manager API key mismatch
  - Changed from `sourcehub_api_key` to `sourcehub_spoke_api_key`
  - Resolved all hub-to-spoke connection failures

- **Featured Image Syncing**: Resolved image download issues
  - Replaced WordPress `download_url()` with manual `wp_remote_get()`
  - Better error handling for image processing
  - Fixed sync settings field name mismatches

- **PHP Errors**: Eliminated all dashboard PHP warnings
  - Added comprehensive variable validation
  - Enhanced error handling in database methods
  - Improved admin notices for missing database tables

### Technical
- **Smart Link Processing**: New `SourceHub_Smart_Links` class
  - Regex-based link detection and replacement
  - Support for content, titles, and excerpts
  - Integration with AI rewriting system

- **Wake-Up System**: New wake-up functionality in hub manager
  - Multi-step site responsiveness checking
  - Graceful degradation for failed wake-ups
  - Custom User-Agent headers for tracking

- **Block Editor Integration**: Enhanced WordPress editor support
  - Custom rich text format types
  - Modal interfaces for link configuration
  - Visual styling with gradients and icons

## [1.0.1] - 2025-01-15

### Added
- Initial release with core hub & spoke functionality
- Basic AI rewriting with OpenAI integration
- Yoast SEO field synchronization
- Connection management and testing
- Activity logging system

### Features
- Hub and Spoke mode selection
- Content syndication with metadata
- Featured image synchronization
- Category and tag mapping
- Basic admin interface

## [1.0.0] - 2025-01-01

### Added
- Initial plugin structure
- Database schema creation
- Basic REST API endpoints
- Core syndication logic
