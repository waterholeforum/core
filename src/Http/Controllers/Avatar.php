<?php

namespace Waterhole\Http\Controllers;

use Exception;
use Waterhole\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\ImageManager;
use Tobyz\JsonApiServer\Exception\ForbiddenException;
use Tobyz\JsonApiServer\Exception\ResourceNotFoundException;
use Tobyz\JsonApiServer\JsonApi;
use Zend\Diactoros\ServerRequest;

class Avatar extends Controller
{
    private function withUser(int $id, callable $callback)
    {
        $api = new JsonApi(url('/api'));

        try {
            if (! $user = User::find($id)) {
                throw new ResourceNotFoundException('users', $id);
            }

            if (! Gate::allows('edit-user', $user)) {
                throw new ForbiddenException;
            }

            $callback($user);

            return (new ApiController)(new ServerRequest([], [], 'users/'.$user->id, 'GET'));
        } catch (Exception $e) {
            return $api->error($e);
        }
    }

    public function upload(int $id, Request $request)
    {
        return $this->withUser($id, function (User $user) use ($request) {
            $user->uploadAvatar((new ImageManager)->make($request->file('file')->getRealPath()));
        });
    }

    public function remove(int $id, Request $request)
    {
        return $this->withUser($id, function (User $user) use ($request) {
            $user->removeAvatar();
        });
    }
}
