# Debug Custom Smart Links

## 🧪 Test Content

Copy this EXACT content into a Classic Editor post and syndicate it:

---

**Test 1: Simple Custom Smart Link**

Check out our [custom-smart-link urls='{"Spoke One":"http://spoke-1.local/test-page"}']Test Page[/custom-smart-link] for more info.

**Test 2: Multiple Spokes**

Visit our [custom-smart-link urls='{"Spoke One":"http://spoke-1.local/special","Spoke Two":"http://spoke-2.local/special"}']Special Offers[/custom-smart-link] page.

---

## 🔍 What to Check

### Step 1: Check Hub Logs
After syndicating, check your logs for these entries:

1. **Shortcode Processing:**
   ```
   SourceHub Custom Smart Links: Found match - URLs JSON: {"Spoke One":"http://spoke-1.local/test-page"}, Text: Test Page
   ```

2. **Connection Info:**
   ```
   SourceHub Custom Smart Links: Processing for spoke connection: Spoke One (ID: 2)
   ```

3. **JSON Decoding:**
   ```
   SourceHub Custom Smart Links: Decoded URLs: Array ( [Spoke One] => http://spoke-1.local/test-page )
   ```

4. **URL Matching:**
   ```
   SourceHub Custom Smart Links: Found URL by name "Spoke One": http://spoke-1.local/test-page
   ```

5. **Link Creation:**
   ```
   SourceHub Custom Smart Links: Creating link - URL: http://spoke-1.local/test-page, Text: Test Page
   ```

### Step 2: Check Spoke Content
On the spoke site, the content should show:
```html
<a href="http://spoke-1.local/test-page" class="sourcehub-custom-smart-link-processed">Test Page</a>
```

## 🚨 Common Issues

### Issue 1: Connection Name Mismatch
**Problem:** Your spoke connection name doesn't match the JSON key exactly.

**Check:** What is the EXACT name of your spoke connection?
- Go to SourceHub → Spoke Connections
- Copy the exact name (case-sensitive, spaces matter)

**Fix:** Use the exact connection name in the shortcode:
```
[custom-smart-link urls='{"EXACT_CONNECTION_NAME":"http://url.com"}']Link Text[/custom-smart-link]
```

### Issue 2: JSON Syntax Error
**Problem:** Invalid JSON in the shortcode.

**Common mistakes:**
- Using double quotes inside double quotes
- Missing quotes around keys or values
- Extra commas

**Correct format:**
```
urls='{"Connection Name":"http://url.com","Another Connection":"http://other.com"}'
```

### Issue 3: HTML Entity Encoding
**Problem:** WordPress might be encoding the JSON.

**Check logs for:** HTML entities like `&quot;` instead of `"`

## 🔧 Quick Fix Test

Try this simplified version first:

```
[custom-smart-link urls='{"Spoke One":"http://example.com"}']Test Link[/custom-smart-link]
```

Replace "Spoke One" with your EXACT connection name from the admin panel.

## 📋 Debug Checklist

- [ ] Connection name matches exactly (case-sensitive)
- [ ] JSON syntax is valid
- [ ] Shortcode is properly formatted
- [ ] Logs show successful processing
- [ ] Spoke site receives the processed link
