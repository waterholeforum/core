<x-waterhole::user-link :user="$user" {{ $attributes->class('user-label') }} :link="$link">
    <x-waterhole::avatar :user="$user" />
    <span>{{ Waterhole\username($user) }}</span>
</x-waterhole::user-link>
