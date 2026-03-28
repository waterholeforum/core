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
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-heading',
                label: __('waterhole::system.text-editor-heading'),
                id: $id,
                format: 'header2',
                hint: '#',
            ),
            'heading',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-bold',
                label: __('waterhole::system.text-editor-bold'),
                id: $id,
                format: 'bold',
                shortcut: 'editor.bold',
            ),
            'bold',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-italic',
                label: __('waterhole::system.text-editor-italic'),
                id: $id,
                format: 'italic',
                shortcut: 'editor.italic',
            ),
            'italic',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-quote',
                label: __('waterhole::system.text-editor-quote'),
                id: $id,
                format: 'blockquote',
                shortcut: 'editor.quote',
            ),
            'quote',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-code',
                label: __('waterhole::system.text-editor-code'),
                id: $id,
                format: 'code',
                shortcut: 'editor.code',
            ),
            'code',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-link',
                label: __('waterhole::system.text-editor-link'),
                id: $id,
                format: 'link',
                shortcut: 'editor.link',
            ),
            'link',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-list',
                label: __('waterhole::system.text-editor-bulleted-list'),
                id: $id,
                format: 'unorderedList',
                shortcut: 'editor.bulleted-list',
            ),
            'bulletedList',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-list-numbers',
                label: __('waterhole::system.text-editor-numbered-list'),
                id: $id,
                format: 'orderedList',
                shortcut: 'editor.numbered-list',
            ),
            'numberedList',
        );

        $this->add(
            fn(string $id) => new TextEditorButton(
                icon: 'tabler-at',
                label: __('waterhole::system.text-editor-mention'),
                id: $id,
                format: '{"prefix":"@"}',
                hint: '@',
            ),
            'mention',
        );

        $this->add(fn() => new TextEditorEmojiButton(), 'emoji');

        $this->add(
            fn(string $id) => Auth::check()
                ? (new TextEditorButton(
                    icon: 'tabler-paperclip',
                    label: __('waterhole::system.text-editor-attachment'),
                    shortcut: 'editor.attachment',
                ))->withAttributes(['data-action' => 'uploads#chooseFiles'])
                : null,
            'attachment',
        );
    }
}
