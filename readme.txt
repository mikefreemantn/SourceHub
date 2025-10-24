=== SourceHub - Hub & Spoke Publisher ===
Contributors: mikefreemantn
Tags: syndication, content distribution, multi-site, seo, hub-spoke
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.2.12
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: mikefreemantn/SourceHub
Primary Branch: main

A powerful content syndication plugin that enables centralized editorial teams to distribute content across multiple WordPress sites with full SEO integration.

== Description ==

 SourceHub transforms your WordPress network into a centralized content distribution system. Create content once on your hub site and automatically syndicate it to multiple spoke sites with intelligent processing, SEO optimization, and custom linking.

= Key Features =

* **Hub & Spoke Architecture** - Central content creation and management
* **AI-Powered Content Rewriting** - OpenAI integration with GPT-4 support
* **Smart Linking System** - Automatic URL adaptation for spoke sites
* **Full Yoast SEO Integration** - All meta fields sync automatically
* **Gallery Syndication** - Automatic image gallery download and ID remapping
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

= 1.2.12 - 2025-10-23 =
* Fix: Version constant mismatch resolved for Git Updater compatibility
* Enhancement: Added Git Updater headers to readme.txt for proper update handling
* Enhancement: All version references now consistent across plugin files

= 1.2.11 - 2025-10-23 =
* Feature: Added post format syndication (video, audio, gallery, standard, etc.)
* Fix: Corrected featured video field name (td_post_video without underscore)
* Fix: Corrected featured audio field name (td_post_audio without underscore)
* Enhancement: Post formats now sync automatically on create and update

= 1.2.10 - 2025-10-23 =
* Fix: Validation timing issue causing false warnings on direct publish
* Fix: Changed validation hook from transition_post_status to save_post
* Enhancement: Validation now runs after spoke selection is saved

= 1.2.9 - 2025-10-23 =
* Fix: Spoke selection no longer clears when saving drafts
* Fix: Posts now syndicate immediately on first publish without template
* Fix: Featured image detection using WordPress _thumbnail_id field
* Fix: TinyMCE script dependency error
* Enhancement: Real-time featured image validation with polling
* Enhancement: Removed template validation requirement (templates optional)

= 1.2.8 - 2025-10-22 =
* Fix: Smart Link modal buttons (Insert, Copy, Cancel) now work correctly
* Fix: Resolved TinyMCE dialog context issue causing button clicks to fail
* Enhancement: Added console logging for Smart Link button debugging

= 1.2.7 - 2025-10-22 =
* Enhancement: Added activity log entries for gallery syndication debugging
* Enhancement: Gallery processing now logs to activity log for easier troubleshooting
* Enhancement: Hub logs gallery image extraction and preparation
* Enhancement: Spoke logs gallery image download and remapping status

= 1.2.6 - 2025-10-22 =
* Feature: Gallery syndication - automatically downloads gallery images and remaps IDs
* Feature: Preserves gallery shortcodes during syndication for spoke-side processing
* Feature: Supports both classic WordPress galleries and Gutenberg gallery blocks
* Feature: Complete image metadata preservation (title, alt, caption, description)
* Fix: Yoast SEO auto-sync now uses delayed scheduling (3-second delay) to ensure meta is ready
* Enhancement: Improved debug logging for gallery image processing
* Enhancement: Added comprehensive error handling for image downloads

= 1.2.5 - 2025-10-21 =
* Test: Git Updater functionality test release
* Added: Version display in admin footer for verification

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
