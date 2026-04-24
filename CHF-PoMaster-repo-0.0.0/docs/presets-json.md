# JSON Presets

## Goal

Allow a prebuilt visual preset to be imported into the plugin, similarly to a structured JSON import workflow.

## Preset structure

```json
{
  "schema_version": "1.0.0",
  "title": "Preset Name",
  "template_type": "header",
  "active": true,
  "conditions": ["entire_site"],
  "elementor_data": []
}
```

## Supported fields

- `schema_version`: JSON schema version
- `title`: Template title
- `template_type`: `header` or `footer`
- `active`: boolean
- `conditions`: array of display conditions
- `elementor_data`: raw Elementor document data

## Notes

- The import page validates the minimum required structure
- Elementor data is stored in `_elementor_data`
- This foundation is intentionally extensible for future preset packs
