# Directorist - Advanced Fields

Directorist - Advanced Fields extends Directorist with advanced custom fields including repeater, address list, media, shortcode, iframe, and editor fields for directory type forms, listing submission, admin editing, and single listing display.

- Version: `2.2.0`
- Plugin URI: https://wpxplore.com/tools/directorist-advanced-fields/
- Author: `wpXplore`
- Author URI: https://wpxplore.com/

## How to Download from GitHub

1. Open the repository on GitHub.
2. Click `Code`.
3. Click `Download ZIP`.
4. Extract the downloaded archive.
5. Keep the plugin folder as `advanced-fields-for-directorist`.

You can also clone the repository:

```bash
git clone <repository-url>
```

## How to Install on WordPress

### Upload ZIP from WordPress Admin

1. Go to `Plugins > Add New Plugin > Upload Plugin`.
2. Upload the plugin ZIP file.
3. Click `Install Now`.
4. Activate `Directorist - Advanced Fields`.

### Install Manually

1. Copy the plugin folder to `wp-content/plugins/advanced-fields-for-directorist`.
2. Open WordPress admin.
3. Go to `Plugins`.
4. Activate `Directorist - Advanced Fields`.

## Features

- Adds advanced field types to Directorist directory type form builder.
- Supports frontend listing submission and admin listing editing.
- Outputs field values on the single listing page.
- Includes repeater field support with JSON-based row storage.
- Includes Address List field support with multiple locations and map display.
- Supports both Google Maps and OpenStreetMap for address rendering.

## Fields

- iFrame
- Shortcode
- YouTube Video
- Vimeo Video
- WP Editor
- Featured Checkbox
- Repeater
- Address List

## How to Use

1. Make sure `Directorist` is installed and active.
2. Go to `Directorist > Directory Types`.
3. Edit an existing directory type or create a new one.
4. Open the Directorist form builder.
5. Add the required field from the `Advanced Fields` section.
6. Configure the field settings.
7. Save the directory type.
8. Create or edit a listing and fill in the field values.
9. Check the values on the single listing page.

## Address List

- Add multiple addresses for one listing.
- Store an optional label for each address.
- Show the addresses in a styled location card layout.
- Show the locations on Google Maps or OpenStreetMap.

## Repeater

- Add repeatable grouped data rows.
- Store row data as JSON for submission and saving.
- Useful for schedules, education, experience, team members, and similar structured content.

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

## Requirements

- WordPress
- Directorist

## License

GPLv2 or later.
