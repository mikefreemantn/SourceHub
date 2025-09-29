#!/bin/bash

# SourceHub GitHub Setup Script
# Run this script from the plugin directory to set up Git and push to GitHub

echo "🚀 SourceHub GitHub Setup"
echo "========================="

# Check if we're in the right directory
if [ ! -f "sourcehub.php" ]; then
    echo "❌ Error: Please run this script from the SourceHub plugin directory"
    exit 1
fi

# Initialize git if not already done
if [ ! -d ".git" ]; then
    echo "📦 Initializing Git repository..."
    git init
else
    echo "✅ Git repository already initialized"
fi

# Add all files
echo "📁 Adding files to Git..."
git add .

# Create initial commit
echo "💾 Creating initial commit..."
git commit -m "Initial commit: SourceHub WordPress Plugin

Features included:
- Hub & Spoke content syndication
- AI-powered content rewriting (OpenAI integration)
- Smart Links system with block editor integration
- Custom Smart Links for per-spoke URL customization
- Site wake-up system for sleeping/inactive sites
- Modern Google Material Design admin interface
- Full Yoast SEO integration
- Comprehensive logging and monitoring
- Featured image and metadata synchronization"

echo ""
echo "🎯 Next Steps:"
echo "1. Create a new repository on GitHub named 'SourceHub'"
echo "2. Copy the repository URL (e.g., https://github.com/yourusername/SourceHub.git)"
echo "3. Run these commands:"
echo ""
echo "   git remote add origin YOUR_GITHUB_REPO_URL"
echo "   git branch -M main"
echo "   git push -u origin main"
echo ""
echo "✨ Your SourceHub plugin will then be live on GitHub!"
