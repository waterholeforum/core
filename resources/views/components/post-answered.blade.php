<a
    href="{{ $post->url }}#{{ dom_id($post, 'answer') }}"
    class="badge bg-success-soft"
>
    @icon('tabler-check')
    <span>{{ __('waterhole::forum.post-answered-badge') }}</span>
</a>
