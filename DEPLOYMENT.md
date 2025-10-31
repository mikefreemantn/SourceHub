# SourceHub Deployment Guide

## Prerequisites
- Git installed and configured
- GitHub CLI (`gh`) installed: `brew install gh`
- GitHub authentication configured: `gh auth login`

## Deployment Checklist

### 1. Update Version Number
Before creating a release, update the version number in **two places**:

**File: `sourcehub.php`**
```php
/**
 * Version: 1.5.0  // <-- Update this
 */

define('SOURCEHUB_VERSION', '1.5.0');  // <-- And this
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

### 4. Create GitHub Release with Zipball
This is the **critical step** for GitUpdater to work!

```bash
gh release create v1.5.0 \
  --title "v1.5.0 - Release Title" \
  --notes "## New Features
- Feature 1
- Feature 2

## Bug Fixes
- Fix 1
- Fix 2

## Improvements
- Improvement 1"
```

**Important:** Do NOT manually upload a zip file. GitHub automatically generates source code archives (zipball/tarball) that GitUpdater uses.

### 5. Verify Release
Check that the release appears at:
```
https://github.com/mikefreemantn/SourceHub/releases
```

Verify the zipball URL exists:
```
https://api.github.com/repos/mikefreemantn/SourceHub/zipball/v1.5.0
```

### 6. Test GitUpdater
1. Go to WordPress admin on a test site
2. Navigate to **Dashboard → Updates**
3. Click **Check Again**
4. Verify the new version appears

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
