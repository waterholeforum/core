# Notifications

Waterhole's notifications system is built using the standard [Laravel Notifications](https://laravel.com/docs/5.8/notifications) implementation, with a few extra concepts layered on top.

All notifications are stored in the database so that a user's notifications can be listed via the REST API. In order to make notification relationships includable in the API, and to group notifications together (eg. when a lot of people react to the same post), Waterhole notifications have a few special properties:

* `sender`: The user whose action caused the notification to be sent. For example, if Bob reacts to Jane's post, causing a notification to be sent to Jane, then Bob is the notification sender.

* `subject`: The model that is the overarching subject of the notification. Notifications of the same type will be grouped by subject so that only one is displayed in the list. For example, if Bob, Fred, and Maria react to Jane's post, then the *post* is the subject, and Jane will only see one notification about it ("Fred and 2 others reacted to your post").

* `content`: The model associated with the individual notification instance. For example, if Bob reacts to Jane's post, then the *reaction* is the content of the notification.

## Notification Types

To define a new type of notification, create a new class extending the abstract `Waterhole\Notifications\Notification` class, and then implement the following methods which correspond to the aforementioned properties:

```php
namespace Vendor\Package;

use Waterhole\Notifications\Notification;

class UnicornBirthed extends Notification
{
    /**
     * @var Unicorn
     */
    protected $unicorn;

    public function __construct(Unicorn $unicorn)
    {
        $this->unicorn = $unicorn;
    }

    public function sender()
    {
        return $this->unicorn->owner;
    }

    public function subject()
    {
        return $this->unicorn->parent;
    }

    public function content()
    {
        return $this->unicorn;
    }
}
```

## Sending Notifications

Send your notification to a `Waterhole\User` as per the [Laravel documentation](https://laravel.com/docs/5.8/notifications#sending-notifications):

```php
use Vendor\Package\Notifications\UnicornBirthed;

$user->notify(new UnicornBirthed($unicorn));
```

The user's notification preferences will be used to send the notification on the appropriate channels.

## Front-end Components

Now the notification will exist in the database and the REST API, but we still need to write a front-end component to render it in the UI.

Waterhole provides a `WaterholeNotification` component which you should use as a basis for your notification's component:

```html
<template>
  <WaterholeNotification
    notification-class="UnicornBirthedNotification"
    :notification="notification"
    :excerpt="`The little one's name is ${child.name}`"
    :route="{ name: 'unicorn', params: { id: unicorn.id }}"
  >
    <template #icon>
      <i class="fas fa-rainbow"></i>
    </template>

    <WaterholeUsername :user="notification.sender"/>'s unicorn {{ parent.name }} birthed a child
  </WaterholeNotification>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import { Model } from 'waterhole/forum';

export default Vue.extend({
    name: 'NotificationUnicornBirthed',

    props: {
        notification: Object as PropType<Notification>,
    },

    computed: {
        parent(): Model {
            return this.notification.subject;
        },

        child(): Model {
            return this.notification.content;
        }
    }
});
</script>
```

Once you have a component for your notification, register it using the front-end `NotificationType` extender:

```js
import { Extend } from 'waterhole/forum';
import NotificationUnicornBirthed from './components/NotificationUnicornBirthed.vue';

export default [
    new Extend.NotificationType(
        'Vendor\\Package\\Notifications\\UnicornBirthed',
        NotificationUnicornBirthed
    ),
];
```

See the [REST API documentation]() for information on what attributes are available on the `notification` prop, and the [JavaScript API reference]() for information about the `WaterholeNotification` component props and slots.

## Notification Emails

Waterhole notifications may also be sent to users via email (and other channels), if they choose to opt-in via their notification preferences.

> Notification emails should only be enabled for notifications that are substantial. Allowing users to receive an email each time someone reacts to one of their posts, for example, would be largely pointless.

In order to allow users to receive notifications on these channels, implement the `Waterhole\Notifications\Mailable` interface on your notification type. This requires the implementation of a number of methods which will be used to construct beautiful HTML and plain-text emails for your notification:

```php
use Waterhole\Notifications\Mailable;
use Waterhole\Notifications\Notification;

class UnicornBirthed extends Notification implements Mailable
{    
    // ...

    /**
     * The text to use for the email subject and to display in the header
     * of the email. Markdown is allowed.
     */
    public function mailText($notifiable): string
    {
        return "**{$this->unicorn->owner->username}'s** unicorn **{$this->unicorn->parent->name}** birthed a child";
    }

    /**
     * The text to use for the call-to-action button.
     */
    public function mailActionText($notifiable): ?string
    {
        return 'View Unicorn';
    }

    /**
     * The URL that the call-to-action button links to.
     */
    public function mailActionUrl($notifiable): ?string
    {
        return route('unicorn', ['id' => $this->unicorn->id]);
    }

    /**
     * An excerpt from the content to display in the email. 
     */
    public function mailExcerpt($notifiable): ?string
    {
        return "The little one's name is {$this->unicorn->name}";
    }

    /**
     * A sentence explaining why the email notification was received.
     */
    public function mailReason($notifiable): ?string
    {
        return 'You received this because you subscribed to email notifications for unicorn birthings.';
    }

    /**
     * The text to use for the unsubscribe link.
     */
    public function mailUnsubscribeText($notifiable): ?string
    {
        return 'Unsubscribe from unicorn birthing notifications';
    }

    /**
     * The URL that the unsubscribe link links to.
     */
    public function mailUnsubscribeUrl($notifiable): ?string
    {
        return URL::signedRoute('unsubscribe.notifications', [
            'user' => $notifiable->getKey(),
            'type' => static::class
        ]);
    }
}
```

The `unsubscribe.notifications` signed route is available for providing a simple unsubscribe URL as demonstrated above.

### Adding a Preference
