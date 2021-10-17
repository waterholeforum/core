<x-waterhole::layout :title="$post->title">
        <div class="post-page" data-controller="post-page">
            <div class="container">

            {{--            @if (! $comment && $comments->onFirstPage())--}}
            <div @if (! $comments->onFirstPage()) hidden @endif data-action="turbo:frame-render@document->post-page#showPostOnFirstPage" data-post-page-target="post">
                <div style="display: flex; align-items: flex-start; justify-content: space-between">
                    <x-waterhole::post-full :post="$post"/>
                    <div class="" style="border-left: 1px solid var(--color-stroke); margin-top: 6rem; position: sticky; top: calc(var(--header-height) + var(--space-xl)); margin-left: var(--space-xxxl); width: 160px; flex-shrink: 0; padding: 0 0 0 var(--space-md); margin-bottom: 0">
                        <div class="post-footer toolbar">

                            <x-waterhole::action-menu :for="$post" style="margin-bottom: .5rem">
                                <x-slot name="button">
                                    <button class="btn btn--small">
                                        <x-waterhole::icon icon="heroicon-o-dots-circle-horizontal"/>
                                        <span>Controls</span>
                                        <x-waterhole::icon icon="heroicon-s-chevron-down"/>
                                    </button>
                                </x-slot>
                            </x-waterhole::action-menu>

                            @components(Waterhole\Extend\PostFooter::getComponents(), compact('post') + ['interactive' => true])



                        </div>
                    </div>
                </div>
                <br><br><br><br>
            </div>
{{--            @endif--}}

{{--            <h1 class="h2" hidden>--}}
{{--                <a href="{{ $post->url }}" style="color: inherit">{{ $post->title }}</a>--}}
{{--            </h1>--}}
            </div>

            <section class="post-comments" tabindex="-1">
{{--                @if ($comment)--}}
{{--                    <div>--}}
{{--                        <a--}}
{{--                            href="{{ request()->fullUrlWithQuery(['comment' => null]) }}#comments"--}}
{{--                            class="with-icon"--}}
{{--                        >--}}
{{--                            <x-waterhole::icon icon="heroicon-s-arrow-sm-left"/>--}}
{{--                            <span>Back to all comments</span>--}}
{{--                        </a>--}}
{{--                    </div>--}}

{{--                    <x-waterhole::comment :comment="$comment" with-replies/>--}}
{{--                @else--}}
{{--                    <x-waterhole::comments-toolbar--}}
{{--                        :post="$post"--}}
{{--                        :comments="$comments"--}}
{{--                        :sorts="$sorts"--}}
{{--                        :current-sort="$currentSort"--}}
{{--                        top--}}
{{--                    />--}}

                <div class="container">
                    <h2 style="margin-bottom: var(--space-xl); padding-top: var(--space-xl)">
                        {{ __('waterhole::forum.post-comment-count', ['count' => $post->comment_count]) }}
                    </h2>

                    <div style="display: flex; align-items: flex-start; justify-content: space-between">

                        <div class="post-comments__comments">
                            <turbo-frame id="page_{{ $comments->currentPage() }}">
                                @if (! $comments->onFirstPage() && request()->query('direction') !== 'forwards')
                                    <turbo-frame
                                        id="page_{{ $comments->currentPage() - 1 }}"
                                        src="{{ $comments->appends('direction', 'backwards')->previousPageUrl() }}"
                                        loading="lazy"
                                        class="next-page"
                                        data-controller="load-backwards"
                                        data-action="
                                            turbo:before-fetch-response->load-backwards#lockScrollPosition
                                            turbo:frame-render->load-backwards#unlockScrollPosition"
                                    ><div class="loading-indicator"></div></turbo-frame>
                                @endif

                                @if (! $comments->onFirstPage())
                                    <div class="divider">
                                        <span>Page {{ $comments->currentPage() }}</span>
                                    </div>
                                @endif

                                <div id="comments" tabindex="-1">
                                    @foreach ($comments as $i => $comment)
                                        <x-waterhole::comment-full :comment="$comment" :data-index="$comments->firstItem() - 1 + $i"/>
                                    @endforeach
                                </div>

                                @if ($comments->hasMorePages())
                                    @if (request()->query('direction') !== 'backwards')
                                        <turbo-frame
                                            id="page_{{ $comments->currentPage() + 1 }}"
                                            src="{{ $comments->appends('direction', 'forwards')->nextPageUrl() }}"
                                            loading="lazy"
                                            class="next-page"
                                        ><div class="loading-indicator"></div></turbo-frame>
                                    @endif
                                @else
                                    <div class="post-comments__reply comment" id="reply">
                                        <div class="attribution">
                                            <x-waterhole::avatar :user="Auth::user()"/>
                                        </div>

                                        <turbo-frame id="reply-composer">
                                            <x-waterhole::comment-reply-composer :post="$post"/>
                                        </turbo-frame>
                                    </div>
                                        <div id="bottom" tabindex="-1"></div>
                                @endif
                            </turbo-frame>
                        </div>

                        <div class="" style="border-left: 1px solid var(--color-stroke);position: sticky; top: calc(var(--header-height) + var(--space-xl)); margin-left: var(--space-xxxl); width: 160px; flex-shrink: 0; padding: 0 0 0 var(--space-md); margin-bottom: 0">
                            <div class="toolbar ruler">
{{--                                <a href="{{ $post->url }}" class="btn btn--small btn--transparent">--}}
{{--                                    <x-waterhole::icon icon="heroicon-s-arrow-up"/>--}}
{{--                                    <span>Original Post</span>--}}
{{--                                </a>--}}

