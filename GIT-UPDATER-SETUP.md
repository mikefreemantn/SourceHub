# Git Updater Setup Complete ✅

## What Was Done

### 1. Added Git Updater Headers to `sourcehub.php`
```php
GitHub Plugin URI: https://github.com/mikefreemantn/SourceHub
Primary Branch: main
Release Asset: false
```

### 2. Fixed Version Consistency
- **Plugin Header**: `Version: 1.2.4`
- **Constant**: `SOURCEHUB_VERSION: 1.2.4`
- **readme.txt**: `Stable tag: 1.2.4`
- **Git Tag**: `1.2.4`

All versions now match! ✅

### 3. Created `readme.txt`
Standard WordPress readme with:
- Proper stable tag
- Changelog for version 1.2.4
- Plugin description and features
- Installation instructions

### 4. Pushed Git Tag
```bash
git tag 1.2.4
git push origin main
git push --tags
```

Tag `1.2.4` is now live on GitHub: https://github.com/mikefreemantn/SourceHub/releases/tag/1.2.4

---

## How to Update Plugin on Your Sites

### Method 1: Automatic via Git Updater (Recommended)

1. **Install Git Updater** on your WordPress site (if not already installed)
   - Download from: https://git-updater.com/
   - Or install via WordPress plugin directory

2. **Refresh Cache**
   - Go to: **Dashboard → Updates**
   - Click **"Check Again"**
   - OR go to: **Git Updater → Settings** → Click **"Refresh Cache"**

3. **Update Plugin**
   - You should see "SourceHub 1.2.4" available
   - Click "Update Now"

### Method 2: Manual Update

If Git Updater doesn't detect the update:

1. **Check Git Updater Settings**
   - Go to: **Settings → Git Updater**
   - Verify SourceHub is listed
   - If it's a private repo, add a GitHub Personal Access Token

2. **Clear All Caches**
   - Clear WordPress object cache
   - Clear any page caching plugins
   - Clear Git Updater cache

3. **Check Plugin Headers**
   - Deactivate and reactivate the plugin
   - This forces WordPress to re-read the headers

---

## For Future Updates

### Quick Checklist

1. **Bump version in 3 places:**
   - [ ] `sourcehub.php` header: `Version: X.X.X`
   - [ ] `sourcehub.php` constant: `SOURCEHUB_VERSION: X.X.X`
   - [ ] `readme.txt`: `Stable tag: X.X.X`

2. **Update changelog in readme.txt**

3. **Commit and tag:**
   ```bash
   git add -A
   git commit -m "Version X.X.X - Description"
   git tag X.X.X
   git push origin main
   git push --tags
   ```

4. **Refresh on sites:**
   - Dashboard → Updates → Check Again
   - OR wait for WP-Cron to check (runs periodically)

---

## Troubleshooting

### "No update available"

**Check:**
- [ ] Tag exists on GitHub: https://github.com/mikefreemantn/SourceHub/tags
- [ ] Tag version matches readme.txt stable tag
- [ ] Plugin folder name is `SourceHub` (matches repo name)
- [ ] Git Updater cache cleared
- [ ] No server/plugin caching interfering

### "Update failed"

**Check:**
- [ ] GitHub repo is accessible (public or token provided)
- [ ] File permissions allow plugin updates
- [ ] No conflicts with other plugins
- [ ] WordPress has write access to plugins directory

### Optional: Instant Updates via Webhook

For immediate update notifications:

1. Go to GitHub repo: https://github.com/mikefreemantn/SourceHub/settings/hooks
2. Add webhook with:
   - **Payload URL**: Your site's Git Updater webhook URL (found in Git Updater → Settings)
   - **Content type**: application/json
   - **Events**: Just the push event
3. Every new tag will instantly notify your site

---

## Current Status

✅ **Version 1.2.4 is live on GitHub**  
✅ **Git Updater headers configured**  
✅ **readme.txt created with proper stable tag**  
✅ **Git tag pushed and available**  

**Your sites should now detect the update automatically!**

Go to **Dashboard → Updates → Check Again** to see it.
