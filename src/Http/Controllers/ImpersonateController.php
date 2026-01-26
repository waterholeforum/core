<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\User;

/**
 * Controller to impersonate users.
 */
class ImpersonateController extends Controller
{
    public function __construct()
    {
        $this->middleware(ValidateSignature::class);
    }

    public function __invoke(User $user)
    {
        Auth::login($user);

        return redirect()->route('waterhole.home');
    }
}
