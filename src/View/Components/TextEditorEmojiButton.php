<?php

namespace Waterhole\View\Components;

use Illuminate\View\Component;

class TextEditorEmojiButton extends Component
{
    public function render(): string
    {
        return <<<'blade'
            <ui-popup
                class="hide-sm"
                data-controller="emoji-picker"
            >
                <button
                    type="button"
                    class="btn btn--transparent btn--icon"
                >
                    @icon('tabler-mood-smile')
                    <ui-tooltip>{{ __('waterhole::system.text-editor-emoji') }}</ui-tooltip>
                </button>

                <div class="menu emoji-picker" hidden>
                    <emoji-picker data-action="emoji-click->text-editor#insertEmoji"></emoji-picker>
                </div>
            </ui-popup>
        blade;
    }
}
