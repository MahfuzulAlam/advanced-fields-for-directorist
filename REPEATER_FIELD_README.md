# Repeater Field for Directorist

## Overview
The Repeater Field allows users to add multiple sets of related data in a structured format. It's perfect for collecting information like education history, work experience, team members, or any other repetitive data.

## Features
- ✅ Add/Remove items dynamically
- ✅ Support for multiple field types (text, textarea, email, number, date, time, color, url, select, radio, checkbox)
- ✅ Responsive design
- ✅ Modern UI with smooth animations
- ✅ Proper data validation and sanitization
- ✅ Single listing display template

## Field Types Supported
1. **Text** - Single line text input
2. **Textarea** - Multi-line text input
3. **Email** - Email validation
4. **Number** - Numeric input
5. **Date** - Date picker
6. **Time** - Time picker
7. **Color** - Color picker
8. **URL** - URL validation
9. **Select** - Dropdown selection
10. **Radio** - Radio button group
11. **Checkbox** - Checkbox group

## Usage

### In Listing Form
The repeater field will automatically appear in the listing form when configured. Users can:
- Click the "+" button to add new items
- Click the "-" button to remove items
- Fill in the fields for each item
- The minimum number of items is 1

### Data Structure
The field stores data in the following format:
```php
[
    0 => [
        'institute' => 'AK High School & College',
        'grade' => '4.5',
        'year' => '2004',
        'repeater_field_key' => 'ssc'
    ],
    1 => [
        'institute' => 'University of Dhaka',
        'grade' => '3.8',
        'year' => '2008',
        'repeater_field_key' => 'bba'
    ]
]
```

### Single Listing Display
The repeater field data is automatically displayed on single listing pages with:
- Clean, organized layout
- Proper field labels
- Formatted values based on field type
- Responsive design

## Configuration
To configure a repeater field:

1. Go to **Directorist > Directory Types > [Your Directory Type] > Form Fields**
2. Add a new **Repeater** field from the Advanced Fields section
3. Configure the field settings:
   - **Label**: Main field label (e.g., "Education")
   - **Field Key**: Unique identifier
   - **Description**: Help text for users
   - **Required**: Whether the field is mandatory

4. Add sub-fields in the **Fields** section:
   - **Field Type**: Choose from available types
   - **Field Key**: Unique identifier for the sub-field
   - **Field Label**: Display label
   - **Field Placeholder**: Placeholder text
   - **Field Description**: Help text
   - **Field Class**: Additional CSS classes
   - **Field Options**: For select/radio/checkbox fields

## Styling
The repeater field includes comprehensive CSS styling:
- Modern card-based design
- Smooth hover effects
- Responsive layout
- Loading states
- Animation effects

## JavaScript API
The repeater field exposes a JavaScript API:

```javascript
// Initialize all repeater fields
DirectoristRepeater.init();

// Add item to specific field
DirectoristRepeater.addItem('field_key');

// Remove item from specific field
DirectoristRepeater.removeItem('field_key', index);
```

## File Structure
```
assets/
├── css/
│   └── repeater.css          # Main styles
├── js/
│   └── repeater.js           # JavaScript functionality
templates/
├── listing-form/
│   └── repeater.php          # Form template
└── single/
    └── repeater.php          # Display template
includes/
├── fields/
│   └── repeater.php          # Field configuration
└── class-helper.php          # Helper methods
```

## Browser Support
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Dependencies
- jQuery 3.0+
- Directorist Plugin
- WordPress 5.0+

## Troubleshooting

### Field not displaying
- Check if the field is properly configured in Directory Types
- Verify the field key is unique
- Ensure the field is assigned to the correct category

### JavaScript not working
- Check browser console for errors
- Verify jQuery is loaded
- Ensure the repeater.js file is enqueued

### Styling issues
- Check if repeater.css is loaded
- Verify no CSS conflicts with theme
- Check responsive breakpoints

## Support
For issues or feature requests, please contact the plugin developer or create an issue in the repository.
