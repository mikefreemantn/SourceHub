# "Spoke One" Connection Test

## 🎯 Test Content

Since your connection name is **"Spoke One"**, try this exact shortcode:

```
[custom-smart-link urls='{"Spoke One":"http://spoke-1.local/test-page"}']Test Link[/custom-smart-link]
```

## 🔍 What Should Happen

### On Hub (after shortcode processing):
```html
<span class="sourcehub-custom-smart-link" data-custom-urls="{&quot;Spoke One&quot;:&quot;http://spoke-1.local/test-page&quot;}">Test Link</span>
```

### On Spoke One (after Smart Links processing):
```html
<a href="http://spoke-1.local/test-page" class="sourcehub-custom-smart-link-processed">Test Link</a>
```

## 🧪 Debug Steps

1. **Create a post** with ONLY this shortcode:
   ```
   [custom-smart-link urls='{"Spoke One":"http://spoke-1.local/test-page"}']Test Link[/custom-smart-link]
   ```

2. **Syndicate to Spoke One**

3. **Check logs** for these messages:
   ```
   SourceHub Custom Smart Links: Processing for connection "Spoke One" (ID: 2)
   SourceHub Custom Smart Links: Found match - URLs JSON: {"Spoke One":"http://spoke-1.local/test-page"}
   SourceHub Custom Smart Links: Decoded URLs: Array ( [Spoke One] => http://spoke-1.local/test-page )
   SourceHub Custom Smart Links: Found URL by name "Spoke One": http://spoke-1.local/test-page
   SourceHub Custom Smart Links: Creating link - URL: http://spoke-1.local/test-page, Text: Test Link
   ```

4. **Check spoke content** - should be a clickable link, not a span

## 🚨 If Still Not Working

The issue might be that the `process_custom_content()` function isn't being called at all. Check if you see this log message:

```
SourceHub Custom Smart Links: Processing for connection "Spoke One" (ID: 2)
```

**If you DON'T see this message**, then the Smart Links processing isn't running, which would be a different issue.

## 🎯 Expected Result

- **✅ Clickable link** on Spoke One
- **✅ No "Spoke 2" references** in the output
- **✅ Clean HTML** without span tags
