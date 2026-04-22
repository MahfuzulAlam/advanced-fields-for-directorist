# Advanced Fields for Directorist

Advanced Fields for Directorist extends the Directorist form builder with extra custom field types for listing submission and single listing display.

Current release: **2.1.0**

## Features

- Add advanced custom fields to Directorist directory types.
- Render field values on single listing pages.
- Support both frontend listing submission and admin listing edit flow.
- Repeater field stores row values in a hidden input as JSON (Directorist handles saving).

## Available Field Types

- iFrame
- Shortcode
- YouTube Video
- Vimeo Video
- WP Editor
- Featured Checkbox
- Repeater
- Multiple Addresses

## Repeater Field

The repeater field lets users add multiple grouped entries (for example: education, experience, schedules, or team members).

Supported sub-field types:

- Text
- Textarea
- Email
- Number
- Date
- Time
- Color
- URL
- Select
- Radio
- Checkbox

How it works:

- Users can add/remove repeater items dynamically.
- JavaScript keeps all rows synced to a hidden input as JSON.
- No custom database logic is required in this plugin for repeater values.

## Requirements

- WordPress
- Directorist plugin (active)

## Installation

1. Upload this plugin to `wp-content/plugins/advanced-fields-for-directorist` (or install from your distribution flow).
2. Activate the plugin from **Plugins** in WordPress admin.
3. Ensure Directorist is active.

## Usage

1. Go to **Directorist -> Directory Types**.
2. Edit a directory type and open the form builder.
3. Add fields from the **Advanced Fields** section.
4. Configure field options (`label`, `field_key`, `description`, etc.).
5. Save and test:
   - Frontend add-listing page.
   - Admin single listing edit page.

## Basic File Structure

```text
advanced-fields-for-directorist/
├── directorist-advanced-fields.php
├── includes/
│   ├── class-advanced-fields.php
│   ├── class-hooks.php
│   ├── class-scripts.php
│   ├── class-helper.php
│   └── fields/
├── templates/
│   ├── listing-form/
│   └── single/
└── assets/
    ├── css/
    └── js/
```

## Changelog

### 2.1.0

- Updated plugin version to 2.1.0.
- Repeater JS/CSS improvements and admin listing edit compatibility updates.

### 0.1.0

- Initial release.

## License

GPLv2 or later.
