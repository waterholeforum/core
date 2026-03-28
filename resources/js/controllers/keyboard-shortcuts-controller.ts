import { Controller } from '@hotwired/stimulus';
import { FrameElement, TurboBeforeStreamRenderEvent } from '@hotwired/turbo';
import {
    createAriaPlugin,
    createLabelsPlugin,
    createSelectionPlugin,
    createShortcuts,
    type SelectionOverflowContext,
    type SelectionPlugin,
    type Shortcut,
    type ShortcutPlugin,
    type Shortcuts,
} from 'inclusive-shortcuts';
import { nextFrame } from '../utils';

const ENABLED_STORAGE_KEY = 'waterhole.shortcuts.enabled';
const SELECTION_STORAGE_KEY = 'waterhole.shortcuts.selection';
const PAGE_FRAME_SELECTOR = 'turbo-frame[data-page-frame]';
const PAGE_LOAD_MORE_SELECTOR = '[data-page-load-more]';

type Boundary = 'first' | 'last';

export default class extends Controller<HTMLElement> {
    static targets = ['toggle'];

    declare readonly toggleTargets: HTMLInputElement[];

    private runtime?: Shortcuts;
    private selection?: SelectionPlugin;

    connect() {
        const selection = createSelectionPlugin({
            storageKey: SELECTION_STORAGE_KEY,
            onSelect: ({ item }) => void this.preloadActionMenu(item),
            onOverflow: (context) => this.moveSelectionAcrossPages(context),
        });

        this.selection = selection;

        this.runtime = createShortcuts({
            shortcuts: this.runtimeShortcuts(),
            root: this.element,
            plugins: [
                selection,
                createAriaPlugin(),
                createLabelsPlugin(),
                this.createEventsPlugin(),
                this.createPreloadActionsPlugin(),
            ],
        });

        this.applyEnabledState();
        this.toggleDocumentListeners(true);
    }

    disconnect() {
        this.toggleDocumentListeners(false);
        this.runtime?.disconnect();
    }

    toggleTargetConnected(target: HTMLInputElement) {
        target.checked = this.shortcutsEnabled();
    }

    toggleEnabled(e: Event) {
        localStorage.setItem(
            ENABLED_STORAGE_KEY,
            String((e.target as HTMLInputElement).checked),
        );

        this.applyEnabledState();
    }

    private shortcutsEnabled(): boolean {
        return localStorage.getItem(ENABLED_STORAGE_KEY) !== 'false';
    }

    private applyEnabledState() {
        const enabled = this.shortcutsEnabled();

        document.documentElement.dataset.shortcutsEnabled = String(enabled);
        this.toggleTargets.forEach((input) => (input.checked = enabled));

        if (enabled) {
            this.runtime?.connect();
        } else {
            this.runtime?.disconnect();
        }
    }

    private refreshRuntime = () => {
        this.applyEnabledState();

        if (this.shortcutsEnabled()) {
            this.runtime?.refresh();
        }
    };

    private handleBeforeStreamRender = (e: TurboBeforeStreamRenderEvent) => {
        const render = e.detail.render;

        e.detail.render = async (stream) => {
            render(stream);
            requestAnimationFrame(this.refreshRuntime);
        };
    };

    private toggleDocumentListeners(add: boolean) {
        const method = add ? 'addEventListener' : 'removeEventListener';

        document[method](
            'turbo:before-stream-render',
            this.handleBeforeStreamRender as EventListener,
        );
        document[method]('turbo:frame-render', this.refreshRuntime);
        document[method]('turbo:render', this.refreshRuntime);
        document[method]('turbo:load', this.refreshRuntime);
    }

    private runtimeShortcuts(): Shortcut[] {
        return (Waterhole.shortcuts || []).map((shortcut) => {
            let handle: NonNullable<Shortcut['handle']> | undefined;

            switch (shortcut.id) {
                case 'selection.next':
                    handle = () => this.selection?.next();
                    break;
                case 'selection.previous':
                    handle = () => this.selection?.previous();
                    break;
                case 'selection.next-page':
                    handle = () => this.moveSelectionPage(1);
                    break;
                case 'selection.previous-page':
                    handle = () => this.moveSelectionPage(-1);
                    break;
                case 'selection.first':
                    handle = ({ target }) =>
                        this.moveSelectionBoundary('first', target);
                    break;
                case 'selection.last':
                    handle = ({ target }) =>
                        this.moveSelectionBoundary('last', target);
                    break;
            }

            return handle ? { ...shortcut, handle } : shortcut;
        });
    }

    private createEventsPlugin(): ShortcutPlugin {
        return {
            beforeShortcut: ({ shortcut }) => {
                const event = new CustomEvent('waterhole:shortcut', {
                    detail: { id: shortcut.id, shortcut },
                    bubbles: true,
                    cancelable: true,
                });

                if (!document.dispatchEvent(event)) {
                    return false;
                }
            },
        };
    }

    private createPreloadActionsPlugin(): ShortcutPlugin {
        return {
            beforeShortcut: ({ shortcut, host }) => {
                const item = this.selection?.selected();

                if (!item || !shortcut.id.startsWith('action.')) {
                    return;
                }

                this.preloadActionMenu(item).then(() => {
                    const target = host.resolveTarget(shortcut);
                    if (target) {
                        activateShortcutTarget(target);
                    }
                });

                return false;
            },
        };
    }

