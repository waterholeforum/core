<x-waterhole::user-profile :user="Auth::user()" title="Notification Preferences">
    <form action="{{ route('waterhole.preferences.notifications') }}" method="post">
        @csrf

        <div class="stack-md">
            <x-waterhole::validation-errors/>

            <div class="card form-groups">
                <div>
                    <h4 class="field__label">Notifications</h4>
                    <div class="notification-grid card card-list">
                        @foreach (Waterhole\Extend\NotificationTypes::build() as $type)
                            @php $channels = (array) old('notification_channels.'.$type, Auth::user()->notification_channels[$type] ?? []) @endphp
                            <div class="toolbar">
                                <div>{{ $type::description() }}</div>
                                <div class="spacer"></div>
                                <label class="choice">
                                    <input type="checkbox" name="notification_channels[{{ $type }}][]" value="database" @if (in_array('database', $channels)) checked @endif> Web
                                </label>
                                <label class="choice">
                                    <input type="checkbox" name="notification_channels[{{ $type }}][]" value="mail" @if (in_array('mail', $channels)) checked @endif> Email
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="field__label">Following</h4>
                    <div>
                        <input type="hidden" name="follow_on_comment" value="0">
                        <label class="choice">
                            <input type="checkbox" name="follow_on_comment" value="1" @if (Auth::user()->follow_on_comment) checked @endif> Automatically follow posts I comment on
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn--primary btn--wide">Save Changes</button>
                </div>
            </div>
            </div>
    </form>
</x-waterhole::user-profile>
