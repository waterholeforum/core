<?php

use Illuminate\Support\Facades\Broadcast;
use Waterhole\Models\User;

Broadcast::channel('Waterhole.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Waterhole.Models.Channel.{id}', function (User $user, $id) {
    return true;
});

Broadcast::channel('Waterhole.Models.Post.{id}', function (User $user, $id) {
    return true;
});