    private actionMenu(item?: HTMLElement): HTMLElement | undefined {
        const owner = item?.dataset.shortcutSelectionKey;

        if (owner) {
            const menu = document.querySelector<HTMLElement>(
                `ui-popup[data-shortcut-selection-owner="${owner}"]`,
            );
            if (menu) {
                return menu;
            }
        }

        if (item?.matches('ui-popup[data-controller~="action-menu"]')) {
            return item;
        }

        return (
            item?.querySelector<HTMLElement>(
                'ui-popup[data-controller~="action-menu"]',
            ) || undefined
        );
    }

    private async preloadActionMenu(item?: HTMLElement) {
        const frame = this.actionMenu(item)?.querySelector<FrameElement>(
            '[data-action-menu-target="frame"]',
        );

        if (!frame) {
            return;
        }

        if (frame.getAttribute('loading') !== 'eager') {
            frame.setAttribute('loading', 'eager');
        }

        return frame.loaded;
    }

    private moveSelectionAcrossPages({
        edge,
        current,
    }: SelectionOverflowContext) {
        const direction = edge === 'last' ? 1 : -1;
        const nextEdge = edge === 'last' ? 'first' : 'last';
        const currentFrame = this.pageFrameForItem(current);
        const nextFrame = currentFrame
            ? this.adjacentPageFrame(currentFrame, direction)
            : undefined;

        if (!nextFrame) {
            return false;
        }

        this.navigateSelectionToFrame(nextFrame, nextEdge);

        return true;
    }

    private moveSelectionPage(direction: 1 | -1) {
        const selection = this.selection;
        const items = selection?.items();

        if (!selection || !items?.length) {
            return false;
        }

        const current = selection.selected();

        if (!current) {
            return selection.navigate(
                direction > 0 ? items[0] : items[items.length - 1],
            );
        }

        const currentFrame = this.pageFrameForItem(current);
        if (!currentFrame && direction < 0) {
            return false;
        }

        const nextFrame = currentFrame
            ? this.adjacentPageFrame(currentFrame, direction)
            : this.pageFrames()[0];

        if (!nextFrame) {
            return selection.navigate(
                direction < 0 ? items[0] : items[items.length - 1],
            );
        }

        this.navigateSelectionToFrame(nextFrame);

        return true;
    }

    private pageFrameForItem(item?: HTMLElement): FrameElement | undefined {
        return item?.closest<FrameElement>(PAGE_FRAME_SELECTOR) || undefined;
    }

    private pageFrames(): FrameElement[] {
        return Array.from(
            document.querySelectorAll<FrameElement>(PAGE_FRAME_SELECTOR),
        ).sort((a, b) => Number(a.dataset.page) - Number(b.dataset.page));
    }

    private adjacentPageFrame(
        frame: FrameElement,
        direction: 1 | -1,
    ): FrameElement | undefined {
        const frames = this.pageFrames();
        const index = frames.indexOf(frame);

        return index === -1 ? undefined : frames[index + direction];
    }

    private pageFrameBoundaryItem(
        frame: FrameElement,
        edge: Boundary = 'first',
    ): HTMLElement | undefined {
        const items = (this.selection?.items() || []).filter(
            (item) => this.pageFrameForItem(item) === frame,
        );

        return edge === 'first' ? items[0] : items[items.length - 1];
    }

    private async navigateSelectionToFrame(
        frame: FrameElement,
        edge: Boundary = 'first',
    ) {
        const item = await this.ensurePageFrameBoundaryItem(frame, edge);
        await nextFrame();
        this.selection?.navigate(item);
    }

    private async ensurePageFrameBoundaryItem(
        frame: FrameElement,
        edge: Boundary = 'first',
    ): Promise<HTMLElement | undefined> {
        const item = this.pageFrameBoundaryItem(frame, edge);
        if (item) {
            return item;
        }

        if (!frame.src) {
            const loadMore = frame.querySelector<HTMLAnchorElement>(
                PAGE_LOAD_MORE_SELECTOR,
            );

            if (loadMore) {
                frame.src = loadMore.href;
            }
        }

        if (frame.getAttribute('loading') !== 'eager') {
            frame.setAttribute('loading', 'eager');
        }

        await frame.loaded;

        return this.pageFrameBoundaryItem(frame, edge);
    }

    private moveSelectionBoundary(edge: Boundary, target?: HTMLElement) {
        const select = () =>
            edge === 'first' ? this.selection?.first() : this.selection?.last();

        if (target instanceof HTMLAnchorElement) {
            document.addEventListener('turbo:load', select, { once: true });
            target.click();
            return;
        }

        return select();
    }
}

function activateShortcutTarget(target: HTMLElement) {
    if (isFocusActivationTarget(target)) {
        target.focus({ preventScroll: true });
        return;
    }

    target.click();
}

function isFocusActivationTarget(target: HTMLElement): boolean {
    return (
        target.matches(
            'input, textarea, select, [contenteditable]:not([contenteditable="false"])',
        ) ||
        (target.tabIndex >= 0 && !isClickActivationTarget(target))
    );
}

function isClickActivationTarget(target: HTMLElement): boolean {
    return target.matches(
        'button, summary, a[href], area[href], audio[controls], video[controls], input:not([type="hidden"]), select, textarea, label',
    );
}
