# EXTENSION-ARCHITECTURE.md — Directorist – Advanced Fields

> Generated from source analysis of the plugin at `wp-content/plugins/directorist-advanced-fields/` (v2.2.0).
> Everything below is derived from the actual files. Statements about Directorist *internals* that
> cannot be seen from this codebase are explicitly marked **[ASSUMPTION]**.

---

## 1. Overview

**What it does:** Adds new custom field types ("widgets") to the Directorist directory-type
**form builder**, renders them in the front-end/admin **Add Listing** form, sanitizes their
values on save, and renders the saved values on the **single listing** page. It also adds a
multi-location radius search that understands the Address List field.

| Item | Value | Source |
|---|---|---|
| Plugin name | `Directorist - Advanced Fields` | `directorist-advanced-fields.php` header |
| Version | `2.2.0` (also `DIRECTORIST_ADVANCED_FIELDS_VERSION`) | header + line 19 |
| Author | wpXplore | header |
| Text domain | `directorist-advanced-fields` | header |
| Required WP / PHP / Directorist versions | **Not declared** — no `Requires at least`, `Requires PHP`, or `Requires Plugins` headers exist | header (see Open questions) |
| Hard dependency | Directorist — bootstrap aborts unless `\Directorist\Directorist_Listing_Form` exists | `directorist-advanced-fields.php:82-90` |

**Active field types** (loaded in `includes/class-advanced-fields.php:22-33`):
`iframe`, `shortcode`, `youtube-video`, `vimeo-video`, `wp-editor`, `addresses` (Address List), `featured-checkbox`.

**Present but DISABLED** (include lines commented out at `class-advanced-fields.php:30-31`):
`feature` (Feature List) and `repeater`. Their field classes, templates, JS/CSS, and sanitizers all
exist and would work if the two `include_once` lines were uncommented.

### Bootstrap / initialization flow

`directorist-advanced-fields.php`:

1. Defines `DIRECTORIST_ADVANCED_FIELDS_VERSION`.
2. Declares final singleton class `Directorist_Advanced_Fields` (global namespace) with static
   `$base_dir` / `$base_url` (set from `plugin_dir_path/url`).
3. On `plugins_loaded` **priority 30**, if `\Directorist\Directorist_Listing_Form` exists, calls
   `Directorist_Advanced_Fields()` → `::instance()` → `init()`:
   - `load_textdomain()`
   - `includes()` → `require_once` of the five `includes/class-*.php` files + `vendor/autoload.php`.
4. **Every included file self-instantiates at the bottom** (`new Advanced_Fields;`,
   `new Daf_Hooks();`, `new DAF_Scripts();`, `new DAF_Multi_Location_Radius_Search();`, and each
   field file ends with `new Advanced_Fields_<X>;`). There is no service container or registry —
   construction *is* registration (constructors only call `add_filter`/`add_action`).

---

## 2. Directory & file map

```
directorist-advanced-fields/
├── directorist-advanced-fields.php   ★ ENTRY POINT — header, singleton bootstrap, includes
├── composer.json                     PSR-4 map "Inc\" → ./includes (unused — see §3)
├── vendor/                           Composer autoloader only (no packages; require {} is empty)
├── README.md                         User-facing readme (install, field list)
├── REPEATER_FIELD_README.md          Repeater field docs (partly aspirational — see §9)
├── languages/directorist-advanced-fields.pot
├── includes/                         ★ CORE LOGIC
│   ├── class-advanced-fields.php     Registers "Advanced Fields" builder group; includes field files
│   ├── class-hooks.php               Daf_Hooks — sanitizes submitted values on save (admin + frontend)
│   ├── class-helper.php              Helper — template loader, kses/shortcode allowlists, URL parsers,
│   │                                 repeater sub-field renderer, conditional-logic field definition
│   ├── class-scripts.php             DAF_Scripts — conditional asset enqueueing (front + admin)
│   ├── class-addresses-radius-serach.php  DAF_Multi_Location_Radius_Search — multi-address radius
│   │                                 search (note filename typo "serach")
│   └── fields/                       One class per field type; each registers the same 4 filters
│       ├── iframe.php                Advanced_Fields_Iframe        (widget 'iframe')
│       ├── shortcode.php             Advanced_Fields_Shortcode     (widget 'shortcode')
│       ├── youtube.php               Advanced_Fields_Youtube       (widget 'youtube-video')
│       ├── vimeo.php                 Advanced_Fields_Vimeo         (widget 'vimeo-video')
│       ├── wp-editor.php             Advanced_Fields_WP_Editor     (widget 'wp-editor')
│       ├── addresses.php             Advanced_Fields_Address_List  (widget 'addresses')
│       ├── featured-checkbox.php     Advanced_Fields_Featured_Checkbox (widget 'featured-checkbox')
│       ├── feature.php               Advanced_Fields_Feature       (widget 'feature') — NOT LOADED
│       └── repeater.php              Advanced_Fields_Repeater      (widget 'repeater') — NOT LOADED
├── templates/
│   ├── listing-form/<widget>.php     Add Listing form markup, one per widget
│   └── single/<widget>.php           Single listing page markup, one per widget
└── assets/
    ├── css/base.css                  Shared styles (single page + form)
    ├── css/repeater.css              Repeater form styles
    ├── js/address.js                 Address List form UI (rows + Google Places autocomplete → JSON)
    ├── js/repeater.js                Repeater form UI (clone/remove rows → JSON hidden input)
    ├── js/google-map.js              Single-page multi-address Google map
    └── js/openstreet-map.js          Single-page multi-address Leaflet/OSM map (lazy-loads Leaflet from unpkg)
```

