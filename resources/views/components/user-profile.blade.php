<x-waterhole::layout :title="$title">
    <div class="section container">
        <div class="row gap-xs wrap justify-end" style="float: right; margin-bottom: -50%; margin-left: 50%; position: relative">
            <x-waterhole::action-menu :for="$user" placement="bottom-end">
                <x-slot name="button">
                    <button type="button" class="btn">
                        <x-waterhole::icon icon="tabler-settings"/>
                        <span>{{ __('waterhole::system.controls-button') }}</span>
                        <x-waterhole::icon icon="tabler-chevron-down"/>
                    </button>
                </x-slot>
            </x-waterhole::action-menu>
        </div>

        <div class="row align-start gap-col-xl gap-row-md wrap">
            <x-waterhole::avatar :user="$user" style="width: 12ch"/>

            <div class="grow stack gap-xs" style="flex-basis: 40ch">
                <h1 class="h1" data-page-target="title">
                    {{ $user->name }}
                </h1>

                @if ($user->headline)
                    <p class="h4">{{ $user->headline }}</p>
                @endif

                @if ($user->bio)
                    <p class="content">{{ $user->bio }}</p>
                @endif

                <div class="row gap-sm wrap align-baseline color-muted text-xs">
                    @components(Waterhole\Extend\UserInfo::build(), compact('user'))
                </div>
            </div>
        </div>
    </div>

    <div class="section container with-sidebar">
        <div class="sidebar sidebar--sticky">
            <x-waterhole::responsive-nav
                :components="Waterhole\build_components(Waterhole\Extend\UserNav::build(), compact('user'))"
            />
        </div>

        <div>
            <h2 class="visually-hidden">{{ $title }}</h2>

            {{ $slot }}
        </div>
    </div>
</x-waterhole::layout>
