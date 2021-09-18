<x-waterhole::layout :title="$post->title">
    <x-slot name="breadcrumb">
        <span style="color: var(--color-text-muted)" data-visible-after="h1">
            &nbsp;›&nbsp; <a href="{{ $post->url }}" style="color: var(--color-text-muted)">{{ $post->title }}</a>
        </span>
    </x-slot>

    <div class="container">
        <div class="post-page">
            @if (! $comment && $comments->onFirstPage())
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
            @else
                <h1 style="margin: 1rem 0" class="h2">
                    <a href="{{ $post->url }}" style="color: inherit">{{ $post->title }}</a>
                </h1>
                <hr>
            @endif

            <section class="post-comments" id="comments">
                @if ($comment)
                    <p>
                      <a href="{{ request()->fullUrlWithQuery(['comment' => null]) }}#comments" style="font-weight: var(--font-weight-medium)">
                        <x-waterhole::icon icon="heroicon-s-arrow-sm-up"/> View all comments
                      </a>
                    </p>

                    <x-waterhole::comment :comment="$comment" with-composer/>
                @else
                    <header class="toolbar post-comments__toolbar">
                        <h2 class="h3 post-comments__title">{{ __('waterhole::forum.post-comment-count', ['count' => $post->comment_count]) }}</h2>

                        @if ($post->comment_count > 1)
                            <div class="tabs scrollable">
                                @foreach ($sorts as $sort)
                                    <a
                                        href="{{ $post->url }}?sort={{ $sort->handle() }}#comments"
                                        class="tab"
                                        title="{{ $sort->description() }}"
                                        @if ($currentSort === $sort) aria-current="page" @endif
                                    >{{ $sort->name() }}</a>
                                @endforeach
                            </div>
                        @endif

                        <div class="spacer"></div>

                        {{ $comments->fragment('comments')->links() }}

                        @if ($post->comment_count)
                            <a href="{{ $comments->fragment('')->url($comments->lastPage()) }}#reply" class="btn btn--primary">Reply</a>
                        @endif
                    </header>

                    <div>
                        @foreach ($comments as $comment)
                            <x-waterhole::comment :comment="$comment"/>
                        @endforeach

                        @if (! $comments->hasMorePages())
                            <div class="post-comments__reply comment" id="reply">
                                <div class="attribution">
                                    <x-waterhole::avatar :user="Auth::user()"/>
                                </div>

                                <x-waterhole::comment-reply-composer :post="$post"/>
                            </div>
                        @endif
                    </div>

                    @if ($comments->hasMorePages())
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