---

## 3. Naming conventions & prefixes

| Thing | Convention | Examples |
|---|---|---|
| Text domain | `directorist-advanced-fields` — **but inconsistently**: many strings use `'directorist'` instead (e.g. all labels in `includes/fields/iframe.php`, `Helper::get_conditional_logic_field()`) | |
| PHP namespace | `Directorist_Advanced_Fields` for everything in `includes/` (the bootstrap class is global-namespace with the *same name*, hence `use Directorist_Advanced_Fields;` in `class-helper.php:13`) | |
| Class prefixes | Mixed: `Advanced_Fields_*` (field classes), `Daf_Hooks`, `DAF_Scripts`, `DAF_Multi_Location_Radius_Search` | |
| Constants | `DIRECTORIST_ADVANCED_FIELDS_VERSION` (only one) | |
| Field keys (defaults) | `custom-<type>` (e.g. `custom-iframe`, `custom-youtube`) — **except** `addresses`, which uses the fixed key `addresses` | field files |
| Meta keys | `'_' . field_key` (underscore prefix added in `Daf_Hooks::sanitize_plugin_listing_meta()`, `class-hooks.php:51`) | `_custom-iframe`, `_addresses` |
| Own hook prefix | `daf_` for its own filters (`daf_allowed_iframe_html`, `daf_allowed_shortcode_tags`), plus the unprefixed aggregator `atbdp_form_advanced_widgets` | `class-helper.php`, `class-advanced-fields.php` |
| Script/style handles | `daf-*` (`daf-style`, `daf-repeater-script`, `daf-address`, `daf-google-map`, `daf-openstreet-map`) | `class-scripts.php` |
| CSS classes | Front form: `directorist-form-group`, `directorist-form-element` (Directorist's own classes); addresses UI uses `address_item`, `google_addresses*`; repeater uses `directorist-repeater*`, `repeater-fieldset` | templates |

**Autoloading: manual includes, not Composer.** `composer.json` maps PSR-4 `"Inc\" => ./includes`,
and `vendor/autoload.php` is required — but **no class in the plugin uses the `Inc\` namespace**, so the
Composer autoloader never loads anything. All real loading is `require_once`/`include_once`:
bootstrap → 5 core classes; `Advanced_Fields::advanced_fields()` → field files. Follow the manual
pattern when adding files; do not rely on the autoloader.

---

## 4. Architecture & classes

All classes are instantiated once at include time and register hooks in `__construct()`.

### `Directorist_Advanced_Fields` (bootstrap, global namespace)
Singleton. Holds `$base_dir`/`$base_url` used by `Helper::get_file_dir()/get_file_uri()`. Loads everything else.

### `Advanced_Fields` — `includes/class-advanced-fields.php`
- `advanced_fields()`: `include_once` of each active field file (this is the **on/off switch** per field type).
- `atbdp_listing_type_form_fields( $fields )` (hooked to Directorist filter of the same name):
  collects all widgets from the extension's own filter `apply_filters('atbdp_form_advanced_widgets', [])`
  and injects them as a new form-builder group:
  ```php
  $fields['widgets']['advanced'] = [
      'title' => 'Advanced Fields', 'description' => '…',
      'allowMultiple' => true, 'widgets' => $advenced_fields,
  ];
  ```
  **[ASSUMPTION]** Directorist's builder UI (a JS app) consumes this `widgets` array to render the
  drag-and-drop palette; that consumption is not visible in this codebase.

### `Daf_Hooks` — `includes/class-hooks.php` (save-time sanitization)
Hooks two Directorist filters (both funnel into `sanitize_plugin_listing_meta()`):
- `atbdp_listing_meta_admin_submission` (admin save)
- `atbdp_ultimate_listing_meta_user_submission` (front-end save)

Flow: resolve directory id (from `$posted_data['directory_id']`, `$meta_data['_directory_type']`, or
a `directory_type` slug looked up in the `ATBDP_TYPE` taxonomy) → `directorist_get_listing_form_fields( $directory_id )`
→ for each field whose meta key `'_' . field_key` is present in `$meta_data`, re-sanitize by
`widget_name`: `iframe` → kses allowlist; `wp-editor` → `wp_kses_post`; `addresses` → per-item
label/address/lat/lng sanitize, re-encoded as JSON; `repeater` → per-sub-field type-aware sanitize
against the configured sub-fields/options, re-encoded as JSON; `youtube-video`/`vimeo-video` → `esc_url_raw`.
`shortcode` and `featured-checkbox` have **no case here** — shortcode is stored raw and only
filtered at render time; featured-checkbox relies on Directorist's default checkbox handling **[ASSUMPTION]**.

### `Helper` — `includes/class-helper.php` (static utility hub)
- `get_template_part( $template, $data )`: the template loader. Computes
  `$listing_form = $data['form'] ?? \Directorist\Directorist_Listing_Form::instance()` and
  `$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data )`, then
  `require`s `templates/<template>.php`. Inside the template, **`$data`, `$listing_form`, and
  `$conditional_logic_attr` are the in-scope variables**.
- Security allowlists: `get_allowed_iframe_html()` / `sanitize_iframe_html()` (iframe tag + safe attrs,
  filterable via `daf_allowed_iframe_html`); `get_allowed_shortcode_tags()` /
  `render_allowed_shortcode()` (default allowlist `audio,caption,gallery,playlist,video`, filterable via
  `daf_allowed_shortcode_tags`; refuses unknown/unregistered shortcodes).
- `parse_youtube()` / `parse_vimeo()`: raw URL → embeddable player URL (used only at render time).
- `display_repeater_field()`: echoes one repeater sub-input (`name="<parent>[<index>][<sub_key>]"`).
- `feature_get_label()` / `feature_option_list()`: option lookups for the (disabled) feature field.
- `get_conditional_logic_field()`: the shared `conditional_logic` option definition every widget adds.
- `get_directorist_option()`: local copy of Directorist's option reader (reads the `atbdp_option` option array).

### `DAF_Scripts` — `includes/class-scripts.php` (assets; see §7)

### `DAF_Multi_Location_Radius_Search` — `includes/class-addresses-radius-serach.php`
Hooks `atbdp_listing_search_query_argument`. When the query args contain `atbdp_geo_query`, it runs
the default geo query AND its own multi-address pass (haversine distance against every address in the
meta keys `addresses` and `_multilocation`), merges the post-ID sets, and rewrites the args to
`post__in` (or `post__in => [0]` when nothing matches). ⚠️ Note the meta keys it reads — see §9.

### Data-flow summary

```
FORM BUILDER (admin, directory type editor)
  Directorist filter 'atbdp_listing_type_form_fields'
    └─ Advanced_Fields adds group 'advanced' ← apply_filters('atbdp_form_advanced_widgets')
         └─ each field class appends its widget definition (label, icon, options schema)
  Directorist filter 'atbdp_single_listing_content_widgets'
    └─ each field class appends its single-page widget options (icon, display toggles)
  ⇒ Directorist persists the configured field into the directory type  [ASSUMPTION: term meta;
    read back via directorist_get_listing_form_fields()]

