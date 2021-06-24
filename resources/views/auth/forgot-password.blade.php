@extends('waterhole::centered')

@section('title', __('waterhole::auth.forgot-password-title'))

@section('content')
    <h1>{{ __('waterhole::auth.forgot-password-title') }}</h1>

    <p>{{ __('waterhole::auth.forgot-password-introduction') }}</p>

    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form action="{{ route('waterhole.password.email') }}" method="POST">
        @csrf

        <div>
            <label for="email">{{ __('waterhole::auth.email-label') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div>{{ $message }}</div> @enderror
        </div>

        <div>
            <button type="submit">{{ __('waterhole::auth.forgot-password-submit') }}</button>
        </div>
    </form>
@endsection
