# Changelog

All notable changes to SourceHub will be documented in this file.

## [2.0.2.7] - 2026-04-17

### Fixed
- **CRITICAL: Duplicate UPDATE Prevention for Race Conditions**: Fixed hub sending multiple UPDATE requests when race condition prevention creates one post but multiple jobs report completion
  - Problem: When hub sends multiple simultaneous CREATE requests (due to duplicate delayed sync triggers), spoke's race condition prevention creates only ONE post but ALL job IDs send completion callbacks
  - Hub receives multiple callbacks with same spoke_post_id but different job IDs
  - Hub tracks all job IDs in sync_status and tries to UPDATE each one
  - Result: Multiple UPDATE requests sent to same spoke post, causing duplicates
  - Example: 3 CREATE jobs → 1 post created, 2 race-prevented → 3 callbacks → hub tries to send 3 UPDATEs
  - Solution: Deduplicate syndicated_spokes before sending UPDATEs by mapping spoke_post_id to connection_id
  - Only send ONE UPDATE per unique spoke post instead of one per job ID
  - Added duplicate detection logging in handle_sync_complete()
  - Added deduplication logic in handle_delayed_sync() before calling update_syndicated_post()
  - Result: Only ONE UPDATE sent per actual spoke post, even when multiple jobs reported completion

### Technical Details
- **Hub Side** - Modified `handle_sync_complete()` in `class-sourcehub-hub-manager.php` (lines 285-323)
  - Added spoke_post_map to detect duplicate spoke_post_ids across different connection IDs
  - Logs when duplicates are detected for debugging
- **Hub Side** - Modified `handle_delayed_sync()` in `class-sourcehub-hub-manager.php` (lines 3187-3248)
  - Added deduplication logic before sending UPDATEs
  - Maps spoke_post_id to connection_id to identify duplicates
  - Only includes first occurrence of each spoke_post_id in unique_spokes array
  - Logs skipped duplicates for transparency
  - Passes deduplicated unique_spokes to update_syndicated_post()
- **Spoke Side** - Added comment in `class-sourcehub-spoke-manager.php` (lines 1426-1428)
  - Clarified that race-prevented jobs still send actual post ID in callback
  - This allows hub to consolidate duplicate job IDs

### Impact
- Eliminates duplicate UPDATE requests when race conditions occur
- Prevents posts from being updated multiple times unnecessarily
- Reduces server load and potential for conflicts
- Maintains all existing race condition prevention benefits
- Backward compatible - works with older spoke versions

### Deployment
- **Hub sites:** Update to v2.0.2.7 (required for duplicate UPDATE prevention)
- **Spoke sites:** Update to v2.0.2.7 (recommended but not required - already sends post IDs correctly)

## [2.0.2.6] - 2026-04-16

### Fixed
- **CRITICAL: Accurate Status Tracking**: Fixed hub showing posts as "published" when they're actually stuck in draft on spoke sites
  - Problem: Hub was clearing status transient immediately after sending requests, before callbacks were received
  - `syndicate_post()` cleared transient after sending CREATE requests (line 2197)
  - `update_syndicated_post()` cleared transient after sending UPDATE requests (line 2396)
  - Result: Hub had no status tracking if delayed sync failed or UPDATE never fired
  - UI would show "completed" even when spokes were stuck in draft state
  - This masked the real issue: delayed sync not firing in poor hosting environments
  - Solution: Removed premature `delete_transient()` calls from both functions
  - Transient now only cleared in `handle_sync_complete()` when all callbacks received
  - Hub now accurately shows which spokes are stuck vs completed
  - Each spoke tracked independently - one failure doesn't affect others
  - Result: Hub status accurately reflects actual spoke state (draft vs published)

### Technical Details
- Modified `syndicate_post()` in `class-sourcehub-hub-manager.php` (line 2195-2198)
- Modified `update_syndicated_post()` in `class-sourcehub-hub-manager.php` (line 2396-2399)
- Removed `delete_transient('sourcehub_sync_status_' . $post_id)` from both functions
- Added comments explaining why transient must remain until callbacks received
- Transient clearing logic remains in `handle_sync_complete()` (lines 395-398)
- Only clears when `$all_done` is true (all spokes reported back)

### Impact
- Hub will now show accurate status for stuck posts instead of falsely showing "completed"
- Admins can identify which posts need manual intervention
- Reveals underlying issues (delayed sync failures, Action Scheduler problems)
- No impact on successful syncs - they still complete normally
- Each spoke operates independently - partial failures visible

