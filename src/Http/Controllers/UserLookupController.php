<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Waterhole\Models\User;
use Waterhole\View\Components\UserLabel;

/**
 * Controller to look up users by name.
 *
 * This is used to populate the @mentions suggestion box in the text editor.
 * The return format is an array of objects with the following keys:
 *
 * - `id`: the user ID
 * - `name`: the user's name
 * - `html`: a rendering of the UserLabel component for the user
 */
class UserLookupController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->query('q');

        if (strlen($query) < 2) {
            abort(400, 'Query must be 2 or more characters');
        }

        return User::query()
            ->where('name', 'like', $query . '%')
            ->orderByRaw('name = ? desc', [$query])
            ->orderBy('name')
            ->take(5)
            ->get(['id', 'name', 'avatar'])
            ->map(
                fn(User $user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'html' => Blade::renderComponent(new UserLabel($user)),
                ],
            );
    }
}
