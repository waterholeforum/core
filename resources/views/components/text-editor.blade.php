<div
    data-controller="text-editor"
    {{ $attributes->class('text-editor') }}
>
    <ui-toolbar class="row scrollable text-editor__toolbar js-only">
        @components(Waterhole\Extend\TextEditor::build(), compact('id'))

        <div class="grow"></div>

        <button
            type="button"
            class="btn btn--transparent text-editor__preview-button"
            aria-pressed="false"
            data-action="text-editor#togglePreview"
            data-text-editor-target="previewButton"
        >
            {{ __('waterhole::system.text-editor-preview') }}
        </button>
    </ui-toolbar>

    <div class="text-editor__content">
        <text-expander
            keys="@"
            data-text-editor-target="expander"
            class="text-editor__expander"
        >
            <textarea
                name="{{ $name }}"
                id="{{ $id }}"
                class="text-editor__input content js-session-resumable"
                data-text-editor-target="input"
                placeholder="{{ $placeholder }}"
            >{{ $value }}</textarea>
        </text-expander>

        <div
            class="text-editor__preview content"
            data-text-editor-target="preview"
            hidden
        ></div>
    </div>
</div>
