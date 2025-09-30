[30-Sep-2025 01:57:32 UTC] SourceHub Stats Debug: SQL = SELECT 
                    status,
                    COUNT(*) as count
                FROM wp_sourcehub_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                GROUP BY status
[30-Sep-2025 01:57:32 UTC] SourceHub Stats Debug: Results = [{"status":"SUCCESS","count":"1"},{"status":"INFO","count":"2"},{"status":"WARNING","count":"2"},{"status":"ERROR","count":"1"}]
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Undefined variable $mode in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 17
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Undefined variable $mode in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 18
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  ucfirst(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 18
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Undefined array key "total" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 72
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 72
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Undefined array key "success" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 76
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 76
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Undefined array key "error" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 80
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 80
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Undefined array key "warning" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 84
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 84
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 01:57:32 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 01:57:32 UTC] SourceHub: Running final sync check
[30-Sep-2025 01:58:13 UTC] SourceHub: Running final sync check
[30-Sep-2025 01:58:44 UTC] SourceHub Stats Debug: SQL = SELECT 
                    status,
                    COUNT(*) as count
                FROM wp_sourcehub_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
                GROUP BY status
[30-Sep-2025 01:58:44 UTC] SourceHub Stats Debug: Results = [{"status":"SUCCESS","count":"1"},{"status":"INFO","count":"2"},{"status":"WARNING","count":"2"},{"status":"ERROR","count":"1"}]
[30-Sep-2025 01:58:44 UTC] SourceHub: Running final sync check
[30-Sep-2025 01:59:19 UTC] SourceHub: Running final sync check
[30-Sep-2025 01:59:22 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:00:49 UTC] SourceHub: save_post_meta called for post 352, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:00:49 UTC] SourceHub: New post detected, syndicating for first time
[30-Sep-2025 02:00:49 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: ALL Yoast meta found: []
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:00:49 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:00:52 UTC] [SourceHub] [SUCCESS] Content successfully rewritten using AI | Data: {"settings":{"enabled":true,"rewrite_title":true,"rewrite_content":true,"rewrite_excerpt":false,"tone":"professional"}}
[30-Sep-2025 02:00:52 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:00:52 UTC] SourceHub Smart Links: Original content: In an alarming event that could easily be mistaken for a gripping Hollywood plot, a tiger has managed to...
[30-Sep-2025 02:00:52 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:00:52 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:52 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:52 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:00:52 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:00:52 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:52 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:52 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:00:52 UTC] SourceHub Smart Links: Processed content: In an alarming event that could easily be mistaken for a gripping Hollywood plot, a tiger has managed to...
[30-Sep-2025 02:00:52 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:00:52 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:00:53 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully syndicated to Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post created successfully","post_id":615,"post_url":"http:\/\/spoke-1.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:00:53 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: ALL Yoast meta found: []
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:00:53 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:00:53 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 2 (http://spoke-2.local/)
[30-Sep-2025 02:00:53 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 11 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:00:53 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-2.local
[30-Sep-2025 02:00:53 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:53 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:53 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:00:53 UTC] SourceHub Custom Smart Links: Processing for connection ID: 3
[30-Sep-2025 02:00:53 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:53 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:00:53 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:00:53 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 11 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:00:53 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 2","url":"http:\/\/spoke-2.local"}
[30-Sep-2025 02:00:53 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 2","response_code":200}
[30-Sep-2025 02:00:53 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully syndicated to Spoke 2 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post created successfully","post_id":75,"post_url":"http:\/\/spoke-2.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:00:53 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:01:30 UTC] [SourceHub] [INFO] Syncing updates for post "Cat escapes from Zoo Enclosure." to 2 spoke sites | Data: {"spoke_ids":[2,3]}
[30-Sep-2025 02:01:30 UTC] SourceHub: Updating 2 selected spokes out of 2 previously syndicated
[30-Sep-2025 02:01:30 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:30 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:32 UTC] [SourceHub] [SUCCESS] Content successfully rewritten using AI | Data: {"settings":{"enabled":true,"rewrite_title":true,"rewrite_content":true,"rewrite_excerpt":false,"tone":"professional"}}
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Original content: In a shocking turn of events, a tiger has managed to escape from its enclosure at the local zoo...
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Processed content: In a shocking turn of events, a tiger has managed to escape from its enclosure at the local zoo...
[30-Sep-2025 02:01:32 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:01:32 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:01:32 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":615,"post_url":"http:\/\/spoke-1.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:32 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:32 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 2 (http://spoke-2.local/)
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-2.local
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: Processing for connection ID: 3
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:32 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:32 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:32 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 2","url":"http:\/\/spoke-2.local"}
[30-Sep-2025 02:01:32 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 2","response_code":200}
[30-Sep-2025 02:01:32 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 2 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":75,"post_url":"http:\/\/spoke-2.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:33 UTC] [SourceHub] [INFO] Syncing updates for post "Cat escapes from Zoo Enclosure." to 2 spoke sites | Data: {"spoke_ids":[2,3]}
[30-Sep-2025 02:01:33 UTC] SourceHub: Updating 2 selected spokes out of 2 previously syndicated
[30-Sep-2025 02:01:33 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:33 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:34 UTC] [SourceHub] [SUCCESS] Content successfully rewritten using AI | Data: {"settings":{"enabled":true,"rewrite_title":true,"rewrite_content":true,"rewrite_excerpt":false,"tone":"professional"}}
[30-Sep-2025 02:01:34 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:01:34 UTC] SourceHub Smart Links: Original content: In a shocking turn of events, a tiger managed to break free from its confines within the zoo,...
[30-Sep-2025 02:01:34 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:01:34 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:34 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:34 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:34 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:01:34 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:34 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:34 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:34 UTC] SourceHub Smart Links: Processed content: In a shocking turn of events, a tiger managed to break free from its confines within the zoo,...
[30-Sep-2025 02:01:34 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:01:35 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:01:35 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":615,"post_url":"http:\/\/spoke-1.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:35 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:35 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:35 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 2 (http://spoke-2.local/)
[30-Sep-2025 02:01:35 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:35 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-2.local
[30-Sep-2025 02:01:35 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:35 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:35 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:35 UTC] SourceHub Custom Smart Links: Processing for connection ID: 3
[30-Sep-2025 02:01:35 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:35 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:35 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:35 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:35 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 2","url":"http:\/\/spoke-2.local"}
[30-Sep-2025 02:01:35 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 2","response_code":200}
[30-Sep-2025 02:01:35 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 2 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":75,"post_url":"http:\/\/spoke-2.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:35 UTC] SourceHub: save_post_meta called for post 352, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:01:35 UTC] SourceHub: Existing post detected, letting handle_post_update handle it
[30-Sep-2025 02:01:35 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:01:50 UTC] [SourceHub] [INFO] Syncing updates for post "Cat escapes from Zoo Enclosure." to 2 spoke sites | Data: {"spoke_ids":[2,3]}
[30-Sep-2025 02:01:50 UTC] SourceHub: Updating 2 selected spokes out of 2 previously syndicated
[30-Sep-2025 02:01:50 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:50 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:52 UTC] [SourceHub] [SUCCESS] Content successfully rewritten using AI | Data: {"settings":{"enabled":true,"rewrite_title":true,"rewrite_content":true,"rewrite_excerpt":false,"tone":"professional"}}
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Original content: In an extraordinary turn of events, a tiger has astonishingly managed to escape from its containment in a...
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Processed content: In an extraordinary turn of events, a tiger has astonishingly managed to escape from its containment in a...
[30-Sep-2025 02:01:52 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:01:52 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:01:52 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":615,"post_url":"http:\/\/spoke-1.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:52 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 2 (http://spoke-2.local/)
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-2.local
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: Processing for connection ID: 3
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:52 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:52 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:52 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 2","url":"http:\/\/spoke-2.local"}
[30-Sep-2025 02:01:52 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 2","response_code":200}
[30-Sep-2025 02:01:52 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 2 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":75,"post_url":"http:\/\/spoke-2.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:52 UTC] [SourceHub] [INFO] Syncing updates for post "Cat escapes from Zoo Enclosure." to 2 spoke sites | Data: {"spoke_ids":[2,3]}
[30-Sep-2025 02:01:52 UTC] SourceHub: Updating 2 selected spokes out of 2 previously syndicated
[30-Sep-2025 02:01:52 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:52 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:55 UTC] [SourceHub] [SUCCESS] Content successfully rewritten using AI | Data: {"settings":{"enabled":true,"rewrite_title":true,"rewrite_content":true,"rewrite_excerpt":false,"tone":"professional"}}
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Original content: In an alarming turn of events, a tiger has managed to escape its enclosure at a local zoo,...
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Processed content: In an alarming turn of events, a tiger has managed to escape its enclosure at a local zoo,...
[30-Sep-2025 02:01:55 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:01:55 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:01:55 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":615,"post_url":"http:\/\/spoke-1.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:55 UTC] SourceHub: Collecting page template for post 352: DEFAULT
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Getting meta for post ID: 352
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Getting meta for post ID: 352
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_6\";}"}
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_6"}
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:01:55 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_6"}}
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 2 (http://spoke-2.local/)
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-2.local
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: Processing for connection ID: 3
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:01:55 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:01:55 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>a tiger escapes from the zoo, kills 10 people. Watch out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:01:55 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 2","url":"http:\/\/spoke-2.local"}
[30-Sep-2025 02:01:55 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 2","response_code":200}
[30-Sep-2025 02:01:55 UTC] [SourceHub] [SUCCESS] Post "Cat escapes from Zoo Enclosure." successfully updated on Spoke 2 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":75,"post_url":"http:\/\/spoke-2.local\/cat-escapes-from-zoo-enclosure\/"}}
[30-Sep-2025 02:01:55 UTC] SourceHub: save_post_meta called for post 352, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:01:55 UTC] SourceHub: Existing post detected, letting handle_post_update handle it
[30-Sep-2025 02:01:55 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:02:01 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:02:03 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:02:39 UTC] SourceHub: save_post_meta called for post 355, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:02:39 UTC] SourceHub: New post detected, syndicating for first time
[30-Sep-2025 02:02:39 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: ALL Yoast meta found: []
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_1\";}"}
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:02:39 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_1"}}
[30-Sep-2025 02:02:39 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:02:39 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things worked out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:02:39 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:02:39 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:02:39 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:02:39 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:02:39 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:02:39 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:02:39 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:02:39 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:02:39 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things worked out. </p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:02:39 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:02:39 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:02:39 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully syndicated to Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post created successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:02:39 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:03:02 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:02 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:02 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_1\";}"}
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_1"}}
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:02 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:02 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:03:02 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:03:02 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:02 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:02 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_1\";}"}
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:02 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_1"}}
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:02 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:02 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:02 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:02 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:03:03 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:03:03 UTC] SourceHub: save_post_meta called for post 355, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:03:03 UTC] SourceHub: Existing post detected, letting handle_post_update handle it
[30-Sep-2025 02:03:03 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:13 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:13 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_1\";}"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_1"}}
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:03:13 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:13 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:13 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_1\";}"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_1"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_1"}}
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:03:13 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:03:13 UTC] SourceHub: Newspaper meta updated for post 355: {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:13 UTC] SourceHub: Post already syndicated, updating existing posts with Newspaper template
[30-Sep-2025 02:03:13 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:13 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_4\";}"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:13 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_4"}}
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:13 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:13 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:13 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:03:13 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:03:13 UTC] SourceHub: save_post_meta called for post 355, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:03:13 UTC] SourceHub: Existing post detected, letting handle_post_update handle it
[30-Sep-2025 02:03:13 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:03:14 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:14 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:14 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_4\";}"}
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_4"}}
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:14 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:14 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:03:14 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:03:14 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:14 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:14 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_4\";}"}
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:14 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_4"}}
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:14 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:14 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:14 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:14 UTC] [SourceHub] [INFO] Spoke site is already awake | Data: {"connection_name":"Spoke 1","response_code":200}
[30-Sep-2025 02:03:15 UTC] [SourceHub] [SUCCESS] Post "Zebra hires chopper to escape the zoo." successfully updated on Spoke 1 | Data: {"success":true,"message":"Post sent successfully","data":{"success":true,"message":"Post updated successfully","post_id":625,"post_url":"http:\/\/spoke-1.local\/zebra-hires-chopper-to-escape-the-zoo\/"}}
[30-Sep-2025 02:03:15 UTC] SourceHub: save_post_meta called for post 355, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:03:15 UTC] SourceHub: Existing post detected, letting handle_post_update handle it
[30-Sep-2025 02:03:15 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:03:38 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:38 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:38 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_4\";}"}
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:38 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_4"}}
[30-Sep-2025 02:03:38 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:38 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:38 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:38 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:38 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:38 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:38 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:38 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:38 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:38 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:38 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:38 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:38 UTC] [SourceHub] [INFO] Sending wake-up ping to spoke site | Data: {"connection_name":"Spoke 1"}
[30-Sep-2025 02:03:40 UTC] [SourceHub] [WARNING] Wake-up attempt completed but site may still be sleeping | Data: {"connection_name":"Spoke 1","verify_error":"HTTP 502"}
[30-Sep-2025 02:03:40 UTC] [SourceHub] [WARNING] Failed to wake up spoke site, proceeding anyway | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local\/"}
[30-Sep-2025 02:03:40 UTC] [SourceHub] [ERROR] Failed to update post "Zebra hires chopper to escape the zoo." on Spoke 1: HTTP 405: <html>
<head><title>405 Not Allowed</title></head>
<body>
<center><h1>405 Not Allowed</h1></center>
<hr><center>nginx/1.26.1</center>
</body>
</html>
 | Data: {"success":false,"message":"HTTP 405: <html>\r\n<head><title>405 Not Allowed<\/title><\/head>\r\n<body>\r\n<center><h1>405 Not Allowed<\/h1><\/center>\r\n<hr><center>nginx\/1.26.1<\/center>\r\n<\/body>\r\n<\/html>\r\n","data":{"hub_id":355,"title":"Zebra hires chopper to escape the zoo.","content":"<!-- wp:paragraph -->\n<p>somehow things didn't work out. <\/p>\n<!-- \/wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><\/p>\n<!-- \/wp:paragraph -->","excerpt":"","status":"publish","slug":"zebra-hires-chopper-to-escape-the-zoo","date":"2025-09-29 21:02:38","date_gmt":"2025-09-30 02:02:38","modified":"2025-09-29 21:03:38","modified_gmt":"2025-09-30 02:03:38","post_type":"post","page_template":"","hub_url":"http:\/\/hub-primary.local","author":{"name":"root","email":"dev-email@wpengine.local","login":"root"},"categories":[{"name":"Uncategorized","slug":"uncategorized","description":""}],"featured_image":{"url":"http:\/\/hub-primary.local\/wp-content\/uploads\/2025\/06\/250608-zebra-escape-capture-vl-351p-eb2aba.webp","width":560,"height":374,"alt":"","caption":"","description":""},"yoast_meta":[],"newspaper_meta":{"td_post_theme_settings":{"td_post_template":"single_template_4"}}}}
[30-Sep-2025 02:03:40 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:40 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:40 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_4\";}"}
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:40 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_4"}}
[30-Sep-2025 02:03:40 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:40 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:40 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:40 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:40 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:40 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:40 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:40 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:40 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:40 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:40 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:40 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:40 UTC] [SourceHub] [INFO] Sending wake-up ping to spoke site | Data: {"connection_name":"Spoke 1"}
[30-Sep-2025 02:03:42 UTC] [SourceHub] [WARNING] Wake-up attempt completed but site may still be sleeping | Data: {"connection_name":"Spoke 1","verify_error":"HTTP 502"}
[30-Sep-2025 02:03:42 UTC] [SourceHub] [WARNING] Failed to wake up spoke site, proceeding anyway | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local\/"}
[30-Sep-2025 02:03:42 UTC] [SourceHub] [ERROR] Failed to update post "Zebra hires chopper to escape the zoo." on Spoke 1: HTTP 405: <html>
<head><title>405 Not Allowed</title></head>
<body>
<center><h1>405 Not Allowed</h1></center>
<hr><center>nginx/1.26.1</center>
</body>
</html>
 | Data: {"success":false,"message":"HTTP 405: <html>\r\n<head><title>405 Not Allowed<\/title><\/head>\r\n<body>\r\n<center><h1>405 Not Allowed<\/h1><\/center>\r\n<hr><center>nginx\/1.26.1<\/center>\r\n<\/body>\r\n<\/html>\r\n","data":{"hub_id":355,"title":"Zebra hires chopper to escape the zoo.","content":"<!-- wp:paragraph -->\n<p>somehow things didn't work out. <\/p>\n<!-- \/wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><\/p>\n<!-- \/wp:paragraph -->","excerpt":"","status":"publish","slug":"zebra-hires-chopper-to-escape-the-zoo","date":"2025-09-29 21:02:38","date_gmt":"2025-09-30 02:02:38","modified":"2025-09-29 21:03:40","modified_gmt":"2025-09-30 02:03:40","post_type":"post","page_template":"","hub_url":"http:\/\/hub-primary.local","author":{"name":"root","email":"dev-email@wpengine.local","login":"root"},"categories":[{"name":"Uncategorized","slug":"uncategorized","description":""}],"featured_image":{"url":"http:\/\/hub-primary.local\/wp-content\/uploads\/2025\/06\/250608-zebra-escape-capture-vl-351p-eb2aba.webp","width":560,"height":374,"alt":"","caption":"","description":""},"yoast_meta":[],"newspaper_meta":{"td_post_theme_settings":{"td_post_template":"single_template_4"}}}}
[30-Sep-2025 02:03:42 UTC] SourceHub: save_post_meta called for post 355, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:03:42 UTC] SourceHub: Existing post detected, letting handle_post_update handle it
[30-Sep-2025 02:03:42 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:03:44 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:44 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:44 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_4\";}"}
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:44 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_4"}}
[30-Sep-2025 02:03:44 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:44 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:44 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:44 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:44 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:44 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:44 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:44 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:44 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:44 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:44 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:44 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:44 UTC] [SourceHub] [INFO] Sending wake-up ping to spoke site | Data: {"connection_name":"Spoke 1"}
[30-Sep-2025 02:03:46 UTC] [SourceHub] [WARNING] Wake-up attempt completed but site may still be sleeping | Data: {"connection_name":"Spoke 1","verify_error":"HTTP 502"}
[30-Sep-2025 02:03:46 UTC] [SourceHub] [WARNING] Failed to wake up spoke site, proceeding anyway | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local\/"}
[30-Sep-2025 02:03:46 UTC] [SourceHub] [ERROR] Failed to update post "Zebra hires chopper to escape the zoo." on Spoke 1: HTTP 405: <html>
<head><title>405 Not Allowed</title></head>
<body>
<center><h1>405 Not Allowed</h1></center>
<hr><center>nginx/1.26.1</center>
</body>
</html>
 | Data: {"success":false,"message":"HTTP 405: <html>\r\n<head><title>405 Not Allowed<\/title><\/head>\r\n<body>\r\n<center><h1>405 Not Allowed<\/h1><\/center>\r\n<hr><center>nginx\/1.26.1<\/center>\r\n<\/body>\r\n<\/html>\r\n","data":{"hub_id":355,"title":"Zebra hires chopper to escape the zoo.","content":"<!-- wp:paragraph -->\n<p>somehow things didn't work out. <\/p>\n<!-- \/wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><\/p>\n<!-- \/wp:paragraph -->","excerpt":"","status":"publish","slug":"zebra-hires-chopper-to-escape-the-zoo","date":"2025-09-29 21:02:38","date_gmt":"2025-09-30 02:02:38","modified":"2025-09-29 21:03:44","modified_gmt":"2025-09-30 02:03:44","post_type":"post","page_template":"","hub_url":"http:\/\/hub-primary.local","author":{"name":"root","email":"dev-email@wpengine.local","login":"root"},"categories":[{"name":"Uncategorized","slug":"uncategorized","description":""}],"featured_image":{"url":"http:\/\/hub-primary.local\/wp-content\/uploads\/2025\/06\/250608-zebra-escape-capture-vl-351p-eb2aba.webp","width":560,"height":374,"alt":"","caption":"","description":""},"yoast_meta":[],"newspaper_meta":{"td_post_theme_settings":{"td_post_template":"single_template_4"}}}}
[30-Sep-2025 02:03:46 UTC] [SourceHub] [INFO] Syncing updates for post "Zebra hires chopper to escape the zoo." to 1 spoke sites | Data: {"spoke_ids":[2]}
[30-Sep-2025 02:03:46 UTC] SourceHub: Updating 1 selected spokes out of 1 previously syndicated
[30-Sep-2025 02:03:46 UTC] SourceHub: Collecting page template for post 355: DEFAULT
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Getting meta for post ID: 355
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: ALL Yoast meta found: {"_yoast_wpseo_primary_category":"1","_yoast_wpseo_content_score":"90","_yoast_wpseo_estimated-reading-time-minutes":"1"}
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_title = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_metadesc = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskw = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_focuskeywords = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_canonical = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-title = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_twitter-description = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-title = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Field _yoast_wpseo_opengraph-description = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Yoast: Collected meta data: []
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Getting meta for post ID: 355
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: ALL Newspaper meta found: {"td_post_theme_settings":"a:1:{s:16:\"td_post_template\";s:17:\"single_template_4\";}"}
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Template field _td_post_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Template field _td_single_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Template field _td_article_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Template field _td_post_settings = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Template field _td_post_style = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Template field _td_post_layout = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Template field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _wp_page_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_settings = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_featured_image = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_theme_settings = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field td_post_theme_settings = {"td_post_template":"single_template_4"}
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_video = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_audio = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_gallery = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_quote = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_review = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_layout = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_sidebar_position = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_page_layout = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_full_page_layout = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_single_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_article_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_style = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_layout = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_single_post_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_module_template = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_featured_image_disable = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_featured_image_url = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_featured_image_alt = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_video_playlist = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_audio_playlist = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_gallery_images_ids = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_quote_on_blocks = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_review_key_ups = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_review_key_downs = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_review_overall = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_social_networks = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_subtitle = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_source_name = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_source_url = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_smart_list = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_views = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_content_positions = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_lock_content = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_exclude_from_display = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_css = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_js = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_title = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_description = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Field _td_post_keywords = EMPTY
[30-Sep-2025 02:03:46 UTC] SourceHub Newspaper: Collected meta data: {"td_post_theme_settings":{"td_post_template":"single_template_4"}}
[30-Sep-2025 02:03:46 UTC] SourceHub Smart Links: Starting processing for connection: Spoke 1 (http://spoke-1.local/)
[30-Sep-2025 02:03:46 UTC] SourceHub Smart Links: Original content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:46 UTC] SourceHub Smart Links: Processing for spoke URL: http://spoke-1.local
[30-Sep-2025 02:03:46 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*data-smart-url="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:46 UTC] SourceHub Smart Links: Trying pattern: /<span[^>]*data-smart-url="([^"]*)"[^>]*class="[^"]*sourcehub-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:46 UTC] SourceHub Smart Links: No smart links found in content
[30-Sep-2025 02:03:46 UTC] SourceHub Custom Smart Links: Processing for connection ID: 2
[30-Sep-2025 02:03:46 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*data-custom-urls="([^"]*)"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:46 UTC] SourceHub Custom Smart Links: Trying pattern: /<span[^>]*data-custom-urls="([^"]*)"[^>]*class="[^"]*sourcehub-custom-smart-link[^"]*"[^>]*>(.*?)<\/span>/i
[30-Sep-2025 02:03:46 UTC] SourceHub Custom Smart Links: No custom smart links found in content
[30-Sep-2025 02:03:46 UTC] SourceHub Smart Links: Processed content: <!-- wp:paragraph -->
<p>somehow things didn't work out. </p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->...
[30-Sep-2025 02:03:46 UTC] [SourceHub] [INFO] Attempting to wake up spoke site | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local"}
[30-Sep-2025 02:03:46 UTC] [SourceHub] [INFO] Sending wake-up ping to spoke site | Data: {"connection_name":"Spoke 1"}
[30-Sep-2025 02:03:48 UTC] [SourceHub] [WARNING] Wake-up attempt completed but site may still be sleeping | Data: {"connection_name":"Spoke 1","verify_error":"HTTP 502"}
[30-Sep-2025 02:03:48 UTC] [SourceHub] [WARNING] Failed to wake up spoke site, proceeding anyway | Data: {"connection_name":"Spoke 1","url":"http:\/\/spoke-1.local\/"}
[30-Sep-2025 02:03:48 UTC] [SourceHub] [ERROR] Failed to update post "Zebra hires chopper to escape the zoo." on Spoke 1: HTTP 405: <html>
<head><title>405 Not Allowed</title></head>
<body>
<center><h1>405 Not Allowed</h1></center>
<hr><center>nginx/1.26.1</center>
</body>
</html>
 | Data: {"success":false,"message":"HTTP 405: <html>\r\n<head><title>405 Not Allowed<\/title><\/head>\r\n<body>\r\n<center><h1>405 Not Allowed<\/h1><\/center>\r\n<hr><center>nginx\/1.26.1<\/center>\r\n<\/body>\r\n<\/html>\r\n","data":{"hub_id":355,"title":"Zebra hires chopper to escape the zoo.","content":"<!-- wp:paragraph -->\n<p>somehow things didn't work out. <\/p>\n<!-- \/wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><\/p>\n<!-- \/wp:paragraph -->","excerpt":"","status":"publish","slug":"zebra-hires-chopper-to-escape-the-zoo","date":"2025-09-29 21:02:38","date_gmt":"2025-09-30 02:02:38","modified":"2025-09-29 21:03:46","modified_gmt":"2025-09-30 02:03:46","post_type":"post","page_template":"","hub_url":"http:\/\/hub-primary.local","author":{"name":"root","email":"dev-email@wpengine.local","login":"root"},"categories":[{"name":"Uncategorized","slug":"uncategorized","description":""}],"featured_image":{"url":"http:\/\/hub-primary.local\/wp-content\/uploads\/2025\/06\/250608-zebra-escape-capture-vl-351p-eb2aba.webp","width":560,"height":374,"alt":"","caption":"","description":""},"yoast_meta":[],"newspaper_meta":{"td_post_theme_settings":{"td_post_template":"single_template_4"}}}}
[30-Sep-2025 02:03:48 UTC] SourceHub: save_post_meta called for post 355, Newspaper theme: YES, has meta: YES
[30-Sep-2025 02:03:48 UTC] SourceHub: Existing post detected, letting handle_post_update handle it
[30-Sep-2025 02:03:49 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:03:49 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:03:54 UTC] SourceHub Stats Debug: SQL = SELECT 
                    status,
                    COUNT(*) as count
                FROM wp_sourcehub_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                GROUP BY status
[30-Sep-2025 02:03:54 UTC] SourceHub Stats Debug: Results = [{"status":"SUCCESS","count":"24"},{"status":"ERROR","count":"5"},{"status":"WARNING","count":"10"},{"status":"INFO","count":"60"}]
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $mode in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 17
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $mode in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 18
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  ucfirst(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 18
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined array key "total" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 72
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 72
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined array key "success" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 76
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 76
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined array key "error" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 80
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 80
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined array key "warning" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 84
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 84
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 183
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 184
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 189
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 195
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 196
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 200
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 200
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 205
[30-Sep-2025 02:03:54 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 206
[30-Sep-2025 02:03:54 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:04:51 UTC] SourceHub Stats Debug: SQL = SELECT 
                    status,
                    COUNT(*) as count
                FROM wp_sourcehub_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
                GROUP BY status
[30-Sep-2025 02:04:51 UTC] SourceHub Stats Debug: Results = [{"status":"SUCCESS","count":"24"},{"status":"ERROR","count":"5"},{"status":"WARNING","count":"10"},{"status":"INFO","count":"60"}]
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $mode in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 17
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $mode in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 18
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  ucfirst(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 18
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined array key "total" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 72
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 72
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined array key "success" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 76
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 76
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined array key "error" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 80
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 80
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined array key "warning" in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 84
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  number_format(): Passing null to parameter #1 ($num) of type float is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 84
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "action" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 111
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "status" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 113
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 588
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtoupper(): Passing null to parameter #1 ($string) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/class-sourcehub-admin.php on line 605
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "message" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 117
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Deprecated:  strtotime(): Passing null to parameter #1 ($datetime) of type string is deprecated in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 154
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "created_at" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 155
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Attempt to read property "id" on array in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 168
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 183
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 184
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 189
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 195
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 196
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 200
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 200
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 205
[30-Sep-2025 02:04:51 UTC] PHP Warning:  Undefined variable $current_page in /Users/michaelfreeman/Local Sites/hub-primary/app/public/wp-content/plugins/SourceHub/admin/views/logs.php on line 206
[30-Sep-2025 02:04:51 UTC] SourceHub: Running final sync check
[30-Sep-2025 02:54:41 UTC] SourceHub: Running final sync check