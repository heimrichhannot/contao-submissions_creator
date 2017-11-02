# Change Log
All notable changes to this project will be documented in this file.

## [1.1.1] - 2017-11-02

### Added
- copying fields from submission archive for convenience

## [1.1.0] - 2017-07-25

### Added
- dependency for new formhybrid_list/frontendedit

## [1.0.17] - 2017-05-09

### Added
- php 7 support

## [1.0.16] - 2017-03-02

### Added
- hash to url

## [1.0.15] - 2017-02-23

### Fixed
- catch $objNews/$objEvent NULL within SubmissionCreator::getRelatedEvents and SubmissionCreator::getRelatedNews

## [1.0.14] - 2017-02-08

### Changed
- noIdBehavior is 'create' if no value is set

## [1.0.13] - 2017-01-17

### Fixed
- fixed getRelatedNews() and getRelatedEvent() within backend filter

## [1.0.12] - 2017-01-16

### Fixed
- fixed getRelatedNews() and getRelatedEvent() for performance issues

### Fixed
- news and event submission relation invokation on datacontainer

## [1.0.11] - 2016-12-05

### Added
- added news_submission_active inserttag
- added event_submission_active inserttag
- add status message to ModuleSubmissionReader when not within submission period

## [1.0.10] - 2016-11-11

### Added
- added formHybridSingleSubmission field to front end module submission_reader

## [1.0.9] - 2016-11-10

### Added
- limit submit period for submissions (on relations and module)  

### Fixed
- news and event submission relation invokation on datacontainer