## [2.0.2.5] - 2026-04-15

### Fixed
- **CRITICAL: Duplicate UPDATE Fix (Part 2)**: Fixed `handle_delayed_sync()` calling `update_syndicated_post()` twice internally
  - Problem: v2.0.2.4's delayed sync lock prevented the function from running twice, but didn't prevent duplicate calls WITHIN a single execution
  - `handle_delayed_sync()` had multiple code paths that both called `update_syndicated_post()`
  - When `pending_completion` flag was set AND `syndicated_spokes` existed, both paths would execute
  - Result: Two UPDATE requests sent in the same second with different job IDs
  - Solution: Modified `handle_delayed_sync()` to only call `update_syndicated_post()` once when completing drafts
  - Other code paths now skip and log warnings instead of calling update again
  - Added `delayed_sync_unexpected_state` warning for debugging edge cases
  - Result: Only ONE UPDATE request sent per delayed sync execution

### Technical Details
- Modified `handle_delayed_sync()` in `class-sourcehub-hub-manager.php` (lines 3157-3185)
- Changed else-if branches to skip updates and log warnings instead of calling `update_syndicated_post()`
- Only the `pending_completion` path calls `update_syndicated_post()` now
- Added warning logs for unexpected states (already-synced without pending flag, never-synced in delayed sync)

## [2.0.2.4] - 2026-04-15

### Fixed
- **CRITICAL: Duplicate UPDATE Prevention**: Added delayed sync lock to prevent duplicate UPDATE requests
  - Problem: `handle_delayed_sync()` was being triggered multiple times for the same post, sending duplicate UPDATE requests to spokes
  - Hub would send 2 separate UPDATE operations (with different job IDs) 4-6 seconds apart
  - Spokes processed both UPDATEs and sent 2 callbacks, creating race conditions
  - Under load or slow network conditions, callbacks could arrive out of order or get lost
  - Result: Intermittent failures where hub showed "synced" but articles remained in draft on spoke sites
  - Solution: Added `sourcehub_delayed_sync_lock_` transient lock at the start of `handle_delayed_sync()`
  - Lock prevents duplicate execution - if delayed sync is already running, subsequent triggers exit immediately
  - Lock expires after 30 seconds (plenty of time for delayed sync to complete)
  - Lock is cleared at the end of execution to allow future delayed syncs
  - Logs duplicate attempts as warnings for debugging visibility
  - Result: Only ONE UPDATE request sent per delayed sync, eliminating race conditions and ensuring consistent syndication

- **CRITICAL: Hub Wake-Up Before Callbacks**: Spokes now wake up hub before sending completion callbacks
  - Problem: On WP Engine, hub sites can go to sleep 15-30 minutes after inactivity
  - Hub wakes up spoke to send syndication request, but hub may sleep again before spoke finishes processing
  - Spoke processing can take 5-45+ seconds (especially with images, Yoast data, etc.)
  - When spoke tries to send callback, hub is asleep and callback fails
  - Result: Hub never receives completion notification, shows "processing" forever while spoke is actually published
  - Solution: Spoke now pings hub's `/wp-json/` endpoint before sending callback
  - Ensures hub is awake and ready to receive the completion notification
  - 1-second pause after wake-up to ensure hub is fully responsive
  - Callback retry system already in place for any remaining failures
  - Result: Hub reliably receives callbacks even on low-traffic sites with sleeping enabled

### Technical Details
- Added lock check at beginning of `handle_delayed_sync()` in `class-sourcehub-hub-manager.php`
- Uses `get_transient()` and `set_transient()` for atomic lock operations
- Lock key format: `sourcehub_delayed_sync_lock_{post_id}`
- Logs blocked duplicates with timestamp for debugging
- Lock cleanup with `delete_transient()` at end of function
- Added hub wake-up in `notify_hub_completion()` in `class-sourcehub-spoke-manager.php`
- Pings hub's `/wp-json/` endpoint with 10-second timeout before sending callback
- User-Agent: `SourceHub-Spoke-Wake-Hub/1.0` for tracking in logs

## [2.0.2.3] - 2026-04-03

