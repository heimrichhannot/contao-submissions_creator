# Change Log
All notable changes to this project will be documented in this file.

## [3.8.5] - 2022-08-23
- Fixed: warnings in php 8
- 
## [3.8.4] - 2022-06-07
- Fixed: warnings in php 8

## [3.8.3] - 2022-03-23
- Changed: made SubmissionCreator::getRelationLink() static (as it's only called static) (fix for php 8)

## [3.8.2] - 2022-02-14

- Fixed: array index issues in php 8+

## [3.8.1] - 2022-02-14

- Fixed: array index issues in php 8+

## [3.8.0] - 2021-09-01

- Added: php8 support

## [3.7.0] - 2020-09-17
- removed `publishOnValid` (wrong context)

## [3.6.0] - 2020-09-16
- added `publishOnValid`

## [3.5.1] - 2020-08-10
- fixed str_replace warning in classes/Hook.php

## [3.5.0] - 2020-06-24
- added Hook to modifyDc of submission form

## [3.4.2] - 2020-03-26
- fixed a warning in contao 4.9

## [3.4.1] - 2019-09-30

### Changed
- text for `tl_submission.privacyJumpTo`

## [3.4.0] - 2019-06-11

### Changed
- text for `tl_submission.privacyJumpTo`

## [3.3.1] - 2019-04-04

### Fixed
- missing clr class for jumpToPrivacy in tl_module

## [3.3.0] - 2019-03-18

### Added
- set field values by GET-parameter

## [3.2.2] - 2019-03-06

### Added
- some english and polish translations

## [3.2.1] - 2019-02-15

### Fixed
- sprinft `privacyJumpTo` only if it was not already replaced before in `ModuleSubmissionReader:modifyDC`

## [3.2.0] - 2018-12-10

### Added
- support for salutation_submission notification token

## [3.1.2] - 2018-12-07

### Fixed
- privacyJumpTo checkbox label issue

## [3.1.1] - 2018-05-25

### Fixed
- missed use statement in `ModuleSubmissionReader` for Url-class

## [3.1.0] - 2018-05-25

### Added
- selectable jumpTo page for privacy policy which is linked to by field `privacyJumpTo` 

## [3.0.1] - 2018-05-14

### Added
- disableSessionCheck,disableAuthorCheck to palette

## [3.0.0] - 2018-02-06

### Added
- dependency to frontendedit 6

## [2.0.0] - 2017-11-23

### Added
- dependency to frontendedit 5

## [1.1.2] - 2017-11-07

### Fixed
- copying fields from submission archive for convenience

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
