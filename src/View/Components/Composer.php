<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\View\Components\Concerns\Streamable;

class Composer extends Component
{
    use Streamable;

    public ?string $body;
    public bool $hasDraft;

    public function __construct(public Post $post, public ?Comment $parent = null)
    {
        $this->body = old('body', $post->userState?->draft_body);
        $draftParentId = old('parent_id', $parent?->id ?: $post->userState?->draft_parent_id);

        $this->hasDraft = $post->userState?->hasDraft();

        if (!$parent && $draftParentId) {
            $parent = $post->comments()->withoutTrashed()->find($draftParentId);
        }

        $this->parent = $parent?->exists ? $parent : null;
    }

    public function render()
    {
        return $this->view('waterhole::components.composer');
    }
}