ADD LISTING FORM (front end / admin)
  Directorist filter 'directorist_field_template' ($template, $field_data)
    └─ field class matches $field_data['widget_name'] → Helper::get_template_part('listing-form/<x>', $field_data)
       (template ECHOES markup; the $template string is returned unchanged)

SAVE
  Directorist collects POST into $meta_data ('_'.field_key => value)  [ASSUMPTION: core behavior]
  filters 'atbdp_listing_meta_admin_submission' / 'atbdp_ultimate_listing_meta_user_submission'
    └─ Daf_Hooks re-sanitizes this extension's keys
  ⇒ stored as post meta on the 'at_biz_dir' post  [ASSUMPTION: core does update_post_meta]

SINGLE LISTING PAGE
  Directorist filter 'directorist_single_item_template' ($template, $field_data)
    └─ field class matches widget_name → Helper::get_template_part('single/<x>', $field_data)
       ($field_data carries 'value', 'label', 'icon', and form config under 'form_data')
```

---

## 5. Directorist integration points

### Hooks this extension LISTENS to (all Directorist-side unless noted)

| Hook | Type | Registered in | Purpose |
|---|---|---|---|
| `plugins_loaded` (prio 30) | action (WP) | main file | Boot after Directorist |
| `atbdp_listing_type_form_fields` | filter | `Advanced_Fields` | Add the "Advanced Fields" widget group to the form builder |
| `atbdp_form_advanced_widgets` | filter (**own hook**, applied in `Advanced_Fields`, added to by every field class) | field classes | Aggregate widget definitions |
| `atbdp_single_listing_content_widgets` | filter | every field class | Register single-page builder options per widget |
| `directorist_field_template` (10, 2) | filter | every field class | Render the Add Listing form field |
| `directorist_single_item_template` (10, 2) | filter | every field class | Render the single-listing output |
| `atbdp_listing_meta_admin_submission` (10, 2) | filter | `Daf_Hooks` | Sanitize meta on admin save |
| `atbdp_ultimate_listing_meta_user_submission` (10, 2) | filter | `Daf_Hooks` | Sanitize meta on front-end save |
| `atbdp_listing_search_query_argument` | filter | `DAF_Multi_Location_Radius_Search` | Multi-address radius search |
| `wp_enqueue_scripts` (prio 999999), `admin_enqueue_scripts` | action (WP) | `DAF_Scripts` | Assets |
| `atbdp_add_listing_wp_editor_settings` | filter (applied) | `templates/listing-form/wp-editor.php` | wp_editor() settings passthrough |
| `directorist_custom_field_meta_key_field_args` | filter (applied) | field classes | Lets Directorist/others adjust the `field_key` option definition |

### Hooks this extension FIRES (public API)

- `atbdp_form_advanced_widgets` — add your own widget into the "Advanced Fields" group.
- `daf_allowed_iframe_html` — extend the iframe kses allowlist.
- `daf_allowed_shortcode_tags` — extend the shortcode allowlist.

### How a field type is REGISTERED
Each field class adds one entry to `atbdp_form_advanced_widgets`:

```php
$widgets['iframe'] = [
    'label' => 'iFrame', 'icon' => 'las la-window-maximize',
    'options' => [
        'type'      => [ 'type' => 'hidden', 'value' => 'iframe' ],
        'label'     => [ 'type' => 'text', ... ],
        'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
            'type' => 'hidden', 'value' => 'custom-iframe',
            'rules' => [ 'unique' => true, 'required' => true ],
        ]),
        'class' => …, 'placeholder' => …, 'description' => …,
        'required' => [ 'type' => 'toggle', ... ],
        'only_for_admin' => [ 'type' => 'toggle', ... ],
        'conditional_logic' => Helper::get_conditional_logic_field(),
    ],
];
```
The **array key** (`iframe`) becomes `widget_name` in `$field_data` everywhere else — it is the
identity of the field type. Option types observed: `hidden`, `text`, `number`, `toggle`, `select`,
`icon`, `color`, `radio`, `multi-fields` (nested repeatable options, supports `show_if` conditions —
see `includes/fields/repeater.php:135-145`).

### How the Add Listing form renders
Directorist applies `directorist_field_template( $template, $field_data )` per field
**[ASSUMPTION: called while output is being generated, since the callbacks echo]**. The matching
class echoes its template via `Helper::get_template_part('listing-form/<x>', $field_data)` and
returns `$template` untouched. Templates use Directorist form services:
`$listing_form->field_label_template($data)`, `->field_description_template($data)`,
`->required($data)`, and print `$conditional_logic_attr` on the wrapper div. The input's
`name` attribute is always `$data['field_key']` (checkbox variants append `[]`; addresses/repeater
serialize rows into one hidden JSON input named `field_key`).

The admin form-builder UI itself is Directorist's — this extension only supplies the widget
definition arrays above; it ships **no builder-side JS**.

### Validation / sanitization / saving
- The extension does **not** save anything itself. Directorist core writes post meta
  **[ASSUMPTION]**; the extension intercepts the two `*_submission` filters and re-sanitizes
  its own keys inside `$meta_data` (details in §4, `Daf_Hooks`).
- Meta key: `'_' . field_key` (e.g. `_custom-youtube`, `_addresses`).
- Storage formats: plain string (iframe HTML, shortcode, URLs), HTML (`wp-editor`),
  JSON string of objects (`addresses`, `repeater`), array of option values (`featured-checkbox`, `feature`) **[ASSUMPTION for the array types — inferred from templates casting `(array) $data['value']`]**.

### Retrieval / display on single listing
Directorist reads the meta and applies `directorist_single_item_template( $template, $field_data )`
per configured single-page widget, with `$field_data['value']` prefilled and form config under
`$field_data['form_data']` **[ASSUMPTION about who builds `$field_data`]**. The matching class echoes
`templates/single/<x>.php`. Render-time transforms:
- youtube/vimeo: `Helper::parse_youtube()/parse_vimeo()` → `<iframe>` embed.
- iframe: `Helper::sanitize_iframe_html()` again at output (defense in depth).
- shortcode: `Helper::render_allowed_shortcode()` (allowlist + `shortcode_exists` + `do_shortcode`).
- addresses: JSON-decoded in the field class (`addresses.php:133`) before the template; optional map
  (`#addresses-map`) driven by `google-map.js` / `openstreet-map.js` reading `data-lat/lng` off the cards.
