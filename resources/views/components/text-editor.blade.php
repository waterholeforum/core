<div
    data-controller="text-editor uploads full-screen"
    data-text-editor-format-url-value="{{ route('waterhole.format') }}"
    data-uploads-url-value="{{ route('waterhole.upload') }}"
    {{
        $attributes->class('input text-editor stack overlay-container')->merge([
            'data-shortcut-scope' => 'editor',
            'data-action' => $attributes->prepends('full-screen:enter->text-editor#fullScreenEnter full-screen:exit->text-editor#fullScreenExit'),
        ])
    }}
>
    <ui-toolbar
        class="text-editor__toolbar row js-only text-xxs scrollable-x no-shrink"
        data-controller="watch-scroll"
    >
        <div class="text-editor__toolbar-content row grow p-xxs">
            @components(\Waterhole\Extend\Ui\TextEditor::class, compact('id'))

            <button
                type="button"
                class="btn btn--transparent text-editor__preview-button push-end"
                aria-pressed="false"
                data-action="text-editor#togglePreview"
                data-text-editor-target="previewButton"
                data-shortcut-trigger="editor.preview"
            >
                {{ __('waterhole::system.text-editor-preview') }}

                <ui-tooltip>
                    {{ __('waterhole::system.text-editor-preview') }}
                    <x-waterhole::shortcut-label shortcut="editor.preview" />
                </ui-tooltip>
            </button>

            <x-waterhole::full-screen-button />
        </div>
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
                data-text-editor-target="input"
                data-uploads-target="input"
                @if ($autofocus) autofocus @endif
            >
{{ $value }}</textarea
            >
        </text-expander>

        <div
            class="text-editor__preview content overlay busy-spinner"
            data-text-editor-target="preview"
            tabindex="-1"
        ></div>
    </div>
</div>
