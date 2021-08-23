<x-waterhole::layout :title="$post->title">
    <div class="container">
        <div class="post-page">
            <article class="post-full">
                <header class="post-header">
                    @components(Waterhole\Extend\PostHeader::getComponents(), compact('post'))
                </header>

                <div class="post-body content">
                    {{ $post->body_html }}
                </div>

                <x-waterhole::post-footer :post="$post" interactive/>
            </article>

            <hr>

            <section class="post-comments">
                @if ($comment)
                    <p><a href="{{ request()->fullUrlWithQuery(['comment' => null]) }}">View all comments</a></p>

                    <x-waterhole::comments.comment :comment="$comment"/>
                @else
                    <header class="toolbar post-comments__toolbar">
                        <h2 class="h3 post-comments__title">{{ __('waterhole::forum.post-comment-count', ['count' => $post->comment_count]) }}</h2>

                        @if ($post->comment_count > 1)
                            <div class="tabs scrollable">
                                @foreach ($sorts as $sort)
                                    <a
                                        href="{{ $post->url }}?sort={{ $sort->handle() }}"
                                        class="tab"
                                        title="{{ $sort->description() }}"
                                        @if ($currentSort === $sort) aria-current="page" @endif
                                    >{{ $sort->name() }}</a>
                                @endforeach
                            </div>
                        @endif

                        <div class="spacer"></div>

                        {{ $comments->links() }}

                        @if ($post->comment_count)
                            <a href="{{ $comments->url($comments->lastPage()) }}#reply" class="btn btn--primary">Reply</a>
                        @endif
                    </header>

                    <div>
                        @foreach ($comments as $comment)
                            <x-waterhole::comments.comment :comment="$comment"/>
                        @endforeach
                    </div>

                    @if (! $comments->hasMorePages())
                        <div class="post-comments__reply comment" id="reply">
                            <div class="attribution">
                                <x-waterhole::ui.avatar :user="Auth::user()"/>
                            </div>

                            <x-waterhole::comments.reply :post="$post"/>
                        </div>
                    @else
                        <footer class="toolbar">
                            <div class="spacer"></div>

                            {{ $comments->links() }}

                            @if ($post->comment_count)
                                <a href="{{ $comments->url($comments->lastPage()) }}#reply" class="btn btn--primary">Reply</a>
                            @endif
                        </footer>
                    @endif
                @endif
            </section>
        </div>
    </div>
</x-waterhole::layout>
