# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.4.11] - 2025-04-21

### Added

- Add support for Laravel 12
- Add descriptive error message for CSRF token expiry ([#73](https://github.com/waterholeforum/core/issues/73))

### Fixed

- Fix taxonomies not being assignable by non-admins ([#75](https://github.com/waterholeforum/core/issues/75))
- Fix error when sending non-Waterhole notifications ([#79](https://github.com/waterholeforum/core/issues/79))
- Fix `FluentTranslator` not forwarding macros calls ([#81](https://github.com/waterholeforum/core/pull/81) by @jacksleight)
- Prevent post page sidebar width from changing
- Fix MariaDB `ONLY_FULL_GROUP_BY` error
- Fix HTML entities in notification email subjects

## [0.4.10] - 2024-10-03

### Fixed

- Fix some search queries causing a server error
- Fix formatter rendering cache error when Waterhole is run across multiple servers

## [0.4.9] - 2024-05-16

### Fixed

- Fix redirecting to incorrect URL when there is a sole auth provider
- Fix lazy loading error in some reaction configurations
- Fix broken Dutch localization ([#77](https://github.com/waterholeforum/core/pull/77) by @Emmanuel71git)

## [0.4.8] - 2024-04-16

### Added

- Add support for Laravel 11

### Fixed

- Fix "show more" rendering incorrectly on Firefox ([#64](https://github.com/waterholeforum/core/issues/64))
- Fix videos having no max width
- Fix emojis appearing full-width in notification emails ([#67](https://github.com/waterholeforum/core/issues/67))
- Fix PHP deprecation error ([#69](https://github.com/waterholeforum/core/issues/69))

## [0.4.7] - 2024-03-17

### Added

- Add PHP 8.3 compatibility ([#62](https://github.com/waterholeforum/core/pull/62) by @saturnphp)
- Add Dutch translation ([#60](https://github.com/waterholeforum/core/pull/60) by @Emmanuel71)

### Fixed

- Fix Chrome bug where textarea scrolls the viewport when selecting text
- Fix post slug mutation when title is highlighted in search results
- Fix icons displaying incorrectly with latest version of `blade-tabler-icons` ([#63](https://github.com/waterholeforum/core/issues/63))

## [0.4.6] - 2024-02-02

### Fixed

- Fix compatibility with `Model::shouldBeStrict()` ([#56](https://github.com/waterholeforum/core/issues/56))
- Fall back to base translator when using translation string keys ([#57](https://github.com/waterholeforum/core/pull/57) by @dsturm)
- Fall back to resource directory for missing translation bundle ([#58](https://github.com/waterholeforum/core/pull/58) by @dsturm)

## [0.4.5] - 2024-01-31

### Fixed

- Fix incorrect database connection being used in some cases
- Fix success message not appearing after change email/password

## [0.4.4] - 2024-01-30

### Fixed

- Fix an issue with Laravel auth integration causing user to be logged out after registration

## [0.4.3] - 2024-01-26

### Fixed

- Fix incorrect database connection being used in some cases ([#55](https://github.com/waterholeforum/core/issues/55))

## [0.4.2] - 2023-12-31

### Fixed

- Fix issues with Laravel integration ([#53](https://github.com/waterholeforum/core/issues/53))
- Add missing translations for CP reactions

## [0.4.1] - 2023-12-24

### Fixed

- Fix auth routes using incorrect path ([#51](https://github.com/waterholeforum/core/issues/51))
- Fix reactions not working due to incorrect permission check
- Fix error when sending notification to unverified email

## [0.4.0] - 2023-12-23

### ⚠️ Breaking Changes

- `waterhole.auth.oauth_providers` config key renamed to `waterhole.auth.providers`
- `.oauth-button` class renamed to `.auth-button`
- `<x-waterhole::oauth-buttons>` component renamed to `<x-waterhole::auth-buttons>`
- `Waterhole\OAuth\Payload` class renamed to `Waterhole\Auth\SsoPayload`
- Waterhole Gate abilities are now prefixed with `waterhole.`
- Waterhole routes no longer use the `web` route middleware group

### Added

- New `sso` auth provider to support custom single sign-on flows
- Auth guard used by Waterhole requests can be configured by setting `waterhole.auth.guard`
- Database connection can be configured by setting `waterhole.system.database`
- Route domain can be configured by settings `waterhole.system.domain`
- Support implicit authentication from existing user base by implementing `Waterhole\Auth\AuthenticatesWaterhole` interface
- Automatically create the formatter and translation cache directories if they don't exist
- Laravel Socialite is now included by default
- Update Traditional Chinese (zh-Hant) translation ([#48](https://github.com/waterholeforum/core/pull/48) by @efast1568)

### Changed

- If there is a single auth provider and passwords are disabled, the login and registration pages will now automatically redirect to the provider
- Add color to inline code spans
- Reduce size of the Create Post button
- Waterhole routes no longer rely on app-aliased middleware

### Fixed

- Fix the forum URL shown at the end of the installation command
- Fix emoji picker inserting emoji multiple times after navigations
- Fix "last reply" link to jump to the last comment instead of below it
- Fix CP users table pagination/sorting links
- Only send notifications to verified email addresses

## [0.3.2] - 2023-12-02

### Added

- Show comment button in post footer on mobile

### Fixed

- Fix comment composer being unreachable on mobile ([#44](https://github.com/waterholeforum/core/issues/44))
- Prevent composer re-appearing on page reload after it has been closed ([#46](https://github.com/waterholeforum/core/issues/46))
- Fix comment composer not clearing after submission
- Prevent unnecessary post page load when jumping to page 1
- Only configure Laravel Echo if Pusher is configured
- Fix entire page scrolling when navigating through @mention suggestions

## [0.3.1] - 2023-11-17

### Added

- Add Traditional Chinese (zh-Hant) translation ([#8](https://github.com/waterholeforum/core/pull/8) by @efast1568)
- Update French translation ([#14](https://github.com/waterholeforum/core/pull/14) by @qiaeru)

### Changed

- Load reaction counts as a relationship rather than via query scopes

### Fixed

- Fix reactions disappearing when following/unfollowing a post
- Opt out of smooth scrolling on Google Chrome
- Fix Copy Link action not working
- Disable login submit button to prevent double-submission
- Fix mobile page selector sometimes displaying incorrect page number
- Fix active nav items and buttons not highlighted in Firefox
- Remove `max-height` from images causing loss of aspect ratio
- Remove extra space from post title on comment page
- Fix post Delete Forever action not working
- Fix crawlers causing 500 error with invalid pagination cursor
- Don't scroll all the way to bottom of the page when opening composer

## [0.3.0] - 2023-07-31

### Added

- Show recent commenters and original poster in @mention suggestions
- Add ability to pin posts to top of feed
- Add ability to hide comments
- Show links in channel picker when creating post
- Get a "mention" notification when someone replies to your comment
- Add a comment button in the post sidebar
- Add ability to soft-delete posts
- Add `.btn--start` and `.btn--end` classes
- Add `--color-fill-soft` token and `.bg-fill-soft` utility

### Changed

- Use more compact layout for comments on small screens
- Use color to make search result keyword highlights more prominent
- Clamp `--space-gutter` between `lg` and `xl`
- Reduce default number of posts/comments per page to improve performance
- Optimize performance of avatar component
- Lazy-load the list of reaction users to improve performance
- Lazy-load action menus to improve performance
- Make "Automatically follow posts I comment on" preference also follow posts you create
- Limit one notification per user per new comment
- Change from idiomorph to nanomorph (due to buggy behavior with some attributes of old nodes remaining)
- Refactored the way Turbo Frames show loading spinners - using CSS instead of HTML

### Removed

- Remove unread badge from channel navigation items to improve performance
- Remove greeting and subcopy from verify email/reset password emails
- Remove unnecessary action button `title` attribute ([#21](https://github.com/waterholeforum/core/issues/21))

### Fixed

- Fix emoji alignment in reactions menu
- Highlight code blocks in streamed content
- Fix unread posts sometimes jumping to the incorrect page
- Don't globally override pagination views which is problematic on Octane
- Fix default layout for Announcements channel
- Fix avatar display for UTF-8 usernames
- Fix highlighting UTF-8 characters and phrases in search results ([#12](https://github.com/waterholeforum/core/issues/12), [#25](https://github.com/waterholeforum/core/issues/25))
- Fix color picker disappearing on Chrome ([#15](https://github.com/waterholeforum/core/issues/15))
- Fix fetch error alerts not showing in some cases, and allow them to disappear
- Fix email verification alert layout
- Fix comment index being incorrectly calculated in some cases
- Fix composer not disappearing after submitting comment
- Encode UTF-8 symbols in CSS files
- Fix other users' notifications getting marked as read
- Remove whitespace in user links
- Fix not being able to edit comments on lazy-loaded pages ([#24](https://github.com/waterholeforum/core/issues/24))
- Add missing translations ([#22](https://github.com/waterholeforum/core/issues/22))
- Fix copy link action being run multiple times ([#20](https://github.com/waterholeforum/core/issues/20))
- Fix composer overlapping header on small screens ([#19](https://github.com/waterholeforum/core/issues/19))

## [0.2.0] - 2023-05-30

### Added

- Add Russian translation ([#2](https://github.com/waterholeforum/core/pull/2) by @Awilum)
- Add French translation ([#5](https://github.com/waterholeforum/core/pull/5) by @qiaeru)
- Add support for looking up users by ID
- Make post last activity time link to the last comment
- Set the `color-scheme` CSS property according to the current theme
- Add `waterhole:reformat` command to reparse formatted content
- Add `body_text` getter to `Post` and `Comment` to get plain-text version of the body
- Add `waterhole.design.emoji_url` config option to configure TextFormatter emoji rendering

### Changed

- Use TextFormatter to render emoji instead of php-twemoji
- Don't open internal links in a new window
- Adjust container width and typographic measure
- Decrease padding on nested comments
- Change default sort for users table to last created
- Display more dates using relative time component
- Stop using deprecated `micro` format for relative times
- Rename `time-ago` component to `relative-time`
- Rename `.twemoji` class to `.emoji`

### Removed

- Remove `Waterhole\Extend\Emoji` class
- Remove `waterhole.design.twemoji_base` config option
- Remove Twemoji rendering in the emoji picker for now
- Remove unused HTML truncation utils

### Fixed

- Fix text editor emoji popup not displaying
- Fix a JavaScript error on posts with no comments
- Fix untranslated variable in resend confirmation email message ([#4](https://github.com/waterholeforum/core/pull/4) by @askerakbar)
- Fix error when running `waterhole:make:extension` command ([#6](https://github.com/waterholeforum/core/issues/6))
- Emojify the post title on the single comment page
- Allow code blocks to wrap in notification emails
- Fix bottom of composer textarea going off-screen
- Remove top border from first comment
- Remove height limit on comment preview tooltips
- Prevent crashing if database content contains invalid XML
- Fix localization of dates

### Security

- Fix an XSS vulnerability where HTML could be injected into emojified text

## [0.1.1] - 2023-05-23

### Fixed

- Fix license error alert incorrectly appearing in trial mode.

## [0.1.0] - 2023-05-23

Initial release.

[unreleased]: https://github.com/waterholeforum/core/compare/v0.4.11...HEAD
[0.4.11]: https://github.com/waterholeforum/core/compare/v0.4.10...v0.4.11
[0.4.10]: https://github.com/waterholeforum/core/compare/v0.4.9...v0.4.10
[0.4.9]: https://github.com/waterholeforum/core/compare/v0.4.8...v0.4.9
[0.4.8]: https://github.com/waterholeforum/core/compare/v0.4.7...v0.4.8
[0.4.7]: https://github.com/waterholeforum/core/compare/v0.4.6...v0.4.7
[0.4.6]: https://github.com/waterholeforum/core/compare/v0.4.5...v0.4.6
[0.4.5]: https://github.com/waterholeforum/core/compare/v0.4.4...v0.4.5
[0.4.4]: https://github.com/waterholeforum/core/compare/v0.4.3...v0.4.4
[0.4.3]: https://github.com/waterholeforum/core/compare/v0.4.2...v0.4.3
[0.4.2]: https://github.com/waterholeforum/core/compare/v0.4.1...v0.4.2
[0.4.1]: https://github.com/waterholeforum/core/compare/v0.4.0...v0.4.1
[0.4.0]: https://github.com/waterholeforum/core/compare/v0.3.2...v0.4.0
[0.3.2]: https://github.com/waterholeforum/core/compare/v0.3.1...v0.3.2
[0.3.1]: https://github.com/waterholeforum/core/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/waterholeforum/core/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/waterholeforum/core/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/waterholeforum/core/compare/v0.1.0...v0.1.1
[0.1.0]: https://github.com/waterholeforum/core/releases/tag/v0.1.0
