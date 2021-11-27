<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\Facades\Image;
use Waterhole\Extend\NotificationTypes;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Views\Components\UserProfileFields;

class PreferencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('waterhole.confirm-password:waterhole.confirm-password')
            ->only(['account', 'changeEmail', 'changePassword']);
    }

    public function index()
    {
        return redirect()->route('waterhole.preferences.profile');
    }

    public function account()
    {
        return view('waterhole::preferences.account');
    }

    public function changeEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        (clone $request->user())
            ->fill($data)
            ->sendEmailVerificationNotification();

        return redirect()
            ->route('waterhole.preferences.account')
            ->with('email_status', "We've sent a verification email to <strong>{$data['email']}</strong>.");
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', Password::defaults()],
        ]);

        $request->user()
            ->fill(['password' => Hash::make($data['password'])])
            ->save();

        return redirect()
            ->route('waterhole.preferences.account')
            ->with('password_status', "Your password has been changed.");
    }

    public function profile()
    {
        return view('waterhole::preferences.profile');
    }

    public function saveProfile(Request $request)
    {
        (new UserProfileFields($request->user()))->save($request);

        return redirect()->route('waterhole.preferences.profile')
            ->with('success', 'Profile saved.');
    }

    public function notifications()
    {
        return view('waterhole::preferences.notifications');
    }

    public function saveNotifications(Request $request)
    {
        $types = NotificationTypes::getComponents();

        $data = $request->validate([
            'notification_channels' => 'array:'.$types->join(','),
            'notification_channels.*' => 'array:0,1',
            'notification_channels.*.*' => 'in:database,mail',
            'follow_on_comment' => 'boolean',
        ]);

        $request->user()->fill($data)->save();

        return redirect()->route('waterhole.preferences.notifications')
            ->with('success', 'Notification preferences saved.');
    }
}
