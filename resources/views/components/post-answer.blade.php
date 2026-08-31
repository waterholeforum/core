<div
    id="@domid($post, 'answer')"
    class="bg-success-soft rounded p-lg stack align-start gap-md"
    tabindex="-1"
>
    <div class="row justify-between align-start gap-sm align-self-stretch">
        <div class="with-icon weight-medium">
            @icon('tabler-circle-check-filled', ['class' => 'text-md'])
            <span>
                {{ __('waterhole::forum.post-answered-by') }}
                <x-waterhole::user-label :user="$post->answer->user" link />
            </span>
        </div>

        <a
            href="{{ $post->urlAtIndex($post->answer->index) }}#{{ dom_id($post->answer) }}"
            class="btn btn--sm btn--transparent btn--icon -m-xs"
        >
            @icon('tabler-arrow-down')
            <ui-tooltip>
                {{ __('waterhole::forum.post-view-answer-link') }}
            </ui-tooltip>
        </a>
    </div>

    <div class="content color-text" data-controller="lightbox">
        {{ $post->answer->body_html }}
    </div>
</div>