- Several classes/templates skip output when the value is empty (`youtube.php:102`,
  `single/iframe.php:13`, etc.).

There is **no theme-override mechanism**: `Helper::get_template_part()` `require`s only from the
plugin's own `templates/` directory.

---

## 6. Data storage

**Field definitions** (what the admin builds in the form builder): persisted by **Directorist**, not
this plugin. Confirmed touchpoints: read back via `directorist_get_listing_form_fields( $directory_id )`
(`class-hooks.php:38`), and the directory id maps to a term in the `ATBDP_TYPE` taxonomy
(`class-hooks.php:104-108`). **[ASSUMPTION]** Definitions therefore live in directory-type term meta.

**Submitted values**: post meta on `at_biz_dir` posts (CPT name confirmed at
`class-scripts.php:114` and `class-addresses-radius-serach.php:84`).

| Widget | Meta key (default field_key) | Shape |
|---|---|---|
| iframe | `_custom-iframe` | Sanitized `<iframe …>` HTML string |
| shortcode | `_custom-shortcode` | Raw shortcode string, e.g. `[gallery ids="1,2"]` |
| youtube-video | `_custom-youtube` | Watch/short URL string (embed URL derived at render) |
| vimeo-video | `_custom-vimeo` | Vimeo URL string |
| wp-editor | `_custom-wp-editor` | `wp_kses_post`-filtered HTML |
| featured-checkbox | `_custom-featured-checkbox` | Array of selected `option_value`s **[ASSUMPTION]** |
| addresses | `_addresses` (field key is fixed, not editable) | JSON string: `[{"label":"","address":"","latitude":"","longitude":""}, …]` |
| repeater (disabled) | `_custom-repeater` | JSON string: `[{"<sub_key>":"value"|["v1","v2"], …}, …]` |
| feature (disabled) | `_custom-feature` | Array of selected `option_value`s **[ASSUMPTION]** |

