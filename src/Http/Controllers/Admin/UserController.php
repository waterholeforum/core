<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Group;
use Waterhole\Models\User;
use Waterhole\Views\Components\UserProfileFields;

/**
 * Controller for admin user management.
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
            preg_match_all('/(?:[^\s"]*)"([^"]*)(?:"|$)|[^\s"]+/i', $q, $tokens, PREG_SET_ORDER);

            foreach ($tokens as $token) {
                if (str_starts_with($token[0], 'group:')) {
                    if ($value = $token[1] ?? substr($token[0], strlen('group:'))) {
                        $query->whereHas('groups', function ($query) use ($value) {
                            $query->whereIn('name', explode(',', $value));
                        });
                    }
                } elseif (filter_var($token[0], FILTER_VALIDATE_EMAIL)) {
                    $query->where('email', $token[0]);
                } else {
                    $query->where('name', 'LIKE', $token[0].'%');
                }
            }
        }

        // Apply sorting to the query. Ensure the requested sort and direction
        // is valid before doing so.
        if (! isset(self::SORTABLE_COLUMNS[$sort = $request->query('sort')])) {
            $sort = array_key_first(self::SORTABLE_COLUMNS);
        }

        $direction = $request->query('direction', self::SORTABLE_COLUMNS[$sort] ?? 'asc');

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query->orderBy($sort, $direction);

        return view('waterhole::admin.users.index', [
            'users' => $query->paginate(50),
            'sort' => $sort,
            'direction' => $direction,
            'sortable' => array_keys(self::SORTABLE_COLUMNS),
        ]);
    }

    public function create()
    {
        return $this->form();
    }

    public function store(Request $request)
    {
        $this->save(new User(), $request);

        return redirect()
            ->route('waterhole.admin.users.index', ['sort' => 'created_at'])
            ->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return $this->form()->with(compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $this->save($user, $request);

        return redirect($request->input('return', route('waterhole.admin.users.index')))
            ->with('success', 'User saved.');
    }

    private function form()
    {
        $groups = Group::selectable()->get();

        return view('waterhole::admin.users.form', compact('groups'));
    }

    private function save(User $user, Request $request): void
    {
        $data = User::validate($request->all(), $user);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        DB::transaction(function () use ($request, $user, $data) {
            $user->fill($data)->save();

            $this->saveGroups($user, $request);

            (new UserProfileFields($user))->save($request);
        });
    }

    private function saveGroups(User $user, Request $request): void
    {
        $data = $request->validate([
            'groups' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->isRootAdmin() && ! in_array(Group::ADMIN_ID, $value)) {
                        $fail('Cannot revoke the admin status of a root admin.');
                    }
                },
            ],
            'groups.*' => [
                'integer',
                Rule::exists(Group::class, 'id')->whereNotIn('id', [Group::GUEST_ID, Group::MEMBER_ID]),
            ],
        ]);

        if (array_key_exists('groups', $data)) {
            $user->groups()->sync($data['groups'] ?: []);
        }
    }
}
