<x-waterhole::layout :title="$title">
    <div class="section container user-profile">
        <div class="row gap-xs wrap justify-end user-profile__controls">
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

        <div class="row align-start gap-col-xl gap-row-md wrap user-profile__inner">
            <x-waterhole::avatar :user="$user"/>

            <div class="grow stack gap-xs user-profile__content">
                <h1 class="h1 user-profile__name" data-page-target="title">
                    {{ $user->name }}
                </h1>

                @if ($user->headline)
                    <p class="h4 user-profile__headline">{{ $user->headline }}</p>
                @endif

                @if ($user->bio)
                    <p class="content user-profile__bio">{{ $user->bio }}</p>
                @endif

                <div class="row gap-sm wrap align-baseline color-muted text-xs user-profile__info">
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
