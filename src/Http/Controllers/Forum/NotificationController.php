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
    }

    public function index(Request $request)
    {
        $request->user()->update([
            'notifications_read_at' => now(),
        ]);

        $models = $request->user()->notifications()
            ->with('subject', 'content')
            ->groupBySubject()
            ->take(10)
            ->get();

        $notifications = $models
            ->groupBy('type')
            ->each(fn($models, $type) => $type::fromDatabase($models))
            ->flatten();

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
}
