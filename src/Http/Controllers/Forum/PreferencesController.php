<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Waterhole\Extend\NotificationTypes;
use Waterhole\Forms\UserProfileForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\View\Components\UserProfileFields;

/**
 * Controller for user preferences views.
 */
class PreferencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('waterhole.confirm-password')->only([
            'account',
            'changeEmail',
            'changePassword',
        ]);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        // Make a copy of the user because we don't want to set the email
        // of the global instance, otherwise a subsequent call to `save` could
        // actually save the new email before it's been verified.
        (clone $request->user())->fill($data)->sendEmailVerificationNotification();

        return redirect()
            ->route('waterhole.preferences.account')
            ->with(
                'success',
                "We've sent a verification email to <strong>{$data['email']}</strong>.",
            );
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', Password::defaults()],
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return redirect()
            ->route('waterhole.preferences.account')
            ->with('success', 'Your password has been changed.');
    }

    public function profile(Request $request)
    {
        $form = new UserProfileForm($request->user());

        return view('waterhole::preferences.profile', compact('form'));
    }

    public function saveProfile(Request $request)
    {
        (new UserProfileForm($request->user()))->submit($request);

        return redirect()
            ->route('waterhole.preferences.profile')
            ->with('success', 'Profile saved.');
    }

    public function notifications()
    {
        return view('waterhole::preferences.notifications');
    }

    public function saveNotifications(Request $request)
    {
        $data = $request->validate([
            'notification_channels' => 'array:' . implode(',', NotificationTypes::build()),
            'notification_channels.*' => 'array:0,1',
            'notification_channels.*.*' => 'in:database,mail',
            'follow_on_comment' => 'boolean',
        ]);

        $request->user()->update($data);

        return redirect()
            ->route('waterhole.preferences.notifications')
            ->with('success', 'Notification preferences saved.');
    }
}
