# Repository Workflow

## Recommended flow

1. Develop locally from this repository
2. Run syntax checks
3. Package the installable plugin ZIP
4. Commit docs + code together
5. Push to GitHub
6. Tag releases for stable milestones

## Suggested branch model

- `main`: stable branch
- `develop`: integration branch
- `feature/*`: isolated feature work
- `hotfix/*`: urgent corrections

## Release packaging

The installable plugin should always be generated from the `plugin/chf-pomaster/` directory.

## GitHub limitation note

This repository is prepared locally and can be pushed to GitHub, but remote push still requires your authenticated Git environment.