Options read (not written): `atbdp_option` (Directorist settings array — `select_listing_map`,
`map_api_key`). The plugin registers **no options, tables, or CPTs of its own**.

---

## 7. Assets (`DAF_Scripts`, `includes/class-scripts.php`)

Versioning: `filemtime()` of the file (cache-busting), falling back to the plugin version.

**Front end** (`wp_enqueue_scripts`, priority 999999) — only on single `at_biz_dir` pages or the
Directorist "form"/"dashboard" pages (`directorist_get_page_id()`):

| Asset | When | Purpose |
|---|---|---|
| `daf-style` (base.css) | single listing OR submission pages | Shared styling for all field output + form UI |
| `daf-repeater-style/script` (+ `repeaterFieldOptions` localization: `ajax_url`, nonce `daf_repeater_nonce`, i18n strings) | submission pages | Repeater add/remove/reindex rows; serializes rows into the hidden JSON input (`repeater.js`) |
| `daf-address` (address.js; depends on `google-map-api` if registered, else jQuery) | submission pages | Address rows UI, Google Places autocomplete, lat/lng capture, JSON serialization into `.google_addresses_json` |
| `daf-openstreet-map` | single listing AND `select_listing_map === 'openstreet'` | Renders `#addresses-map` with Leaflet (lazy-loaded from unpkg CDN) |
| `daf-google-map` (depends on `google-map-api`) | single listing AND `select_listing_map === 'google'` | Renders `#addresses-map` with Google Maps (AdvancedMarkerElement) |

**Admin** (`admin_enqueue_scripts`) — only on `post`/`post-new` screens for post type `at_biz_dir`:
base.css + repeater assets + address assets (the admin listing edit screen reuses the same form markup).

