<?php

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Waterhole\Models\Channel;
use Waterhole\Models\Enums\PinnedScope;
use Waterhole\Models\Post;
use Waterhole\View\Components\Concerns\Streamable;

class PostFeedPinned extends Component
{
    use Streamable;

    public Collection $posts;

    public function __construct(
        public ?Channel $channel = null,
    ) {
        $query = Post::withoutTrashed()->whereNotNull('pinned_scope')->whereNot->ignoring();

        if ($channel) {
            $query->whereBelongsTo($channel);
        } else {
            $query->where('pinned_scope', PinnedScope::Global->value);
            $query->whereDoesntHave('channel', fn($query) => $query->ignoring());
        }

        $query->with(['channel.userState', 'userState']);

        if (Auth::check()) {
            $query->with('bookmark');
        }

        $query->withUnreadCommentsCount();

        $this->posts = $query->latest()->get();
    }

    public function shouldRender(): bool
    {
        return $this->posts->isNotEmpty();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-feed-pinned');
    }
}
