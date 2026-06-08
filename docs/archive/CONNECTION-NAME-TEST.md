# Connection Name Debug Test

## 🔍 The Problem

From your logs, I can see:
1. **Custom smart links are being created** (shortcodes work)
2. **But they're not being processed into clickable links** (Smart Links processor not matching)
3. **HTML entities are double-encoded** (`&quot;` instead of `"`)

## 🎯 Root Cause

The issue is likely that your **actual connection name** doesn't match what you're using in the shortcode.

## 🧪 Debug Test

### Step 1: Find Your Exact Connection Name

1. **Go to SourceHub → Spoke Connections**
2. **Copy the EXACT name** of your spoke connection (case-sensitive, spaces matter)
3. **Tell me what it says**

### Step 2: Test with Exact Name

Use this shortcode with your EXACT connection name:

```
[custom-smart-link urls='{"YOUR_EXACT_CONNECTION_NAME":"http://spoke-1.local/test"}']Test Link[/custom-smart-link]
```

### Step 3: Check Debug Logs

After syndicating, look for these log entries:

**Expected to see:**
```
SourceHub Custom Smart Links: Processing for spoke connection: YOUR_EXACT_NAME (ID: 2)
SourceHub Custom Smart Links: Decoded URLs: Array ( [YOUR_EXACT_NAME] => http://spoke-1.local/test )
SourceHub Custom Smart Links: Found URL by name "YOUR_EXACT_NAME": http://spoke-1.local/test
```

**If you see this instead:**
```
SourceHub Custom Smart Links: No URL found for spoke "YOUR_EXACT_NAME" (ID: 2)
SourceHub Custom Smart Links: Available URL keys: Array ( [0] => DIFFERENT_NAME )
```

Then the connection name in your shortcode doesn't match.

## 🔧 Quick Fix Test

Try this shortcode using the connection ID instead of name:

```
[custom-smart-link urls='{"2":"http://spoke-1.local/test"}']Test Link[/custom-smart-link]
```

(Replace "2" with your actual connection ID)

## 📋 What I Need

1. **Exact connection name** from your admin panel
2. **Connection ID** (visible in the URL when editing a connection)
3. **Debug logs** after trying the exact name

## 🎯 Expected Result

Once we get the connection name right, you should see:
- **On Spoke 1:** `<a href="http://spoke-1.local/test">Test Link</a>`
- **No "Spoke 2" references** on Spoke 1 site

The HTML entity encoding fix should also help with the JSON parsing.
