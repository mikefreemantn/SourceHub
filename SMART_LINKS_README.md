# SourceHub Smart Links Feature

## Overview
Smart Links allow you to create links that automatically adapt to each spoke site's base URL during syndication. This is perfect for creating consistent navigation and internal linking across your network of sites.

## How It Works

### 1. Creating Smart Links
1. **In the WordPress Block Editor**: Select text you want to make into a smart link
2. **Open the formatting dropdown**: Click the dropdown arrow next to Bold/Italic buttons
3. **Select "Smart Link"**: Choose the Smart Link option from the dropdown
4. **Enter the path**: In the modal, enter the URL path (e.g., `/weather`, `/sports`, `/contact`)
5. **Apply**: Click "Apply Smart Link" to create the smart link

### 2. Smart Link Processing
When content is syndicated to spoke sites:
- **Hub content**: `Check out our <smart-link>/weather</smart-link> page`
- **Spoke 1 (spoke1.com)**: `Check out our [weather](https://spoke1.com/weather) page`
- **Spoke 2 (spoke2.com)**: `Check out our [weather](https://spoke2.com/weather) page`

### 3. Visual Indicators
- **In Editor**: Smart links appear with a purple gradient background and 🔗 icon
- **On Frontend**: Smart links become regular links with the spoke's URL

## Use Cases

### Navigation Links
```
Visit our [About Us](/about) page for more information.
```
- Hub: Creates smart link with path `/about`
- Spoke 1: Becomes `https://spoke1.com/about`
- Spoke 2: Becomes `https://spoke2.com/about`

### Section References
```
Read more in our [Sports](/sports) section.
```
- Automatically links to each spoke's sports section

### Contact Information
```
[Contact us](/contact) for more details.
```
- Links to each spoke's contact page

### Local Pages
```
Check our [Events Calendar](/events) for upcoming activities.
```
- Links to each spoke's events page

## Path Formats

### Absolute Paths (Recommended)
- Start with `/`: `/weather`, `/sports/baseball`, `/contact-us`
- These are appended directly to the spoke's base URL

### Relative Paths
- No leading `/`: `weather`, `sports/baseball`, `contact-us`
- Also work but absolute paths are clearer

## Technical Details

### Processing Order
1. **Content Creation**: Smart links are stored in post content with special markup
2. **AI Rewriting**: If enabled, AI processes content while preserving smart links
3. **Smart Link Processing**: Links are converted to spoke-specific URLs
4. **Syndication**: Final content with proper links is sent to spoke

### Storage Format
Smart links are stored as:
```html
<span class="sourcehub-smart-link" data-smart-url="/weather">Weather Page</span>
```

### Output Format
On spoke sites, they become:
```html
<a href="https://spoke-site.com/weather" class="sourcehub-smart-link-processed">Weather Page</a>
```

## Best Practices

### 1. Use Consistent Paths
- Ensure all spoke sites have the same page structure
- Use standardized paths like `/about`, `/contact`, `/services`

### 2. Test Links
- Verify that target pages exist on all spoke sites
- Check that paths are correctly formatted

### 3. Documentation
- Maintain a list of standard paths used across your network
- Share this with content creators and spoke site managers

### 4. Fallback Planning
- Consider what happens if a page doesn't exist on a spoke site
- Implement 404 handling or redirects as needed

## Troubleshooting

### Smart Links Not Working
1. **Check Hub Mode**: Smart links only work in Hub mode
2. **Verify Spoke URLs**: Ensure spoke connections have correct base URLs
3. **Check Logs**: Look for smart link processing messages in SourceHub logs

### Links Not Converting
1. **Path Format**: Ensure paths start with `/` or are valid relative paths
2. **Spoke URL**: Verify the spoke's URL is correctly configured
3. **Content Processing**: Check that smart link processing is running

### Styling Issues
1. **Editor Styles**: Smart links should appear with purple background in editor
2. **Frontend Styles**: Processed links get `sourcehub-smart-link-processed` class
3. **Custom CSS**: Add custom styles for the processed link class if needed

## Keyboard Shortcuts
- **Ctrl/Cmd + L**: Quick access to Smart Link modal (when text is selected)

## Integration with AI Rewriting
Smart links are preserved during AI rewriting:
- AI instructions include preserving smart link markup
- Links are processed after AI rewriting is complete
- Final output maintains proper link functionality

This ensures that AI-rewritten content still has working smart links on each spoke site.
