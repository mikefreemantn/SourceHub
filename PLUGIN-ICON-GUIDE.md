# SourceHub Plugin Icon Guide

## Icon Requirements

For your plugin to display an icon in the WordPress admin:

### File Sizes Needed:
1. **icon-128x128.png** - Standard resolution (required)
2. **icon-256x256.png** - High-DPI/Retina displays (recommended)

### Design Guidelines:
- **Format:** PNG with transparency
- **Style:** Simple, recognizable icon
- **Colors:** Match your brand (consider using your hub/spoke network theme)
- **Content:** Should represent "syndication" or "network" concept
  - Ideas: Connected nodes, hub with spokes, network diagram, broadcast icon

### Where to Place:
Put the icon files in: `/assets/` directory

```
SourceHub/
├── assets/
│   ├── icon-128x128.png
│   └── icon-256x256.png
```

## How to Create Your Icon

### Option 1: Design Tool
1. Use Figma, Canva, or Photoshop
2. Create 256x256px canvas
3. Design your icon with transparency
4. Export as PNG at both sizes

### Option 2: Use Dashicons
WordPress has built-in Dashicons you can use temporarily:
- Currently using: `dashicons-admin-multisite` (network icon)
- This shows in the menu but not in plugins list

### Option 3: AI Generation
Use an AI tool like:
- DALL-E
- Midjourney  
- Stable Diffusion

Prompt: "Simple flat icon for WordPress plugin, hub and spoke network, modern minimal design, transparent background"

## Implementation

Once you have your icon files:

1. Add them to `/assets/` directory
2. Commit to GitHub
3. WordPress will automatically detect and use them

No code changes needed - WordPress looks for these files automatically!

## Current Status

Currently using Dashicon in menu: `dashicons-admin-multisite`
This shows in the WordPress admin menu but you need actual PNG files for the plugins page.
