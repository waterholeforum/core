<?php

namespace Waterhole\Http\Controllers\Forum\Concerns;

use Illuminate\Support\Arr;
use Waterhole\Models\User;

trait SavesPostDrafts
{
    protected function savePostDraft(User $user, array $input): void
    {
        $payload = Arr::except($input, ['_token', '_method', 'commit', 'draft_action']);

        if (filled($payload['title'] ?? null) || filled($payload['body'] ?? null)) {
            $user->drafts()->updateOrCreate([], ['payload' => $payload]);
        } else {
            $user->drafts()->delete();
        }
    }
}
