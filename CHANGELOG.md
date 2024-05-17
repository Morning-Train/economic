# Changelog

All notable changes to `e-conomic` will be documented in this file.

## v0.7.2 - 2024-05-17

### What's Changed

* Hotfix/product by @larasmorningtrain in https://github.com/Morning-Train/economic/pull/23
* Feature/pdf response by @larasmorningtrain in https://github.com/Morning-Train/economic/pull/22
* Bump aglipanci/laravel-pint-action from 2.3.1 to 2.4 by @dependabot in https://github.com/Morning-Train/economic/pull/26
* ♻️ Set typehint for body to array or string by @larasmorningtrain in https://github.com/Morning-Train/economic/pull/24

### New Contributors

* @larasmorningtrain made their first contribution in https://github.com/Morning-Train/economic/pull/23

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.7.1...v0.7.2

## v0.7.1 - 2024-02-29

### What's Changed

* 🐛 Allow mixed value as operatororvalue by @mschadegg in https://github.com/Morning-Train/economic/pull/21

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.7.0...v0.7.1

## v0.7.0 - 2024-02-29

### What's Changed

* ♻️ Filters by @mschadegg in https://github.com/Morning-Train/economic/pull/20

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.6.0...v0.7.0

## v0.6.0 - 2024-02-28

### What's Changed

* Use DTO for invoice pdf property @SimonJnsson in https://github.com/Morning-Train/economic/pull/19

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.5.4...v0.6.0

## v0.5.4 - 2024-02-26

### What's Changed

* 🐛 Us Throwable instead of Exception when trying to set selv by @mschadegg in https://github.com/Morning-Train/economic/pull/18

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.5.3...v0.5.4

## v0.5.3 - 2024-02-26

### What's Changed

* 🐛 Add project in create request by @mschadegg in https://github.com/Morning-Train/economic/pull/17

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.5.2...v0.5.3

## v0.5.2 - 2024-02-26

### What's Changed

* Project in invoice by @mschadegg in https://github.com/Morning-Train/economic/pull/16

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.5.1...v0.5.2

## v0.5.1 - 2024-02-25

### What's Changed

* ✨ Add Unit and DepartementalDistribution to ProductLine by @mschadegg in https://github.com/Morning-Train/economic/pull/15

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.5.0...v0.5.1

## v0.5.0 - 2024-02-25

* Changed how the Resources is formatted for the API requests.

Note Breaking Changes: Changed "currency" in the Product resource from string to Currency resource.

## v0.4.2 - 2024-02-22

* Added Customer Contact Resource Endpoints

## v0.4.1 - 2024-02-21

* Allow int as VatZone in Recipient
* Removed unnessesary code

## v0.4.0 - 2024-02-20

Updated the Invoice endpoint

OBS: Breaking changes in invoice creation from v0.3.x:

- Moved some DTOs releated to Invoice Creation in to a subfolder
- Moved ProductLine in to DTO subfolder
- Replaced non static create method with a save method and created a static create method

## v0.3.1 - 2024-02-16

### What's Changed

* Use full URL for self links in resources by @SimonJnsson in https://github.com/Morning-Train/economic/pull/10

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.3.0...v0.3.1

## v0.3.0 - 2024-02-08

### What's Changed

* Product resource by @mschadegg in https://github.com/Morning-Train/economic/pull/8

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.2.5...v0.3.0

## v0.2.5 - Add ability to add notes to invoice - 2024-01-29

### What's Changed

* Feature/notes by @SimonJnsson in https://github.com/Morning-Train/economic/pull/7

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.2.4...v0.2.5

## v0.2.4 - Keep falsy values - 2024-01-25

### What's Changed

* ✅ Created VatZone test by @mschadegg in https://github.com/Morning-Train/economic/pull/5
* Feature/keep falsy values by @SimonJnsson in https://github.com/Morning-Train/economic/pull/6

### New Contributors

* @mschadegg made their first contribution in https://github.com/Morning-Train/economic/pull/5

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.2.3...v0.2.4

## v0.2.3 - NemHandelType - 2024-01-22

### What's Changed

* Feature/nem handel type by @SimonJnsson in https://github.com/Morning-Train/economic/pull/4

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.2.2...v0.2.3

## v0.2.2 - Make Customer updatable - 2024-01-09

### What's Changed

* ✨ Handle updating customer resource by @SimonJnsson in https://github.com/Morning-Train/economic/pull/3

**Full Changelog**: https://github.com/Morning-Train/economic/compare/v0.2.1...v0.2.2

## v0.2.0 - 2024-01-04

* Renamed namespace from `MorningTrain\Economic` to `Morningtrain\Economic`

## v0.1.0 - 2024-01-04

Initial release with basic functionality
