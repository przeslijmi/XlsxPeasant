# Changelog

## v1.3.2 - 2021-07-03

- Change: fleshgrinder/uuid lib is no loner needed.

## v1.3.0 - 2021-03-13

- New: Added methods to build-up fast large data `Tables`.

## v1.2.2 - 2020-10-11

- New: Shared strings provider has now cache builded.

## v1.2.0 - 2020-07-03

- New: Xml generator has three more configuration settings: `Xml::NO_VALIDATION_NODE_NAME`, `Xml::NO_VALIDATION_ATTR_NAME`, `Xml::NO_VALIDATION_ATTR_VALUE`. All Xlsx Xml are bu default defined with this setting.
- New: Added `->getColumnsNames()` method to `Table`.
- New: Added `->hasColumns()` method to `Table` that checks if table has identical set of columns added and also in given order only.
- Change: Final cell XML is now generated directly as XML - without array step.
- Change: Generation of big XLSX files speed up significant (few times faster).
- Change: Removed unused tag `@since` from docs.
- Fix: Defining cell value as null is not causing error anymore. Empty string is used.

## v1.1.1 - 2020-06-17

- Change: First try for better optimisation of memory.

## v1.1.0 - 2020-05-21

- New: Added `getStopReadingOnEmptyRows()` and `setStopReadingOnEmptyRows()` methods for `Reader`.

## v1.0.3 - 2020-04-15

- Change: Updated `shippable.yml`.

## v1.0.2 - 2020-04-15

- New: Added `resources/configSpecimen.php`.
- Change: Updated exceptions.
- Change: Updated `phpcs.xml`.

## v1.0.1 - 2020-02-16

- New: Added `Shippable.yml`.
- Change: Reached 100 % code coverage.

## v1.0.0 - 2020-02-16

- New: Offical release.
