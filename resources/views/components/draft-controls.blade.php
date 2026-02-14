<turbo-frame id="{{ $frame }}" {{ $attributes }}>
    <div class="row gap-xs align-center text-xs color-muted">
        <span class="with-icon" data-draft-target="saving" hidden>
            {{ __('waterhole::forum.draft-saving-message') }}
        </span>

        <span
            class="with-icon color-success weight-medium"
            data-draft-target="saved"
            @unless($saved) hidden @endunless
        >
            @icon('tabler-check', ['class' => 'icon--thick'])
            {{ __('waterhole::forum.draft-saved-message') }}
        </span>

        <span class="color-danger weight-medium" data-draft-target="error" hidden>
            {{ __('waterhole::forum.draft-error-message') }}
        </span>

        <button
            class="btn btn--transparent no-js-only"
            data-draft-target="saveButton"
            formaction="{{ $action }}"
            formmethod="POST"
            data-turbo-frame="{{ $frame }}"
            name="draft_action"
            type="submit"
            value="save"
        >
            {{ __('waterhole::forum.save-draft-button') }}
        </button>

        <button
            class="btn btn--transparent btn--icon"
            formaction="{{ $action }}"
            formmethod="POST"
            data-turbo-frame="{{ $frame }}"
            name="_method"
            type="submit"
            value="DELETE"
        >
            @icon('tabler-trash')
            <ui-tooltip>{{ __('waterhole::forum.discard-draft-button') }}</ui-tooltip>
        </button>
    </div>
</turbo-frame>
