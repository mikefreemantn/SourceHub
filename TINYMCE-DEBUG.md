# TinyMCE Modal Debug Guide

## 🔍 Issue Analysis

The modal is displaying but buttons aren't working. This suggests:

1. **JavaScript loading issue** - TinyMCE plugin not properly registered
2. **Event binding issue** - Button click handlers not properly attached
3. **Context issue** - `this` reference problems in button callbacks

## 🧪 Debug Steps

### Step 1: Check if TinyMCE Plugin is Loading

1. **Open browser dev tools** (F12)
2. **Go to Console tab**
3. **Create/edit a post** in Classic Editor
4. **Look for errors** related to SourceHub or TinyMCE

**Expected:** No JavaScript errors
**If errors:** The plugin isn't loading properly

### Step 2: Check Button Registration

1. **In Classic Editor**, look at the toolbar
2. **Count the buttons** - you should see 🔗 and 🌐 icons
3. **Click each button** - modals should open

**If buttons missing:** TinyMCE plugin registration failed
**If modals don't open:** JavaScript errors in plugin

### Step 3: Check AJAX Endpoint

1. **Open Network tab** in dev tools
2. **Click Custom Smart Link button**
3. **Look for AJAX request** to `admin-ajax.php`
4. **Check response** - should return spoke connections

**Expected response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "name": "Spoke One",
      "url": "http://spoke-1.local"
    }
  ]
}
```

## 🔧 Quick Fixes to Try

### Fix 1: Clear Browser Cache
- Hard refresh (Ctrl+F5 or Cmd+Shift+R)
- Clear browser cache completely

### Fix 2: Check WordPress Admin
- Go to **SourceHub → Settings**
- Verify you're in **Hub mode**
- Check that you have **spoke connections** set up

### Fix 3: Check File Permissions
The JavaScript file should be accessible:
```
/wp-content/plugins/SourceHub/assets/js/tinymce-shortcodes.js
```

### Fix 4: Manual Test
Try this in browser console when on post edit page:
```javascript
console.log('TinyMCE:', typeof tinymce);
console.log('SourceHub Admin:', typeof sourcehub_admin);
```

**Expected:**
- `TinyMCE: object`
- `SourceHub Admin: object`

## 🚨 Common Issues

### Issue 1: Plugin Not Registered
**Symptom:** Buttons don't appear in toolbar
**Fix:** Check that `add_tinymce_buttons()` is being called

### Issue 2: AJAX Failing
**Symptom:** Custom Smart Link modal doesn't populate fields
**Fix:** Check nonce and permissions

### Issue 3: Context Problems
**Symptom:** Buttons appear but don't respond to clicks
**Fix:** JavaScript `this` binding issues in callbacks

## 🎯 Next Steps

1. **Try the debug steps above**
2. **Report what you find** in console/network tabs
3. **I'll provide targeted fixes** based on the specific issue

The modal looks perfect visually, so this is likely a JavaScript event binding issue that we can fix quickly once we identify the root cause.
