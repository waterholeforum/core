<x-waterhole::attribution
    :user="$post->user"
    :date="$post->created_at"
    :permalink="$post->url"
    :edit-date="$post->edited_at"
    :primary-target="request()->routeIs('waterhole.posts.show')"
/>
