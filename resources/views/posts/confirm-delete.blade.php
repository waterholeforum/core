@foreach ($posts as $post)
    <div class="card">
        <div class="post-summary">
            <x-waterhole::avatar :user="$post->user" class="post-summary__avatar"/>
            <div class="post-summary__content">
                <h3 class="post-summary__title">
                    {{ $post->title }}
                </h3>
                <div class="post-summary__info">
                    <x-waterhole::channel-label :channel="$post->channel"/>
                </div>
            </div>
        </div>
    </div>
@endforeach
