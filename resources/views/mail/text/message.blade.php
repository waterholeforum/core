<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="route('waterhole.home')">
            {{ config('waterhole.forum.name') }}
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <x-mail::subcopy>
                {{ $subcopy }}
            </x-mail::subcopy>
        </x-slot:subcopy>
    @endisset
</x-mail::layout>
