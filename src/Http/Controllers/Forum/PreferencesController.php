<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Waterhole\Extend\NotificationTypes;
use Waterhole\Forms\UserProfileForm;
use Waterhole\Http\Controllers\Controller;

/**
 * Controller for user preferences views.
 */
class PreferencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth');
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
        if ($request->user()->originalUser()) {
            abort(404);
        }

        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        // Make a copy of the user because we don't want to set the email
        // of the global instance, otherwise a subsequent call to `save` could
        // actually save the new email before it's been verified.
        (clone $request->user())->fill($data)->sendEmailVerificationNotification();

        return redirect()
            ->route('waterhole.preferences.account')
            ->with('success', __('waterhole::auth.email-verification-sent-message', $data));
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', Password::defaults()],
        ]);

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return redirect()
            ->route('waterhole.preferences.account')
            ->with('success', __('waterhole::passwords.reset'));
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
            ->with('success', __('waterhole::user.profile-saved-message'));
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
            ->with('success', __('waterhole::user.notification-preferences-saved-message'));
    }
}
