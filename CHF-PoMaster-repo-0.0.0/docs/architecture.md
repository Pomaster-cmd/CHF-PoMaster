# Architecture

## Philosophy

The plugin is intentionally split into focused classes to avoid a monolithic codebase.

## Main layers

### Bootstrap
- `chf-pomaster.php`
- Loads constants
- Loads the main plugin class
- Boots the plugin on `plugins_loaded`

### Core
- `Post_Type`
- `Template_Resolver`
- `Frontend`
- `Elementor_Integration`
- `Assets`

### Admin
- `Admin`
- `Meta_Boxes`
- `Preset_Manager`

### Elementor Widgets
- `Site_Logo_Widget`
- `Nav_Menu_Widget`

## Rendering flow

1. A template is created in the custom post type
2. The template is edited with Elementor
3. Location and display rules are saved
4. Frontend resolver checks the current request
5. Matching header renders at `wp_body_open`
6. Matching footer renders at `wp_footer`

## Compatibility strategy

- WordPress hooks only where possible
- No Elementor Pro dependency
- Defensive checks before calling Elementor APIs
- Dedicated asset registration
- Graceful degradation when Elementor is missing
