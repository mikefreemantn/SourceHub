# SourceHub Post Validation System 🛡️

## Overview
The SourceHub validation system ensures posts meet all requirements before they can be published or updated. This prevents incomplete posts from being syndicated to spoke sites.

## Validation Requirements

### 1. **SourceHub Spoke Selection** ✅
- **Requirement:** At least one spoke site must be selected for syndication
- **Check:** `_sourcehub_selected_spokes` meta field must contain array with 1+ items
- **Warning Message:** "select at least one SourceHub Spoke site to syndicate to"

### 2. **Featured Image** 🖼️
- **Requirement:** Post must have a featured image set
- **Check:** `has_post_thumbnail($post_id)` must return true
- **Warning Message:** "set a featured image for the post"

### 3. **Category Selection** 📂
- **Requirement:** Post must have at least one category assigned
- **Check:** `wp_get_post_categories($post_id)` must return non-empty array
- **Warning Message:** "select at least one category for the post"

### 4. **Post Template** 📄
- **Requirement:** A post template must be selected
- **Check:** WordPress page template or Newspaper theme template fields
- **Warning Message:** "select a post template to use"

## How It Works

### Server-Side Validation (PHP) - Warning Only
- **Hook:** `transition_post_status` (safe, non-blocking)
- **Trigger:** When post status changes to 'publish'
- **Action:** Posts publish normally, validation issues shown as warnings
- **Notice:** Admin warning notice shows what should be improved

### Client-Side Validation (JavaScript) - Status Only
- **Real-time validation:** Updates validation status as user makes changes
- **No blocking:** Publishing proceeds normally regardless of validation
- **Visual feedback:** Shows validation status in publish box
- **User experience:** Helpful warnings guide optimization

## User Experience Flow

### 1. **Real-Time Status Indicator**
```
┌─────────────────────────────┐
│ SourceHub Status: Ready to  │
│ publish ✅                  │
└─────────────────────────────┘
```

### 2. **Validation Errors**
```
┌─────────────────────────────┐
│ SourceHub Status: You need  │
│ to select a spoke site,     │
│ set featured image ❌       │
└─────────────────────────────┘
```

### 3. **Publish Attempt with Errors**
```
┌─────────────────────────────────────────┐
│ ⚠️ SourceHub Validation Failed          │
│                                         │
│ You need to:                           │
│ • select at least one spoke site       │
│ • set a featured image                 │
│                                         │
│ Please fix these issues before         │
│ publishing.                            │
└─────────────────────────────────────────┘
```

## Technical Implementation

### Files Created
- `includes/class-sourcehub-validation.php` - Main validation logic
- `assets/js/validation.js` - Client-side validation and UI

### Key Functions

#### `SourceHub_Validation::get_validation_errors($post_id)`
Returns array of validation error messages for a post.

#### `SourceHub_Validation::is_post_valid($post_id)`
Returns boolean indicating if post passes all validation checks.

#### `SourceHub_Validation::validate_post_before_save()`
Prevents publication if validation fails.

### AJAX Endpoint
- **Action:** `sourcehub_validate_post`
- **Purpose:** Real-time validation checks
- **Returns:** `{valid: boolean, errors: array}`

## Testing Scenarios

### ✅ **Valid Post**
- Has spoke site selected
- Has featured image
- Has template selected
- **Result:** Publishes successfully

### ❌ **Invalid Post - No Spoke**
- No spoke sites selected
- Has featured image
- Has template selected
- **Result:** Blocked with "select at least one SourceHub Spoke site"

### ❌ **Invalid Post - No Featured Image**
- Has spoke site selected
- No featured image
- Has template selected
- **Result:** Blocked with "set a featured image for the post"

### ❌ **Invalid Post - No Template**
- Has spoke site selected
- Has featured image
- No template selected
- **Result:** Blocked with "select a post template to use"

### ❌ **Invalid Post - Multiple Issues**
- No spoke sites selected
- No featured image
- No template selected
- **Result:** Blocked with "select at least one SourceHub Spoke site, set a featured image for the post, select a post template to use"

## Benefits

### 🛡️ **Data Integrity**
- Ensures all syndicated posts are complete
- Prevents broken or incomplete content on spoke sites

### 👥 **User Experience**
- Clear, actionable error messages
- Real-time feedback prevents frustration
- Guides users to complete required fields

### 🔧 **Developer Experience**
- Extensible validation system
- Easy to add new validation rules
- Comprehensive error handling

## Future Enhancements

### Possible Additional Validations
- **Content length:** Minimum word count
- **SEO requirements:** Meta description, focus keyword
- **Category selection:** Required categories
- **Custom field validation:** Site-specific requirements
- **Image optimization:** Alt text, file size limits

### Advanced Features
- **Validation profiles:** Different rules for different post types
- **Conditional validation:** Rules based on selected spokes
- **Batch validation:** Check multiple posts at once
- **Validation reports:** Admin dashboard for validation status