Note: the repeater assets are enqueued even though the repeater *field* is currently disabled; they
are harmless without `.directorist-repeater` markup on the page.

---

## 8. ★ How to add a new custom field type (step-by-step recipe)

The codebase's pattern is: **one field class + two templates + (optional) a sanitize case + (optional) assets.**
Example below: a "Spotify Track" field storing a Spotify URL, widget name `spotify-track`.

### Step 1 — Create the field class `includes/fields/spotify.php`

Follow the existing skeleton exactly (copy `includes/fields/youtube.php`, it's the cleanest):

```php
<?php
namespace Directorist_Advanced_Fields;

defined( 'ABSPATH' ) || exit;

use Directorist_Advanced_Fields\Helper;

class Advanced_Fields_Spotify
{
    public function __construct()
    {
        add_filter('atbdp_form_advanced_widgets', array($this, 'atbdp_form_advanced_widgets'));
        add_filter('atbdp_single_listing_content_widgets', array($this, 'atbdp_single_listing_content_widgets'));
        add_filter('directorist_field_template', array($this, 'directorist_field_template'), 10, 2);
        add_filter('directorist_single_item_template', array($this, 'directorist_single_item_template'), 10, 2);
    }

    // 1) Form-builder registration. Array key = widget_name everywhere else.
    public function atbdp_form_advanced_widgets($widgets)
    {
        $widgets['spotify-track'] = array(
            'label' => 'Spotify Track',
            'icon'  => 'lab la-spotify',
            'options' => [
                'type' => [ 'type' => 'hidden', 'value' => 'text' ],
                'field_key' => apply_filters('directorist_custom_field_meta_key_field_args', [
                    'type'  => 'hidden',
                    'label' => __('Key', 'directorist'),
                    'value' => 'custom-spotify',           // meta key becomes  _custom-spotify
                    'rules' => [ 'unique' => true, 'required' => true ],
                ]),
                'label'       => [ 'type' => 'text', 'label' => __('Label', 'directorist'), 'value' => 'Spotify Track' ],
                'class'       => [ 'type' => 'text', 'label' => __('Class', 'directorist'), 'value' => 'directorist-field-spotify' ],
                'placeholder' => [ 'type' => 'text', 'label' => __('Placeholder', 'directorist'), 'value' => 'Only Spotify URLs.' ],
                'required'       => [ 'type' => 'toggle', 'label' => __('Required', 'directorist'), 'value' => false ],
                'only_for_admin' => [ 'type' => 'toggle', 'label' => __('Only For Admin Use', 'directorist'), 'value' => false ],
                'conditional_logic' => Helper::get_conditional_logic_field(),
            ],
        );
        return $widgets;
    }

    // 2) Single-page builder options (at minimum the icon).
    public function atbdp_single_listing_content_widgets($widgets)
    {
        $widgets['spotify-track'] = [
            'options' => [
                'icon' => [ 'type' => 'icon', 'label' => 'Icon', 'value' => 'lab la-spotify' ],
            ],
        ];
        return $widgets;
    }

    // 3) Add Listing form rendering.
    public function directorist_field_template($template, $field_data)
    {
        if ('spotify-track' === $field_data['widget_name']) {
            Helper::get_template_part('listing-form/spotify', $field_data);
        }
        return $template;
    }

    // 4) Single listing rendering.
    public function directorist_single_item_template($template, $field_data)
    {
        if ('spotify-track' === $field_data['widget_name']) {
            if (!empty($field_data['value'])) {
                Helper::get_template_part('single/spotify', $field_data);
            }
        }
        return $template;
    }
}

new Advanced_Fields_Spotify;   // ← mandatory: files self-instantiate
```

### Step 2 — Register the include

Edit `includes/class-advanced-fields.php` → `advanced_fields()` and add:

```php
include_once Helper::get_file_dir() . '/includes/fields/spotify.php';
```

(This is the only "registration" step; without it nothing loads.)

### Step 3 — Create the form template `templates/listing-form/spotify.php`

Inside the template you have `$data` (the field config + `value` + `field_key`…), `$listing_form`,
and `$conditional_logic_attr` (provided by `Helper::get_template_part()`):

```php
<?php
if (!defined('ABSPATH')) exit;
?>
<div class="directorist-form-group <?php echo esc_attr( $data['class'] ); ?>" <?php echo $conditional_logic_attr; // phpcs:ignore ?>>
    <?php $listing_form->field_label_template( $data ); ?>
    <input type="url" name="<?php echo esc_attr( $data['field_key'] ); ?>"
           id="<?php echo esc_attr( $data['field_key'] ); ?>"
           class="directorist-form-element"
           value="<?php echo esc_attr( $data['value'] ); ?>"
           placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>"
           <?php \Directorist\Directorist_Listing_Form::instance()->required( $data ); ?>>
    <?php $listing_form->field_description_template( $data ); ?>
</div>
```

**The input `name` MUST equal `$data['field_key']`** — that's what Directorist collects into
`$meta_data['_' . field_key]` on save.

### Step 4 — Create the single template `templates/single/spotify.php`

```php
<?php
if (!defined('ABSPATH')) exit;

if ( ! $data['value'] ) return;
?>
<div class="directorist-single-info directorist-single-info-spotify <?php echo esc_attr( $data['form_data']['class'] ); ?>">
    <div class="directorist-single-info__label">
        <span class="directorist-single-info__label-icon"><?php directorist_icon( $data['icon'] ); ?></span>
        <span class="directorist-single-info__label--text"><?php echo esc_html( $data['label'] ); ?></span>
    </div>
    <div class="directorist-single-info__value">
        <a href="<?php echo esc_url( $data['value'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $data['value'] ); ?></a>
    </div>
</div>
```

Note: in single templates the form config sits under `$data['form_data']` (e.g.
`$data['form_data']['class']`), while `label`, `icon`, `value` are top-level.

### Step 5 — (Recommended) add a sanitize case in `Daf_Hooks`

In `includes/class-hooks.php`, `sanitize_plugin_listing_meta()`, extend the `switch ( $widget_name )`:

```php
case 'spotify-track':
    $meta_data[ $meta_key ] = esc_url_raw( (string) $raw_value );
    break;
```

Without this, the value is stored with whatever sanitization Directorist's default handling applies
for the hidden `type` you chose (`text` etc.) **[ASSUMPTION]** — always escape at output regardless.

### Step 6 — (Optional) assets

If the field needs JS/CSS, add files under `assets/` and enqueue them in
`includes/class-scripts.php` following the private `enqueue_*` helpers, gated by
`is_frontend_submission_context()` (form) and/or `is_singular('at_biz_dir')` (single page), and mirror
in `daf_admin_enqueue_scripts()` for the admin listing edit screen.

### Step 7 — Test checklist
1. Directory Types → edit type → form builder → the field appears in the **Advanced Fields** group.
2. Drag it in, save; also add the matching widget on the Single Page layout tab (its options come
   from your `atbdp_single_listing_content_widgets` entry).
3. Submit a listing front-end AND edit one in wp-admin (both save paths run different filters).
4. Verify post meta `_custom-spotify` and the single-page output.

### Common pitfalls / gotchas (observed in this codebase)

1. **Forgetting `new Class;` at the file bottom or the `include_once` in `Advanced_Fields`** — there
   is no autoloading of these classes (the Composer PSR-4 map is dead, §3). Both `feature` and
   `repeater` are "invisible" today for exactly this reason (commented-out includes).
2. **Widget key mismatch**: the key used in `atbdp_form_advanced_widgets`, in
   `atbdp_single_listing_content_widgets`, in both `widget_name` comparisons, and in the `Daf_Hooks`
   switch must be *identical strings* (`youtube-video`, not `youtube`).
3. **`$data` shape differs between contexts**: form templates read `$data['class']`; single templates
   read `$data['form_data']['class']`. Copy from an existing pair.
4. **The template filters must still `return $template;`** — they are filters used for side-effect
   (echo). Returning nothing would corrupt other fields' rendering.
5. **`field_key` collisions**: defaults like `custom-iframe` rely on the builder's `unique` rule;
   the `addresses` widget hardcodes a non-editable key `addresses`, so only one Address List behaves
   correctly per form and other code (radius search) depends on that exact key.
6. **Both save paths**: admin saves go through `atbdp_listing_meta_admin_submission`, front-end
   through `atbdp_ultimate_listing_meta_user_submission`. Sanitize in the shared private method as
   `Daf_Hooks` does, or you'll cover only one path.
7. **JSON-serialized fields** (addresses, repeater) submit BOTH row inputs and a hidden JSON input
   named `field_key`; keep the hidden input authoritative and re-encode after sanitizing
   (`Daf_Hooks::sanitize_addresses_value()` pattern). `decode_json_array()` tolerates receiving an
   already-decoded array.
8. **Text domain**: existing code mixes `'directorist'` and `'directorist-advanced-fields'`. Use the
   plugin's own domain for new strings (matches the header and `/languages`).
9. **Escape at output even if sanitized at save** — the iframe field does this
   (`single/iframe.php:15` re-runs the kses allowlist), the shortcode field only render-filters.

---

## 9. Extension points & gaps

### Public hooks for third parties
- **Filter `atbdp_form_advanced_widgets`** — inject additional widgets into the "Advanced Fields"
  builder group without touching this plugin (you'd still need your own template/sanitize hooks).
- **Filter `daf_allowed_iframe_html`** — extend allowed iframe attributes/tags.
- **Filter `daf_allowed_shortcode_tags`** — extend the render-time shortcode allowlist.
- **JS: `window.DirectoristRepeater.init(scope)`** (`repeater.js:330-333`) — re-init repeaters after
  injecting DOM. (A MutationObserver already auto-inits added nodes.)

### Inconsistencies / fragile spots worth flagging

1. **Dead Composer autoloader**: `composer.json` PSR-4 maps `Inc\` → `includes/`, but every class
   uses namespace `Directorist_Advanced_Fields`. `vendor/autoload.php` is required for nothing.
   Also `"license": "GLP"` is a typo (GPL).
2. **Radius-search meta-key mismatch**: `DAF_Multi_Location_Radius_Search::set_geo_query_parameters()`
   reads meta keys `addresses` and `_multilocation` (`class-addresses-radius-serach.php:74`), but the
   Address List field is saved as **`_addresses`** (underscore prefix, `class-hooks.php:51`). Unless
   Directorist also writes an unprefixed copy **[not visible in this codebase]**, the radius search
   never sees this field's data. Likely bug.
3. **Disabled-but-half-wired repeater/feature**: field files exist but aren't included, while the
   repeater sanitizer (`Daf_Hooks`), repeater assets (`DAF_Scripts`), templates, and a dedicated
   README all remain active/shipped. `REPEATER_FIELD_README.md` also documents JS APIs that don't
   exist (`DirectoristRepeater.addItem/removeItem`) — only `.init()` is real.
4. **Unsanitized-at-save shortcode field**: stored raw; safety depends entirely on
   `Helper::render_allowed_shortcode()` at output. Fine as long as no other code echoes the meta.
5. **`extract($data)` in `templates/listing-form/addresses.php:12` and `single/addresses.php:13`**
   — works because the callers pass `['data' => $field_data, 'addresses' => …]`, so `extract`
   *re-defines* `$data` as the inner array. Confusing double meaning of `$data`; easy to break.
6. **Filename typo**: `class-addresses-radius-serach.php` ("serach") — referenced with the same typo
   in the bootstrap, so it works, but renaming requires touching both.
7. **Single templates receive `$conditional_logic_attr` needlessly**; `Helper::get_template_part()`
   always calls `get_conditional_logic_attributes()`, even for single-page rendering, and
   `repeater`/`feature` form templates recompute it themselves.
8. **OpenStreetMap JS loads Leaflet from unpkg CDN at runtime** (`openstreet-map.js`) — an external
   dependency not registered through WordPress, invisible to asset tooling and blocked on locked-down
   sites.
9. **Version drift**: `assets` version fallback hardcodes `'2.2.0'` (`class-scripts.php:30`) in
   addition to the constant; keep in sync on release.
10. **No uninstall/cleanup**: no `uninstall.php`; saved meta and builder config persist after removal
    (arguably correct, but undocumented).

---

## Open questions (not answerable from these files)

1. **Where exactly Directorist persists the form-builder config** (term meta name/shape for
   directory types) — only the read API `directorist_get_listing_form_fields()` is visible here.
2. **How Directorist maps POST → `$meta_data`** (which input names it collects, how `type: hidden`
   `'text'/'checkbox'` affect default sanitization) — inferred from the templates' `name=field_key`
   convention and the `_`-prefixed keys in `Daf_Hooks`.
3. **Whether `directorist_field_template` / `directorist_single_item_template` are applied inside an
   output buffer or during direct output** — callbacks echo and return `$template` unchanged, which
   only makes sense if Directorist echoes around this filter; the core call site isn't in this repo.
4. **Minimum supported Directorist/WP/PHP versions** — no requirement headers exist. Code uses PHP 7+
   syntax (null coalescing `??`, arrow-free closures), so PHP ≥ 7.0 is implied at minimum.
5. **Whether the `featured-checkbox` value is stored as an array or serialized string** — no sanitize
   case exists for it; templates cast `(array) $data['value']`.
6. **The intended consumer of the `_multilocation` meta key** in the radius search (no writer exists
   in this plugin).
