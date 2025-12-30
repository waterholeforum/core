<?php

namespace Waterhole\Extend\Ui;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Support\ComponentList;
use Waterhole\View\Components\TextEditorButton;
use Waterhole\View\Components\TextEditorEmojiButton;

/**
 * Toolbar buttons and controls for the text editor UI.
 *
 * Use this extender to add, remove, or reorder components rendered in this
 * region of the UI.
 */
class TextEditor extends ComponentList
{
    public function __construct()
    {
        $this->add(
            'heading',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-heading',
                label: __('waterhole::system.text-editor-heading'),
                id: $id,
                format: 'header2',
            ),
        );

        $this->add(
            'bold',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-bold',
                label: __('waterhole::system.text-editor-bold'),
                id: $id,
                format: 'bold',
                hotkey: 'Meta+b',
            ),
        );

        $this->add(
            'italic',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-italic',
                label: __('waterhole::system.text-editor-italic'),
                id: $id,
                format: 'italic',
                hotkey: 'Meta+i',
            ),
        );

        $this->add(
            'quote',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-quote',
                label: __('waterhole::system.text-editor-quote'),
                id: $id,
                format: 'blockquote',
                hotkey: 'Meta+Shift+.',
            ),
        );

        $this->add(
            'code',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-code',
                label: __('waterhole::system.text-editor-code'),
                id: $id,
                format: 'code',
                hotkey: 'Meta+e',
            ),
        );

        $this->add(
            'link',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-link',
                label: __('waterhole::system.text-editor-link'),
                id: $id,
                format: 'link',
                hotkey: 'Meta+k',
            ),
        );

        $this->add(
            'bulletedList',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-list',
                label: __('waterhole::system.text-editor-bulleted-list'),
                id: $id,
                format: 'unorderedList',
                hotkey: 'Meta+Shift+8',
            ),
        );

        $this->add(
            'numberedList',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-list-numbers',
                label: __('waterhole::system.text-editor-numbered-list'),
                id: $id,
                format: 'orderedList',
                hotkey: 'Meta+Shift+7',
            ),
        );

        $this->add(
            'mention',
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-at',
                label: __('waterhole::system.text-editor-mention'),
                id: $id,
                format: '{"prefix":"@"}',
            ),
        );

        $this->add('emoji', fn() => new TextEditorEmojiButton());

        $this->add(
            'attachment',
            fn(string $id) => Auth::check()
                ? (new TextEditorButton(
                    icon: 'tabler-paperclip',
                    label: __('waterhole::system.text-editor-attachment'),
                ))->withAttributes(['data-action' => 'uploads#chooseFiles'])
                : null,
        );
    }
}
