# TinyMCE Button Debug Test

## 🔧 Changes Made

1. **✅ Fixed Icons** - Changed from `icon:` to `text:` with emojis (🔗 and 🌐)
2. **✅ Added Debug Logging** - Console messages for all button clicks
3. **✅ Hub Mode Check** - Only shows buttons in hub mode

## 🧪 Debug Test Steps

### Step 1: Clear Cache & Refresh
1. **Hard refresh** your browser (Ctrl+F5 or Cmd+Shift+R)
2. **Clear all browser cache** if needed

### Step 2: Check Console Messages
1. **Open browser dev tools** (F12)
2. **Go to Console tab**
3. **Navigate to post edit page** in Classic Editor
4. **Look for these messages:**
   ```
   SourceHub TinyMCE plugin loading...
   SourceHub TinyMCE plugin initialized for editor: content
   ```

**If you DON'T see these messages:** The JavaScript file isn't loading properly.

### Step 3: Check Button Visibility
1. **Look at Classic Editor toolbar**
2. **You should see:** 🔗 and 🌐 buttons
3. **If buttons are missing:** TinyMCE plugin registration failed

### Step 4: Test Button Clicks
1. **Click the 🔗 button**
   - **Console should show:** "Smart Link button clicked"
   - **Modal should open**

2. **Click the 🌐 button**
   - **Console should show:** "Custom Smart Link button clicked"
   - **Modal should open**

### Step 5: Test Modal Buttons
1. **In any modal, click Cancel**
   - **Console should show:** "Smart Link Cancel button clicked" or "Custom Smart Link Cancel button clicked"
   - **Modal should close**

## 🚨 Troubleshooting

### Issue 1: No Console Messages
**Problem:** JavaScript file not loading
**Solutions:**
- Check file exists: `/wp-content/plugins/SourceHub/assets/js/tinymce-shortcodes.js`
- Check file permissions (should be readable)
- Verify you're in Hub mode (not Spoke mode)

### Issue 2: Plugin Loading But No Buttons
**Problem:** TinyMCE button registration failed
**Check in console:**
```javascript
tinymce.activeEditor.buttons
```
Look for `sourcehub_smart_link` and `sourcehub_custom_smart_link`

### Issue 3: Buttons Visible But Not Clickable
**Problem:** Event handlers not binding
**This is what we're debugging with the console messages**

### Issue 4: Modal Opens But Buttons Don't Work
**Problem:** Modal button event binding
**The console messages will show if buttons are being clicked**

## 🎯 What to Report

After testing, tell me:

1. **Console messages you see** (copy/paste them)
2. **Whether buttons appear** in toolbar
3. **Whether buttons respond to clicks** (console messages)
4. **Whether modal buttons work** (console messages)
5. **Any JavaScript errors** in console

## 🔍 Quick Check Commands

Run these in browser console on post edit page:

```javascript
// Check if TinyMCE is loaded
console.log('TinyMCE loaded:', typeof tinymce !== 'undefined');

// Check if our plugin is registered
console.log('Our plugin registered:', tinymce.PluginManager.get('sourcehub_shortcodes'));

// Check if buttons are registered
if (tinymce.activeEditor) {
    console.log('Smart Link button:', tinymce.activeEditor.buttons.sourcehub_smart_link);
    console.log('Custom Smart Link button:', tinymce.activeEditor.buttons.sourcehub_custom_smart_link);
}

// Check if SourceHub admin object is available
console.log('SourceHub Admin:', typeof sourcehub_admin);
```

This will help us pinpoint exactly where the issue is!
