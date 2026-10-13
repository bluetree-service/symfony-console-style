# Change Log
All notable changes to this project will be documented in this file.

## 0.8.1.0 - 2026-10-13
### Added
* `MultiSelect::getCursor()` row under cursor when list was confirmed

## 0.8.0.0 - 2026-10-13
### Added
* `MultiSelect::renderMultiSelect()` preselected and locked (can't be unselected) keys
* `MultiSelect::setLockedChar()` mark for locked rows
* `MultiSelect::setPageSize()` scrolling for lists longer than terminal (terminal height by default)
* optional input stream in `MultiSelect` constructor (tests)
### Changed
* list stops on input EOF instead of busy loop
### Removed
* `MultiSelect::renderBasicList()` (first render uses `renderListWithSelection()`)

## 0.7.0.0 - 2026-03-10
* Composer scripts for testing
* Makefile for easier development
* Testing on multiple PHP versions
* Code coverage report generation
* GitHub Actions for CI/CD
* Parameters and return types
* Variable types
* Some small improvements
### Changed
* Minimal PHP version set to 8.2
* Updated required libraries
* some method logic
* improved tests
### Removed
* php-coveralls
* version from composer.json
* composer.lock

## 0.6.1.1 - 2022-07-03
### Added
* Circle-CI badge
### Changed
* Fixed composer.json version

## 0.6.1.0 - 2022-05-22
### Changed
* List rendering timeout changed from 0.5s to 0.1s

**## 0.6.0.0 - 2020-08-09
### Added
* An added helper to run commands in multiple processes
* Styles for multiple process progress bar
* Floating progress bar
* Multi line block
* Circle CI configuration
* README description
### Changed
* PSR-12 fixes
* Fixes from CI/CD services**

## 0.5.0.0 - 2020-07-05
### Added
* Message timer with date time
* Style tests
### Changed
* Style::renderBlock make public
* PSR-12 fixes

## 0.4.0.0 - 2019-05-04
### Added
* Message timer
### Changed
* Some block styles
* Split `Style` class

## 0.3.0.4 - 2018-12-18
### Added
* Some new tests
* Some todo in `src/Style.php`
* New examples
* Coverage composer script

## 0.3.0.3 - 2018-11-11
### Added
* Clover reporter as dev dependency

## 0.3.0.2 - 2018-11-06
### Removed
* Support for PHP 7.0

## 0.3.0.1 - 2018-10-31
### Changed
* Symfony console required version changed from 4.* to ~4.0

## 0.3.0.0 - 2018-10-23
### Added
* Some example files (./examples)
* Some additional styles
* Symfony var-dumper
* Basic animation for process waiting class
### Changed
* Symfony console required version changed from 4.0.* to 4.*
* Fixed errorLine with multiple messages
* Fixed single select
### Removed
* PHP 5.6 support
* composer.lock as library convention approach

## 0.2.0.0 - 2018-09-14
### Added
* Basic display style
### Changed
* Minimal PHP requirements changed to 7.0

## 0.1.0.0 - 2016-01-29
### Added
* Proof of concept
* Composer dependencies
