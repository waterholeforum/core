<div
    data-controller="text-editor"
    {{ $attributes->class('text-editor') }}
>
    <ui-toolbar gap-xs wrap
        class="row wrap text-editor__toolbar js-only"
        data-text-editor-target="row gap-xs wrap"
    >
        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-action="text-editor#format"
            data-text-editor-format-param="header2"
        >
            <x-waterhole::icon icon="go-heading-16"/>
            <ui-tooltip>Heading</ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-hotkey-scope="{{ $id }}"
            data-hotkey="Meta+b"
            data-action="text-editor#format"
            data-text-editor-format-param="bold"
        >
            <x-waterhole::icon icon="go-bold-16"/>
            <ui-tooltip>Bold <small>&lt;cmd-b&gt;</small></ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-hotkey-scope="{{ $id }}"
            data-hotkey="Meta+i"
            data-action="text-editor#format"
            data-text-editor-format-param="italic"
        >
            <x-waterhole::icon icon="go-italic-16"/>
            <ui-tooltip>Italic <small>&lt;cmd-i&gt;</small></ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-hotkey-scope="{{ $id }}"
            data-hotkey="Meta+Shift+."
            data-action="text-editor#format"
            data-text-editor-format-param="blockquote"
        >
            <x-waterhole::icon icon="go-quote-16"/>
            <ui-tooltip>Quote <small>&lt;cmd-shift-.&gt;</small></ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-hotkey-scope="{{ $id }}"
            data-hotkey="Meta+e"
            data-action="text-editor#format"
            data-text-editor-format-param="code"
        >
            <x-waterhole::icon icon="go-code-16"/>
            <ui-tooltip>Code <small>&lt;cmd-e&gt;</small></ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-hotkey-scope="{{ $id }}"
            data-hotkey="Meta+k"
            data-action="text-editor#format"
            data-text-editor-format-param="link"
        >
            <x-waterhole::icon icon="go-link-16"/>
            <ui-tooltip>Link <small>&lt;cmd-k&gt;</small></ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-hotkey-scope="{{ $id }}"
            data-hotkey="Meta+Shift+8"
            data-action="text-editor#format"
            data-text-editor-format-param="unorderedList"
        >
            <x-waterhole::icon icon="go-list-unordered-16"/>
            <ui-tooltip>Bulleted List <small>&lt;cmd-shift-8&gt;</small></ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-hotkey-scope="{{ $id }}"
            data-hotkey="Meta+Shift+7"
            data-action="text-editor#format"
            data-text-editor-format-param="orderedList"
        >
            <x-waterhole::icon icon="go-list-ordered-16"/>
            <ui-tooltip>Numbered List <small>&lt;cmd-shift-7&gt;</small></ui-tooltip>
        </button>

        <button
            type="button"
            class="btn btn--transparent btn--icon"
            data-action="text-editor#format"
            data-text-editor-format-param='{"prefix":"@"}'
        >
            <x-waterhole::icon icon="go-mention-16"/>
            <ui-tooltip>Mention a User</ui-tooltip>
        </button>

        <div class="grow"></div>

        <button
            type="button"
            class="btn btn--transparent text-editor__preview-button"
            aria-pressed="false"
            data-action="text-editor#togglePreview"
            data-text-editor-target="previewButton"
        >Preview</button>
    </ui-toolbar gap-xs wrap>

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
