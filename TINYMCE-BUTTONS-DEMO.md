# SourceHub TinyMCE Smart Link Buttons - Demo Guide

## 🎯 What You Get

Two new buttons in your Classic Editor toolbar:

1. **🔗 Smart Link Button** - Creates regular smart links
2. **🌐 Custom Smart Link Button** - Creates custom smart links with specific URLs per spoke

## 📝 How to Use

### Step 1: Open Classic Editor
- Create or edit a post using Classic Editor
- Look for the new SourceHub buttons in the toolbar

### Step 2: Smart Link Button
1. Click the **🔗 Smart Link** button
2. Modal opens with fields:
   - **Link Text**: What the link should say (e.g., "Contact Us")
   - **Path**: The path to link to (e.g., "/contact")
3. See live preview of the shortcode
4. Choose:
   - **Insert**: Adds shortcode directly to editor
   - **Copy Shortcode**: Copies to clipboard for manual pasting

### Step 3: Custom Smart Link Button
1. Click the **🌐 Custom Smart Link** button
2. Modal opens with fields:
   - **Link Text**: What the link should say
   - **URL fields for each spoke**: One field per connected spoke site
3. Fill in custom URLs for each spoke
4. See live preview of the shortcode
5. Choose Insert or Copy

## 🧪 Test Scenarios

### Test 1: Regular Smart Link
- Click Smart Link button
- Link Text: "Our Services"
- Path: "/services"
- Result: `[smart-link path="/services"]Our Services[/smart-link]`

### Test 2: Custom Smart Link
- Click Custom Smart Link button
- Link Text: "Special Offer"
- Spoke 1 URL: "https://spoke1.com/promo"
- Spoke 2 URL: "https://spoke2.com/deals"
- Result: `[custom-smart-link urls='{"Spoke 1":"https://spoke1.com/promo","Spoke 2":"https://spoke2.com/deals"}']Special Offer[/custom-smart-link]`

## 🔧 Features

- ✅ **Live Preview** - See shortcode as you type
- ✅ **Copy to Clipboard** - Easy shortcode copying
- ✅ **Direct Insert** - Add shortcode directly to editor
- ✅ **Auto-populated Spokes** - Custom Smart Link modal shows all your connected spoke sites
- ✅ **Validation** - Won't let you create invalid shortcodes
- ✅ **Responsive** - Works on mobile/tablet

## 🚨 Troubleshooting

**Buttons don't appear:**
- Make sure you're using Classic Editor (not Gutenberg)
- Check that SourceHub is in Hub mode
- Verify you have spoke connections set up

**Modal doesn't open:**
- Check browser console for JavaScript errors
- Ensure you have admin permissions

**AJAX errors:**
- Verify spoke connections exist
- Check WordPress admin permissions

## 🎉 Success!

Once working, you'll have an easy way to create Smart Links without memorizing shortcode syntax!
