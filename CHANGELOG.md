# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.3.0] - 2023-07-31

### Added

-   Show recent commenters and original poster in @mention suggestions
-   Add ability to pin posts to top of feed
-   Add ability to hide comments
-   Show links in channel picker when creating post
-   Get a "mention" notification when someone replies to your comment
-   Add a comment button in the post sidebar
-   Add ability to soft-delete posts
-   Add `.btn--start` and `.btn--end` classes
-   Add `--color-fill-soft` token and `.bg-fill-soft` utility

### Changed

-   Use more compact layout for comments on small screens
-   Use color to make search result keyword highlights more prominent
-   Clamp `--space-gutter` between `lg` and `xl`
-   Reduce default number of posts/comments per page to improve performance
-   Optimize performance of avatar component
-   Lazy-load the list of reaction users to improve performance
-   Lazy-load action menus to improve performance
-   Make "Automatically follow posts I comment on" preference also follow posts you create
-   Limit one notification per user per new comment
-   Change from idiomorph to nanomorph (due to buggy behavior with some attributes of old nodes remaining)
-   Refactored the way Turbo Frames show loading spinners - using CSS instead of HTML

### Removed

-   Remove unread badge from channel navigation items to improve performance
-   Remove greeting and subcopy from verify email/reset password emails
-   Remove unnecessary action button `title` attribute ([#21](https://github.com/waterholeforum/core/issues/21))

### Fixed

-   Fix emoji alignment in reactions menu
-   Highlight code blocks in streamed content
-   Fix unread posts sometimes jumping to the incorrect page
-   Don't globally override pagination views which is problematic on Octane
-   Fix default layout for Announcements channel
-   Fix avatar display for UTF-8 usernames
-   Fix highlighting UTF-8 characters and phrases in search results ([#12](https://github.com/waterholeforum/core/issues/12), [#25](https://github.com/waterholeforum/core/issues/25))
-   Fix color picker disappearing on Chrome ([#15](https://github.com/waterholeforum/core/issues/15))
-   Fix fetch error alerts not showing in some cases, and allow them to disappear
-   Fix email verification alert layout
-   Fix comment index being incorrectly calculated in some cases
-   Fix composer not disappearing after submitting comment
-   Encode UTF-8 symbols in CSS files
-   Fix other users' notifications getting marked as read
-   Remove whitespace in user links
-   Fix not being able to edit comments on lazy-loaded pages ([#24](https://github.com/waterholeforum/core/issues/24))
-   Add missing translations ([#22](https://github.com/waterholeforum/core/issues/22))
-   Fix copy link action being run multiple times ([#20](https://github.com/waterholeforum/core/issues/20))
-   Fix composer overlapping header on small screens ([#19](https://github.com/waterholeforum/core/issues/19))

## [0.2.0] - 2023-05-30

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
-   Fix localization of dates

### Security

-   Fix an XSS vulnerability where HTML could be injected into emojified text

## [0.1.1] - 2023-05-23

### Fixed

-   Fix license error alert incorrectly appearing in trial mode.

## [0.1.0] - 2023-05-23

Initial release.

[unreleased]: https://github.com/waterholeforum/core/compare/v0.3.0...HEAD
[0.3.0]: https://github.com/waterholeforum/core/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/waterholeforum/core/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/waterholeforum/core/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/waterholeforum/core/releases/tag/v0.1.0
