# Permissions

Waterhole features a powerful permissions system which gives administrators and extension developers fine-grained control over exactly what users can see and do.

## Permission Logic

Permission configuration (as seen in the admin CP) is stored in the `permissions` database table. Each permission record has an **ability** (eg. `view-discussions`) and a **recipient** (usually a Group, but could also be a User).

A permission record may also have a **scope** (like a Category). If present, this causes the list of recipients to which the ability is granted to be *reset* for any entities within that scope. For example, given the following permission records...

| Scope | Ability | Recipient |
|------------|------------------|-----------|
| `NULL` | view-discussions | Group A |
| `NULL` | view-discussions | Group B |
| Category X | view-discussions | Group A |

...Discussions within Category X (or its children) will *only* be visible to Group A; all other discussions will be visible to Groups A and B.

However, a **modifier** may be present on a permission record which allows the ability to be granted/denied to a recipient *without resetting* the list of recipients in the parent scope. So given the following permission records...

| Scope | Ability | Recipient | Modifier |
|------------|------------------|-----------|----------|
| `NULL` | view-discussions | Group A |  |
| `NULL` | view-discussions | Group B |  |
| Category X | view-discussions | Group A | deny |
| Category X | view-discussions | Group C | grant |

...Discussions within Category X (or its children) will be visible to Group B (from the parent scope) and Group C, but not Group A.

## Gates

Permission checking is powered by the standard [Laravel Gate](https://laravel.com/docs/6.x/authorization) implementation. Waterhole intercepts all gate checks to automatically grant the ability if the user is an administrator, or according to the configured permissions as per the above logic.

> Note that [Policies](https://laravel.com/docs/6.x/authorization#creating-policies) cannot be used in Waterhole, because intercepting policy checks only works for methods within the same policy class, which prohibits extensibility.

### Writing Gates

If you need to implement custom gate logic on top of the default permission-checking logic (for example, disallowing replying to a discussion based on when it was started), you can use the `Gate` extender:

```php
use Waterhole\Models\Discussion;
use Waterhole\Extend;
use Waterhole\Models\User;

return [
    new Extend\Gate('reply-to-discussion', function (?User $user, Discussion $discussion) {
        if ($discussion->created_at->isLastYear()) {
            return false;
        }
    }),
];
```

Note that you should explicitly return `true` or `false` only if your logic applies; otherwise, return void or `null` to allow the check to fall through to Waterhole's default logic.

### Authorizing Actions

You can authorize actions as per the [Laravel Gate documentation](https://laravel.com/docs/6.x/authorization#authorizing-actions-via-gates).

## Visibility Scopes

When querying the database for records (eg. discussions or posts), Waterhole applies "visibility scopes" to ensure only records that the actor is allowed to see are returned.

Visibility scopes can be added using the following extenders:

* `CategoryVisibility`
* `DiscussionVisibility`
* `PostVisibility`
* `TagVisibility`

```php
use Waterhole\Extend;
use Waterhole\Models\User;
use Illuminate\Database\Eloquent\Builder;

return [
    new Extend\DiscussionVisibility(function (Builder $query, ?User $user) {
        // Don't allow users to see a particular user's discussions
        $query->where('user_id', '!=', 123);
    }),
];
```
