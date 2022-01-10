# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Types of changes
 * **Added** for new features.
 * **Changed** for changes in existing functionality.
 * **Deprecated** for soon-to-be removed features.
 * **Removed** for now removed features.
 * **Fixed** for any bug fixes.
 * **Security** in case of vulnerabilities.

## [0.8.0] 2022-01-10
### Added
 * Initial phpstan 1.x support

## [0.7.6] 2021-08-10
### Added
 * Compatibility with yii 2.0.43 (#45)

## [0.7.5] 2021-07-08
### Added
 * Compatibility with phpstan 0.12.91 (#44)

## [0.7.4] 2021-04-26
### Added
 * Enable PHP 8.0 (#35)
 * Resolve services defined by string & BaseObject configurations (#38)
 * Resolve ActiveRecord stan type from static method return type (#40)
 * Ensure YII_DEBUG is defined (#41)

### Fixed
 * ActiveRecord array access (#39)

## [0.7.3] 2020-07-13
### Added
 * Add support for non-singleton services (definitions) (#30)

## [0.7.2] 2020-05-01
### Changed
 * Replace YiiDynamicStaticMethodReturnTypeExtension with a stub

## [0.7.1] 2019-12-16
### Removed
 * 'Call to an undefined method yii\console\Response' ignored error

## [0.7.0] 2019-12-12
### Added
 * Support for phpstan 0.12
 * GitHub actions

### Removed
 * Travis CI integration

## [0.6.0] 2019-09-12
### Added
 * Support for already initialized components (#15)
 * Support for HeaderCollection.get (#17)

### Changed
 * Required PHP version to at least 7.2

## [0.5.0] 2019-05-06
### Added
 * Add support for phpstan 0.11
 * Extension tests (Yii2 is now a dev dependency)

## [0.4.2] 2018-12-18
### Fixed
 * Allow configuration without singletons (#10)

## [0.4.1] 2018-10-25
### Added
 * Update phpstan and move it to require-dev
 * Add php-cs-fixer & fix code-style
 * Add ServiceMap tests
 * Travis CI integration
 * Add .gitattributes
 
## [0.4.0] 2018-08-21
### Added
 * Add support for phpstan 0.10.3

## [0.3.0] 2018-06-16
### Added
 * Add support for phpstan 0.10

## [0.2.0] 2018-03-09
### Added
 * Provides correct methods and properties for Yii::$app->request

## [0.1.0] 2018-03-07
### Added
 * first initial release
