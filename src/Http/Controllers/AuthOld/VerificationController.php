<?php

namespace Waterhole\Http\Controllers\AuthOld;

use Waterhole\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify', 'change');
        $this->middleware('throttle:6,1')->only('verify', 'resend', 'change');
    }

    public function verify(Request $request)
    {
        return parent::verify($request)->with('toast', [
            'type' => 'success',
            'icon' => 'fas fa-check',
            'message' => 'Thanks for verifying your email address!'
        ]);
    }

    public function change(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException;
        }

        $user = $request->user();
        $user->email = $request->get('email');
        $user->markEmailAsVerified();

        return redirect($this->redirectPath())->with('verified', true)->with('toast', [
            'type' => 'success',
            'icon' => 'fas fa-check',
            'message' => 'Thanks for verifying your email address!'
        ]);
    }
}
