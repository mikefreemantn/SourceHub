# Activity Logs Performance Debugging

## Steps to Identify the Bottleneck

I've added performance profiling to the logs page. Here's how to identify what's slow:

### 1. Load the Activity Logs Page
Navigate to: **SourceHub → Activity Logs**

### 2. Check the Debug Log
The profiling will output to your WordPress debug log. Check:
- `/wp-content/debug.log` (if WP_DEBUG_LOG is enabled)
- Or your server's error log

### 3. Look for This Line
```
[SourceHub Performance] Logs Page - Total: X.XXs | Logs Query: X.XXs | Count: X.XXs | Stats: X.XXs | Total Logs: XXXX
```

### 4. Interpret the Results

**If "Logs Query" is slow (>5 seconds):**
- Problem: The main query to fetch logs is slow
- Likely cause: Too many log entries or missing database indexes
- Solution: Add more indexes or implement log archiving

**If "Count" is slow (>5 seconds):**
- Problem: Counting total logs is slow
- Likely cause: Full table scan on large table
- Solution: Cache the count or add index

**If "Stats" is slow (>5 seconds):**
- Problem: Statistics aggregation is slow
- Likely cause: GROUP BY query on large dataset
- Solution: Cache stats or limit date range

**If template rendering is slow (Total >> sum of queries):**
- Problem: PHP processing in the view template
- Likely cause: Too much data being processed in loops
- Solution: Optimize template or reduce data

## Potential Issues to Check

### 1. **Massive Log Table**
```sql
SELECT COUNT(*) FROM wp_sourcehub_logs;
```
If you have >100,000 logs, that's the problem.

### 2. **Large Data Fields**
```sql
SELECT MAX(LENGTH(data)) as max_data_size, AVG(LENGTH(data)) as avg_data_size 
FROM wp_sourcehub_logs;
```
If max_data_size > 100KB, large JSON blobs are slowing things down.

### 3. **Missing Indexes**
The indexes should exist on:
- `created_at`
- `status`
- `connection_id`
- `status_created (status, created_at)` - composite
- `action_created (action, created_at)` - composite

### 4. **JSON Decoding in Loop**
In `get_formatted_logs()`, we decode JSON for every log entry:
```php
'data' => !empty($log->data) ? json_decode($log->data, true) : array(),
```

If you have 20 logs with 50KB JSON each, that's 1MB of JSON parsing.

## Quick Fixes to Try

### Option 1: Reduce Logs Per Page
In `admin/class-sourcehub-admin.php` line 427:
```php
$per_page = 10; // Reduce from 20
```

### Option 2: Don't Decode JSON Unless Needed
Remove JSON decoding from `get_formatted_logs()` and only decode when viewing details.

### Option 3: Implement Log Cleanup
Run the cleanup function regularly:
```php
SourceHub_Database::clean_old_logs(30); // Keep only 30 days
```

### Option 4: Add Caching
Cache the stats query result for 5 minutes using WordPress transients.

## Next Steps

1. Load the logs page
2. Check the debug output
3. Report back which query is slow
4. I'll provide a targeted fix
