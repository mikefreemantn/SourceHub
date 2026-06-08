# SourceHub — Developer Handoff

**Last updated:** 2026-06-08
**Current version:** 2.4.2
**Status:** Production — running on hub2a.wpenginepowered.com and ~10 spoke sites.

This is the single entry-point document for any developer taking over the project. Read this first; it points you to everything else.

---

## 1. What this plugin is

SourceHub is a WordPress plugin that implements a **hub-and-spoke content syndication network** for a group of news sites (Williamson Source, Sumner County Source, Rutherford County Source, etc.).

- One **hub** site (the central editorial team writes posts here).
- N **spoke** sites (each spoke is a separate WordPress install on its own domain).
- When the hub publishes a post, SourceHub pushes it to selected spokes, optionally rewrites it with AI per-spoke, rewrites smart-links to local URLs, syncs Yoast SEO metadata, featured images, galleries, and taxonomies.
- Updates to the hub post flow back out to the spokes.

The **same plugin** runs on both hub and spoke. The active mode is selected per-site via `wp_options` key `sourcehub_mode` (`'hub'` or `'spoke'`).

For a fuller feature list, see `README.md`. For original project context, see `project_overview.md`.

---

## 2. First 30 minutes — get oriented

1. **Read this file end to end** (~10 min).
2. **Skim `README.md`** for the user-facing feature surface.
3. **Read `DEPLOYMENT.md`** — release flow is non-obvious because of GitUpdater. Don't ship anything until you understand it.
4. **Open `sourcehub.php`** (the bootstrap file, 326 lines). Trace `SourceHub::instance() → __construct → init_hooks → includes → init_components → init`.
5. **Open `includes/class-sourcehub-hub-manager.php`** and look at the `init()` method (lines 30–95). That's the central nervous system on the hub side. ~25 WordPress hooks are registered here.
6. **Open `includes/class-sourcehub-spoke-manager.php`** and look at its `init()`. That's the spoke side.

After that you should be able to find anything by grep.

---

## 3. Architecture in 60 seconds

### Two modes, one plugin

```
sourcehub.php (bootstrap, singleton)
├── if (mode === 'hub')   → SourceHub_Hub_Manager::init()
└── if (mode === 'spoke') → SourceHub_Spoke_Manager::init()
```

### Key classes (in `includes/`)

| File | Lines | Role |
|---|---|---|
| `class-sourcehub-hub-manager.php` | 4,200 | **Hub brain.** Hooks into `save_post`, `transition_post_status`, `post_updated`. Builds payloads, calls spoke REST endpoints, handles AI rewrite per-spoke, delayed sync, retries, callbacks. |
| `class-sourcehub-spoke-manager.php` | 2,150 | **Spoke brain.** REST endpoints (`/receive-post`, `/update-post`, `/trash-post`, etc.), background job queue, canonical tag injection, stuck-draft detection. |
| `class-sourcehub-api-handler.php` | 670 | REST endpoints for managing connections, logs, test pings. |
| `class-sourcehub-database.php` | 620 | Schema. Creates all `wp_sourcehub_*` tables on activation. |
| `class-sourcehub-ai-rewriter.php` | 600 | OpenAI integration. Per-spoke tone, instructions, model selection. Smart-link aware. |
| `class-sourcehub-smart-links.php` | 430 | The `[smart-link]` shortcode + TinyMCE buttons. Rewrites paths to absolute spoke URLs at sync time. |
| `class-sourcehub-yoast-integration.php` | 440 | Reads/writes Yoast meta (`_yoast_wpseo_*`) on both sides. |
| `class-sourcehub-newspaper-integration.php` | 390 | Newspaper theme (`tagDiv`) custom meta sync — featured image, post settings. |
| `class-sourcehub-logger.php` | 610 | The activity log system. Writes to `wp_sourcehub_logs`. |
| `class-sourcehub-validation.php` | 440 | Pre-syndication content checks. |
| `class-sourcehub-bug-tracker.php` | 1,050 | In-app bug reporting (separate from activity log). |
| `class-sourcehub-messaging.php` | 1,580 | In-plugin Slack-like messaging between hub editors. **Independent feature**, not in the syndication path. |
| `class-sourcehub-auto-syndicate.php` | 220 | Rule-based auto-spoke-selection on save. |

