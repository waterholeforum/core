<x-waterhole::layout :title="__('waterhole::auth.forgot-password-title')">
    <div class="section">
        <x-waterhole::dialog
            :title="__('waterhole::auth.forgot-password-title')"
            class="dialog--sm"
        >
            <div class="stack gap-lg">
                <p class="content">
                    {{ __('waterhole::auth.forgot-password-introduction') }}
                </p>

                @if (session('status'))
                    <x-waterhole::alert type="success">
                        {{ session('status') }}
                    </x-waterhole::alert>
                @else
                    <form action="{{ route('waterhole.forgot-password') }}" method="POST">
                        @csrf

                        <div class="stack gap-lg">
                            <x-waterhole::field
                                name="email"
                                :label="__('waterhole::auth.email-label')"
                            >
                                <input
                                    type="email"
                                    id="{{ $component->id }}"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                >
                            </x-waterhole::field>

                            <button type="submit" class="btn bg-accent block">
                                {{ __('waterhole::auth.forgot-password-submit') }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>
