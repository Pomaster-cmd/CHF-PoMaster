# CHF PoMaster

Header & Footer Builder for Elementor Free, designed as a serious and evolutive WordPress plugin architecture.

## Repository purpose

This repository contains:

- the installable WordPress plugin
- the technical documentation
- the JSON preset foundations
- the GitHub CI bootstrap
- the long-term maintenance strategy

## Current version

- Plugin version: `0.0.0`
- Author: `PoMaster`

## Core goals

- Build **headers and footers without Elementor Pro**
- Keep the builder **fully editable in Elementor**
- Provide a **real modular codebase**
- Support **JSON presets** import/export
- Stay **portable across websites**
- Remain **maintainable for future WordPress / Elementor / PHP versions**

## What is included in this first repository scaffold

- Custom post type for Header / Footer templates
- Frontend injection engine
- Elementor integration bootstrap
- Custom Elementor widgets:
  - Site Logo
  - Nav Menu
- Template display rules
- JSON preset import/export foundation
- GitHub PHP lint workflow
- Architecture and roadmap docs

## Installation

### Repository package
This repository ZIP is intended for development and versioning.

### Installable plugin package
Use the dedicated plugin ZIP generated alongside this repository:
- `chf-pomaster-0.0.0.zip`

## Minimum stack

- WordPress 6.5+
- PHP 7.4+
- Elementor (Free) active

## Technical notes

- The plugin is modular: no monolithic PHP file
- Code comments are written in English
- The builder is site-agnostic
- JSON presets can be imported into the plugin admin page
- The project is prepared for iterative patching and future releases

## Next planned phases

1. Expand widget library
2. Add advanced display conditions
3. Add preset library manager
4. Add GitHub-assisted release workflow
5. Add stronger backward compatibility tooling

See:
- `docs/architecture.md`
- `docs/roadmap.md`
- `docs/presets-json.md`
- `docs/maintenance-strategy.md`
- `docs/repo-workflow.md`
