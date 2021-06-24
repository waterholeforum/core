<?php

use Illuminate\Support\Facades\Broadcast;
use Waterhole\Models\Group;

Broadcast::channel('Waterhole.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Waterhole.Group.{id}', function ($user, $id) {
    return (int) $id == Group::MEMBER_ID || $user->groups->contains($id);
});
