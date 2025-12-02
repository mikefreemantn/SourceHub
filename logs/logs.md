[2025-12-01T13:09:31.834270+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:09:32.595670+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:09:33.484265+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-01T13:09:33.484289+00:00] SourceHub Spoke: API key match: YES
[2025-12-01T13:09:33.490041+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 1 OFFSET 0
[2025-12-01T13:09:33.490058+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 1#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-01T13:09:33.492962+00:00] SourceHub get_logs results count: 1
[2025-12-01T13:09:33.493537+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-01T13:09:33.493548+00:00] SourceHub Spoke: API key match: YES
[2025-12-01T13:09:33.493561+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-01T13:09:33.493565+00:00] SourceHub Spoke: API key match: YES
[2025-12-01T13:09:34.976043+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:09:35.826772+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:09:36.877739+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"wp-core","name":"wp-includes","slug":"wp-includes","ver":"6.8.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.877753+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"wp-core","name":"wp-admin","slug":"wp-admin","ver":"6.8.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.908414+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"Bill Catch Errors","slug":"bill-catch-errors.php","ver":"4.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.908527+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine System","slug":"mu-plugin.php","ver":"6.5.2","sig":"v1:nohash"}
[2025-12-01T13:09:36.909172+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"Force Strong Passwords - WPE Edition","slug":"slt-force-strong-passwords.php","ver":"1.8.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.909226+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Cache Plugin","slug":"wpe-cache-plugin.php","ver":"1.3.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.909783+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Update Source Selector","slug":"wpe-update-source-selector.php","ver":"1.1.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.910732+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Seamless Login Plugin","slug":"wpe-wp-sign-on-plugin.php","ver":"1.6.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.911336+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Security Auditor","slug":"wpengine-security-auditor.php","ver":"1.1.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.911684+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"AirLift","slug":"airlift/airlift.php","ver":"6.15","sig":"v1:nohash"}
[2025-12-01T13:09:36.911733+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Broadstreet","slug":"broadstreet/broadstreet.php","ver":"1.51.10","sig":"v1:nohash"}
[2025-12-01T13:09:36.911771+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Bulk Delete","slug":"bulk-delete/bulk-delete.php","ver":"6.0.2","sig":"v1:nohash"}
[2025-12-01T13:09:36.911829+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Classic Editor","slug":"classic-editor/classic-editor.php","ver":"1.6.7","sig":"v1:nohash"}
[2025-12-01T13:09:36.911871+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Contact Form 7","slug":"contact-form-7/wp-contact-form-7.php","ver":"6.1.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.911918+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Bulk Post Category Creator","slug":"create-category-in-bulk/bulk-category-creator-wordpress.php","ver":"1.7","sig":"v1:nohash"}
[2025-12-01T13:09:36.911973+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Debug Log Manager","slug":"debug-log-manager/debug-log-manager.php","ver":"2.4.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.912040+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Download Plugin","slug":"download-plugin/download-plugin.php","ver":"2.3.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.912088+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast Duplicate Post","slug":"duplicate-post/duplicate-post.php","ver":"4.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.912137+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Empowerlocal","slug":"empowerlocal-wordpress-plugin/empowerlocal.php","ver":"1.1.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.912190+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Git Remote Updater","slug":"git-remote-updater/git-remote-updater.php","ver":"3.2.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.912239+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Git Updater","slug":"git-updater/git-updater.php","ver":"12.19.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.912284+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Hometown Holidays","slug":"hometown-holidays/hometown-holidays.php","ver":"1.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.912330+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Imagify","slug":"imagify/imagify.php","ver":"2.2.6","sig":"v1:nohash"}
[2025-12-01T13:09:36.912393+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"WPCode Lite","slug":"insert-headers-and-footers/ihaf.php","ver":"2.3.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.912477+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Login With Ajax","slug":"login-with-ajax/login-with-ajax.php","ver":"4.5.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.912533+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"One User Avatar","slug":"one-user-avatar/one-user-avatar.php","ver":"2.5.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.912592+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Quiz Maker","slug":"quiz-maker/quiz-maker.php","ver":"6.7.0.76","sig":"v1:nohash"}
[2025-12-01T13:09:36.912662+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Resize Image After Upload","slug":"resize-image-after-upload/resize-image-after-upload.php","ver":"1.8.6","sig":"v1:nohash"}
[2025-12-01T13:09:36.912712+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Restore and Enable Classic Widgets No Expiration","slug":"restore-classic-widgets/restore_classic_widgets.php","ver":"4.86","sig":"v1:nohash"}
[2025-12-01T13:09:36.912764+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"SourceHub - Hub & Spoke Publisher","slug":"sourcehub/sourcehub.php","ver":"1.9.8.7","sig":"v1:nohash"}
[2025-12-01T13:09:36.912824+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Cloud Library","slug":"td-cloud-library/td-cloud-library.php","ver":"3.9 | built on 27.03.2025 10:27","sig":"v1:nohash"}
[2025-12-01T13:09:36.912877+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Composer","slug":"td-composer/td-composer.php","ver":"5.4.1 | built on 29.04.2025 10:27","sig":"v1:nohash"}
[2025-12-01T13:09:36.912925+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Newsletter","slug":"td-newsletter/td-newsletter.php","ver":"2.1 | built on 25.04.2025 8:30","sig":"v1:nohash"}
[2025-12-01T13:09:36.912989+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Social Counter","slug":"td-social-counter/td-social-counter.php","ver":"5.6 | built on 11.04.2025 11:20","sig":"v1:nohash"}
[2025-12-01T13:09:36.913030+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Standard Pack","slug":"td-standard-pack/td-standard-pack.php","ver":"2.7 | built on 11.04.2025 11:20","sig":"v1:nohash"}
[2025-12-01T13:09:36.913081+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Theme Downloader","slug":"theme-downloader/theme-downloader.php","ver":"1.1.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.913135+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Social Media and Share Icons (Ultimate Social Media)","slug":"ultimate-social-media-icons/ultimate_social_media_icons.php","ver":"2.9.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.913179+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Wordfence Security","slug":"wordfence/wordfence.php","ver":"8.1.2","sig":"v1:nohash"}
[2025-12-01T13:09:36.913215+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast SEO Premium","slug":"wordpress-seo-premium/wp-seo-premium.php","ver":"25.9","sig":"v1:nohash"}
[2025-12-01T13:09:36.913257+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast SEO","slug":"wordpress-seo/wp-seo.php","ver":"26.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.913304+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"WP Maps","slug":"wp-google-map-plugin/wp-google-map-plugin.php","ver":"4.8.6","sig":"v1:nohash"}
[2025-12-01T13:09:36.913339+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Akismet Anti-spam: Spam Protection","slug":"akismet/akismet.php","ver":"5.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.913400+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"FS Poster","slug":"fs-poster/init.php","ver":"7.3.2","sig":"v1:nohash"}
[2025-12-01T13:09:36.913453+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter","slug":"newsletter/plugin.php","ver":"9.0.7","sig":"v1:nohash"}
[2025-12-01T13:09:36.913470+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Addons Manager and Support","slug":"newsletter-extensions/extensions.php","ver":"1.3.8","sig":"v1:nohash"}
[2025-12-01T13:09:36.913483+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Archive","slug":"newsletter-archive/archive.php","ver":"4.1.8","sig":"v1:nohash"}
[2025-12-01T13:09:36.913494+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Automated Newsletters","slug":"newsletter-automated/automated.php","ver":"4.8.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.913506+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Contact Form 7 Integration","slug":"newsletter-cf7/cf7.php","ver":"4.4.8","sig":"v1:nohash"}
[2025-12-01T13:09:36.913517+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Extended Composer Blocks","slug":"newsletter-blocks/blocks.php","ver":"1.6.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.913533+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Geolocation","slug":"newsletter-geo/geo.php","ver":"1.3.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.913545+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Google Analytics","slug":"newsletter-analytics/analytics.php","ver":"1.3.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.913560+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Import/Export","slug":"newsletter-import/import.php","ver":"1.5.9","sig":"v1:nohash"}
[2025-12-01T13:09:36.913572+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Leads Addon","slug":"newsletter-leads/leads.php","ver":"1.6.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.913588+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Reports and Retargeting","slug":"newsletter-reports/reports.php","ver":"4.7.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.913601+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Sendgrid Addon","slug":"newsletter-sendgrid/sendgrid.php","ver":"4.5.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.913620+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Poll Maker","slug":"poll-maker/poll-maker-ays.php","ver":"21.4.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.913649+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"RSS Control","slug":"rss-control/rss-control.php","ver":"3.0.14","sig":"v1:nohash"}
[2025-12-01T13:09:36.913671+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"SourceLocal Child Site Plugin","slug":"sourcelocal_child_site_plugin/sourcelocal_child_site_plugin.php","ver":"1.7.0","sig":"v1:nohash"}
[2025-12-01T13:09:36.913687+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Survey Maker","slug":"survey-maker/survey-maker.php","ver":"5.1.9.4","sig":"v1:nohash"}
[2025-12-01T13:09:36.913699+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"TablePress","slug":"tablepress/tablepress.php","ver":"3.2.5","sig":"v1:nohash"}
[2025-12-01T13:09:36.913740+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Trending Article Manager","slug":"trending-article-manager/trending-article-manager.php","ver":"3.1","sig":"v1:nohash"}
[2025-12-01T13:09:36.913755+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Weather Write","slug":"weather-write/weather-write.php","ver":"1.2.6","sig":"v1:nohash"}
[2025-12-01T13:09:36.913774+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"WP Crontrol","slug":"wp-crontrol/wp-crontrol.php","ver":"1.19.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.913792+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"WP Rocket","slug":"wp-rocket/wp-rocket.php","ver":"3.18.3","sig":"v1:nohash"}
[2025-12-01T13:09:36.913818+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Zapier for WordPress","slug":"zapier/zapier.php","ver":"1.5.3","sig":"v1:nohash"}
[2025-12-01T13:09:37.322669+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"theme","name":"Newspaper","slug":"Newspaper","ver":"12.7.1","sig":"v1:nohash"}
[2025-12-01T13:09:41.489955+00:00] Cron unschedule event error for hook: wordfence_start_scheduled_scan, Error code: could_not_set, Error message: The cron event list could not be saved., Data: {"schedule":false,"args":[1764591954]}
[2025-12-01T13:09:41.688755+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:09:48.656024+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-01T13:10:28.423636+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:10:28.754234+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:11:15.411766+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:11:15.752079+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:12:02.404849+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:12:02.730070+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:12:48.375999+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:12:48.704629+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:13:34.440095+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:13:34.808962+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:14:21.435728+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:14:21.751120+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:15:07.453614+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:15:07.809783+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:15:54.447428+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:15:54.757634+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:16:41.436064+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:16:41.760392+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:17:27.406420+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:17:27.725865+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:18:13.658621+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:18:14.140733+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:19:00.445518+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:19:00.799212+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:19:47.395709+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:19:47.726799+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:20:33.653038+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:20:33.958310+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:21:20.421201+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:21:20.742694+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:22:06.463881+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:22:07.055867+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:22:53.285488+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:22:53.580130+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T13:23:39.396978+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T21:59:21.867024+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T21:59:22.528166+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T21:59:22.870050+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"Bill Catch Errors","slug":"bill-catch-errors.php","ver":"4.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.870115+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine System","slug":"mu-plugin.php","ver":"6.5.2","sig":"v1:nohash"}
[2025-12-01T21:59:22.870769+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"Force Strong Passwords - WPE Edition","slug":"slt-force-strong-passwords.php","ver":"1.8.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.870824+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Cache Plugin","slug":"wpe-cache-plugin.php","ver":"1.3.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.871382+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Update Source Selector","slug":"wpe-update-source-selector.php","ver":"1.1.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.872165+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Seamless Login Plugin","slug":"wpe-wp-sign-on-plugin.php","ver":"1.6.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.872843+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Security Auditor","slug":"wpengine-security-auditor.php","ver":"1.1.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.873117+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"AirLift","slug":"airlift/airlift.php","ver":"6.15","sig":"v1:nohash"}
[2025-12-01T21:59:22.873172+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Broadstreet","slug":"broadstreet/broadstreet.php","ver":"1.51.10","sig":"v1:nohash"}
[2025-12-01T21:59:22.873210+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Bulk Delete","slug":"bulk-delete/bulk-delete.php","ver":"6.0.2","sig":"v1:nohash"}
[2025-12-01T21:59:22.873252+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Classic Editor","slug":"classic-editor/classic-editor.php","ver":"1.6.7","sig":"v1:nohash"}
[2025-12-01T21:59:22.873301+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Contact Form 7","slug":"contact-form-7/wp-contact-form-7.php","ver":"6.1.3","sig":"v1:nohash"}
[2025-12-01T21:59:22.873358+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Bulk Post Category Creator","slug":"create-category-in-bulk/bulk-category-creator-wordpress.php","ver":"1.7","sig":"v1:nohash"}
[2025-12-01T21:59:22.873394+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Debug Log Manager","slug":"debug-log-manager/debug-log-manager.php","ver":"2.4.3","sig":"v1:nohash"}
[2025-12-01T21:59:22.873429+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Download Plugin","slug":"download-plugin/download-plugin.php","ver":"2.3.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.873511+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast Duplicate Post","slug":"duplicate-post/duplicate-post.php","ver":"4.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.873560+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Empowerlocal","slug":"empowerlocal-wordpress-plugin/empowerlocal.php","ver":"1.1.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.873598+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Git Remote Updater","slug":"git-remote-updater/git-remote-updater.php","ver":"3.2.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.873628+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Git Updater","slug":"git-updater/git-updater.php","ver":"12.19.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.873670+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Hometown Holidays","slug":"hometown-holidays/hometown-holidays.php","ver":"1.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.873721+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Imagify","slug":"imagify/imagify.php","ver":"2.2.6","sig":"v1:nohash"}
[2025-12-01T21:59:22.873770+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"WPCode Lite","slug":"insert-headers-and-footers/ihaf.php","ver":"2.3.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.873834+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Login With Ajax","slug":"login-with-ajax/login-with-ajax.php","ver":"4.5.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.873891+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"One User Avatar","slug":"one-user-avatar/one-user-avatar.php","ver":"2.5.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.873948+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Quiz Maker","slug":"quiz-maker/quiz-maker.php","ver":"6.7.0.76","sig":"v1:nohash"}
[2025-12-01T21:59:22.874011+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Resize Image After Upload","slug":"resize-image-after-upload/resize-image-after-upload.php","ver":"1.8.6","sig":"v1:nohash"}
[2025-12-01T21:59:22.874062+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Restore and Enable Classic Widgets No Expiration","slug":"restore-classic-widgets/restore_classic_widgets.php","ver":"4.86","sig":"v1:nohash"}
[2025-12-01T21:59:22.874119+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"SourceHub - Hub & Spoke Publisher","slug":"sourcehub/sourcehub.php","ver":"1.9.8.7","sig":"v1:nohash"}
[2025-12-01T21:59:22.874178+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Cloud Library","slug":"td-cloud-library/td-cloud-library.php","ver":"3.9 | built on 27.03.2025 10:27","sig":"v1:nohash"}
[2025-12-01T21:59:22.874238+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Composer","slug":"td-composer/td-composer.php","ver":"5.4.1 | built on 29.04.2025 10:27","sig":"v1:nohash"}
[2025-12-01T21:59:22.874300+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Newsletter","slug":"td-newsletter/td-newsletter.php","ver":"2.1 | built on 25.04.2025 8:30","sig":"v1:nohash"}
[2025-12-01T21:59:22.874369+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Social Counter","slug":"td-social-counter/td-social-counter.php","ver":"5.6 | built on 11.04.2025 11:20","sig":"v1:nohash"}
[2025-12-01T21:59:22.874421+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Standard Pack","slug":"td-standard-pack/td-standard-pack.php","ver":"2.7 | built on 11.04.2025 11:20","sig":"v1:nohash"}
[2025-12-01T21:59:22.874511+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Theme Downloader","slug":"theme-downloader/theme-downloader.php","ver":"1.1.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.874581+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Social Media and Share Icons (Ultimate Social Media)","slug":"ultimate-social-media-icons/ultimate_social_media_icons.php","ver":"2.9.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.874628+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Wordfence Security","slug":"wordfence/wordfence.php","ver":"8.1.2","sig":"v1:nohash"}
[2025-12-01T21:59:22.874681+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast SEO Premium","slug":"wordpress-seo-premium/wp-seo-premium.php","ver":"25.9","sig":"v1:nohash"}
[2025-12-01T21:59:22.874726+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast SEO","slug":"wordpress-seo/wp-seo.php","ver":"26.3","sig":"v1:nohash"}
[2025-12-01T21:59:22.874775+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"WP Maps","slug":"wp-google-map-plugin/wp-google-map-plugin.php","ver":"4.8.6","sig":"v1:nohash"}
[2025-12-01T21:59:22.874809+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Akismet Anti-spam: Spam Protection","slug":"akismet/akismet.php","ver":"5.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.874866+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"FS Poster","slug":"fs-poster/init.php","ver":"7.3.2","sig":"v1:nohash"}
[2025-12-01T21:59:22.874902+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter","slug":"newsletter/plugin.php","ver":"9.0.7","sig":"v1:nohash"}
[2025-12-01T21:59:22.874920+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Addons Manager and Support","slug":"newsletter-extensions/extensions.php","ver":"1.3.8","sig":"v1:nohash"}
[2025-12-01T21:59:22.874936+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Archive","slug":"newsletter-archive/archive.php","ver":"4.1.8","sig":"v1:nohash"}
[2025-12-01T21:59:22.874951+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Automated Newsletters","slug":"newsletter-automated/automated.php","ver":"4.8.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.874965+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Contact Form 7 Integration","slug":"newsletter-cf7/cf7.php","ver":"4.4.8","sig":"v1:nohash"}
[2025-12-01T21:59:22.874977+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Extended Composer Blocks","slug":"newsletter-blocks/blocks.php","ver":"1.6.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.874990+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Geolocation","slug":"newsletter-geo/geo.php","ver":"1.3.3","sig":"v1:nohash"}
[2025-12-01T21:59:22.875004+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Google Analytics","slug":"newsletter-analytics/analytics.php","ver":"1.3.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.875019+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Import/Export","slug":"newsletter-import/import.php","ver":"1.5.9","sig":"v1:nohash"}
[2025-12-01T21:59:22.875032+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Leads Addon","slug":"newsletter-leads/leads.php","ver":"1.6.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.875044+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Reports and Retargeting","slug":"newsletter-reports/reports.php","ver":"4.7.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.875059+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Sendgrid Addon","slug":"newsletter-sendgrid/sendgrid.php","ver":"4.5.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.875078+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Poll Maker","slug":"poll-maker/poll-maker-ays.php","ver":"21.4.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.875109+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"RSS Control","slug":"rss-control/rss-control.php","ver":"3.0.14","sig":"v1:nohash"}
[2025-12-01T21:59:22.875125+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"SourceLocal Child Site Plugin","slug":"sourcelocal_child_site_plugin/sourcelocal_child_site_plugin.php","ver":"1.7.0","sig":"v1:nohash"}
[2025-12-01T21:59:22.875134+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Survey Maker","slug":"survey-maker/survey-maker.php","ver":"5.1.9.4","sig":"v1:nohash"}
[2025-12-01T21:59:22.875142+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"TablePress","slug":"tablepress/tablepress.php","ver":"3.2.5","sig":"v1:nohash"}
[2025-12-01T21:59:22.875165+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Trending Article Manager","slug":"trending-article-manager/trending-article-manager.php","ver":"3.1","sig":"v1:nohash"}
[2025-12-01T21:59:22.875176+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Weather Write","slug":"weather-write/weather-write.php","ver":"1.2.6","sig":"v1:nohash"}
[2025-12-01T21:59:22.875189+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"WP Crontrol","slug":"wp-crontrol/wp-crontrol.php","ver":"1.19.3","sig":"v1:nohash"}
[2025-12-01T21:59:22.875200+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"WP Rocket","slug":"wp-rocket/wp-rocket.php","ver":"3.18.3","sig":"v1:nohash"}
[2025-12-01T21:59:22.875216+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Zapier for WordPress","slug":"zapier/zapier.php","ver":"1.5.3","sig":"v1:nohash"}
[2025-12-01T21:59:23.382988+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"wp-core","name":"wp-includes","slug":"wp-includes","ver":"6.8.3","sig":"v1:nohash"}
[2025-12-01T21:59:23.383003+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"wp-core","name":"wp-admin","slug":"wp-admin","ver":"6.8.3","sig":"v1:nohash"}
[2025-12-01T21:59:24.754960+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"theme","name":"Newspaper","slug":"Newspaper","ver":"12.7.1","sig":"v1:nohash"}
[2025-12-01T22:11:20.385482+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:20.727362+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:21.733073+00:00] auditor:event=wp_login {"user_id":38,"blog_id":1,"event":"wp_login","current_user_id":0,"remote_addr":"75.30.67.77"}
[2025-12-01T22:11:23.180807+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:24.353666+00:00] Array#012(#012    [headers] => WpOrg\Requests\Utility\CaseInsensitiveDictionary Object#012        (#012            [data:protected] => Array#012                (#012                    [date] => Mon, 01 Dec 2025 22:11:24 GMT#012                    [content-type] => text/html; charset=UTF-8#012                    [content-encoding] => br#012                    [x-powered-by] => PHP/8.0.20#012                    [access-control-allow-origin] => *#012                    [access-control-allow-methods] => POST,GET,OPTIONS#012                    [access-control-allow-headers] => Origin, X-Requested-With, Content-Type, Accept#012                    [cf-cache-status] => DYNAMIC#012                    [report-to] => {"endpoints":[{"url":"https:\/\/a.nel.cloudflare.com\/report\/v4?s=wfbeu3%2FX8dHrWciavyIj9aq8GCFEjgHK969UbaZoHcsXoM5EI9haxRtuJXXno44SOZI5Il7d%2FHNrc3VPoCo%2B4kbx7YQ119riJEBVdmaAqZjCTqFXn%2BEUG0nl3mnbpzIuSBg%3D"}],"group":"cf-nel","max_age":604800}#012                    [nel] => {"success_fraction":0,"report_to":"cf-nel","max_age":604800}#012                    [server] => cloudflare#012                    [cf-ray] => 9a75eb872fde57bc-SEA#012                    [server-timing] => cfL4;desc="?proto=TCP&rtt=6669&min_rtt=6664&rtt_var=2509&sent=5&recv=6&lost=0&retrans=0&sent_bytes=2839&recv_bytes=1025&delivery_rate=630071&cwnd=191&unsent_bytes=0&cid=50463cb66fa8fdaf&ts=980&x=0"#012                )#012#012        )#012#012    [body] => {#012    "api_reply": {#012        "key_is_valid": true,#012        "key_status": "max_active_key"#012    },#012    "debug": {#012        "sql": [#012            {#012                "query": "SELECT * FROM ip_tagdiv WHERE IP = '34.118.196.197' LIMIT 1",#012                "run_time": 7.390975952148438e-5#012            },#012            {#012                "query": "SELECT * FROM ip_exceptions WHERE IP = '34.118.196.197' LIMIT 1",#012                "run_time": 3.0994415283203125e-5#012            },#012            {#012                "query": "SELECT * FROM envato_cache WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' LIMIT 1",#012                "run_time": 0.417316198348999#012            },#012            {#012                "query": "SELECT * FROM key_domains WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' AND domain = 'hub20spokesite.wpenginepowered.com'",#012                "run_time": 0.0001709461212158203#012            },#012            {#012                "query": "SELECT * FROM key_domains WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' AND is_active = '1'",#012                "run_time": 0.00010704994201660156#012            }#012        ]#012    }#012}#012    [response] => Array#012        (#012            [code] => 200#012            [message] => OK#012        )#012#012    [cookies] => Array#012        (#012        )#012#012    [filename] => #012    [http_response] => WP_HTTP_Requests_Response Object#012        (#012            [data] => #012            [headers] => #012            [status] => #012            [response:protected] => WpOrg\Requests\Response Object#012                (#012                    [body] => {#012    "api_reply": {#012        "key_is_valid": true,#012        "key_status": "max_active_key"#012    },#012    "debug": {#012        "sql": [#012            {#012                "query": "SELECT * FROM ip_tagdiv WHERE IP = '34.118.196.197' LIMIT 1",#012                "run_time": 7.390975952148438e-5#012            },#012            {#012                "query": "SELECT * FROM ip_exceptions WHERE IP = '34.118.196.197' LIMIT 1",#012                "run_time": 3.0994415283203125e-5#012            },#012            {#012                "query": "SELECT * FROM envato_cache WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' LIMIT 1",#012                "run_time": 0.417316198348999#012            },#012            {#012                "query": "SELECT * FROM key_domains WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' AND domain = 'hub20spokesite.wpenginepowered.com'",#012                "run_time": 0.0001709461212158203#012            },#012            {#012                "query": "SELECT * FROM key_domains WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' AND is_active = '1'",#012                "run_time": 0.00010704994201660156#012            }#012        ]#012    }#012}#012                    [raw] => HTTP/1.1 200 OK#015#012Date: Mon, 01 Dec 2025 22:11:24 GMT#015#012Content-Type: text/html; charset=UTF-8#015#012Transfer-Encoding: chunked#015#012Connection: close#015#012Content-Encoding: br#015#012X-Powered-By: PHP/8.0.20#015#012Access-Control-Allow-Origin: *#015#012Access-Control-Allow-Methods: POST,GET,OPTIONS#015#012Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept#015#012cf-cache-status: DYNAMIC#015#012Report-To: {"endpoints":[{"url":"https:\/\/a.nel.cloudflare.com\/report\/v4?s=wfbeu3%2FX8dHrWciavyIj9aq8GCFEjgHK969UbaZoHcsXoM5EI9haxRtuJXXno44SOZI5Il7d%2FHNrc3VPoCo%2B4kbx7YQ119riJEBVdmaAqZjCTqFXn%2BEUG0nl3mnbpzIuSBg%3D"}],"group":"cf-nel","max_age":604800}#015#012NEL: {"success_fraction":0,"report_to":"cf-nel","max_age":604800}#015#012Server: cloudflare#015#012CF-RAY: 9a75eb872fde57bc-SEA#015#012server-timing: cfL4;desc="?proto=TCP&rtt=6669&min_rtt=6664&rtt_var=2509&sent=5&recv=6&lost=0&retrans=0&sent_bytes=2839&recv_bytes=1025&delivery_rate=630071&cwnd=191&unsent_bytes=0&cid=50463cb66fa8fdaf&ts=980&x=0"#015#012#015#012{#012    "api_reply": {#012        "key_is_valid": true,#012        "key_status": "max_active_key"#012    },#012    "debug": {#012        "sql": [#012            {#012                "query": "SELECT * FROM ip_tagdiv WHERE IP = '34.118.196.197' LIMIT 1",#012                "run_time": 7.390975952148438e-5#012            },#012            {#012                "query": "SELECT * FROM ip_exceptions WHERE IP = '34.118.196.197' LIMIT 1",#012                "run_time": 3.0994415283203125e-5#012            },#012            {#012                "query": "SELECT * FROM envato_cache WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' LIMIT 1",#012                "run_time": 0.417316198348999#012            },#012            {#012                "query": "SELECT * FROM key_domains WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' AND domain = 'hub20spokesite.wpenginepowered.com'",#012                "run_time": 0.0001709461212158203#012            },#012            {#012                "query": "SELECT * FROM key_domains WHERE envato_key = 'f00a0e73-650d-4aff-a36f-0b3ec88c762d' AND is_active = '1'",#012                "run_time": 0.00010704994201660156#012            }#012        ]#012    }#012}#012                    [headers] => WpOrg\Requests\Response\Headers Object#012                        (#012                            [data:protected] => Array#012                                (#012                                    [date] => Array#012                                        (#012                                            [0] => Mon, 01 Dec 2025 22:11:24 GMT#012                                        )#012#012                                    [content-type] => Array#012                                        (#012                                            [0] => text/html; charset=UTF-8#012                                        )#012#012                                    [content-encoding] => Array#012                                        (#012                                            [0] => br#012                                        )#012#012                                    [x-powered-by] => Array#012                                        (#012                                            [0] => PHP/8.0.20#012                                        )#012#012                                    [access-control-allow-origin] => Array#012                                        (#012                                            [0] => *#012                                        )#012#012                      
[2025-12-01T22:11:30.870082+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:30.883762+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:31.061365+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:35.255475+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:37.555073+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:41.544879+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:49.548362+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:50.895948+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:51.225699+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:54.427650+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:11:56.358135+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:12:06.045594+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:12:08.107529+00:00] auditor:event=upgrader_process_complete_plugin {"plugin":"sourcehub/sourcehub.php","type":"plugin","action":"update","temp_backup":{"slug":"sourcehub","src":"/nas/content/live/hub20spokesite/wp-content/plugins","dir":"plugins"},"blog_id":1,"event":"upgrader_process_complete_plugin","current_user_id":38,"remote_addr":"75.30.67.77"}
[2025-12-01T22:12:10.237644+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:12:10.392345+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:12:11.456304+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:13:10.617321+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:13:11.585905+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:13:11.894056+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-01T22:14:02.392737+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:14:02.729299+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:14:02.847332+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-01T22:14:02.847352+00:00] SourceHub Spoke: API key match: YES
[2025-12-01T22:14:02.847392+00:00] SourceHub Spoke: receive_post started
[2025-12-01T22:14:02.853179+00:00] SourceHub Spoke: Validation took 0.00 seconds
[2025-12-01T22:14:02.885764+00:00] SourceHub Spoke: Job queued in 0.04 seconds, returning 202
[2025-12-01T22:14:02.886792+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-01T22:14:02.886808+00:00] SourceHub Spoke: API key match: YES
[2025-12-01T22:14:02.886828+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-01T22:14:02.886835+00:00] SourceHub Spoke: API key match: YES
[2025-12-01T22:15:11.718518+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:15:11.727244+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:15:14.899862+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:15:15.084738+00:00] SourceHub: Processing job BIRM9aYMDb64NaegaheuFh3HFfqC44Z3 (create)
[2025-12-01T22:15:15.089875+00:00] SourceHub: Creating post as draft, will publish as 'draft' after processing
[2025-12-01T22:15:15.115878+00:00] SourceHub Newspaper: Checking format for post 47391: standard
[2025-12-01T22:15:15.155301+00:00] SourceHub Gallery: No gallery_images found in data for post 47391
[2025-12-01T22:15:15.157276+00:00] SourceHub: Post 47391 created as draft, now updating with Yoast meta (still as draft)
[2025-12-01T22:15:15.174712+00:00] SourceHub Newspaper: Checking format for post 47391: standard
[2025-12-01T22:15:15.188966+00:00] SourceHub: Successfully updated post 47391 with Yoast meta (still draft)
[2025-12-01T22:15:15.193665+00:00] SourceHub: Job BIRM9aYMDb64NaegaheuFh3HFfqC44Z3 completed in 0.11 seconds (post_id: 47391)
[2025-12-01T22:15:15.195635+00:00] SourceHub Spoke: Notifying hub of completion - URL: https://hub2a.wpenginepowered.com/wp-json/sourcehub/v1/sync-complete, Payload: {"job_id":"BIRM9aYMDb64NaegaheuFh3HFfqC44Z3","hub_post_id":46767,"spoke_post_id":47391,"status":"completed","error_message":null,"spoke_url":"https:\/\/hub20spokesite.wpenginepowered.com"}
[2025-12-01T22:15:15.476540+00:00] SourceHub Spoke: Hub notification response - Code: 200, Body: {"success":true,"message":"Status updated"}
[2025-12-01T22:15:15.479119+00:00] SourceHub: Job BIRM9aYMDb64NaegaheuFh3HFfqC44Z3 not found or already processed
[2025-12-01T22:17:11.734648+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:17:12.743703+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-01T22:19:12.749091+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:19:13.276060+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-01T22:21:13.730835+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:21:13.748322+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-01T22:21:14.311189+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:17:00.707950+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:17:00.894262+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:17:01.760267+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:17:02.119537+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"Bill Catch Errors","slug":"bill-catch-errors.php","ver":"4.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.119643+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine System","slug":"mu-plugin.php","ver":"6.5.2","sig":"v1:nohash"}
[2025-12-02T01:17:02.120259+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"Force Strong Passwords - WPE Edition","slug":"slt-force-strong-passwords.php","ver":"1.8.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.120344+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Cache Plugin","slug":"wpe-cache-plugin.php","ver":"1.3.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.120909+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Update Source Selector","slug":"wpe-update-source-selector.php","ver":"1.1.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.121896+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Seamless Login Plugin","slug":"wpe-wp-sign-on-plugin.php","ver":"1.6.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.122524+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"mu-plugin","name":"WP Engine Security Auditor","slug":"wpengine-security-auditor.php","ver":"1.1.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.122993+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"AirLift","slug":"airlift/airlift.php","ver":"6.15","sig":"v1:nohash"}
[2025-12-02T01:17:02.123060+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Broadstreet","slug":"broadstreet/broadstreet.php","ver":"1.51.10","sig":"v1:nohash"}
[2025-12-02T01:17:02.123116+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Bulk Delete","slug":"bulk-delete/bulk-delete.php","ver":"6.0.2","sig":"v1:nohash"}
[2025-12-02T01:17:02.123177+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Classic Editor","slug":"classic-editor/classic-editor.php","ver":"1.6.7","sig":"v1:nohash"}
[2025-12-02T01:17:02.123225+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Contact Form 7","slug":"contact-form-7/wp-contact-form-7.php","ver":"6.1.3","sig":"v1:nohash"}
[2025-12-02T01:17:02.123278+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Bulk Post Category Creator","slug":"create-category-in-bulk/bulk-category-creator-wordpress.php","ver":"1.7","sig":"v1:nohash"}
[2025-12-02T01:17:02.123339+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Debug Log Manager","slug":"debug-log-manager/debug-log-manager.php","ver":"2.4.3","sig":"v1:nohash"}
[2025-12-02T01:17:02.123388+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Download Plugin","slug":"download-plugin/download-plugin.php","ver":"2.3.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.123522+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast Duplicate Post","slug":"duplicate-post/duplicate-post.php","ver":"4.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.123580+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Empowerlocal","slug":"empowerlocal-wordpress-plugin/empowerlocal.php","ver":"1.1.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.123630+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Git Remote Updater","slug":"git-remote-updater/git-remote-updater.php","ver":"3.2.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.123681+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Git Updater","slug":"git-updater/git-updater.php","ver":"12.19.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.123724+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Hometown Holidays","slug":"hometown-holidays/hometown-holidays.php","ver":"1.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.123765+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Imagify","slug":"imagify/imagify.php","ver":"2.2.6","sig":"v1:nohash"}
[2025-12-02T01:17:02.123820+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"WPCode Lite","slug":"insert-headers-and-footers/ihaf.php","ver":"2.3.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.123881+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Login With Ajax","slug":"login-with-ajax/login-with-ajax.php","ver":"4.5.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.123945+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"One User Avatar","slug":"one-user-avatar/one-user-avatar.php","ver":"2.5.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.123998+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Quiz Maker","slug":"quiz-maker/quiz-maker.php","ver":"6.7.0.76","sig":"v1:nohash"}
[2025-12-02T01:17:02.124048+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Resize Image After Upload","slug":"resize-image-after-upload/resize-image-after-upload.php","ver":"1.8.6","sig":"v1:nohash"}
[2025-12-02T01:17:02.124085+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Restore and Enable Classic Widgets No Expiration","slug":"restore-classic-widgets/restore_classic_widgets.php","ver":"4.86","sig":"v1:nohash"}
[2025-12-02T01:17:02.124119+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"SourceHub - Hub & Spoke Publisher","slug":"sourcehub/sourcehub.php","ver":"1.9.8.8","sig":"v1:nohash"}
[2025-12-02T01:17:02.124161+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Cloud Library","slug":"td-cloud-library/td-cloud-library.php","ver":"3.9 | built on 27.03.2025 10:27","sig":"v1:nohash"}
[2025-12-02T01:17:02.124201+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Composer","slug":"td-composer/td-composer.php","ver":"5.4.1 | built on 29.04.2025 10:27","sig":"v1:nohash"}
[2025-12-02T01:17:02.124241+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Newsletter","slug":"td-newsletter/td-newsletter.php","ver":"2.1 | built on 25.04.2025 8:30","sig":"v1:nohash"}
[2025-12-02T01:17:02.124282+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Social Counter","slug":"td-social-counter/td-social-counter.php","ver":"5.6 | built on 11.04.2025 11:20","sig":"v1:nohash"}
[2025-12-02T01:17:02.124328+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"tagDiv Standard Pack","slug":"td-standard-pack/td-standard-pack.php","ver":"2.7 | built on 11.04.2025 11:20","sig":"v1:nohash"}
[2025-12-02T01:17:02.124365+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Theme Downloader","slug":"theme-downloader/theme-downloader.php","ver":"1.1.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.124428+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Social Media and Share Icons (Ultimate Social Media)","slug":"ultimate-social-media-icons/ultimate_social_media_icons.php","ver":"2.9.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.124583+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Wordfence Security","slug":"wordfence/wordfence.php","ver":"8.1.2","sig":"v1:nohash"}
[2025-12-02T01:17:02.124639+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast SEO Premium","slug":"wordpress-seo-premium/wp-seo-premium.php","ver":"25.9","sig":"v1:nohash"}
[2025-12-02T01:17:02.124686+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"Yoast SEO","slug":"wordpress-seo/wp-seo.php","ver":"26.3","sig":"v1:nohash"}
[2025-12-02T01:17:02.124741+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"active-plugin","name":"WP Maps","slug":"wp-google-map-plugin/wp-google-map-plugin.php","ver":"4.8.6","sig":"v1:nohash"}
[2025-12-02T01:17:02.124777+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Akismet Anti-spam: Spam Protection","slug":"akismet/akismet.php","ver":"5.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.124833+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"FS Poster","slug":"fs-poster/init.php","ver":"7.3.2","sig":"v1:nohash"}
[2025-12-02T01:17:02.124875+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter","slug":"newsletter/plugin.php","ver":"9.0.7","sig":"v1:nohash"}
[2025-12-02T01:17:02.124891+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Addons Manager and Support","slug":"newsletter-extensions/extensions.php","ver":"1.3.8","sig":"v1:nohash"}
[2025-12-02T01:17:02.124908+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Archive","slug":"newsletter-archive/archive.php","ver":"4.1.8","sig":"v1:nohash"}
[2025-12-02T01:17:02.124920+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Automated Newsletters","slug":"newsletter-automated/automated.php","ver":"4.8.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.124930+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Contact Form 7 Integration","slug":"newsletter-cf7/cf7.php","ver":"4.4.8","sig":"v1:nohash"}
[2025-12-02T01:17:02.124940+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Extended Composer Blocks","slug":"newsletter-blocks/blocks.php","ver":"1.6.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.124950+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Geolocation","slug":"newsletter-geo/geo.php","ver":"1.3.3","sig":"v1:nohash"}
[2025-12-02T01:17:02.124959+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Google Analytics","slug":"newsletter-analytics/analytics.php","ver":"1.3.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.124968+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Import/Export","slug":"newsletter-import/import.php","ver":"1.5.9","sig":"v1:nohash"}
[2025-12-02T01:17:02.124977+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Leads Addon","slug":"newsletter-leads/leads.php","ver":"1.6.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.124986+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Reports and Retargeting","slug":"newsletter-reports/reports.php","ver":"4.7.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.124995+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Newsletter - Sendgrid Addon","slug":"newsletter-sendgrid/sendgrid.php","ver":"4.5.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.125008+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Poll Maker","slug":"poll-maker/poll-maker-ays.php","ver":"21.4.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.125026+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"RSS Control","slug":"rss-control/rss-control.php","ver":"3.0.14","sig":"v1:nohash"}
[2025-12-02T01:17:02.125040+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"SourceLocal Child Site Plugin","slug":"sourcelocal_child_site_plugin/sourcelocal_child_site_plugin.php","ver":"1.7.0","sig":"v1:nohash"}
[2025-12-02T01:17:02.125050+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Survey Maker","slug":"survey-maker/survey-maker.php","ver":"5.1.9.4","sig":"v1:nohash"}
[2025-12-02T01:17:02.125059+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"TablePress","slug":"tablepress/tablepress.php","ver":"3.2.5","sig":"v1:nohash"}
[2025-12-02T01:17:02.125084+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Trending Article Manager","slug":"trending-article-manager/trending-article-manager.php","ver":"3.1","sig":"v1:nohash"}
[2025-12-02T01:17:02.125093+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Weather Write","slug":"weather-write/weather-write.php","ver":"1.2.6","sig":"v1:nohash"}
[2025-12-02T01:17:02.125108+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"WP Crontrol","slug":"wp-crontrol/wp-crontrol.php","ver":"1.19.3","sig":"v1:nohash"}
[2025-12-02T01:17:02.125121+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"WP Rocket","slug":"wp-rocket/wp-rocket.php","ver":"3.18.3","sig":"v1:nohash"}
[2025-12-02T01:17:02.125139+00:00] auditor:scan=fingerprint {"blog_id":1,"kind":"installed-plugin","name":"Zapier for WordPress","slug":"zapier/zapier.php","ver":"1.5.3","sig":"v1:nohash"}
[2025-12-02T01:18:00.696309+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:18:01.211704+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:19:00.658367+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:19:01.099515+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:20:01.759382+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:20:02.212898+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:21:01.670390+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:21:02.151606+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:22:02.677589+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:22:03.110703+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:22:06.892656+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:23:03.622826+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:23:04.073776+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:24:07.636708+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:24:08.308833+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:25:04.762669+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:25:05.298077+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:26:08.652797+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:26:09.027755+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:27:05.702199+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:27:06.197917+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:28:09.651061+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:28:10.093360+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:29:06.679848+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:29:07.149246+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:30:10.800453+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:30:11.273320+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:31:07.652150+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:31:08.027861+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:32:12.859355+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:32:13.750418+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:38:30.332012+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:38:30.844046+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:38:38.067966+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:38:42.575370+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:38:51.135094+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:38:51.635487+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:38:52.507031+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:38:56.950281+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:38:58.469539+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:39:14.343324+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:39:17.833352+00:00] auditor:event=upgrader_process_complete_plugin {"plugin":"sourcehub/sourcehub.php","type":"plugin","action":"update","temp_backup":{"slug":"sourcehub","src":"/nas/content/live/hub20spokesite/wp-content/plugins","dir":"plugins"},"blog_id":1,"event":"upgrader_process_complete_plugin","current_user_id":38,"remote_addr":"75.30.67.77"}
[2025-12-02T01:39:19.892856+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:39:20.960686+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:40:19.673751+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:20.323479+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:21.568016+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:43.818081+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:44.258741+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:44.434980+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T01:40:44.435002+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T01:40:44.435033+00:00] SourceHub Spoke: receive_post started
[2025-12-02T01:40:44.439093+00:00] SourceHub Spoke: Validation took 0.00 seconds
[2025-12-02T01:40:44.675720+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:44.838277+00:00] SourceHub: Processing job 8zPdJLxHcyOF3WAjtZIT8zvyh1Yi5pWC (create)
[2025-12-02T01:40:44.846002+00:00] SourceHub: Creating post as draft, will publish as 'draft' after processing
[2025-12-02T01:40:44.874819+00:00] SourceHub Newspaper: Checking format for post 47392: standard
[2025-12-02T01:40:44.923869+00:00] SourceHub Gallery: No gallery_images found in data for post 47392
[2025-12-02T01:40:44.926245+00:00] SourceHub: Post 47392 created as draft, now updating with Yoast meta (still as draft)
[2025-12-02T01:40:44.948331+00:00] SourceHub Newspaper: Checking format for post 47392: standard
[2025-12-02T01:40:44.964554+00:00] SourceHub: Successfully updated post 47392 with Yoast meta (still draft)
[2025-12-02T01:40:44.971055+00:00] SourceHub: Job 8zPdJLxHcyOF3WAjtZIT8zvyh1Yi5pWC completed in 0.13 seconds (post_id: 47392)
[2025-12-02T01:40:44.974119+00:00] SourceHub Spoke: Notifying hub of completion - URL: https://hub2a.wpenginepowered.com/wp-json/sourcehub/v1/sync-complete, Payload: {"job_id":"8zPdJLxHcyOF3WAjtZIT8zvyh1Yi5pWC","hub_post_id":46768,"spoke_post_id":47392,"status":"completed","error_message":null,"spoke_url":"https:\/\/hub20spokesite.wpenginepowered.com"}
[2025-12-02T01:40:45.308606+00:00] SourceHub Spoke: Hub notification response - Code: 200, Body: {"success":true,"message":"Status updated"}
[2025-12-02T01:40:45.311604+00:00] SourceHub: Job 8zPdJLxHcyOF3WAjtZIT8zvyh1Yi5pWC not found or already processed
[2025-12-02T01:40:45.329343+00:00] SourceHub Spoke: Job queued in 0.89 seconds, returning 202
[2025-12-02T01:40:45.330666+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T01:40:45.330687+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T01:40:45.330710+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T01:40:45.330719+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T01:40:51.391920+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:52.062606+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:55.700238+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:40:55.703484+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:07.516492+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:12.652238+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:14.816124+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:41:18.479177+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:21.702773+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:21.728959+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:35.672696+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:36.386733+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:39.216618+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:39.245082+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:45.474225+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:41:45.902819+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 20 OFFSET 0
[2025-12-02T01:41:45.902846+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 20#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T01:41:45.903670+00:00] SourceHub get_logs results count: 20
[2025-12-02T01:41:45.906374+00:00] [SourceHub Performance] Logs Page - Total: 0.01s | Logs Query: 0.00s | Count: 0.00s | Stats: 0.00s | Total Logs: 41
[2025-12-02T01:41:46.501685+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:42:16.570521+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:42:16.949884+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:42:18.069652+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:42:20.779285+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:42:22.621330+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:42:33.019216+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:42:38.571315+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:43:28.518326+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:43:31.742426+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:44:24.048323+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:44:25.875767+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:44:42.812619+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:44:44.443930+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:44:47.381643+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:44:47.465226+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:44:53.335793+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:44:55.113519+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:44:58.932556+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:45:03.098519+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:45:03.524757+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 20 OFFSET 0
[2025-12-02T01:45:03.524777+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 20#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T01:45:03.525972+00:00] SourceHub get_logs results count: 20
[2025-12-02T01:45:03.528963+00:00] [SourceHub Performance] Logs Page - Total: 0.01s | Logs Query: 0.00s | Count: 0.00s | Stats: 0.00s | Total Logs: 41
[2025-12-02T01:45:03.803230+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:45:06.258619+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:45:06.457610+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 1000 OFFSET 0
[2025-12-02T01:45:06.457645+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 1000#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T01:45:06.458975+00:00] SourceHub get_logs results count: 41
[2025-12-02T01:45:06.460045+00:00] SourceHub Export: Retrieved 41 logs with filters: Array#012(#012    [limit] => 1000#012    [order] => DESC#012)
[2025-12-02T01:45:34.056037+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:46:04.109141+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:46:04.596409+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:46:34.074763+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:47:04.130479+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:47:04.742788+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:47:34.063827+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:48:04.119817+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:48:04.976213+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:48:34.070720+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:48:34.443616+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:49:04.739016+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:49:05.341572+00:00] message repeated 4 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:49:34.664390+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:50:31.734631+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:50:32.145244+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:51:05.941254+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:51:06.628240+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:51:31.697676+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:52:31.660535+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:52:32.060803+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:53:06.668424+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:53:07.060465+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:53:31.708045+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:54:31.712734+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:54:32.146937+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:55:07.676181+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:55:08.141773+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:55:31.676389+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:56:31.657019+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:56:32.019652+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:57:08.649052+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:57:09.027428+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:57:31.670527+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:58:31.793605+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:58:32.153893+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:59:09.637236+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T01:59:10.056740+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T01:59:31.655649+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:00:32.457211+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:00:33.245700+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:01:31.755807+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:01:32.133186+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:02:31.659209+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:02:32.045890+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:03:31.751847+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:03:32.151976+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:04:31.700808+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:04:32.137746+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:05:04.556512+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:05:04.816995+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:05:05.525867+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:05:34.622262+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:04.689359+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:05.110427+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:15.518764+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:15.817330+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:22.316037+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:26.274827+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:06:34.253676+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:35.583746+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:45.838878+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:06:47.481012+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:03.131914+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:03.757310+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:05.886516+00:00] auditor:event=upgrader_process_complete_plugin {"plugin":"sourcehub/sourcehub.php","type":"plugin","action":"update","temp_backup":{"slug":"sourcehub","src":"/nas/content/live/hub20spokesite/wp-content/plugins","dir":"plugins"},"blog_id":1,"event":"upgrader_process_complete_plugin","current_user_id":38,"remote_addr":"75.30.67.77"}
[2025-12-02T02:07:08.000869+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:08.265843+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:09.191089+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:43.854929+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:46.404049+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:07:46.461529+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:09:46.780612+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:09:46.798419+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:09:47.385314+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:33.961162+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:34.302296+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:34.720326+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:34.874571+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:10:34.874588+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:10:34.874626+00:00] SourceHub Spoke: receive_post started
[2025-12-02T02:10:34.879012+00:00] SourceHub Spoke: Validation took 0.00 seconds
[2025-12-02T02:10:35.025922+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:35.162551+00:00] SourceHub: Processing job W0IOBuDrqHeDNX9ftuMgMtIa5T28rN2C (create)
[2025-12-02T02:10:35.171874+00:00] SourceHub: Creating post as draft, will publish as 'draft' after processing
[2025-12-02T02:10:35.203350+00:00] SourceHub Newspaper: Checking format for post 47393: standard
[2025-12-02T02:10:35.251853+00:00] SourceHub Gallery: No gallery_images found in data for post 47393
[2025-12-02T02:10:35.254112+00:00] SourceHub: Post 47393 created as draft, now updating with Yoast meta (still as draft)
[2025-12-02T02:10:35.278043+00:00] SourceHub Newspaper: Checking format for post 47393: standard
[2025-12-02T02:10:35.293975+00:00] SourceHub: Successfully updated post 47393 with Yoast meta (still draft)
[2025-12-02T02:10:35.298387+00:00] SourceHub: Job W0IOBuDrqHeDNX9ftuMgMtIa5T28rN2C completed in 0.14 seconds (post_id: 47393)
[2025-12-02T02:10:35.300476+00:00] SourceHub Spoke: Notifying hub of completion - URL: https://hub2a.wpenginepowered.com/wp-json/sourcehub/v1/sync-complete, Payload: {"job_id":"W0IOBuDrqHeDNX9ftuMgMtIa5T28rN2C","hub_post_id":46769,"spoke_post_id":47393,"status":"completed","error_message":null,"spoke_url":"https:\/\/hub20spokesite.wpenginepowered.com"}
[2025-12-02T02:10:35.676534+00:00] SourceHub Spoke: Hub notification response - Code: 200, Body: {"success":true,"message":"Status updated"}
[2025-12-02T02:10:35.680594+00:00] SourceHub: Job W0IOBuDrqHeDNX9ftuMgMtIa5T28rN2C not found or already processed
[2025-12-02T02:10:35.697330+00:00] SourceHub Spoke: Job queued in 0.82 seconds, returning 202
[2025-12-02T02:10:35.698936+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:10:35.698962+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:10:35.698989+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:10:35.698997+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:10:38.049570+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:40.709740+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:40.765977+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:45.404902+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:47.443825+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:47.446718+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:49.381810+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:10:53.145408+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:11:07.158127+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:15.242380+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:16.243789+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:32.065715+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:35.621148+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:35.681966+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:48.768658+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:51.233534+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:54.487818+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:11:56.717112+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:11:57.853111+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:12:00.839146+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:12:00.857652+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:12:47.343205+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:12:49.759775+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:12:53.331859+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:12:56.063961+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:12:56.078332+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:13:25.593113+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:13:33.068264+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:14:33.654760+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:14:34.296018+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:14:47.947646+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:14:48.597320+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 20 OFFSET 0
[2025-12-02T02:14:48.597350+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 20#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T02:14:48.598210+00:00] SourceHub get_logs results count: 20
[2025-12-02T02:14:48.603100+00:00] [SourceHub Performance] Logs Page - Total: 0.01s | Logs Query: 0.00s | Count: 0.00s | Stats: 0.00s | Total Logs: 52
[2025-12-02T02:14:49.190068+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:14:54.886836+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:14:55.247940+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:14:55.453942+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 1000 OFFSET 0
[2025-12-02T02:14:55.453963+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 1000#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T02:14:55.454877+00:00] SourceHub get_logs results count: 52
[2025-12-02T02:14:55.456243+00:00] SourceHub Export: Retrieved 52 logs with filters: Array#012(#012    [limit] => 1000#012    [order] => DESC#012)
[2025-12-02T02:15:19.646412+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:15:30.105891+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:15:33.942485+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:15:34.166231+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:15:34.563661+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:15:36.664585+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:16:37.690774+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:16:38.231685+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:18:32.623131+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:18:32.954324+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:18:34.687795+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:18:38.388567+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:18:38.393704+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:18:59.839722+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:19:07.769786+00:00] message repeated 6 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:19:31.148224+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:19:33.665637+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:19:33.801427+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:19:37.248859+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:19:39.296498+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:20:39.664480+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:20:40.349082+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:21:14.959780+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:15.656614+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:18.313664+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:18.317542+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:22.879895+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:23.406225+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 20 OFFSET 0
[2025-12-02T02:21:23.406244+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 20#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T02:21:23.407345+00:00] SourceHub get_logs results count: 20
[2025-12-02T02:21:23.410497+00:00] [SourceHub Performance] Logs Page - Total: 0.01s | Logs Query: 0.00s | Count: 0.00s | Stats: 0.00s | Total Logs: 52
[2025-12-02T02:21:23.793856+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:29.914982+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:30.326679+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 1000 OFFSET 0
[2025-12-02T02:21:30.326703+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 1000#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T02:21:30.327836+00:00] SourceHub get_logs results count: 52
[2025-12-02T02:21:30.329369+00:00] SourceHub Export: Retrieved 52 logs with filters: Array#012(#012    [limit] => 1000#012    [order] => DESC#012)
[2025-12-02T02:21:54.653637+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:21:55.941337+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:22:24.656402+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:22:24.778508+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:22:54.735895+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:22:55.130091+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:23:24.646211+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:23:33.759362+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:23:43.504823+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:23:45.963177+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:23:50.417260+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:23:52.732574+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:24:38.986243+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:24:42.798662+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:25:42.789334+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:25:49.889146+00:00] message repeated 8 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:26:07.065345+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:26:15.499646+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:26:31.121704+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:26:33.577494+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:26:33.639817+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:26:42.219142+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:26:49.604767+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:27:07.139946+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:27:09.729330+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:27:18.441127+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:27:35.241653+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:27:40.950190+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:27:47.001942+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:27:50.181223+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:27:54.206115+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:27:54.556098+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:27:56.225036+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:27:58.189987+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:02.193321+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:28:04.193816+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:06.182774+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:08.187076+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:10.192400+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:26.185843+00:00] message repeated 8 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:28:28.181943+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:30.183233+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:32.181504+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:38.184823+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:28:40.177710+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:28:48.310207+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:29:16.614310+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:29:22.686673+00:00] message repeated 6 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:30:21.364798+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:30:26.443374+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:30:52.823701+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:30:54.640120+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:31:25.802660+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:31:27.216792+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:32:27.305328+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:32:27.911842+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:33:10.247576+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:33:11.894417+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:34:12.686662+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:34:12.709025+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:34:13.277007+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:13.712466+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:14.057460+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:14.467573+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:14.597653+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:35:14.597676+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:35:14.728060+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:14.870393+00:00] SourceHub: Processing job vhvv4ZXrHs2WH3mBqQbwsULKx65VbHgK (update)
[2025-12-02T02:35:15.234415+00:00] SourceHub Newspaper: Checking format for post 47393: standard
[2025-12-02T02:35:15.631023+00:00] SourceHub Spoke: Update job queued in 1.03 seconds, returning 202
[2025-12-02T02:35:15.632382+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:35:15.632398+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:35:15.632415+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:35:15.632420+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:35:15.632453+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:35:15.632462+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:35:15.632481+00:00] SourceHub Spoke: Received API key: Uucctj99... | Stored key: Uucctj99...
[2025-12-02T02:35:15.632489+00:00] SourceHub Spoke: API key match: YES
[2025-12-02T02:35:18.029485+00:00] SourceHub Yoast: Setting meta for post ID: 47393 with data: {"_yoast_wpseo_focuskw":"1.9.9.0 test"}
[2025-12-02T02:35:18.034882+00:00] SourceHub Yoast: Field _yoast_wpseo_title not found in meta data
[2025-12-02T02:35:18.034903+00:00] SourceHub Yoast: Field _yoast_wpseo_metadesc not found in meta data
[2025-12-02T02:35:18.034915+00:00] SourceHub Yoast: Processing field _yoast_wpseo_focuskw with value: 1.9.9.0 test
[2025-12-02T02:35:18.041565+00:00] SourceHub Yoast: Update result for _yoast_wpseo_focuskw: SUCCESS
[2025-12-02T02:35:18.041583+00:00] SourceHub Yoast: Field _yoast_wpseo_focuskeywords not found in meta data
[2025-12-02T02:35:18.041588+00:00] SourceHub Yoast: Field _yoast_wpseo_canonical not found in meta data
[2025-12-02T02:35:18.041593+00:00] SourceHub Yoast: Field _yoast_wpseo_twitter-title not found in meta data
[2025-12-02T02:35:18.041598+00:00] SourceHub Yoast: Field _yoast_wpseo_twitter-description not found in meta data
[2025-12-02T02:35:18.041603+00:00] SourceHub Yoast: Field _yoast_wpseo_opengraph-title not found in meta data
[2025-12-02T02:35:18.041608+00:00] SourceHub Yoast: Field _yoast_wpseo_opengraph-description not found in meta data
[2025-12-02T02:35:18.051951+00:00] SourceHub: Job vhvv4ZXrHs2WH3mBqQbwsULKx65VbHgK completed in 3.18 seconds (post_id: 1)
[2025-12-02T02:35:18.055524+00:00] SourceHub Spoke: Notifying hub of completion - URL: https://hub2a.wpenginepowered.com/wp-json/sourcehub/v1/sync-complete, Payload: {"job_id":"vhvv4ZXrHs2WH3mBqQbwsULKx65VbHgK","hub_post_id":46769,"spoke_post_id":true,"status":"completed","error_message":null,"spoke_url":"https:\/\/hub20spokesite.wpenginepowered.com"}
[2025-12-02T02:35:18.448571+00:00] SourceHub Spoke: Hub notification response - Code: 200, Body: {"success":true,"message":"Status updated"}
[2025-12-02T02:35:18.452710+00:00] SourceHub: Job vhvv4ZXrHs2WH3mBqQbwsULKx65VbHgK not found or already processed
[2025-12-02T02:35:21.971416+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:22.536300+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:35:28.451202+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:30.904014+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:35:36.892509+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:37.555420+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 20 OFFSET 0
[2025-12-02T02:35:37.555469+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 20#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T02:35:37.556908+00:00] SourceHub get_logs results count: 20
[2025-12-02T02:35:37.559534+00:00] [SourceHub Performance] Logs Page - Total: 0.01s | Logs Query: 0.00s | Count: 0.00s | Stats: 0.00s | Total Logs: 60
[2025-12-02T02:35:37.987062+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:49.138886+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:51.553571+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:35:58.113079+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:36:02.973076+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:36:08.125496+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:36:58.233527+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:36:58.939599+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:38:08.216471+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:38:08.900844+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:38:46.680920+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:38:49.718132+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:38:50.320001+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 20 OFFSET 0
[2025-12-02T02:38:50.320025+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 20#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T02:38:50.320871+00:00] SourceHub get_logs results count: 20
[2025-12-02T02:38:50.323756+00:00] [SourceHub Performance] Logs Page - Total: 0.01s | Logs Query: 0.00s | Count: 0.00s | Stats: 0.00s | Total Logs: 60
[2025-12-02T02:38:50.634686+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:38:51.438256+00:00] message repeated 2 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:39:21.063741+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:39:28.907068+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:39:29.087965+00:00] SourceHub get_logs SQL: SELECT l.*, #012                       c.name as connection_name, #012                       c.url as connection_url,#012                       c.mode as connection_mode#012                FROM wp_sourcehub_logs l#012                LEFT JOIN wp_sourcehub_connections c ON l.connection_id = c.id#012                WHERE 1=1 #012                ORDER BY l.created_at DESC LIMIT 1000 OFFSET 0
[2025-12-02T02:39:29.087981+00:00] SourceHub get_logs args: Array#012(#012    [post_id] => #012    [connection_id] => #012    [status] => #012    [action] => #012    [search] => #012    [limit] => 1000#012    [offset] => 0#012    [order] => DESC#012)
[2025-12-02T02:39:29.088662+00:00] SourceHub get_logs results count: 60
[2025-12-02T02:39:29.090134+00:00] SourceHub Export: Retrieved 60 logs with filters: Array#012(#012    [limit] => 1000#012    [order] => DESC#012)
[2025-12-02T02:39:51.062389+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:39:51.467581+00:00] message repeated 3 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:40:21.213155+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:40:21.649219+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:40:53.363086+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:40:57.845508+00:00] message repeated 5 times: [ SourceHub Validation: Skipping validation - site is in spoke mode]
[2025-12-02T02:40:59.526040+00:00] SourceHub Validation: Skipping validation - site is in spoke mode
[2025-12-02T02:41:00.122312+00:00] SourceHub Validation: Skipping validation - site is in spoke mode