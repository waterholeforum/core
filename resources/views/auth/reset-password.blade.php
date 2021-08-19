<x-waterhole::layout-centered :title="__('waterhole::auth.reset-password-title')">
    <h1>{{ __('waterhole::auth.reset-password-title') }}</h1>

    <form action="{{ route('waterhole.password.update') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email">{{ __('waterhole::auth.email-label') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
            @error('email') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="password">{{ __('waterhole::auth.new-password-label') }}</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
            @error('password') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="password_confirmation">{{ __('waterhole::auth.confirm-password-label') }}</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <button type="submit">{{ __('waterhole::auth.reset-password-submit') }}</button>
        </div>
    </form>
</x-waterhole::layout-centered>