### Admin classes (in `admin/`)

| File | Role |
|---|---|
| `class-sourcehub-admin.php` (1,920 lines) | All admin pages, settings, meta boxes, AJAX endpoints. |
| `class-sourcehub-calendar.php` (430 lines) | Editorial calendar view. |
| `views/` | PHP templates for admin pages. |
| `css/`, `js/` | Admin assets. |

### Database tables (created in `class-sourcehub-database.php`)

| Table | Purpose |
|---|---|
| `wp_sourcehub_connections` | Each spoke connection (URL, API key, name, settings) |
| `wp_sourcehub_logs` | Activity log (the CSVs in `logs/newlogs/` are exports of this) |
| `wp_sourcehub_queue` | Legacy job queue (mostly superseded by Action Scheduler) |
| `wp_sourcehub_sync_jobs` | Current async job state |
| `wp_sourcehub_messages`, `_groups`, `_group_members`, `_message_reads`, `_user_activity`, `_message_reactions` | The messaging feature |
| `wp_sourcehub_bug_*` | Bug tracker feature |

### Async / background work

SourceHub uses **Action Scheduler** (bundled in `lib/action-scheduler/`) for all background jobs. Don't replace this; it's WP-compatible and battle-tested. Hub-side jobs are dispatched via `wp_remote_post()` to spoke REST endpoints; the spoke acks immediately and processes via Action Scheduler on its end, then calls back to `/wp-json/sourcehub/v1/sync-complete` on the hub.

---

## 4. End-to-end dataflow: "a post is published on the hub"

This is the single most important flow in the codebase. Trace it once on day 1.

```
USER clicks "Publish" on hub
  │
  ▼
WP fires `transition_post_status` (draft → publish)
  │
  ▼  hub-manager.php :: handle_status_transition()
First-publish path:
  → syndicate_post($post_id, $spoke_ids)
  │
  ▼  hub-manager.php :: syndicate_post()
For each selected spoke:
  1. wake_up_spoke()           [HTTP GET to /wp-json/sourcehub/v1/status]
  2. Build content payload     [original content, before AI]
  3. ai_rewriter::rewrite()    [if AI enabled for that spoke]
  4. smart_links::process()    [/path/ → https://spoke.com/path/]
  5. yoast_integration::collect_yoast_data()
  6. POST /wp-json/sourcehub/v1/receive-post on spoke
        Spoke creates a DRAFT post immediately, returns job_id + 202
  7. Hub logs `syndicate_queued` with job_id
  │
  ▼  When all 10 spokes have responded with draft created:
hub-manager.php :: handle_sync_complete() (REST callback from spoke)
  → Increments completion counter
  → When counter hits N (spoke_count), schedules `sourcehub_delayed_sync`
       via wp_schedule_single_event(time() + 2, ...)
  │
  ▼  2 seconds later
hub-manager.php :: handle_delayed_sync()
  → For each spoke: send UPDATE with featured image, Yoast data,
    and `post_status: publish` so drafts flip to published.
  → Spoke flips its draft to publish, calls back with sync_complete.
```

**Why the two-phase draft-then-publish?** It avoids race conditions where the spoke would try to publish before all metadata had arrived. Slow spokes (notably Williamson Source) used to publish empty/stub posts during the gap.

**Update flow** (`update_syndicated_post`) is similar but skips the draft phase — just sends one UPDATE per spoke.

---

## 5. Local development setup

The original developer used **Local by Flywheel** ("Local WP"). The hub site root is:

```
/Users/michaelfreeman/Local Sites/hub-primary/app/public/
```

The plugin lives at `wp-content/plugins/SourceHub/` and is git-tracked separately from the rest of the WP install.

