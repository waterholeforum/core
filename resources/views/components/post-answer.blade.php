<div class="bg-success-soft color-inherit rounded p-lg stack align-start gap-md">
    <div class="with-icon color-success weight-medium">
        <x-waterhole::icon icon="tabler-circle-check-filled" class="text-md"/>
        <span>
            {{ __('waterhole::forum.post-answered-by') }}
            <x-waterhole::user-label :user="$post->answer->user" link/>
        </span>
    </div>

    <div class="content">
        {{ Waterhole\emojify($post->answer->body_html) }}
    </div>

    <a href="{{ $post->answer->post_url }}" class="with-icon weight-medium">
        <x-waterhole::icon icon="tabler-arrow-down"/>
        {{ __('waterhole::forum.post-view-answer-link') }}
    </a>
</div>
