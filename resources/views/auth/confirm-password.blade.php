<x-waterhole::layout :title="__('waterhole::auth.confirm-password-title')">
    <div class="container section">
        <x-waterhole::dialog
            :title="__('waterhole::auth.confirm-password-title')"
            class="dialog--sm"
        >
            {{--
                Opt-out of Turbo so that any fragment that may be present in the
                redirect URL will be followed.
            --}}
            <form method="POST" action="{{ route('waterhole.confirm-password') }}">
                @csrf

                <div class="stack gap-xl">
                    <p class="content">
                        {{ __('waterhole::auth.confirm-password-introduction') }}
                    </p>

                    <x-waterhole::field
                        name="password"
                        :label="__('waterhole::auth.password-label')"
                    >
                        <input
                            class="input"
                            type="password"
                            id="{{ $component->id }}"
                            name="password"
                            required
                            autocomplete="current-password"
                            autofocus
                        >
                    </x-waterhole::field>

                    <button type="submit" class="btn bg-accent full-width">
                        {{ __('waterhole::auth.confirm-password-submit') }}
                    </button>
                </div>
            </form>
        </x-waterhole::dialog>
    </div>
</x-waterhole::layout>
