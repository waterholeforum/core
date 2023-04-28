<?php

namespace Waterhole\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Waterhole\Mail\Markdown;
use Waterhole\Models\Model;
use Waterhole\Models\User;

/**
 * Base class for a Waterhole notification.
 *
 * Waterhole builds on top of the Laravel Notifications system to make
 * implementing notifications even easier. Waterhole takes care of all the
 * boilerplate: managing user preferences, generating HTML emails, handling
 * secure unsubscribe links, and rendering notifications.
 *
 * To define a new Waterhole Notification type, extend this class and implement
 * the methods to describe your notification content.
 */
abstract class Notification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Determine whether the notification should be sent to this user.
     */
    public function shouldSend($notifiable): bool
    {
        return true;
    }

    /**
     * Get the notification's delivery channels.
     *
     * For Waterhole notifications, each user is able to set a preference for
     * which channels they would like to receive their notifications on.
     */
    public function via($notifiable): array
    {
        return $notifiable->notification_channels[get_class($this)] ?? [];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): Mailable
    {
        $title = $this->title();

        $markdown = resolve(Markdown::class);
        $view = 'waterhole::mail.notification';
        $data = [
            'title' => $title,
            'avatar' => $this->sender()->avatar_url,
            'name' => $this->sender()->name,
            'excerpt' => $this->excerpt(),
            'button' => $this->button(),
            'url' => $this->url(),
            'reason' => $this->reason(),
            'unsubscribeText' => $this->unsubscribeText(),
            'unsubscribeUrl' => $this->unsubscribeUrl($notifiable),
        ];

        return (new Mailable())
            ->to($notifiable->routeNotificationFor('mail'))
            ->subject(strip_tags($title))
            ->view($markdown->render($view, $data))
            ->text($markdown->renderText($view, $data));
    }

    /**
     * The model associated with the individual notification instance.
     *
     * This must be the same as your notification's constructor argument. It
     * will be used to reconstruct the notification instance after the
     * notification is read from the database.
     */
    public function content(): ?Model
    {
        return null;
    }

    /**
     * The user whose action caused the notification to be sent.
     *
     * If present, the user's avatar will be displayed alongside the
     * notification. In the case of a notification for a new comment, it is the
     * author of the comment.
     */
    public function sender(): ?User
    {
        return null;
    }

    /**
     * The name of an icon to represent the notification.
     */
    public function icon(): ?string
    {
        return null;
    }

    /**
     * The title of the notification.
     *
     * This can be a plain string or an `HtmlString` if you want to wrap
     * certain words in `<strong>`, for example. Just be sure to escape user-
     * generated content if returning an `HtmlString`.
     */
    abstract public function title(): string|HtmlString;

    /**
     * An excerpt from the notification content.
     */
    public function excerpt(): null|string|HtmlString
    {
        return null;
    }

    /**
     * The URL that the user should be taken to when they click the
     * notification.
     */
    public function url(): ?string
    {
        return null;
    }

    /**
     * The button text to be displayed on the action button in the notification
     * email.
     */
    public function button(): ?string
    {
        return null;
    }

    /**
     * A common model to group multiple notifications together.
     *
     * For example, for a notification about a new comment, this could be the
     * post of the comment, causing notifications for multiple new comments
     * on the same post to be grouped together on the user's notification list.
     */
    public function group(): ?Model
    {
        return null;
    }

    /**
     * The URL that the user should be taken to when they click a grouped
     * notification.
     */
    public function groupedUrl(): ?string
    {
        return $this->url();
    }

    /**
     * The reason that the user is receiving the notification.
     */
    public function reason(): ?string
    {
        return null;
    }

    /**
     * The label of the unsubscribe link for this notification type.
     */
    public function unsubscribeText(): ?string
    {
        return __('waterhole::notifications.unsubscribe-link');
    }

    /**
     * The URL to unsubscribe the user from this notification type.
     */
    public function unsubscribeUrl($notifiable): string
    {
        return URL::signedRoute('waterhole.notifications.unsubscribe', [
            'payload' => Crypt::encrypt([
                'type' => get_class($this),
                'notifiable_type' => $notifiable->getMorphClass(),
                'notifiable_id' => $notifiable->getKey(),
                'content_type' => $this->content()?->getMorphClass(),
                'content_id' => $this->content()?->getKey(),
            ]),
        ]);
    }

    /**
     * Unsubscribe the user from this notification type.
     */
    public function unsubscribe(User $user): void
    {
        $type = get_class($this);

        if ($channels = $user->notification_channels[$type] ?? null) {
            $user->notification_channels[$type] = collect($channels)->reject('mail');
            $user->save();
        }
    }

    /**
     * A description of this notification type in the user preferences.
     */
    public static function description(): ?string
    {
        return null;
    }

    /**
     * Load additional relationships onto the notifications models before
     * displaying the notification list.
     */
    public static function load(Collection $notifications): void
    {
    }
}
