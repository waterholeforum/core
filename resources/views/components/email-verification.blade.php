<div class="email-verification-banner bg-warning-soft text-xs">
    <form
        action="{{ route('waterhole.verify-email.resend') }}"
        method="POST"
        class="container content"
    >
        @csrf

        {!!
            __('waterhole::auth.email-verification-sent-message', [
                'email' => '<strong>' . e(Auth::user()->email) . '</strong>',
            ])
        !!}

        <button type="submit" class="link weight-bold color-accent">
            {{ __('waterhole::auth.email-verification-resend-button') }}
        </button>
    </form>
</div>
