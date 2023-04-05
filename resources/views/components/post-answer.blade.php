<div class="bg-success-soft rounded p-lg stack align-start gap-md">
    <div class="with-icon weight-medium">
        @icon('tabler-circle-check-filled', ['class' => 'text-md'])
        <span>
            {{ __('waterhole::forum.post-answered-by') }}
            <x-waterhole::user-label :user="$post->answer->user" link/>
        </span>
    </div>

    <div class="content color-text">
        {{ Waterhole\emojify($post->answer->body_html) }}
    </div>

    <a href="{{ $post->answer->post_url }}" class="with-icon weight-medium">
        @icon('tabler-arrow-down')
        {{ __('waterhole::forum.post-view-answer-link') }}
    </a>
</div>