### Minimum to get a working local hub

1. Install Local WP (https://localwp.com).
2. Create a new local site.
3. Clone the plugin into that site's `wp-content/plugins/`:
   ```bash
   cd /path/to/local-site/app/public/wp-content/plugins
   git clone git@github.com:mikefreemantn/SourceHub.git
   ```
4. Activate the plugin in WP admin.
5. SourceHub → Settings → choose Hub or Spoke mode.
6. If Hub: SourceHub → Settings → add OpenAI API key (for AI rewrite testing).

### To test syndication locally

You need **two** Local sites running simultaneously — one configured as hub, one as spoke. On the spoke:

1. Set mode to Spoke.
2. Copy its generated API key (SourceHub → Settings).
3. On the hub: SourceHub → Connections → Add connection, paste in the spoke's URL + API key.
4. Test connection. Should return success.
5. Publish a post on the hub with the spoke selected.

### Debugging

Enable in `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

SourceHub also writes its own activity log to `wp_sourcehub_logs` and displays it under SourceHub → Activity Logs. The CSVs in `logs/newlogs/` are exports from production for past investigation work — feel free to delete them.

---

## 6. Deployment

**Read `DEPLOYMENT.md` in full before your first deploy.** Key points:

- Production sites use **GitUpdater** to auto-update from GitHub releases.
- GitUpdater requires specific plugin headers (`Release Asset: true`, `Update URI`, `GitHub Plugin URI`, `Primary Branch: main`). Do not remove them.
- Releases must have a **manually-uploaded zip asset** named `SourceHub-vX.Y.Z.zip` — GitUpdater prefers it over GitHub's auto-zipball.
- Build the zip with `git archive` (only tracked files), not by manually zipping the working dir.
- Version number must be bumped in **both** `sourcehub.php` header AND `SOURCEHUB_VERSION` constant.

There's a `deploy.sh` script but **read it before using** — it doesn't currently upload the zip asset, so it leaves releases incomplete for GitUpdater.

### Standard release commands

```bash
# 1. Edit version in sourcehub.php (header + constant), update CHANGELOG.md, commit
git commit -am "vX.Y.Z: <summary>"
git push origin main

# 2. Tag
git tag -a vX.Y.Z -m "vX.Y.Z - <summary>"
git push origin vX.Y.Z

# 3. Build clean zip
git archive --format=zip --prefix=SourceHub/ -o /tmp/SourceHub-vX.Y.Z.zip vX.Y.Z

# 4. Release with asset
gh release create vX.Y.Z /tmp/SourceHub-vX.Y.Z.zip \
  --title "vX.Y.Z - <summary>" \
  --notes "<release notes>"
```

---

## 7. Current state (as of 2026-06-08)

### Just shipped

- **v2.4.2** (2026-06-01) — Fixed duplicate hook registration that was causing every syndication request to fire 2× per save. Root cause was `Hub_Manager::init()` / `Spoke_Manager::init()` being invoked more than once per request on WPEngine (likely their MU plugin layer). Fix is a `static $initialized` guard matching the existing pattern in `SourceHub_Admin` and `SourceHub_Validation`. See `CHANGELOG.md` for full notes.

### Pending / known issues (prioritized)

1. **`save_post_meta` nonce expiration logging is noisy** — when a post editor is left open >24h, the nonce becomes stale and SourceHub silently drops the spoke selection on save. Recommended for v2.4.3: distinguish `wp_verify_nonce` return value `2` (stale-but-recently-valid) from `false` (invalid), and surface the failure to the editor via `add_settings_error()` so the user knows their selection was dropped.
2. **Slow spoke (Williamson Source) async-create timing** — Williamson is the slowest spoke; if anything else slips through the v2.4.2 fix, it'll show up there first. Watch for `race_prevented` entries in its activity log.
3. **`TODO.md` has more pending items.** Read it. The "Real-Time Syndication Status Updates" entry has a `⚠️ HIGH COMPLEXITY` warning that was earned the hard way.
4. **`log-viewer-new.php` is an empty 0-byte file** in `includes/`. Either delete it or finish the rewrite.

### Areas of accumulated tech debt

- `class-sourcehub-hub-manager.php` is **4,200 lines**. A long-term goal is to extract syndication logic into focused services (e.g., `SyndicationDispatcher`, `DelayedSyncScheduler`, `SpokeClient`). Don't try this in a small PR — needs a deliberate refactor branch with full test coverage first.
- `class-sourcehub-admin.php` is **1,920 lines**. Same shape — admin pages should each be their own file.
- There's **no automated test suite**. All testing has been manual: publish a post on local hub, watch the spoke. Adding PHPUnit + a couple of integration tests would be high-value for the next dev.

---

## 8. Document map

What to read, in what order, and what to ignore.

### Read first
- **`HANDOFF.md`** ← you are here
- **`README.md`** — feature overview
- **`DEPLOYMENT.md`** — release flow (critical, GitUpdater is non-obvious)
- **`CHANGELOG.md`** — version history
- **`TODO.md`** — known open issues
- **`project_overview.md`** — original project brief
- **`CONTRIBUTING.md`** — coding standards

### Reference (read when relevant)
- **`AUTO-SYNDICATE-API.md`** — rule-based spoke selection
- **`GIT-UPDATER-SETUP.md`** — how GitUpdater is configured per-site
- **`SMART_LINKS_README.md`** — the `[smart-link]` shortcode system
- **`PUBLICATION_STORAGE_REFERENCE.md`** — how publication metadata is stored
- **`VALIDATION-SYSTEM.md`** — pre-syndication content checks
- **`project_documentation_important_notes.md`** — historical gotchas
- **`PROJECT_SUMMARY.md`** — client-facing project report (note: dates from Dec 2025, version listed there is stale)

### Archived (in `docs/archive/`)
Historical debug notes from completed work. Keep for context; nothing actionable.

---

## 9. Contact points

- **GitHub repo:** https://github.com/mikefreemantn/SourceHub
- **Original developer:** Mike Freeman
- **Production hub:** https://hub2a.wpenginepowered.com (WPEngine)
- **Production spokes:** Williamson Source, Sumner County Source, Rutherford County Source, Wilson County Source, Robertson County Source, Cannon County Source, Davidson County Source, Maury County Source, Dickson County Source, Cheatham County Source.

---

## 10. Things that will bite you if you don't read them

1. **WPEngine + GitUpdater = branch-locked installs.** If a site was originally installed by downloading the `main` branch zip (before `Release Asset: true` was added), it will permanently track branch HEAD and ignore tags. See "Critical Gotcha" in `DEPLOYMENT.md`. The fix is deactivate → delete → reinstall via GitUpdater **Install Plugin** with the GitHub URL.

2. **The hub fires hooks twice on some hosts** (unless you have v2.4.2+). If you ever revert the `static $initialized` guard, you'll resurrect a class of bugs that took weeks to diagnose. The guard is documented inline in `class-sourcehub-hub-manager.php:30-43`.

3. **Spoke "draft first, publish later" is intentional**, not a bug. Don't optimize it away. The 2-second delay before the "publish" UPDATE is what gives slow spokes time to write featured images + Yoast data before they go live.

4. **AI rewriting is non-deterministic per-call.** Two calls with the same input produce slightly different output. This is why duplicate-fire bugs (like the one v2.4.2 fixed) cause "wrong content" — last-write-wins between two simultaneous AI rewrites of the same post.

5. **The `Newspaper` theme (tagDiv)** is in use on hub and several spokes. It writes a lot of its own meta on `save_post`. SourceHub's `save_post_meta` runs at priority **99** to fire after Newspaper's meta saves. Don't lower that priority.

6. **Don't delete `lib/action-scheduler/`.** It's WooCommerce's job queue library, bundled here. Replacing it is a multi-day project.

---

That's it. Welcome aboard. When in doubt, grep — the codebase is consistent enough that following naming conventions will get you most of the way.
