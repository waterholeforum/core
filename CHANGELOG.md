# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

-   Add Russian translation ([#2](https://github.com/waterholeforum/core/pull/2) by @Awilum)
-   Add French translation ([#5](https://github.com/waterholeforum/core/pull/5) by @qiaeru)
-   Add support for looking up users by ID
-   Make post last activity time link to the last comment
-   Set the `color-scheme` CSS property according to the current theme
-   Add `waterhole:reformat` command to reparse formatted content
-   Add `body_text` getter to `Post` and `Comment` to get plain-text version of the body
-   Add `waterhole.design.emoji_url` config option to configure TextFormatter emoji rendering

### Changed

-   Use TextFormatter to render emoji instead of php-twemoji
-   Don't open internal links in a new window
-   Adjust container width and typographic measure
-   Decrease padding on nested comments
-   Change default sort for users table to last created
-   Display more dates using relative time component
-   Stop using deprecated `micro` format for relative times
-   Rename `time-ago` component to `relative-time`
-   Rename `.twemoji` class to `.emoji`

### Removed

-   Remove `Waterhole\Extend\Emoji` class
-   Remove `waterhole.design.twemoji_base` config option
-   Remove Twemoji rendering in the emoji picker for now
-   Remove unused HTML truncation utils

### Fixed

-   Fix text editor emoji popup not displaying
-   Fix a JavaScript error on posts with no comments
-   Fix untranslated variable in resend confirmation email message ([#4](https://github.com/waterholeforum/core/pull/4) by @askerakbar)
-   Fix error when running `waterhole:make:extension` command ([#6](https://github.com/waterholeforum/core/issues/6))
-   Emojify the post title on the single comment page
-   Allow code blocks to wrap in notification emails
-   Fix bottom of composer textarea going off-screen
-   Remove top border from first comment
-   Remove height limit on comment preview tooltips
-   Prevent crashing if database content contains invalid XML

### Security

-   Fix an XSS vulnerability where HTML could be injected into emojified text

## [0.1.1] - 2023-05-23

### Fixed

-   Fix license error alert incorrectly appearing in trial mode.

## [0.1.0] - 2023-05-23

Initial release.

[unreleased]: https://github.com/waterholeforum/core/compare/v0.1.1...HEAD
[0.1.1]: https://github.com/waterholeforum/core/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/waterholeforum/core/releases/tag/v0.1.0
