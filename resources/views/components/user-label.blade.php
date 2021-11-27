@php
    $tag = 'span';
    $attributes = $attributes->class('user-label');

    if ($link && ($href = $user->url)) {
        $tag = 'a';
        $attributes = $attributes->merge(compact('href'));
    }
@endphp

<{{ $tag }} {{ $attributes }}>
    <x-waterhole::avatar :user="$user"/>
    <span>{{ $user?->name ?: 'Anonymous' }}</span>
</{{ $tag }}>
