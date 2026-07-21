# Directorist – Advanced Fields — User Guide

**Version 2.3.0** · An add-on for the Directorist directory plugin.

This guide is written for site owners using the WordPress admin. You do **not** need to know any code to use this plugin.

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Requirements](#2-requirements)
3. [Installation & Activation](#3-installation--activation)
4. [Getting Started](#4-getting-started)
5. [Field Types Reference](#5-field-types-reference)
   - [YouTube Video](#youtube-video)
   - [Vimeo Video](#vimeo-video)
   - [iFrame](#iframe)
   - [Shortcode](#shortcode)
   - [WP Editor](#wp-editor)
   - [Featured Checkbox](#featured-checkbox)
   - [Repeater](#repeater)
   - [Address List](#address-list)
6. [Common Use Cases / Examples](#6-common-use-cases--examples)
7. [Troubleshooting](#7-troubleshooting)
8. [FAQ](#8-faq)
9. [Open Questions](#9-open-questions)

---

## 1. Introduction

**Directorist** is a free WordPress plugin that turns your website into a directory — a place where people submit and browse listings (businesses, properties, jobs, events, and so on). Every listing is created through a **listing form**, and Directorist lets you decide which fields that form contains using a drag‑and‑drop tool called the **form builder**.

**Directorist – Advanced Fields** is an *add-on* (an extra plugin that plugs into Directorist). It gives you **eight new field types** to add to your listing forms — things Directorist doesn't offer on its own, like embedding a YouTube video, adding a rich‑text editor, listing multiple addresses on a map, or repeating a group of fields as many times as needed.

Once you add one of these fields to a form, the people who submit listings can fill it in, and the value is shown nicely on the published listing page.

**The eight advanced field types are:**

| Field | In one line |
| --- | --- |
| **YouTube Video** | Show a YouTube video on the listing. |
| **Vimeo Video** | Show a Vimeo video on the listing. |
| **iFrame** | Embed content from another website (maps, booking widgets, etc.). |
| **Shortcode** | Run a WordPress shortcode inside a listing. |
| **WP Editor** | A full visual text editor with formatting. |
| **Featured Checkbox** | A tick‑box list of features/amenities. |
| **Repeater** | A repeatable group of fields (add as many rows as needed). |
| **Address List** | One or more addresses with optional labels and a map. |

> 📸 **[SCREENSHOT: the "Advanced Fields" group inside the Directorist form builder, showing all eight field icons]**

---

## 2. Requirements

Before using this plugin you need:

- **WordPress** — a working WordPress website (a recent version is recommended).
- **Directorist** — the free Directorist plugin must be **installed and active first**. Advanced Fields will not do anything on its own; it only adds features *inside* Directorist.
  - Your Directorist version must be recent enough to include **Directory Types** and the **form builder** (the drag‑and‑drop field editor). This plugin has been used with current Directorist 8.x releases.
- **PHP 7.0 or higher** (most modern hosts already meet this).
- **For maps and address search (Address List field only):** a **Google Maps API key** entered in Directorist's settings if you want Google address auto‑complete and Google maps. If you prefer, Directorist can use **OpenStreetMap** instead, which needs no key.

> **Tip:** If Directorist is not active, this plugin stays completely silent — no fields appear. Always activate Directorist first.

---

## 3. Installation & Activation

You install this like any other WordPress plugin.

**Option A — Upload the ZIP from your dashboard**

1. In WordPress admin, go to **Plugins → Add New Plugin**.
2. Click **Upload Plugin** at the top.
3. Click **Choose File** and select the `directorist-advanced-fields.zip` file.
4. Click **Install Now**.
5. When it finishes, click **Activate Plugin**.

**Option B — Install manually by copying files**

1. Unzip the plugin.
2. Copy the `directorist-advanced-fields` folder into `wp-content/plugins/` on your server.
3. In WordPress admin, go to **Plugins**.
4. Find **Directorist – Advanced Fields** and click **Activate**.

> 📸 **[SCREENSHOT: the Plugins screen with "Directorist – Advanced Fields" activated]**

**After activating:** make sure **Directorist** itself is also active. If it isn't, activate it, then continue to *Getting Started*.

---

## 4. Getting Started

Here is the full journey — from opening the form builder to seeing your new field live on a listing. We'll add a **YouTube Video** field as a simple example; every other field works the same way.

**Step 1 — Open your Directory Type**

1. In WordPress admin, go to **Directorist → Directory Types**.
2. Click the directory type you want to edit (for a simple site this is usually called **General**), or create a new one.

> A **Directory Type** is a template for a kind of listing — for example "Restaurants" or "Properties." Each type has its own form and its own layout.

**Step 2 — Open the form builder and find the Advanced Fields**

1. Inside the directory type, open the **Form Builder** (also called **Fields** or **Submission Form** depending on your version).
2. Look at the list of field groups on the side. You'll see a group called **Advanced Fields**.
3. That group contains all eight field types from this plugin.

> 📸 **[SCREENSHOT: the form builder with the "Advanced Fields" group expanded]**

**Step 3 — Add a field to the form**

1. Click (or drag) the **YouTube Video** field to drop it onto your form.
2. A settings panel opens for that field.
3. Change the **Label** if you like (for example, "Watch our video"), and adjust any other settings.

**Step 4 — Show the field on the listing page**

For the value to appear on the finished listing, add the matching item to the **single listing layout**:

1. Still inside the directory type, open the **Single Listing** layout builder (the tab that controls how a published listing looks).
2. Find the same field (for example **YouTube Video**) in the available widgets and place it where you want it to appear.
3. Adjust its display settings (icon, whether to show the label, etc.).

**Step 5 — Save**

1. Click **Save** on the directory type.
2. Now create or edit a listing (**Directorist → All Listings**, or the front‑end "Add Listing" form). Your new field appears — fill it in and save.
3. Open the published listing to see the result.

> 📸 **[SCREENSHOT: a finished listing page showing an embedded video from the YouTube Video field]**

> **Remember two places:** the **form builder** controls what people *fill in*, and the **single listing** layout controls what visitors *see*. Add your field in both.

---

## 5. Field Types Reference

This is the heart of the guide. Each field type is described the same way so they're easy to compare.

A few settings appear on **almost every** field, so they're explained once here and referenced below:

- **Label** — the field's title, shown above the field on the form (e.g. "Menu," "Video"). 
- **Show Label** — turn the label on the *form* on or off. *Default: On.*
- **Placeholder** — light grey hint text inside an empty field (where available).
- **Description** — a short help line under the field (where available).
- **Required** — if On, the listing can't be submitted until this field is filled. *Default: Off.*
- **Only For Admin Use** — if On, only administrators see the field; regular users submitting listings don't. *Default: Off.*
- **Class** — an advanced, optional box for adding a CSS class name (for custom styling). You can safely ignore it; each field comes with a sensible default.
- **Conditional Logic** — show or hide this field depending on what was chosen in another field (a Directorist core feature). *Default: Off.*

On the **single listing** side, most fields share:

- **Icon** — the small icon shown next to the field's label on the published listing.
- **Display Label** — show or hide the label on the *listing page*. *Default: On.*

---

### YouTube Video

- **What it is:** A field where you paste a YouTube link. On the listing it turns into a playable, embedded video.
- **When to use it:** A restaurant's promo clip, a property walk‑through tour, an event highlights reel — any time a YouTube video belongs on the listing.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "Youtube Video."*
  - **Show Label** — show the label on the form. *Default: On.*
  - **Placeholder** — hint text in the box. *Default: "Only YouTube URLs."*
  - **Required** — force the field to be filled. *Default: Off.*
  - **Only For Admin Use** — admins only. *Default: Off.*
  - **Class** — optional CSS class. *Default: `directorist-field-youtube`.*
  - **Conditional Logic** — show/hide based on other fields. *Default: Off.*
- **Settings / Options (single listing):**
  - **Icon** — icon by the label. *Default: a YouTube icon.*
  - **Display Label** — show the label on the listing. *Default: On.*
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **YouTube Video**.
  2. Set the label and (optionally) mark it required.
  3. Add the **YouTube Video** widget to the single‑listing layout.
  4. Save.
- **Example:** The listing owner pastes `https://www.youtube.com/watch?v=abc123`. On the listing, visitors see a video player they can press play on — no link‑clicking needed.
- **Notes / Tips:** Only YouTube links work here (normal watch links, short `youtu.be` links, and Shorts are all fine). If the link isn't a valid YouTube URL, nothing is shown — so the listing never displays a broken box.

> 📸 **[SCREENSHOT: the YouTube Video field settings panel in the form builder]**
> 📸 **[SCREENSHOT: an embedded YouTube video on a published listing]**

---

### Vimeo Video

- **What it is:** The same idea as the YouTube field, but for **Vimeo** links. Paste a Vimeo URL and it becomes an embedded video.
- **When to use it:** When your videos live on Vimeo instead of (or as well as) YouTube.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "Vimeo Video."*
  - **Show Label** — *Default: On.*
  - **Placeholder** — *Default: "Only Vimeo URLs."*
  - **Required** — *Default: Off.*
  - **Only For Admin Use** — *Default: Off.*
  - **Class** — *Default: `directorist-field-vimeo`.*
  - **Conditional Logic** — *Default: Off.*
- **Settings / Options (single listing):**
  - **Icon** — *Default: a Vimeo icon.*
  - **Display Label** — *Default: On.*
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **Vimeo Video**.
  2. Configure the label and options.
  3. Add the **Vimeo Video** widget to the single‑listing layout.
  4. Save.
- **Example:** The owner pastes `https://vimeo.com/123456789`. Visitors get an embedded Vimeo player on the listing.
- **Notes / Tips:** Only Vimeo links work. An invalid link simply shows nothing.

> 📸 **[SCREENSHOT: the Vimeo Video field settings panel in the form builder]**
> 📸 **[SCREENSHOT: an embedded Vimeo video on a published listing]**

---

### iFrame

- **What it is:** An "iFrame" is a window that displays another web page inside your page. This field lets an owner paste embed code (an `<iframe>` snippet) — for example a Google Map, a booking calendar, or a 360° tour.
- **When to use it:** Embedding a third‑party widget that gives you `<iframe>` code to copy, such as an OpenTable reservation widget, a Google Maps embed, or a virtual tour.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "iFrame."*
  - **Show Label** — *Default: On.*
  - **Placeholder** — hint text. *Default: empty.*
  - **Description** — help line under the field. *Default: empty.*
  - **Required** — *Default: Off.*
  - **Only For Admin Use** — *Default: Off.* (Often worth turning **On** — see Tips.)
  - **Class** — *Default: `directorist-field-iframe`.*
  - **Conditional Logic** — *Default: Off.*
- **Settings / Options (single listing):**
  - **Icon** — *Default: a window icon.*
  - **Display Label** — *Default: On.*
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **iFrame**.
  2. Enter a label and (recommended) consider turning on **Only For Admin Use**.
  3. Add the **iFrame** widget to the single‑listing layout.
  4. Save.
- **Example:** An owner pastes a Google Map embed code. Visitors see a live, interactive map inside the listing.
- **Notes / Tips:** For safety, only the `<iframe>` tag and a small set of standard attributes are allowed; other HTML is stripped out. Because embed code can come from anywhere, many site owners set this field to **Only For Admin Use** so that only staff can add embeds.

> 📸 **[SCREENSHOT: the iFrame field settings panel in the form builder]**
> 📸 **[SCREENSHOT: an embedded map or widget on a published listing]**

---

### Shortcode

- **What it is:** A field where you type a WordPress **shortcode** — a short code in square brackets like `[gallery]` that WordPress turns into real content. On the listing, the shortcode runs and its output is shown.
- **When to use it:** Displaying a WordPress gallery, an audio player, a video, or a playlist inside a listing.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "Shortcode."*
  - **Show Label** — *Default: On.*
  - **Placeholder** — *Default: empty.*
  - **Description** — *Default: empty.*
  - **Required** — *Default: Off.*
  - **Only For Admin Use** — *Default: Off.* (Often worth turning **On** — see Tips.)
  - **Class** — *Default: `directorist-field-shortcode`.*
  - **Conditional Logic** — *Default: Off.*
- **Settings / Options (single listing):**
  - **Icon** — *Default: a code icon.*
  - **Display Label** — *Default: On.*
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **Shortcode**.
  2. Set the label and options.
  3. Add the **Shortcode** widget to the single‑listing layout.
  4. Save.
- **Example:** An owner types `[gallery ids="12,15,18"]`. Visitors see a WordPress image gallery on the listing.
- **Notes / Tips:** For safety, only these built‑in WordPress shortcodes are allowed: **audio, caption, gallery, playlist, video**. Anything else is ignored. Like the iFrame field, many owners set this to **Only For Admin Use**.

> 📸 **[SCREENSHOT: the Shortcode field settings panel in the form builder]**
> 📸 **[SCREENSHOT: a gallery produced by a shortcode on a published listing]**

---

### WP Editor

- **What it is:** A full visual text editor — the same familiar box you use to write a WordPress post, with buttons for bold, italic, lists, links, and more. It lets owners write nicely formatted text instead of one plain paragraph.
- **When to use it:** Longer, formatted content such as an "About us" section, a detailed description, terms and conditions, or a formatted menu.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "WP Editor."*
  - **Show Label** — *Default: On.*
  - **Required** — *Default: Off.*
  - **Only For Admin Use** — *Default: Off.*
  - **Class** — *Default: `directorist-field-wp-editor`.*
  - **Conditional Logic** — *Default: Off.*
  - *(This field has no placeholder or description option.)*
- **Settings / Options (single listing):**
  - **Icon** — *Default: a text/align icon.*
  - **Display Label** — *Default: On.*
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **WP Editor**.
  2. Set the label.
  3. Add the **WP Editor** widget to the single‑listing layout.
  4. Save.
- **Example:** An owner writes a formatted description with a bulleted list of services and a bold heading. It appears on the listing exactly as formatted.
- **Notes / Tips:** For safety, the same set of HTML allowed in normal WordPress posts is allowed here; anything unusual is cleaned out. The editing toolbar (media buttons, etc.) matches Directorist's standard editor settings.

> 📸 **[SCREENSHOT: the WP Editor field as it appears on the Add Listing form]**
> 📸 **[SCREENSHOT: formatted rich text on a published listing]**

---

### Featured Checkbox

- **What it is:** A list of tick‑boxes you define (for example amenities or features). Whoever submits the listing ticks the ones that apply, and the chosen items are shown as a neat list with an icon next to each.
- **When to use it:** Amenities (Wi‑Fi, Parking, AC), included features, or any "which of these apply?" list.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "Featured Checkbox."*
  - **Show Label** — *Default: On.*
  - **Options** — the list of choices. Click **Add Option** for each one and fill in:
    - **Option Value** — the stored value (keep it simple, e.g. `wifi`).
    - **Option Label** — the wording the visitor sees (e.g. "Free Wi‑Fi").
  - **Placeholder** — *Default: empty.*
  - **Description** — *Default: empty.*
  - **Required** — *Default: Off.*
  - **Only For Admin Use** — *Default: Off.*
  - **Class** — *Default: `directorist-field-featured-checkbox`.*
  - **Conditional Logic** — *Default: Off.*
- **Settings / Options (single listing):**
  - **Icon** — icon next to the field label. *Default: a check‑square icon.*
  - **Item Icon** — the icon shown before **each ticked item** in the list. *Default: `las la-check-circle` (a circled check). If you leave it empty, the circled check is used.*
  - **Display Label** — *Default: On.*
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **Featured Checkbox**.
  2. Add your options (Value + Label) one by one.
  3. Add the **Featured Checkbox** widget to the single‑listing layout, and optionally pick an **Item Icon**.
  4. Save.
- **Example:** You create options "Free Wi‑Fi," "Parking," "Air Conditioning," "Pet Friendly." A listing owner ticks the first three. The listing shows a tidy list — each row a check icon followed by the feature name.
- **Notes / Tips:** Want a different icon in front of every item? Set **Item Icon** in the single‑listing settings (for example a star). It applies to all items in that field.

> 📸 **[SCREENSHOT: the Featured Checkbox options being set up in the form builder]**
> 📸 **[SCREENSHOT: the ticked features displayed as a list on a published listing]**

---

### Repeater

- **What it is:** A **repeatable group of fields**. You design a small set of fields once (for example "Name" + "Price"), and the listing owner can add that group over and over — one row per item.
- **When to use it:** Any list of similar items: menu dishes with prices, education/work history, team members, opening times, package tiers, FAQs — anything where the same few fields repeat.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "Repeater."*
  - **Show Label** — *Default: On.*
  - **Fields** — the sub‑fields that make up **one row**. Click **Add Field** for each, and set:
    - **Field Type** — the kind of input. Choose from: **Text, Textarea, Email, Date, Time, Color, Number, URL, Radio, Select, Checkbox.** *Default: Text.*
    - **field_key** — a short unique name for this sub‑field (e.g. `dish_name`). *Must be unique within the row.*
    - **Field Label** — the wording shown above the sub‑field (e.g. "Dish name").
    - **Field Placeholder** — hint text inside the sub‑field.
    - **Field Description** — a small help line under the sub‑field.
    - **Field Class** — optional CSS class (advanced).
    - **Options** — only appears when the Field Type is **Select, Radio, or Checkbox.** Click **Add Option** and set an **Option Value** and **Option Label** for each choice.
  - **Description** — help line under the whole field. *Default: empty.*
  - **Required** — *Default: Off.*
  - **Only For Admin Use** — *Default: Off.*
  - **Class** — *Default: `directorist-field-repeater`.*
  - **Conditional Logic** — *Default: Off.*
- **Settings / Options (single listing):**
  - **Icon** — *Default: a list icon.*
  - **Display Label** — *Default: On.*
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **Repeater**.
  2. Under **Fields**, add each sub‑field you want in a row (pick a Field Type, give it a key and label).
  3. Add the **Repeater** widget to the single‑listing layout.
  4. Save.
- **Example — an "Education" repeater:** You add two sub‑fields, "Name" (Text) and "CGPA" (Number). On the form, the owner sees a card titled with an editable heading. They fill in "Ak School / 4.63," press **+** to add another row, and fill "DRMC / 4.7," and so on. The listing then shows one tidy card per entry.
- **Notes / Tips:**
  - **Editable row titles:** each row on the form has a heading you can type into (for example "High School," "College"). Whatever the owner types becomes the card title on the listing page. Leave it blank and it falls back to "Label #1," "Label #2," and so on.
  - **Number fields accept decimals** (like `4.63`) — useful for prices, ratings, and grades.
  - Use the **+** and **–** buttons to add and remove rows. There's always at least one row.

> 📸 **[SCREENSHOT: setting up the Repeater's sub-fields in the form builder]**
> 📸 **[SCREENSHOT: a Repeater on the Add Listing form, with two rows filled in]**
> 📸 **[SCREENSHOT: the Repeater displayed as cards on a published listing]**

---

### Address List

- **What it is:** A field for **one or more addresses**. The owner searches for each place, and the plugin stores its exact location. On the listing, the addresses appear as tidy location cards — optionally with a map.
- **When to use it:** A business with several branches, a chain of stores, service areas, or any listing that has more than one location.
- **Settings / Options (form builder):**
  - **Label** — field title. *Default: "Address List."*
  - **Show Label** — *Default: On.*
  - **Placeholder** — hint text in the search box. *Default: "Select a place from google."*
  - **Limit** — the maximum number of addresses allowed. *Default: empty. `0` or empty means unlimited.*
  - **Allow Label** — if On, each address also gets a small **Label** box (e.g. "Main Branch," "Warehouse"). *Default: Off.*
  - **Allow Map** — if On, a map is available for this field. *Default: Off.*
  - **Admin Only** — if On, only administrators can edit this field. *Default: Off.*
- **Settings / Options (single listing):**
  - **Icon** — *Default: a map icon.*
  - **Allow Field Label** — show the field's label on the listing. *Default: Off.*
  - **Display** — what to show for each address: **Label**, **Address**, or **Both.**
- **How to add it:**
  1. Form builder → **Advanced Fields** → click **Address List**.
  2. Decide whether to allow labels, a map, and a limit.
  3. Add the **Address List** widget to the single‑listing layout, and choose the **Display** option (Label / Address / Both).
  4. Save.
- **Example:** A restaurant chain adds three branches. With **Allow Label** on, the owner labels them "Downtown," "Airport," and "Mall." With **Allow Map** on, visitors see all three as cards plus pins on a map.
- **Notes / Tips:**
  - The address **search box** uses Google's place look‑up, so a **Google Maps API key** must be set in Directorist's settings for auto‑complete to work.
  - The **map** on the listing follows Directorist's map setting: it uses **Google Maps** (needs the API key) or **OpenStreetMap** (needs no key), depending on what you've chosen in Directorist.
  - Set **Limit** to `1` if you only ever want a single address.

> 📸 **[SCREENSHOT: the Address List field settings panel in the form builder]**
> 📸 **[SCREENSHOT: the multi-address input on the Add Listing form, with the "Add another location" button]**
> 📸 **[SCREENSHOT: address cards with a map on a published listing]**

---

## 6. Common Use Cases / Examples

Here are a few realistic setups that combine several advanced fields.

### Restaurant listing

- **WP Editor** → a formatted "About the restaurant" section.
- **Featured Checkbox** → amenities: Outdoor Seating, Wi‑Fi, Parking, Vegan Options, Card Payment.
- **Repeater** → a menu: sub‑fields "Dish" (Text) and "Price" (Number, which accepts decimals like `12.50`).
- **YouTube Video** → a short promo clip.
- **Address List** → the restaurant's branches, with **Allow Map** on.

> 📸 **[SCREENSHOT: a finished restaurant listing showing several advanced fields together]**

### Real‑estate listing

- **WP Editor** → the full property description.
- **Featured Checkbox** → features: Garage, Garden, Air Conditioning, Furnished, Pet Friendly.
- **iFrame** → an embedded 360° virtual tour.
- **Repeater** → room details: sub‑fields "Room" (Text), "Size" (Text), "Notes" (Textarea).
- **Address List** → the property's location (set **Limit** to `1`).

### Professional / résumé‑style listing

- **Repeater** ("Experience") → sub‑fields "Company," "Role," "Years."
- **Repeater** ("Education") → sub‑fields "Name" (Text) and "CGPA" (Number), using the editable row titles for "High School," "College," "University."
- **Featured Checkbox** → skills.
- **Vimeo Video** → an introduction video.

---

## 7. Troubleshooting

**The "Advanced Fields" group doesn't appear in the form builder.**
- Make sure both **Directorist** and **Directorist – Advanced Fields** are active (Plugins screen).
- Make sure Directorist is a version that has **Directory Types** and the form builder.

**I added a field but nothing shows on the published listing.**
- The form builder controls the *input*; you also need to add the same field to the **Single Listing** layout so it *displays*. See [Getting Started](#4-getting-started), Step 4.
- Some fields hide themselves when empty (for example a video with no link). Check that the listing actually has a value.
- Re‑save the directory type after making changes.

**My video/embedded content isn't showing.**
- YouTube and Vimeo fields only accept links from those sites. Double‑check the URL.
- For the **iFrame** field, only the `<iframe>` tag and standard attributes survive; other code is removed for safety.

**My shortcode isn't running.**
- Only these WordPress shortcodes are allowed: **audio, caption, gallery, playlist, video.** Others won't display.

**The Address List search box doesn't suggest places, or the map is blank.**
- Address auto‑complete needs a **Google Maps API key** set in Directorist's settings.
- For the listing map, check Directorist's map setting (Google needs the key; OpenStreetMap does not).

**Decimal values in a Repeater number field get rejected.**
- They shouldn't — Repeater number sub‑fields accept decimals (like `4.63`). If a decimal is refused, clear your browser cache and reload the form so the latest scripts load.

**I changed a setting but the listing looks the same.**
- Re‑save the directory type, then refresh the listing page. If your site uses caching, clear the cache.

---

## 8. FAQ

**Do I need to know how to code?**
No. Everything is done through the Directorist form builder and the WordPress admin.

**Does this replace Directorist?**
No. It's an add‑on. Directorist must be installed and active; this plugin only adds extra field types inside it.

**Can I add the same field type more than once?**
Yes for most fields. The **Address List** field uses a fixed internal name, so it's designed to be used once per form.

**Can I hide a field's label?**
Yes. Turn off **Show Label** to hide it on the *form*, and turn off **Display Label** to hide it on the *listing page*. Each can be set independently.

**Can I limit a field to staff only?**
Yes — turn on **Only For Admin Use** (or **Admin Only** on the Address List). Regular users submitting listings won't see it.

**Can I show or hide a field based on another field's answer?**
Yes — use **Conditional Logic** in the field's settings. This is a built‑in Directorist feature exposed on every advanced field.

**Which fields are best set to "Admin only"?**
The **iFrame** and **Shortcode** fields accept embed/shortcode content. Many site owners restrict these to administrators so that only trusted staff can add them.

**Will my data survive an update?**
Yes. Field values are stored on the listing like any other Directorist data. (Uninstalling the plugin doesn't erase existing values, but the fields stop displaying until it's reactivated.)

---

## 9. Open Questions

A few points couldn't be pinned down from the plugin alone and may depend on your Directorist version or setup:

1. **Exact minimum Directorist version.** The plugin does not declare one. It relies on Directorist's multi‑directory form builder, so a current 8.x release is safe; the precise oldest supported version is unconfirmed.
2. **Exact minimum WordPress/PHP versions.** Not declared in the plugin. PHP 7.0+ is a safe assumption.
3. **A "Feature List" field exists in the plugin's code but is currently switched off**, so it does **not** appear in the form builder and is intentionally left out of this guide. If a future update turns it on, this document should be expanded to cover it.
4. **Precise wording of the builder tabs** ("Form Builder" / "Fields" / "Single Listing" / "Single Page") can vary slightly between Directorist versions; the steps here describe the general flow.
