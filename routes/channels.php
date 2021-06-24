<?php

use Illuminate\Support\Facades\Broadcast;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\Models\User;

Broadcast::channel(User::class, function (User $actor, User $user) {
    return $actor->id === $user->id;
});

Broadcast::channel(Channel::class, function (User $user, Channel $channel) {
    return $channel->exists();
});

Broadcast::channel(Post::class, function (User $user, Post $post) {
    return $post->exists();
});
