<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Waterhole.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Waterhole.Models.Channel.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('Waterhole.Models.Post.{id}', function ($user, $id) {
    return true;
});
