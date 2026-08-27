<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Waterhole\Actions\DeleteSelf;
use Waterhole\Models\User;

uses(RefreshDatabase::class);

describe('profile preferences', function () {
    test('rejects avatar uploads larger than configured max upload size', function () {
        config(['waterhole.uploads.max_upload_size' => 1]);

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->from(route('waterhole.preferences.profile'))
            ->post(route('waterhole.preferences.profile'), [
                'show_online' => 1,
                'avatar' => UploadedFile::fake()->image('avatar.png')->size(2),
            ])
            ->assertRedirect(route('waterhole.preferences.profile'))
            ->assertSessionHasErrors('avatar');

        expect($user->fresh()->avatar)->toBeNull();
    });
});

describe('account preferences', function () {
    test('deleting your account redirects to the forum home', function () {
        User::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('waterhole.actions.store'), [
            'actionable' => User::class,
            'id' => $user->id,
            'action_class' => DeleteSelf::class,
            'confirmed' => true,
        ])->assertRedirect(route('waterhole.home'));

        $this->assertGuest();
        $this->assertModelMissing($user);
    });
});
