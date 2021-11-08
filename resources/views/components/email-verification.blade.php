<div class="email-verification-notice">
    <div class="container">
        <div>
            We've sent a confirmation email to <strong>{{ auth()->user()->email }}</strong>.
            If it doesn't arrive soon, check your spam folder.
        </div>
        <form action="{{ route('waterhole.verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn--link">Resend</button>
        </form>
    </div>
</div>
