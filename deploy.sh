#!/bin/bash

# Check if version argument provided
if [ -z "$1" ]; then
    echo "Usage: ./deploy.sh v1.7.0"
    exit 1
fi

VERSION=$1

# Update version in sourcehub.php
sed -i '' "s/Version: [0-9][0-9.]*$/Version: ${VERSION#v}/" sourcehub.php
sed -i '' "s/SOURCEHUB_VERSION', '[0-9][0-9.]*'/SOURCEHUB_VERSION', '${VERSION#v}'/" sourcehub.php

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
