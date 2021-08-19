<x-waterhole::layout-centered :title="__('waterhole::auth.register-title')">
    <h1>{{ __('waterhole::auth.register-title') }}</h1>


    <form action="{{ route('waterhole.register') }}" method="POST">
        @csrf

        <div>
            <label for="name">{{ __('waterhole::auth.name-label') }}</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="email">{{ __('waterhole::auth.email-label') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="password">{{ __('waterhole::auth.password-label') }}</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
            @error('password') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <button type="submit">{{ __('waterhole::auth.register-submit') }}</button>
        </div>
    </form>
</x-waterhole::layout-centered>
