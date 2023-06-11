<x-mail::layout>
    {{-- Header --}}
    <x-slot name="header">
        <x-mail::header :url="route('waterhole.home')">
            {{ config('waterhole.forum.name') }}
        </x-mail::header>
    </x-slot>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot name="subcopy">
            <x-mail::subcopy>
                {{ $subcopy }}
            </x-mail::subcopy>
        </x-slot>
    @endisset
</x-mail::layout>
