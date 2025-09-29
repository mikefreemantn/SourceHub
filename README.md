# SourceHub - Hub & Spoke WordPress Plugin

A powerful content syndication plugin that enables centralized editorial teams to distribute content across multiple WordPress sites with full SEO integration, AI rewriting, and smart linking capabilities.

## 🎯 Overview

SourceHub transforms your WordPress network into a centralized content distribution system. Create content once on your hub site and automatically syndicate it to multiple spoke sites with intelligent processing, SEO optimization, and custom linking.

## ✨ Key Features

### 🏢 **Hub & Spoke Architecture**
- **Hub Mode**: Central content creation and management
- **Spoke Mode**: Automated content receiving and publishing
- **Dual Mode Plugin**: Single plugin works in both modes

### 🤖 **AI-Powered Content Rewriting**
- **OpenAI Integration**: GPT-4, GPT-4 Turbo, GPT-4o Mini support
- **Per-Spoke Customization**: Different AI settings for each spoke site
- **Per-Post Control**: Override AI settings on individual posts
- **Tone & Style**: Customize rewriting tone and instructions

### 🔗 **Smart Linking System**
- **Smart Links**: Automatic URL adaptation (`/weather` → `spoke1.com/weather`)
- **Custom Smart Links**: Unique URLs per spoke site for external links
- **Block Editor Integration**: Visual formatting tools in WordPress editor
- **AI Compatible**: Links preserved during AI rewriting

### 🎨 **Modern Admin Interface**
- **Google Material Design**: Beautiful, responsive dashboard
- **Real-time Monitoring**: Live syndication status and logs
- **Connection Management**: Easy spoke site setup and testing
- **Activity Logging**: Comprehensive audit trail

### 🔍 **SEO & Technical Features**
- **Full Yoast SEO Integration**: All meta fields sync automatically
- **Canonical URL Management**: Proper SEO structure maintained
- **Featured Image Sync**: Complete media handling
- **Category & Tag Mapping**: Taxonomy synchronization
- **Site Wake-Up System**: Handles sleeping/inactive sites

## 🚀 **Quick Start**

### Installation
1. Upload the plugin to `/wp-content/plugins/sourcehub/`
2. Activate the plugin through WordPress admin
3. Choose Hub or Spoke mode in SourceHub settings

### Hub Site Setup
1. Set mode to "Hub" in SourceHub → Settings
2. Add spoke connections in SourceHub → Connections
3. Configure AI settings (optional) in SourceHub → Settings → AI Integration
4. Start creating and syndicating content!

### Spoke Site Setup
1. Set mode to "Spoke" in SourceHub → Settings
2. Copy the API key and provide it to your hub site
3. Content will automatically appear when syndicated from hub

## 🛠 **Technical Requirements**

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher
- **Yoast SEO**: Recommended for full SEO features
- **OpenAI API Key**: Required for AI rewriting features

## 📋 **Use Cases**

### 🗞️ **News Organizations**
- Central newsroom creates content
- Automatic distribution to local market sites
- AI adapts content for local audiences
- Consistent SEO and branding

### 🏢 **Multi-Location Businesses**
- Corporate content creation
- Location-specific customization
- Smart links adapt to local pages
- Centralized content management

### 📱 **Digital Agencies**
- Manage multiple client sites
- Efficient content distribution
- Custom AI settings per client
- Comprehensive reporting

## 🔧 **Advanced Features**

### Smart Links
```html
<!-- In Editor -->
Visit our <smart-link>/weather</smart-link> section

<!-- On Spoke Sites -->
Visit our <a href="https://spoke1.com/weather">weather</a> section
Visit our <a href="https://spoke2.com/weather">weather</a> section
```

### Custom Smart Links
```html
<!-- Different URLs per spoke -->
Check your <custom-smart-link>county website</custom-smart-link>
<!-- spoke1.com → links to dallascounty.org -->
<!-- spoke2.com → links to harriscounty.org -->
```

### AI Rewriting
- **Automatic**: Content rewritten based on spoke settings
- **Per-Post Override**: Disable AI for specific posts
- **Tone Control**: Professional, casual, local, etc.
- **Custom Instructions**: Specific rewriting guidelines

## 📊 **Monitoring & Logs**

- **Real-time Dashboard**: Live syndication status
- **Activity Logs**: Detailed operation history
- **Success Metrics**: Syndication success rates
- **Error Tracking**: Comprehensive error logging
- **Wake-up Monitoring**: Site responsiveness tracking

## 🔐 **Security**

- **API Key Authentication**: Secure hub-spoke communication
- **Nonce Verification**: CSRF protection
- **Permission Checks**: Proper user capability validation
- **Input Sanitization**: All data properly sanitized
- **Audit Logging**: Complete activity tracking

## 🤝 **Contributing**

We welcome contributions! Please see our contributing guidelines and submit pull requests for any improvements.

## 📄 **License**

This plugin is licensed under the GPL v2 or later.

## 🆘 **Support**

For support, feature requests, or bug reports, please open an issue on GitHub.

---

**Made with ❤️ for the WordPress community**
