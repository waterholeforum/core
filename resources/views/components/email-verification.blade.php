<div class="email-verification-banner bg-warning-soft text-xs">
    <div class="container row gap-xs">
        <div>
            {!! __('waterhole::auth.email-verification-message', ['email' => '<strong>' . Auth::user()->email . '</strong>']) !!}
        </div>
        <form action="{{ route('waterhole.verify-email.resend') }}" method="POST">
            @csrf
            <button type="submit" class="link weight-bold color-accent">
                {{ __('waterhole::auth.email-verification-resend-button') }}
            </button>
        </form>
    </div>
</div>