{{--                                <x-waterhole::action-menu :for="$post" style="margin-bottom: var(--space-sm)">--}}
{{--                                    <x-slot name="button">--}}
{{--                                        <button class="btn">--}}
{{--                                            <x-waterhole::icon icon="heroicon-o-dots-circle-horizontal"/>--}}
{{--                                            <span>Controls</span>--}}
{{--                                            <x-waterhole::icon icon="heroicon-s-chevron-down"/>--}}
{{--                                        </button>--}}
{{--                                    </x-slot>--}}
{{--                                </x-waterhole::action-menu>--}}

{{--                                <h4 style="margin-top: 1rem">--}}
{{--                                    {{ __('waterhole::forum.post-comment-count', ['count' => $post->comment_count]) }}--}}
{{--                                </h4>--}}

{{--                                    <button class="btn" style="margin-bottom: var(--space-sm)">--}}
{{--                                        <x-waterhole::icon icon="heroicon-o-bell"/>--}}
{{--                                        <span>Follow</span>--}}
{{--                                        <x-waterhole::icon icon="heroicon-s-chevron-down"/>--}}
{{--                                    </button>--}}

                                    <nav class="pagination tabs">

                                <a class="tab" href="{{ $post->url }}">
                                    <x-waterhole::icon icon="heroicon-s-chevron-double-up" style="font-size:90%; margin-left: -3px"/>
                                    <span>First</span>
                                </a>

                                {{ $comments->appends('direction', null)->fragment('comments')->links() }}

                                        <a class="tab" href="{{ $comments->fragment('bottom')->url($comments->lastPage()) }}">
                                            <x-waterhole::icon icon="heroicon-s-chevron-double-down" style="font-size:90%; margin-left: -3px"/>
                                            <span>Last</span>
                                        </a>
                                    </nav>
                            </div>
                        </div>
                    </div>
                </div>

{{--                    @if ($comments->hasMorePages())--}}
{{--                        <x-waterhole::comments-toolbar--}}
{{--                            :post="$post"--}}
{{--                            :comments="$comments"--}}
{{--                            :sorts="$sorts"--}}
{{--                            :current-sort="$currentSort"--}}
{{--                        />--}}
{{--                    @endif--}}
{{--                @endif--}}
            </section>
    </div>
</x-waterhole::layout>
