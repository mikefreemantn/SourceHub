# SourceHub - Things To Do

## High Priority Issues

### 1. Fix Connection Test to Use Form API Key
**Problem:** The "Test Connection" button uses the API key stored in the database instead of the key typed in the form.

**Location:** `/includes/class-sourcehub-hub-manager.php` line 1665 - `ajax_test_connection()`

**Current Behavior:**
- User changes API key in form
- Clicks "Test Connection"
- Test uses OLD key from database (not the new one)
- Test passes even with wrong key in form
- User saves wrong key, breaking syndication

**Fix Needed:**
- Accept API key from AJAX request parameters
- Test with the provided key (not database key)
- Only allow saving if test passes with new key

**Security Impact:** Users can't verify new keys before saving, risking broken connections

---

### 2. Fix Activity Logs Stats Display
**Problem:** Activity Logs page shows "0 Total Logs, 0 Success, 0 Warnings, 0 Errors" even when logs exist.

**Location:** `/admin/views/logs.php` - Stats display section

**Current Behavior:**
- Stats counters show all zeros
- Actual log entries display correctly below
- Stats calculation or display is broken

**Fix Needed:**
- Debug `SourceHub_Logger::get_stats()` function
- Verify stats are being calculated correctly
- Ensure stats are passed to template properly
- Check if filters are affecting stats count

**User Impact:** Can't see overview of log health at a glance

---

### 3. Real-Time Syndication Status Updates (⚠️ HIGH COMPLEXITY)
**Problem:** Meta box shows "Processing..." indefinitely. Doesn't update to "Syndicated" when spoke accepts the post.

**Location:** Post editor meta box - syndication status display

**Current Behavior:**
- User publishes post
- Meta box shows blue "Processing..." badge next to each spoke
- Status never updates even after syndication completes
- User must refresh page to see final status

**Desired Behavior:**
- Show "Processing..." initially
- Use AJAX to poll for completion
- Update to "Syndicated" (green) when spoke responds with 202 Accepted
- Update to "Failed" (red) if syndication fails
- No page refresh needed

**Fix Needed:**
- Add AJAX endpoint to check syndication job status
- Implement polling mechanism in meta box JavaScript
- Update status badges in real-time
- Handle multiple spokes updating independently
- Stop polling when all jobs complete or timeout

**⚠️ CRITICAL WARNING:**
- This area has caused major issues before
- Last attempt required hours of fixes
- Tread VERY carefully
- Test thoroughly on local before deploying
- Consider feature flag to disable if it breaks

**Technical Considerations:**
- Job queue system already exists
- Async processing returns job_id
- Need endpoint to query job status by job_id
- Must handle race conditions
- Consider WebSocket vs polling trade-offs

**User Impact:** Better UX - immediate feedback on syndication success/failure

---

## Notes
- Issues identified on Nov 10, 2025
- Currently on branch: `yoast-draft-then-update`
- Main branch at v1.8.8
- Issue #3 marked as HIGH COMPLEXITY - approach with extreme caution
