<?php

namespace Waterhole\Extend\Ui;

use Closure;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Waterhole\Extend\Core\Actions;
use Waterhole\Extend\Support\OrderedList;
use Waterhole\Ui\KeyboardShortcut;
use Waterhole\View\Components\CreatePostButton;

/**
 * Keyboard shortcuts available in the forum UI.
 *
 * Use this extender to add, remove, or reorder shortcuts.
 */
class KeyboardShortcuts extends OrderedList
{
    private ?array $resolved = null;

    public function __construct()
    {
        $this->add(
            new KeyboardShortcut(
                id: 'navigation.shortcuts',
                keys: ['Shift+?'],
                description: __('waterhole::system.keyboard-shortcuts-reference-description'),
                category: 'navigation',
            ),
            'navigation.shortcuts',
        );

        $this->add(
            fn() => config('waterhole.system.search_engine')
                ? new KeyboardShortcut(
                    id: 'navigation.search',
                    keys: ['/'],
                    description: __('waterhole::system.keyboard-shortcuts-search-description'),
                    category: 'navigation',
                )
                : null,
            'navigation.search',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'navigation.close',
                keys: ['Escape'],
                description: __('waterhole::system.keyboard-shortcuts-close-description'),
                category: 'navigation',
                scopes: ['surface', 'editor', 'global'],
            ),
            'navigation.close',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'navigation.home',
                keys: ['g h'],
                description: __('waterhole::system.keyboard-shortcuts-home-description'),
                category: 'navigation',
            ),
            'navigation.home',
        );

        $this->add(
            fn() => Auth::check()
                ? new KeyboardShortcut(
                    id: 'navigation.user-menu',
                    keys: ['g u'],
                    description: __('waterhole::system.keyboard-shortcuts-user-menu-description'),
                    category: 'navigation',
                )
                : null,
            'navigation.user-menu',
        );

        $this->add(
            fn() => Auth::check()
                ? new KeyboardShortcut(
                    id: 'navigation.notifications',
                    keys: ['g n'],
                    description: __(
                        'waterhole::system.keyboard-shortcuts-notifications-description',
                    ),
                    category: 'navigation',
                )
                : null,
            'navigation.notifications',
        );

        $this->add(
            fn() => Auth::check()
                ? new KeyboardShortcut(
                    id: 'navigation.saved',
                    keys: ['g s'],
                    description: __('waterhole::system.keyboard-shortcuts-saved-description'),
                    category: 'navigation',
                )
                : null,
            'navigation.saved',
        );

        $this->add(
            fn() => Auth::user()?->can('waterhole.moderate')
                ? new KeyboardShortcut(
                    id: 'navigation.moderation',
                    keys: ['g m'],
                    description: __('waterhole::system.keyboard-shortcuts-moderation-description'),
                    category: 'navigation',
                )
                : null,
            'navigation.moderation',
        );

        $this->add(
            fn() => Auth::check() &&
            CreatePostButton::resolveTarget(Auth::user())['response']->allowed()
                ? new KeyboardShortcut(
                    id: 'navigation.create-post',
                    keys: ['g p'],
                    description: __('waterhole::system.keyboard-shortcuts-create-post-description'),
                    category: 'navigation',
                )
                : null,
            'navigation.create-post',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.next',
                keys: ['j'],
                description: __('waterhole::system.keyboard-shortcuts-next-description'),
                category: 'discussion',
            ),
            'selection.next',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.previous',
                keys: ['k'],
                description: __('waterhole::system.keyboard-shortcuts-previous-description'),
                category: 'discussion',
            ),
            'selection.previous',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.next-page',
                keys: ['Shift+J'],
                description: __('waterhole::system.keyboard-shortcuts-next-page-description'),
                category: 'discussion',
            ),
            'selection.next-page',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.previous-page',
                keys: ['Shift+K'],
                description: __('waterhole::system.keyboard-shortcuts-previous-page-description'),
                category: 'discussion',
            ),
            'selection.previous-page',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.first',
                keys: ['g t'],
                description: __('waterhole::system.keyboard-shortcuts-top-description'),
                category: 'discussion',
            ),
            'selection.first',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.last',
                keys: ['g b'],
                description: __('waterhole::system.keyboard-shortcuts-bottom-description'),
                category: 'discussion',
            ),
            'selection.last',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.open',
                keys: ['o'],
                description: __('waterhole::system.keyboard-shortcuts-open-description'),
                category: 'discussion',
                scopes: ['selection'],
            ),
            'selection.open',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.actions',
                keys: ['a'],
                description: __('waterhole::system.keyboard-shortcuts-actions-description'),
                category: 'discussion',
                scopes: ['selection'],
            ),
            'selection.actions',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.reply',
                keys: ['r'],
                description: __('waterhole::forum.comment-reply-button'),
                category: 'discussion',
                scopes: ['selection'],
            ),
            'selection.reply',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'selection.react',
                keys: ['l'],
                description: __('waterhole::forum.add-reaction-button'),
                category: 'discussion',
                scopes: ['selection'],
            ),
            'selection.react',
        );

        $this->add(
            new KeyboardShortcut(
                id: 'form.submit',
                keys: ['$mod+Enter'],
                description: __('waterhole::system.keyboard-shortcuts-submit-description'),
                category: 'editor',
                scopes: ['form', 'editor'],
            ),
            'form.submit',
        );

        $editorShortcuts = [
            'editor.discard' => [
                '$mod+Shift+D',
                'waterhole::forum.discard-draft-button',
                ['global', 'editor'],
            ],
            'editor.bold' => ['$mod+B', 'waterhole::system.text-editor-bold', ['editor']],
            'editor.italic' => ['$mod+I', 'waterhole::system.text-editor-italic', ['editor']],
            'editor.quote' => ['$mod+Shift+>', 'waterhole::system.text-editor-quote', ['editor']],
            'editor.code' => ['$mod+E', 'waterhole::system.text-editor-code', ['editor']],
            'editor.link' => ['$mod+K', 'waterhole::system.text-editor-link', ['editor']],
            'editor.attachment' => [
                '$mod+Shift+A',
                'waterhole::system.text-editor-attachment',
                ['editor'],
            ],
            'editor.bulleted-list' => [
                '$mod+Shift+*',
                'waterhole::system.text-editor-bulleted-list',
                ['editor'],
            ],
            'editor.numbered-list' => [
                '$mod+Shift+&',
                'waterhole::system.text-editor-numbered-list',
                ['editor'],
            ],
            'editor.preview' => [
                '$mod+Shift+P',
                'waterhole::system.text-editor-preview',
                ['editor'],
            ],
            'editor.full-screen' => [
                '$mod+Shift+F',
                'waterhole::system.full-screen-enter-button',
                ['editor'],
            ],
        ];

        foreach ($editorShortcuts as $id => [$keys, $label, $scopes]) {
            $this->add(
                new KeyboardShortcut(
                    id: $id,
                    keys: [$keys],
                    description: __($label),
                    category: 'editor',
                    scopes: $scopes,
                ),
                $id,
            );
        }
    }

    public function shortcuts(): array
    {
        if ($this->resolved !== null) {
            return $this->resolved;
        }

        $shortcuts = array_values(
            array_filter(
                array_map(function ($shortcut) {
                    if ($shortcut instanceof Closure) {
                        $shortcut = app()->call($shortcut);
                    }

                    return $shortcut instanceof KeyboardShortcut ? $shortcut : null;
                }, $this->items()),
            ),
        );

        if (Auth::check()) {
            $shortcuts = [...$shortcuts, ...resolve(Actions::class)->shortcuts()];
        }

        $duplicateIds = collect($shortcuts)
            ->countBy(fn(KeyboardShortcut $shortcut) => $shortcut->id)
            ->filter(fn(int $count) => $count > 1)
            ->keys();

        if ($duplicateIds->isNotEmpty()) {
            $duplicateId = $duplicateIds->first();

            throw new InvalidArgumentException(
                "Duplicate keyboard shortcut [{$duplicateId}]. Add extra bindings to the shortcut's keys array instead of registering the same id multiple times.",
            );
        }

        return $this->resolved = $shortcuts;
    }
}
