<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Waterhole\Models\User;

/**
 * Controller to impersonate users.
 */
class ImpersonateController extends Controller
{
    public function __construct()
    {
        $this->middleware('signed');
    }
    public function __invoke(User $user)
    {
        Auth::login($user);

        return redirect()->route('waterhole.home');
    }
}
