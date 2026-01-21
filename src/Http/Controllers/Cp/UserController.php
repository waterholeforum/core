<?php

namespace Waterhole\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Waterhole\Forms\UserForm;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\User;

/**
 * Controller for CP user management.
 */
class UserController extends Controller
{
    /**
     * A map of sortable columns and their initial direction.
     */
    private const SORTABLE_COLUMNS = [
        'name' => 'asc',
        'created_at' => 'desc',
        'last_seen_at' => 'desc',
    ];

    public function index(Request $request)
    {
        $query = User::with('groups');

        // Explode the filter query into space-separated tokens (as long as the
        // space is not within a pair of quotes). For each token, add a where
        // clause to the query.
        if ($q = $request->query('q')) {
            $isPgsql = (new User())->getConnection()->getDriverName() === 'pgsql';
            $likeOperator = $isPgsql ? 'ILIKE' : 'LIKE';

            preg_match_all('/(?:[^\s"]*)"([^"]*)(?:"|$)|[^\s"]+/i', $q, $tokens, PREG_SET_ORDER);

            foreach ($tokens as $token) {
                if (str_starts_with($token[0], 'group:')) {
                    if ($value = $token[1] ?? substr($token[0], strlen('group:'))) {
                        $query->whereHas('groups', function ($query) use ($value) {
                            $query->whereIn('name', explode(',', $value));
                        });
                    }
                } elseif ($token[0] === 'is:suspended') {
                    $query->where('suspended_until', '>', now());
                } elseif (filter_var($token[0], FILTER_VALIDATE_EMAIL)) {
                    $query->where('email', $token[0]);
                } elseif (filter_var($token[0], FILTER_VALIDATE_INT)) {
                    $query->where('id', $token[0]);
                } else {
                    $query->where('name', $likeOperator, $token[0] . '%');
                }
            }
        }

        // Apply sorting to the query. Ensure the requested sort and direction
        // is valid before doing so.
        if (!isset(self::SORTABLE_COLUMNS[($sort = $request->query('sort'))])) {
            $sort = array_key_first(self::SORTABLE_COLUMNS);
        }

        $direction = $request->query('direction', self::SORTABLE_COLUMNS[$sort] ?? 'asc');

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);

        return view('waterhole::cp.users.index', [
            'users' => $query->paginate(50),
            'sort' => $sort,
            'direction' => $direction,
            'sortable' => array_keys(self::SORTABLE_COLUMNS),
        ]);
    }

    public function create()
    {
        $form = $this->form(new User());

        return view('waterhole::cp.users.form', compact('form'));
    }

    public function store(Request $request)
    {
        $this->form($user = new User())->submit($request);

        $user->markEmailAsVerified();

        return redirect()
            ->route('waterhole.cp.users.index', ['sort' => 'created_at'])
            ->with('success', __('waterhole::cp.user-created-message'));
    }

    public function edit(User $user)
    {
        $form = $this->form($user);

        return view('waterhole::cp.users.form', compact('form', 'user'));
    }

    public function update(User $user, Request $request)
    {
        $this->form($user)->submit($request);

        return redirect($request->input('return', route('waterhole.cp.users.index')))->with(
            'success',
            __('waterhole::cp.user-saved-message'),
        );
    }

    private function form(User $user)
    {
        return new UserForm($user);
    }
}
