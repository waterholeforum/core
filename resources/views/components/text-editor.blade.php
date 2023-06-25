<div
    data-controller="text-editor"
    data-text-editor-format-url-value="{{ route('waterhole.format') }}"
    {{ $attributes->class('input text-editor stack overlay-container') }}
>
    <ui-toolbar
        class="text-editor__toolbar row js-only text-xxs scrollable-x"
        data-controller="watch-scroll"
    >
        @components(Waterhole\Extend\TextEditor::build(), compact('id'))

        <button
            type="button"
            class="btn btn--transparent text-editor__preview-button push-end"
            aria-pressed="false"
            data-action="text-editor#togglePreview"
            data-text-editor-target="previewButton"
        >
            {{ __('waterhole::system.text-editor-preview') }}
        </button>
    </ui-toolbar>

    <div class="text-editor__content grow stack">
        <text-expander
            keys="@"
            data-controller="mentions"
            data-mentions-user-lookup-url-value="{{ $userLookupUrl }}"
            class="text-editor__expander grow stack"
        >
            <textarea
                name="{{ $name }}"
                id="{{ $id }}"
                class="text-editor__input grow content"
                placeholder="{{ $placeholder }}"
                data-controller="uploads"
                data-uploads-url-value="{{ route('waterhole.upload') }}"
                data-text-editor-target="input"
            >
{{ $value }}</textarea
            >
        </text-expander>

        <div
            class="text-editor__preview content overlay busy-spinner"
            data-text-editor-target="preview"
            hidden
        ></div>
    </div>
</div>
