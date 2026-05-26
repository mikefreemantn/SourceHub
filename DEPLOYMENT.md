# SourceHub Deployment Guide

## Prerequisites
- Git installed and configured
- GitHub CLI (`gh`) installed: `brew install gh`
- GitHub authentication configured: `gh auth login`

## Required Plugin Headers (READ FIRST)

GitUpdater needs **all** of the following headers in `sourcehub.php` to track tagged releases instead of falling back to branch HEAD:

```php
/**
 * Plugin Name: SourceHub - Hub & Spoke Publisher
 * Version: 2.4.1
 * GitHub Plugin URI: https://github.com/mikefreemantn/SourceHub
 * Primary Branch: main
 * Release Asset: true
 * Update URI: https://github.com/mikefreemantn/SourceHub
 */
```

If `Release Asset: true` is missing, GitUpdater may treat the install as a **branch install** (tracking `main` HEAD) and ignore tagged releases. The Plugins screen / GitUpdater status panel will show "Current branch is main / No previous tags to rollback to" — that is the smoking gun.

## Critical Gotcha: Branch-Installed vs Release-Installed Plugins

If a site installed SourceHub by downloading the `main` branch zip (e.g. `Code -> Download ZIP`) **before** the `Release Asset: true` header existed, GitUpdater permanently tracks that install as a branch install. **Headers alone will not fix that site.** You must:

1. Deactivate SourceHub
2. Delete the plugin
3. Reinstall via GitUpdater **Install Plugin** using the GitHub repo URL (so it pulls from a tagged release)

After that, future tag-based releases auto-update normally.

## Deployment Checklist

### 1. Update Version Number
Before creating a release, update the version number in **two places**:

**File: `sourcehub.php`**
```php
/**
 * Version: 2.4.1  // <-- Update this
 */

define('SOURCEHUB_VERSION', '2.4.1');  // <-- And this
```

### 2. Commit Your Changes
```bash
git add -A
git commit -m "Your commit message describing the changes"
git push origin main
```

### 3. Create and Push Git Tag
```bash
# Create annotated tag with release notes
git tag -a v1.5.0 -m "v1.5.0 - Brief description

Detailed release notes here..."

# Push the tag to GitHub
git push origin v1.5.0
```

### 4. Create GitHub Release
This is the **critical step** for GitUpdater to detect the update.

```bash
gh release create v2.4.1 \
  --title "v2.4.1 - Release Title" \
  --notes "## Fixes
- Fix 1
- Fix 2

## New Features
- Feature 1"
```

### 5. Build and Upload Release Zip Asset
With `Release Asset: true` in the plugin header, GitUpdater uses the **uploaded zip asset** on the release rather than GitHub's auto-generated zipball. Always upload the zip explicitly:

```bash
# Build a clean zip from the tagged commit (no untracked logs/dumps/etc.)
git archive --format=zip --prefix=SourceHub/ -o /tmp/SourceHub-v2.4.1.zip v2.4.1

# Upload to the release
gh release upload v2.4.1 /tmp/SourceHub-v2.4.1.zip
```

Notes:
- `git archive` ensures only tracked files are included (no `.DS_Store`, no local log dumps, no `node_modules`, etc.).
- The `--prefix=SourceHub/` keeps the install structure clean inside the zip.
- The asset filename should clearly include the version (e.g. `SourceHub-v2.4.1.zip`).

### 6. Verify Release
Check that the release page shows the zip asset:
```
https://github.com/mikefreemantn/SourceHub/releases/tag/v2.4.1
```

Both URLs below should return data:
```
# Auto-zipball (fallback)
https://api.github.com/repos/mikefreemantn/SourceHub/zipball/v2.4.1

# Explicit asset (preferred by GitUpdater with Release Asset: true)
https://github.com/mikefreemantn/SourceHub/releases/download/v2.4.1/SourceHub-v2.4.1.zip
```

### 7. Clear Caches & Test GitUpdater
On each hub/spoke site, after the release is live:

1. **Clear transients** (via WP-CLI or a plugin):
   ```bash
   wp transient delete --all
   ```
2. **GitUpdater -> Refresh Cache**
3. **Dashboard -> Updates -> Check Again**
4. SourceHub should now show the new version as available.

If a site still shows the old version after this:
- Confirm the plugin header on disk actually says the new version (`grep "Version:" /path/to/sourcehub.php`).
- Confirm the install isn't branch-locked (see the "Critical Gotcha" section at the top of this doc).
- Last resort: deactivate, delete, reinstall via GitUpdater **Install Plugin** to switch from branch-tracking to release-tracking.

## Common Issues

### GitUpdater Not Detecting Update
**Cause:** Version number in `sourcehub.php` doesn't match the tag version.

**Solution:** 
1. Update version in `sourcehub.php`
2. Commit the change
3. Delete and recreate the tag:
```bash
git tag -d v1.5.0
git tag -a v1.5.0 -m "Release notes"
git push origin v1.5.0 --force
```

### Wrong Zip Structure
**Cause:** Manually created zip with incorrect folder structure.

**Solution:** Don't upload custom zips. GitHub's automatic zipball has the correct structure:
```
SourceHub-abc1234/
  ├── sourcehub.php
  ├── admin/
  ├── includes/
  └── ...
```

### Release Not Showing on GitHub
**Cause:** Tag was pushed but release wasn't created.

**Solution:** Use `gh release create` command (see step 4 above).

## Version Numbering

Follow semantic versioning: `MAJOR.MINOR.PATCH`

- **MAJOR** (1.x.x): Breaking changes
- **MINOR** (x.5.x): New features, non-breaking changes
- **PATCH** (x.x.1): Bug fixes only

Examples:
- Bug fixes only: `1.4.3` → `1.4.4`
- New features: `1.4.3` → `1.5.0`
- Breaking changes: `1.4.3` → `2.0.0`

## Quick Deploy Script

Save this as `deploy.sh` for faster deployments:

```bash
#!/bin/bash

# Check if version argument provided
if [ -z "$1" ]; then
    echo "Usage: ./deploy.sh v1.5.0"
    exit 1
fi

VERSION=$1

# Update version in sourcehub.php
sed -i '' "s/Version: [0-9.]\+/Version: ${VERSION#v}/" sourcehub.php
sed -i '' "s/SOURCEHUB_VERSION', '[0-9.]\+'/SOURCEHUB_VERSION', '${VERSION#v}'/" sourcehub.php

# Commit version bump
git add sourcehub.php
git commit -m "Bump version to ${VERSION#v}"
git push origin main

# Create and push tag
git tag -a $VERSION -m "$VERSION"
git push origin $VERSION

# Create GitHub release
gh release create $VERSION \
  --title "$VERSION" \
  --generate-notes

echo "✅ Deployment complete! Check: https://github.com/mikefreemantn/SourceHub/releases"
```

Make executable: `chmod +x deploy.sh`

Usage: `./deploy.sh v1.5.0`

## Notes

- **Always** update version in `sourcehub.php` before tagging
- **Never** manually upload zip files to releases
- GitHub's automatic zipball is what GitUpdater uses
- Test on a staging site before deploying to production
- Keep release notes clear and organized
