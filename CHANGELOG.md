# Changelog

All notable changes to `e-conomic` will be documented in this file.

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
