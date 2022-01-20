<turbo-frame id="modal">
    <x-waterhole::dialog
        title="Update"
        class="dialog--xl"
        data-controller="process"
    >
        <x-slot name="header">
            <form
                action="{{ route('waterhole.admin.updates.run') }}"
                method="post"
                data-controller="auto-submit"
                data-action="turbo:submit-start->process#start turbo:submit-end->process#finish"
            >
                @csrf
                @foreach ((array) request('packages') as $package)
                    <input type="hidden" name="packages[]" value="{{ $package }}">
                @endforeach

                <div
                    data-process-target="loading"
                    class="loading-indicator loading-indicator--inline"
                ></div>

                <button
                    type="button"
                    class="btn btn--transparent btn--icon dialog__close"
                    data-action="modal#hide"
                    data-process-target="done"
                    hidden
                >
                    <x-waterhole::icon icon="heroicon-o-x"/>
                </button>
            </form>
        </x-slot>

        <turbo-frame
            id="composer_output"
            src="{{ route('waterhole.admin.updates.output') }}"
            hidden
            disabled
            data-process-target="output"
            style="background: #000; overflow: auto; height: 60vh; white-space: pre-wrap; font-family: var(--font-mono); font-size: var(--text-xxs); padding: var(--space-md); border-radius: var(--border-radius-sm); line-height: 1.2"
        ></turbo-frame>
    </x-waterhole::dialog>
</turbo-frame>
