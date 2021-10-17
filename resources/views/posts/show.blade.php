<x-waterhole::layout :title="$post->title">
        <div class="post-page" data-controller="post-page">
            <div class="container">

            {{--            @if (! $comment && $comments->onFirstPage())--}}
            <div @if (! $comments->onFirstPage()) hidden @endif data-action="turbo:frame-render@document->post-page#showPostOnFirstPage" data-post-page-target="post">
                <x-waterhole::post-full :post="$post"/>
                <br><br><br><br>
            </div>
{{--            @endif--}}

{{--            <h1 class="h2" hidden>--}}
{{--                <a href="{{ $post->url }}" style="color: inherit">{{ $post->title }}</a>--}}
{{--            </h1>--}}
            </div>

            <section class="post-comments" id="comments" tabindex="-1">
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
                    <x-waterhole::comments-toolbar
                        :post="$post"
                        :comments="$comments"
                        :sorts="$sorts"
                        :current-sort="$currentSort"
                        top
                    />

                <div class="container">
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

                            <div>
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
    </div>
</x-waterhole::layout>
