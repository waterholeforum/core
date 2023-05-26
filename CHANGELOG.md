# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

-   Add support for looking up users by ID
-   Add Russian translation ([#2](https://github.com/waterholeforum/core/pull/2) by @Awilum)
-   Add French translation ([#5](https://github.com/waterholeforum/core/pull/5) by @qiaeru)
-   Set the `color-scheme` CSS property according to the current theme

### Changed

-   Don't open internal links in a new window

### Fixed

-   Fix text editor emoji popup not displaying
-   Fix a JavaScript error on posts with a single page of comments
-   Fix untranslated variable in resend confirmation email message ([#4](https://github.com/waterholeforum/core/pull/4) by @askerakbar)
-   Fix undefined array key error when running `waterhole:make:extension` command ([#6](https://github.com/waterholeforum/core/issues/6))
-   Emojify the post title on the single comment page

## [0.1.1] - 2023-05-23

### Fixed

-   Fix license error alert incorrectly appearing in trial mode.

## [0.1.0] - 2023-05-23

Initial release.

[unreleased]: https://github.com/waterholeforum/core/compare/v0.1.1...HEAD
[0.1.1]: https://github.com/waterholeforum/core/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/waterholeforum/core/releases/tag/v0.1.0
