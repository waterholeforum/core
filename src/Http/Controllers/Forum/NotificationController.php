<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('unsubscribe');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $user->update(['notifications_read_at' => now()]);

        $query = $user->notifications()
            ->select('*')
            ->selectRaw('ROW_NUMBER() OVER(PARTITION BY type, COALESCE(subject_type, id), COALESCE(subject_id, id) ORDER BY created_at DESC) AS r');

        $notifications = Notification::from('notifications')
            ->withExpression('notifications', $query)
            ->with('subject', 'content')
            ->where('r', 1)
            ->latest()
            ->paginate(10);

        $notifications
            ->groupBy('type')
            ->each(fn($models, $type) => $type::load($models));

        return view('waterhole::forum.notifications', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        Notification::groupedWith($notification)->update(['read_at' => now()]);

        return redirect($notification->template->groupedUrl());
    }

    public function read(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->route('waterhole.notifications.index');
    }

    public function unsubscribe(Request $request)
    {
        $attributes = $request->only('type', 'notifiable_type', 'notifiable_id', 'content_type', 'content_id');

        $notification = Notification::where($attributes)->firstOrFail();

        $notification->template->unsubscribe($notification->notifiable);

        return redirect()->route('waterhole.home')
            ->with('success', "You've been unsubscribed from these notifications.");
    }
}
