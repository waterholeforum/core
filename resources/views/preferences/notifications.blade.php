@php
    $title = __('waterhole::user.notification-preferences-title');
@endphp

<x-waterhole::user-profile :user="Auth::user()" :title="$title">
    <h2 class="visually-hidden">{{ $title }}</h2>

    <x-waterhole::form
        :submit-label="__('waterhole::system.save-changes-button')"
        action="{{ route('waterhole.preferences.notifications') }}"
        data-controller="dirty-form"
    >
        <div class="card card__body stack dividers">
            <div class="field">
                <h4 class="field__label">
                    {{ __('waterhole::user.notifications-label') }}
                </h4>
                <div class="notification-grid card">
                    @foreach (resolve(Waterhole\Extend\Core\NotificationTypes::class)->values() as $type)
                        @continue(! $type::availableFor(Auth::user()))
                        @php
                            $userChannels = (array) old('notification_channels.' . $type, Auth::user()->notification_channels[$type] ?? []);
                            $supportedChannels = $type::channels();
                            $channels = ['web' => 'database', 'email' => 'mail'];
                        @endphp

                        <div class="card__row row gap-xs">
                            <div>
                                {{ $type::description() }}
                            </div>
                            <div class="push-end row">
                                @foreach ($channels as $key => $channel)
                                    @if (in_array($channel, $supportedChannels))
                                        <label class="choice">
                                            <input
                                                name="notification_channels[{{ $type }}][]"
                                                type="checkbox"
                                                value="{{ $channel }}"
                                                @checked(in_array($channel, $userChannels))
                                            />
                                            {{ __("waterhole::user.notification-channel-$key") }}
                                        </label>
                                    @else
                                        <span class="choice"></span>
                                    @endif
                                @endforeach
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
        </div>
    </x-waterhole::form>
</x-waterhole::user-profile>
