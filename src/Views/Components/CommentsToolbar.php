<?php

namespace Waterhole\Views\Components;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Waterhole\Models\Post;
use Waterhole\Sorts\Sort;

class CommentsToolbar extends Component
{
    public Post $post;
    public LengthAwarePaginator $comments;
    public Collection $sorts;
    public Sort $currentSort;

    public function __construct(
        Post $post,
        LengthAwarePaginator $comments,
        Collection $sorts,
        Sort $currentSort
    ) {
        $this->post = $post;
        $this->comments = $comments;
        $this->sorts = $sorts;
        $this->currentSort = $currentSort;
    }

    public function render()
    {
        return view('waterhole::components.comments-toolbar');
    }
}
