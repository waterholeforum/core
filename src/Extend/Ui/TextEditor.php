<?php

namespace Waterhole\Extend\Ui;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\AnonymousComponent;
use Waterhole\Extend\Support\ComponentList;
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
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-heading',
                'label' => __('waterhole::system.text-editor-heading'),
                'format' => 'header2',
                'hint' => '#',
            ]),
            'heading',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-bold',
                'label' => __('waterhole::system.text-editor-bold'),
                'format' => 'bold',
                'shortcut' => 'editor.bold',
            ]),
            'bold',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-italic',
                'label' => __('waterhole::system.text-editor-italic'),
                'format' => 'italic',
                'shortcut' => 'editor.italic',
            ]),
            'italic',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-quote',
                'label' => __('waterhole::system.text-editor-quote'),
                'format' => 'blockquote',
                'shortcut' => 'editor.quote',
            ]),
            'quote',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-code',
                'label' => __('waterhole::system.text-editor-code'),
                'format' => 'code',
                'shortcut' => 'editor.code',
            ]),
            'code',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-link',
                'label' => __('waterhole::system.text-editor-link'),
                'format' => 'link',
                'shortcut' => 'editor.link',
            ]),
            'link',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-list',
                'label' => __('waterhole::system.text-editor-bulleted-list'),
                'format' => 'unorderedList',
                'shortcut' => 'editor.bulleted-list',
            ]),
            'bulletedList',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-list-numbers',
                'label' => __('waterhole::system.text-editor-numbered-list'),
                'format' => 'orderedList',
                'shortcut' => 'editor.numbered-list',
            ]),
            'numberedList',
        );

        $this->add(
            fn(string $id) => $this->button($id, [
                'icon' => 'tabler-at',
                'label' => __('waterhole::system.text-editor-mention'),
                'format' => '{"prefix":"@"}',
                'hint' => '@',
            ]),
            'mention',
        );

        $this->add(fn() => new TextEditorEmojiButton(), 'emoji');

        $this->add(
            fn(string $id) => Auth::check()
                ? $this->button($id, [
                    'icon' => 'tabler-paperclip',
                    'label' => __('waterhole::system.text-editor-attachment'),
                    'shortcut' => 'editor.attachment',
                ])->withAttributes(['data-action' => 'uploads#chooseFiles'])
                : null,
            'attachment',
        );
    }

    private function button(string $id, array $data): AnonymousComponent
    {
        return new AnonymousComponent('waterhole::components.text-editor-button', [
            'id' => $id,
            ...$data,
        ]);
    }
}
