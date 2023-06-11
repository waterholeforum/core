@php
    $title = __('waterhole::user.notification-preferences-title');
@endphp

<x-waterhole::user-profile :user="Auth::user()" :title="$title">
    <h2 class="visually-hidden">{{ $title }}</h2>

    <form action="{{ route('waterhole.preferences.notifications') }}" method="post">
        @csrf

        <div class="stack gap-md">
            <x-waterhole::validation-errors />

            <div class="card card__body stack dividers">
                <div class="field">
                    <h4 class="field__label">
                        {{ __('waterhole::user.notifications-label') }}
                    </h4>
                    <div class="notification-grid card">
                        @foreach (Waterhole\Extend\NotificationTypes::build() as $type)
                            @php
                                $channels = (array) old('notification_channels.' . $type, Auth::user()->notification_channels[$type] ?? [])
                            @endphp

                            <div class="card__row row gap-xs">
                                <div>
                                    {{ $type::description() }}
                                </div>
                                <div class="push-end row">
                                    <label class="choice">
                                        <input
                                            name="notification_channels[{{ $type }}][]"
                                            type="checkbox"
                                            value="database"
                                            @checked(in_array('database', $channels))
                                        />
                                        {{ __('waterhole::user.notification-channel-web') }}
                                    </label>
                                    <label class="choice">
                                        <input
                                            type="checkbox"
                                            name="notification_channels[{{ $type }}][]"
                                            value="mail"
                                            @checked(in_array('mail', $channels))
                                        />
                                        {{ __('waterhole::user.notification-channel-email') }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="field">
                    <h4 class="field__label">
                        {{ __('waterhole::user.notifications-following-label') }}
                    </h4>
                    <div>
                        <input type="hidden" name="follow_on_comment" value="0" />
                        <label class="choice">
                            <input
                                type="checkbox"
                                name="follow_on_comment"
                                value="1"
                                @checked(Auth::user()->follow_on_comment)
                            />
                            {{ __('waterhole::user.follow-on-comment-label') }}
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn bg-accent btn--wide">
                        {{ __('waterhole::system.save-changes-button') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-waterhole::user-profile>