### Fixed
- **CRITICAL: Accurate Sync Status Tracking**: Hub now properly tracks CREATE vs UPDATE completion for 2-step syndication
  - Problem: Hub marked articles as "synced" after CREATE callback, before UPDATE/publish actually happened
  - If UPDATE requests failed or were blocked (e.g., by security rules), hub showed "synced" but articles stayed in draft
  - No visibility into whether UPDATE step succeeded or failed
  - Solution: Hub now tracks CREATE and UPDATE separately with distinct statuses
  - CREATE callback → marks as `draft_created` (not "success")
  - UPDATE callback → marks as `success` (actually synced)
  - UI shows "Draft Created (Publishing...)" status when waiting for UPDATE
  - Overall status shows "📝 Publishing..." when any spoke is in draft_created state
  - Only shows "✓ Synced" after UPDATE confirms article is published
  - Added "⚠️ Partially Synced" status when some spokes fail
  - JavaScript polling updated to recognize and persist `draft_created` status
  - Result: Hub never falsely claims articles are synced when they're stuck in draft

### Technical Details
- Modified `handle_sync_complete()` in `class-sourcehub-hub-manager.php` to check for `pending_completion` flag
- Added `draft_created` status for CREATE callbacks when pending completion
- Updated overall status display to check `sync_status` meta for persistent state
- Updated JavaScript `sync-status.js` to handle `draft_created` status
- Polling continues while spokes are in `draft_created` state
- Prevents premature "all done" determination when UPDATE hasn't completed

## [2.0.0.2] - 2025-12-05

### Fixed
- **Critical Race Condition Fix**: Moved `_sourcehub_pending_completion` flag to the very beginning of `syndicate_post()`
  - Problem: Fast spokes (completing in <1 second) would send completion callbacks before the pending flag was set
  - The flag was being set AFTER the sync lock check, causing a race condition
  - Spoke callbacks would arrive, check for pending flag, find it false, and skip scheduling delayed sync
  - Result: Posts stayed in draft forever, never got images/Yoast/publish status
  - Solution: Set `_sourcehub_pending_completion` as the FIRST operation in `syndicate_post()`, before any locks or checks
  - This guarantees callbacks will always see the flag, even if spoke completes instantly
  - Combined with v2.0.0.1 debounce fix, this ensures 100% reliable first-publish syndication

## [2.0.0.1] - 2025-12-05

### Fixed
- **Duplicate Publish Protection**: Added 10-second debounce to prevent duplicate syndication from multiple publish events
  - Problem: Users clicking "Publish" multiple times or plugins triggering duplicate status transitions caused posts to stay in draft
  - WordPress can fire `draft → publish` transition multiple times (double-clicks, plugin conflicts, browser double-submission)
  - This caused syndication to trigger multiple times, confusing the delayed sync logic
  - Solution: Added debounce check that ignores duplicate publish events within 10 seconds
  - First publish sets a transient lock for 30 seconds, subsequent publishes within 10 seconds are ignored
  - Logs "Duplicate publish ignored (debounced)" when duplicate detected
  - Fixes issue where "Save Draft → Leave → Publish" worked but immediate publish didn't

## [2.0.0.0] - 2025-12-04

### 🎉 MAJOR RELEASE: 100% Reliable Async Processing

This is a **major architectural upgrade** that eliminates all wp-cron dependencies and ensures bulletproof reliability across all hosting environments.

### Changed
- **BREAKING: Spoke Job Processing Now Uses Action Scheduler**: Replaced unreliable wp-cron with Action Scheduler for all spoke-side async job processing
  - Previously: Spokes used `wp_schedule_single_event()` + `spawn_cron()` which failed on some hosting environments (WP Engine, etc.)
  - Now: Spokes use `as_enqueue_async_action()` - same reliable system the hub uses
  - Result: Jobs now visible in Tools → Scheduled Actions on spoke sites for debugging
  - No more silent failures from blocked loopback requests or disabled wp-cron
  - Consistent behavior across all environments (test, staging, production)

### Why This is v2.0.0.0
- **Architectural Change**: Complete migration from wp-cron to Action Scheduler for spoke processing
- **Reliability**: Eliminates the #1 cause of syndication failures in production
- **Consistency**: Hub and spokes now use the same async processing system
- **Visibility**: All async jobs now visible in WordPress admin for debugging
- **No More Hodgepodge**: Single, proven async system throughout the entire plugin

### Migration Notes
- **Automatic**: No configuration changes needed
- **Backwards Compatible**: Existing posts and connections work without modification
- **Pending Jobs**: Any jobs stuck in wp-cron will need to be manually re-synced (one-time only)

