# Maintenance Strategy

## Long-term objectives

The plugin must remain patchable for future WordPress, PHP and Elementor versions.

## Strategy

### 1. Modular architecture
Each responsibility has its own class and file.

### 2. Defensive integration
All Elementor-dependent logic is wrapped in existence checks.

### 3. Versioned releases
The plugin starts at `0.0.0` and evolves using controlled increments.

### 4. CI baseline
GitHub Actions runs PHP linting on every push.

### 5. Backward-compatibility discipline
New features should be added behind dedicated classes and conditions, not by rewriting stable code.

### 6. JSON schema versioning
Preset payloads are versioned independently.

### 7. Progressive testability
The plugin structure is prepared for future unit / integration / smoke tests.
