<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\OrderedList;
use Waterhole\View\Components\TextEditorButton;

class TextEditor
{
    use OrderedList;
}

TextEditor::add(
    'heading',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-heading',
        label: __('waterhole::system.text-editor-heading'),
        format: 'header2',
    ),
);

TextEditor::add(
    'bold',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-bold',
        label: __('waterhole::system.text-editor-bold'),
        format: 'bold',
        hotkey: 'Meta+b',
    ),
);

TextEditor::add(
    'italic',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-italic',
        label: __('waterhole::system.text-editor-italic'),
        format: 'italic',
        hotkey: 'Meta+i',
    ),
);

TextEditor::add(
    'quote',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-quote',
        label: __('waterhole::system.text-editor-quote'),
        format: 'blockquote',
        hotkey: 'Meta+Shift+.',
    ),
);

TextEditor::add(
    'code',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-code',
        label: __('waterhole::system.text-editor-code'),
        format: 'code',
        hotkey: 'Meta+e',
    ),
);

TextEditor::add(
    'link',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-link',
        label: __('waterhole::system.text-editor-link'),
        format: 'link',
        hotkey: 'Meta+k',
    ),
);

TextEditor::add(
    'bulletedList',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-list',
        label: __('waterhole::system.text-editor-bulleted-list'),
        format: 'unorderedList',
        hotkey: 'Meta+Shift+8',
    ),
);

TextEditor::add(
    'numberedList',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-list-numbers',
        label: __('waterhole::system.text-editor-numbered-list'),
        format: 'orderedList',
        hotkey: 'Meta+Shift+7',
    ),
);

TextEditor::add(
    'mention',
    fn(string $id) => new TextEditorButton(
        id: $id,
        icon: 'tabler-at',
        label: __('waterhole::system.text-editor-mention'),
        format: '{"prefix":"@"}',
    ),
);