### Technical Details
- Removed `wp_schedule_single_event()` and `spawn_cron()` from `class-sourcehub-spoke-manager.php`
- Both `receive_post()` and `update_post()` now use Action Scheduler exclusively
- Jobs are queued in `wp_sourcehub_sync_jobs` table and processed via Action Scheduler
- Async processing happens immediately via Action Scheduler's async request system

## [1.9.9.12] - 2025-12-04

### Fixed
- **CRITICAL: Delayed Sync Not Scheduling for Slow Spokes**: Fixed posts stuck in draft when spokes complete at different times
  - Problem: When one spoke completed quickly but another took 30-60+ seconds, delayed sync never scheduled
  - Root cause: Scheduling logic only checked on first callback, assumed all spokes would be done by then
  - This worked in fast test environments (140ms response) but failed in slow production (10-60s response)
  - When fast spoke completed first, it checked if all done → NO → didn't schedule
  - When slow spoke completed later, it checked again but didn't schedule (assumed already done)
  - Result: Posts stuck as drafts, never got images/Yoast/published
  - Solution: Now checks on EVERY callback and schedules when all complete, regardless of timing
  - Added duplicate prevention using Action Scheduler's `as_get_scheduled_actions()` to avoid race conditions
  - Handles production environments with variable spoke performance gracefully

## [1.9.9.11] - 2025-12-02

### Fixed
- **CRITICAL: Manual Save Overwrites Sync Status**: Fixed posts stuck in draft when user clicks "Save Draft" or "Publish" during delayed sync window
  - When user manually saved post during 2-second delay, `save_post` hook fired
  - Pre-population code unconditionally reset ALL spoke statuses to "processing"
  - This overwrote spokes that had already completed with "success" status
  - Result: `all_creates_complete` never became TRUE, delayed sync never fired
  - Now preserves existing "success" statuses when pre-populating sync_status
  - Users can now safely click "Save Draft" or "Publish" without breaking sync

## [1.9.9.10] - 2025-12-02

### Fixed
- **CRITICAL: Spoke Post ID Bug**: Fixed spokes returning `true` instead of actual post ID in callbacks
  - `update_post_from_data()` was returning `true` on success instead of the post ID
  - This caused hub to receive `spoke_post_id: true` in completion callbacks
  - Broke the entire delayed sync completion tracking logic
  - Hub couldn't properly track which spokes completed their CREATE actions
  - Now correctly returns the post ID from update operations
  - Delayed sync can now properly detect when all creates are complete

### Added
- **Enhanced Delayed Sync Logging**: Added detailed logging to track delayed sync execution
  - Logs when all creates are complete and delayed sync is scheduled
  - Logs Action Scheduler scheduling details
  - Logs delayed sync execution with full state information
  - Shows pending_completion flag, syndicated_spokes, and sync_status
  - Makes it easy to diagnose if delayed sync is scheduling/executing properly

## [1.9.9.9] - 2025-12-02

### Fixed
- **THE REAL FIX: Sync Status Race Condition**: Pre-populate sync_status for ALL spokes before sending requests
  - Root cause: Fast spokes completed before slow spokes' status was written to database
  - When first callback arrived, it only saw its own status in sync_status array
  - Thought other spokes didn't exist, so didn't schedule delayed sync
  - Solution: Set ALL spokes to "processing" status BEFORE sending any requests
  - Now callbacks always see complete list of spokes in sync_status
  - Delayed sync reliably schedules when all CREATEs complete

## [1.9.9.8] - 2025-12-02

### Fixed
- **Critical Lock Collision Fix**: Removed delayed_sync_lock self-blocking issue
  - `handle_delayed_sync()` was checking for `delayed_sync_lock` and blocking itself
  - The 2-second delay caused the delayed sync to run while its own lock was still active
  - Now only checks for main `sync_lock` to avoid blocking legitimate delayed sync execution
  - Removed unnecessary `delayed_sync_lock` transient that was causing false positives
  - This was the root cause of posts staying in draft mode

## [1.9.9.7] - 2025-12-02

### Fixed
- **Action Scheduler Fallback**: Added diagnostic logging and automatic fallback to wp-cron if Action Scheduler fails
  - Check if `as_schedule_single_action()` function exists before calling
  - Log detailed error messages if Action Scheduler is unavailable or returns false
  - Automatically fall back to wp-cron + spawn_cron() if Action Scheduler fails
  - Ensures delayed sync always gets scheduled even if Action Scheduler has issues

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
