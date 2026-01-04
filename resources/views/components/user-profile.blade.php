<x-waterhole::layout :title="$title">
    <div class="section container user-profile stack gap-gutter">
        <div class="card card__body user-profile__card">
            <div class="user-profile__controls">
                <x-waterhole::action-menu
                    :for="$user"
                    placement="bottom-end"
                    :button-attributes="['class' => 'btn']"
                >
                    <x-slot name="button">
                        @icon('tabler-settings')
                        <span>{{ __('waterhole::system.controls-button') }}</span>
                        @icon('tabler-chevron-down')
                    </x-slot>
                </x-waterhole::action-menu>
            </div>

            <div class="row align-start gap-x-xl gap-y-md wrap">
                <x-waterhole::avatar :user="$user" />

                <div class="grow stack gap-xs user-profile__content">
                    <h1 class="h1 user-profile__name" data-page-target="title">
                        {{ Waterhole\username($user) }}
                    </h1>

                    @if ($user->headline)
                        <p class="h4 user-profile__headline">
                            {{ Waterhole\emojify($user->headline) }}
                        </p>
                    @endif

                    @if ($user->bio_html)
                        <div class="content user-profile__bio">{{ $user->bio_html }}</div>
                    @endif

                    <div
                        class="row gap-sm wrap align-center color-muted text-xs user-profile__info"
                    >
                        @components(\Waterhole\Extend\Ui\UserInfo::class, compact('user'))
                    </div>
                </div>
            </div>
        </div>

        <div class="with-sidebar">
            <div class="sidebar sidebar--sticky">
                <x-waterhole::collapsible-nav
                    :components="Waterhole\build_components(\Waterhole\Extend\Ui\UserNav::class, compact('user'))"
                />
            </div>

            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</x-waterhole::layout>
