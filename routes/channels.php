<?php

use Illuminate\Support\Facades\Broadcast;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\User;

Broadcast::channel('Waterhole.Models.User.{id}', function (User $user, int $id) {
    return $user->id === $id;
});

Broadcast::channel('Waterhole.Models.Channel.{id}', function (User $user, int $id) {
    return (bool) Channel::find($id);
});

Broadcast::channel('Waterhole.Models.Post.{id}', function (User $user, int $id) {
    return (bool) Post::find($id);
});
