<div
    data-controller="text-editor"
    data-text-editor-format-url-value="{{ route('waterhole.format') }}"
    data-text-editor-user-lookup-url-value="{{ route('waterhole.user-lookup') }}"
    data-text-editor-upload-url-value="{{ route('waterhole.upload') }}"
    {{ $attributes->class('input text-editor stack overlay-container') }}
>
    <ui-toolbar class="row text-editor__toolbar js-only text-xxs scrollable-x">
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
            data-text-editor-target="expander"
            class="text-editor__expander grow stack"
        >
            <textarea
                name="{{ $name }}"
                id="{{ $id }}"
                class="text-editor__input grow content"
                data-text-editor-target="input"
                placeholder="{{ $placeholder }}"
            >{{ $value }}</textarea>
        </text-expander>

        <div
            class="text-editor__preview content overlay"
            data-text-editor-target="preview"
            hidden
        ></div>
    </div>
</div>
