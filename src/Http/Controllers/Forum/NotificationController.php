<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Crypt;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Notification;
use Waterhole\View\Components\Notification as NotificationComponent;

/**
 * Controller for notification management.
 */
class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth');
        $this->middleware(ValidateSignature::class)->only('unsubscribe');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $user->update(['notifications_read_at' => now()]);

        $connection = $user->notifications()->getRelated()->getConnection();

        $cast = $connection->getDriverName() === 'pgsql' ? 'TEXT' : 'CHAR';

        // Notifications can be grouped together by their subject. When listing
        // notifications, we only show the most recent notification in each
        // "group", so the user doesn't get overwhelmed by lots of activity.
        $query = $user
            ->notifications()
            ->select('*')
            ->selectRaw(
                "ROW_NUMBER() OVER(PARTITION BY type, COALESCE(CAST(group_type AS $cast), CAST(id AS $cast)), COALESCE(CAST(group_id AS $cast), CAST(id AS $cast)) ORDER BY created_at DESC) AS r"
            );

        $notifications = Notification::from('notifications')
            ->withExpression('notifications', $query)
            ->with('content')
            ->where('r', 1)
            ->latest()
            ->paginate(10);

        // Give notification types the opportunity to eager-load additional
        // relationships.
        $notifications->groupBy('type')->each(fn($models, $type) => $type::load($models));

        return view('waterhole::forum.notifications', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        return Blade::renderComponent(new NotificationComponent($notification));
    }

    public function go(Notification $notification)
    {
        Notification::groupedWith($notification)->update(['read_at' => now()]);

        $notification->type::load(new Collection([$notification]));

        return redirect($notification->template->groupedUrl());
    }

    public function read(Request $request)
    {
        $request
            ->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return redirect()->route('waterhole.notifications.index');
    }

    public function unsubscribe(Request $request)
    {
        $payload = collect(Crypt::decrypt($request->query('payload')));

        // An unsubscribe request will come in with the notification type,
        // its user, and content. Find the matching notification in the database
        // so we can reconstruct its template and call its unsubscribe method.
        $notification = Notification::where(
            $payload
                ->only('type', 'notifiable_type', 'notifiable_id', 'content_type', 'content_id')
                ->all(),
        )->firstOrFail();

        $notification->template->unsubscribe($notification->notifiable);

        return redirect()
            ->route('waterhole.home')
            ->with('success', __('waterhole::notifications.unsubscribe-success-message'));
    }
}
