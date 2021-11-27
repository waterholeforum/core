<?php

namespace Waterhole\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Group;
use Waterhole\Models\User;
use Waterhole\Views\Components\UserProfileFields;

class UserController extends Controller
{
    const SORTABLE_COLUMNS = [
        'name' => 'asc',
        'created_at' => 'desc',
        'last_seen_at' => 'desc',
    ];

    public function index(Request $request)
    {
        $query = User::with('groups');

        if ($q = $request->query('q')) {
            preg_match_all('/(?:[^\s"]*)"([^"]*)(?:"|$)|[^\s"]+/i', $q, $tokens, PREG_SET_ORDER);

            foreach ($tokens as $token) {
                if (str_starts_with($token[0], 'group:')) {
                    $value = $token[1] ?? substr($token[0], strlen('group:'));
                    $query->whereHas('groups', function ($query) use ($value) {
                        $query->where('name', $value);
                    });
                } else {
                    $query->where('name', 'LIKE', $token[0].'%');

                    if (filter_var($token[0], FILTER_VALIDATE_EMAIL)) {
                        $query->orWhere('email', $token[0]);
                    }
                }
            }
        }

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
        DB::transaction(function () use ($request) {
            $user = User::create($this->data($request));
            $this->saveGroups($user, $request);
            (new UserProfileFields($user))->save($request);
        });

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
        DB::transaction(function () use ($user, $request) {
            $user->update($this->data($request, $user));
            $this->saveGroups($user, $request);
            (new UserProfileFields($user))->save($request);
        });

        return redirect()
            ->route('waterhole.admin.users.index')
            ->with('success', 'User saved.');
    }

    private function form()
    {
        $groups = Group::whereNotIn('id', [Group::GUEST_ID, Group::MEMBER_ID])->get();

        return view('waterhole::admin.users.form', compact('groups'));
    }

    private function data(Request $request, User $user = null): array
    {
        $data = $request->validate(
            User::rules($user)
        );

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $data;
    }

    private function saveGroups(User $user, Request $request)
    {
        $data = $request->validate([
            'groups' => [
                'array',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user->isRootAdmin() && ! in_array(Group::ADMIN_ID, $value)) {
                        $fail('Cannot revoke the admin status of a root admin.');
                    }
                },
            ],
            'groups.*' => [
                'integer',
                Rule::exists(Group::class, 'id')->whereNotIn('id', [Group::GUEST_ID, Group::MEMBER_ID])
            ],
        ]);

        if (isset($data['groups'])) {
            $user->groups()->sync($data['groups']);
        }
    }
}
