<?php

namespace Waterhole\Extend;

use Illuminate\Support\Facades\Auth;
use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\TextEditorButton;
use Waterhole\View\Components\TextEditorEmojiButton;

class TextEditor
{
    use OrderedList;
}

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-heading',
        label: __('waterhole::system.text-editor-heading'),
        id: $id,
        format: 'header2',
    ),
    0,
    'heading',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-bold',
        label: __('waterhole::system.text-editor-bold'),
        id: $id,
        format: 'bold',
        hotkey: 'Meta+b',
    ),
    0,
    'bold',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-italic',
        label: __('waterhole::system.text-editor-italic'),
        id: $id,
        format: 'italic',
        hotkey: 'Meta+i',
    ),
    0,
    'italic',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-quote',
        label: __('waterhole::system.text-editor-quote'),
        id: $id,
        format: 'blockquote',
        hotkey: 'Meta+Shift+.',
    ),
    0,
    'quote',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-code',
        label: __('waterhole::system.text-editor-code'),
        id: $id,
        format: 'code',
        hotkey: 'Meta+e',
    ),
    0,
    'code',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-link',
        label: __('waterhole::system.text-editor-link'),
        id: $id,
        format: 'link',
        hotkey: 'Meta+k',
    ),
    0,
    'link',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-list',
        label: __('waterhole::system.text-editor-bulleted-list'),
        id: $id,
        format: 'unorderedList',
        hotkey: 'Meta+Shift+8',
    ),
    0,
    'bulletedList',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-list-numbers',
        label: __('waterhole::system.text-editor-numbered-list'),
        id: $id,
        format: 'orderedList',
        hotkey: 'Meta+Shift+7',
    ),
    0,
    'numberedList',
);

TextEditor::add(
    fn(string $id) => new TextEditorButton(
        icon: 'tabler-at',
        label: __('waterhole::system.text-editor-mention'),
        id: $id,
        format: '{"prefix":"@"}',
    ),
    0,
    'mention',
);

TextEditor::add(fn() => new TextEditorEmojiButton(), 0, 'emoji');

TextEditor::add(
    fn(string $id) => Auth::check()
        ? (new TextEditorButton(
            icon: 'tabler-paperclip',
            label: __('waterhole::system.text-editor-attachment'),
        ))->withAttributes(['data-action' => 'text-editor#chooseFiles'])
        : null,
    0,
    'attachment',
);
