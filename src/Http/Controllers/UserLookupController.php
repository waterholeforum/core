<?php

namespace Waterhole\Http\Controllers;

use Illuminate\Http\Request;
use Waterhole\Models\User;
use Waterhole\Views\Components\UserLabel;

class UserLookupController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->query('q');

        if (strlen($query) < 2) {
            abort(400, 'query must be 2 or more characters');
        }

        return User::query()
            ->where('name', 'like', $query.'%')
            ->orderByRaw('name = ? desc', [$query])
            ->orderByRaw('name like ? desc', [$query.'%'])
            ->take(5)
            ->get(['id', 'name', 'avatar'])
            ->map(function (User $user) {
                $component = new UserLabel($user);
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'html' => $component->resolveView()->with($component->data())->render(),
                ];
            });
    }
}
