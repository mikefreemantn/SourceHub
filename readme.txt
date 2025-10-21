=== SourceHub - Hub & Spoke Publisher ===
Contributors: mikefreemantn
Tags: syndication, content distribution, multi-site, seo, hub-spoke
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A powerful content syndication plugin that enables centralized editorial teams to distribute content across multiple WordPress sites with full SEO integration.

== Description ==

SourceHub transforms your WordPress network into a centralized content distribution system. Create content once on your hub site and automatically syndicate it to multiple spoke sites with intelligent processing, SEO optimization, and custom linking.

= Key Features =

* **Hub & Spoke Architecture** - Central content creation and management
* **AI-Powered Content Rewriting** - OpenAI integration with GPT-4 support
* **Smart Linking System** - Automatic URL adaptation for spoke sites
* **Full Yoast SEO Integration** - All meta fields sync automatically
* **Featured Image Sync** - Complete media handling with metadata
* **Real-time Monitoring** - Live syndication status and comprehensive logs
* **Modern Admin Interface** - Beautiful, responsive dashboard

= Use Cases =

* News organizations with multiple local market sites
* Multi-location businesses with location-specific content
* Digital agencies managing multiple client sites
* Enterprise content distribution networks

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/sourcehub/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Choose Hub or Spoke mode in SourceHub → Settings
4. Configure your connections and start syndicating!

== Changelog ==

= 1.2.4 - 2025-10-21 =
* Performance: Fixed critical 21-second page load issue
* Performance: Eliminated infinite validation loop
* Performance: Optimized database queries (51 queries → 1 query)
* Performance: Added composite indexes for faster filtering
* Performance: Removed excessive debug logging
* Feature: Added featured image title to syndication
* Feature: Complete image metadata now preserved (title, alt, caption, description)
* Fix: Property access errors in logs view
* Fix: Undefined variable warnings in templates
* Fix: Data type mismatches between logger and templates
* Added: Performance profiling for monitoring
* Added: Git Updater support headers

= 1.2.3 - 2025-09-30 =
* Initial public release
* Hub & Spoke architecture
* AI rewriting with OpenAI
* Smart links and custom smart links
* Yoast SEO integration
* Newspaper theme integration

== Upgrade Notice ==

= 1.2.4 =
Major performance improvements! Logs page now loads in under 1 second (previously 21 seconds). Featured image titles now sync properly. Recommended update for all users.

== Frequently Asked Questions ==

= What is Hub & Spoke architecture? =

Hub & Spoke is a content distribution model where you create content once on a central "hub" site and automatically distribute it to multiple "spoke" sites. Each spoke can have custom AI rewriting, smart links, and SEO settings.

= Do I need OpenAI for this to work? =

No, OpenAI is optional. The plugin works perfectly without AI rewriting. If you want to automatically customize content for each spoke site, you'll need an OpenAI API key.

= Does this work with Yoast SEO? =

Yes! SourceHub has full Yoast SEO integration. All meta titles, descriptions, focus keywords, and other Yoast fields are automatically synced to spoke sites.

= Can I use this with multisite? =

Yes, SourceHub is multisite compatible and can work across separate WordPress installations or within a multisite network.

== Screenshots ==

1. Dashboard with real-time syndication monitoring
2. Connection management interface
3. Smart links configuration
4. AI rewriting settings per spoke
5. Activity logs with performance metrics

== Support ==

For support, feature requests, or bug reports, please visit:
https://github.com/mikefreemantn/SourceHub/issues
