<?php

namespace Waterhole\Actions;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Waterhole\Forms\Fields\PostTags;
use Waterhole\Forms\PostTagsForm;
use Waterhole\Models\Model;
use Waterhole\Models\Post;
use Waterhole\Models\User;

class EditTags extends Action
{
    public bool $confirm = true;

    public function appliesTo(Model $model): bool
    {
        return $model instanceof Post && (new PostTags($model))->shouldRender();
    }

    public function authorize(?User $user, Model $model): bool
    {
        return $user && $user->can('waterhole.post.edit', $model);
    }

    public function label(Collection $models): string
    {
        return __('waterhole::forum.edit-tags-button');
    }

    public function icon(Collection $models): string
    {
        return 'tabler-tags';
    }

    public function confirm(Collection $models): View
    {
        return view('waterhole::posts.edit-tags', [
            'form' => new PostTagsForm($models->first()),
        ]);
    }

    public function confirmButton(Collection $models): string
    {
        return __('waterhole::system.save-changes-button');
    }

    public function run(Collection $models): void
    {
        (new PostTagsForm($models->first()))->submit(request());
    }
}
