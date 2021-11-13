<x-waterhole::user-profile :user="Auth::user()" title="Account Settings">
    <div class="card form-groups">
        <div>
            <h4 class="field__label">Username</h4>
            <div>{{ Auth::user()->name }}</div>
        </div>

        <div>
            <h4 class="field__label">Email</h4>
            <turbo-frame id="change-email">
                @if (session('email_status'))
                    <div>
                        <x-waterhole::alert type="success">
                            {!! session('email_status') !!}
                        </x-waterhole::alert>
                    </div>
                @else
                    <form action="{{ route('waterhole.preferences.email') }}" method="POST">
                        @csrf
                        <x-waterhole::field name="email">
                            <div class="toolbar toolbar--nowrap">
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="input grow">
                                <button class="btn">Change</button>
                            </div>
                        </x-waterhole::field>
                    </form>
                @endif
            </turbo-frame>
        </div>

        <div>
            <h4 class="field__label">Password</h4>
            <turbo-frame id="change-password">
                @if (session('password_status'))
                    <div>
                        <x-waterhole::alert type="success">
                            {!! session('password_status') !!}
                        </x-waterhole::alert>
                    </div>
                @else
                    <form action="{{ route('waterhole.preferences.password') }}" method="POST">
                        @csrf
                        <x-waterhole::field name="password">
                            <div class="toolbar toolbar--nowrap">
                                <input type="password" name="password" placeholder="New Password" class="input grow" autocomplete="new-password">
                                <button class="btn">Change</button>
                            </div>
                        </x-waterhole::field>
                    </form>
                @endif
            </turbo-frame>
        </div>

        <div>
            <button class="btn btn--danger">Delete Account</button>
        </div>
    </div>
</x-waterhole::user-profile>
