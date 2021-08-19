<x-waterhole::layout-centered :title="__('waterhole::auth.login-title')">
    <h1>{{ __('waterhole::auth.login-title') }}</h1>

    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form action="{{ route('waterhole.login') }}" method="POST">
        @csrf

        <div>
            <label for="email">{{ __('waterhole::auth.email-label') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="password">{{ __('waterhole::auth.password-label') }}</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            @error('password') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                {{ __('waterhole::auth.remember-me-label') }}
            </label>
        </div>

        <div>
            <a href="{{ route('waterhole.password.request') }}">{{ __('waterhole::auth.forgot-password-link') }}</a>
        </div>

        <div>
            <button type="submit">{{ __('waterhole::auth.login-submit') }}</button>
        </div>
    </form>
</x-waterhole::layout-centered>
