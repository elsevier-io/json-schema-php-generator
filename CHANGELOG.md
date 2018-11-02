# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [0.11.0] - 2018-11-02
### Added
- Add support for minLength and maxLength properties for strings

## [0.10.0] - 2018-10-04
### Added
- Add support for type that aren't required

## [0.9.0] - 2018-07-03
### Added
- Add support for properties that contain an array of scalars
### Fixed
- Fix issue where generated code would produce invalid JSON for filtered array properties (as numeric keys in PHP were preserved)

## [0.8.0] - 2018-04-16
### Added
- Add support for propertyOrder

## [0.7.1] - 2017-10-18
### Added
- Add basic logging
### Fixed
- Fix issue with "anyOf" spelled in lowercase

## [0.7.0] - 2017-10-17
### Changed
- Convert "anyof" into empty interface

## [0.6.0] - 2017-10-13
### Added
- Add support for properties that contain an array of objects
### Changed
- Switch to use PHP float type for number properties

## [0.5.0] - 2017-10-12
### Added
- Add support for reference properties and object definitions
- Ensure all property types supported as optional properties
- Add support for named enums
### Fixed
- Minor spacing and naming fixes

## [0.4.0] - 2017-10-12
### Added
- Add custom CS ruleset, including linting of test files
- Add support for objects with multiple properties
- Add support for required and optional properties

## [0.3.0] - 2017-10-04
### Added
- Add default outputDir to gitignore
- Add top-level class to arguments to command line app
- Delete files from output dir before running
### Fixed
- Fix indenting of jsonSerialize return value in generated code

## [0.2.0] - 2017-10-02
### Added
- Add cmd line script