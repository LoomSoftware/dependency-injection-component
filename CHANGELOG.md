# Luma | Dependency Injection Component Change Log

## [1.3.0] - 2024-05-05
### Added
- Add support for configuration parameters as service arguments

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Updated dependencies

---

## [1.2.2] - 2024-03-02
- Minor housekeeping; `package.json` cleanup, `composer.json` cleanup
- Update build pipelines

## [1.2.1] - 2024-02-23
- Update build pipelines

## [1.2.0] - 2024-02-22
- Added CHANGELOG
- Added automated build pipeline
- `DependencyManager` now throws a RuntimeException if `loadDependenciesFromFile` is called with an invalid filetype (such as JSON).
- Increased test coverage to 100%