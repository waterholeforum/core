import { Controller } from '@hotwired/stimulus';
import {
    DragDropManager,
    type DragEndEvent,
    type DragMoveEvent,
    type DragOverEvent,
    type DragStartEvent,
} from '@dnd-kit/dom';
import { isSortable, Sortable } from '@dnd-kit/dom/sortable';
import { isKeyboardEvent } from '@dnd-kit/dom/utilities';

const INDENT_WIDTH = 32;

type DragState = {
    element: HTMLElement;
    initialDepth: number;
    depth: number;
    descendants: HTMLElement[];
    placeholder: HTMLElement;
};

/** A flat sortable tree with horizontal depth projection. */
export default class extends Controller<HTMLElement> {
    static targets = ['container', 'orderInput'];

    declare readonly containerTarget: HTMLElement;
    declare readonly hasOrderInputTarget: boolean;
    declare readonly orderInputTarget: HTMLInputElement;

    private manager!: DragDropManager;
    private stopMonitoring: (() => void)[] = [];
    private sortables = new Map<HTMLElement, Sortable>();
    private drag?: DragState;
    private dragStartFrame?: number;
    private projectionFrame?: number;
    private dropTimer?: number;

    connect() {
        this.manager = new DragDropManager();
        this.stopMonitoring = [
            this.manager.monitor.addEventListener(
                'dragstart',
                this.onDragStart,
            ),
            this.manager.monitor.addEventListener('dragmove', this.onDragMove),
            this.manager.monitor.addEventListener('dragover', this.onDragOver),
            this.manager.monitor.addEventListener('dragend', this.onDragEnd),
        ];
        this.buildSortables();
    }

    disconnect() {
        if (this.dropTimer) clearTimeout(this.dropTimer);
        this.restoreDescendants();
        this.resetDrag();
        this.destroySortables();
        this.stopMonitoring.forEach((stop) => stop());
        this.stopMonitoring = [];
        this.manager.destroy();
    }

    private buildSortables() {
        this.items().forEach((item, index) => {
            this.sortables.set(
                item,
                new Sortable(
                    {
                        id: item.dataset.id!,
                        index,
                        element: item,
                        alignment: { x: 'start', y: 'start' },
                        handle:
                            item.querySelector<HTMLElement>('[data-handle]') ||
                            undefined,
                    },
                    this.manager,
                ),
            );
        });
    }

    private destroySortables() {
        this.sortables.forEach((sortable) => sortable.destroy());
        this.sortables.clear();
    }

    private onDragStart = (event: DragStartEvent) => {
        const { source } = event.operation;

        if (
            !source ||
            !isSortable(source) ||
            !(source.element instanceof HTMLElement)
        ) {
            return;
        }

        const element = source.element;
        const depth = this.depth(element);
        this.dragStartFrame = requestAnimationFrame(() => {
            this.dragStartFrame = undefined;
            this.initializeDrag(element, depth);
        });
    };

    private initializeDrag(source: HTMLElement, depth: number) {
        const placeholder = source.nextElementSibling;

        if (
            !(placeholder instanceof HTMLElement) ||
            !placeholder.hasAttribute('data-dnd-placeholder')
        ) {
            return;
        }

        this.drag = {
            element: source,
            initialDepth: depth,
            depth,
            descendants: this.descendantsOf(source),
            placeholder,
        };

        this.drag.descendants.forEach((item) => {
            this.sortables.get(item)?.destroy();
            this.sortables.delete(item);
            item.remove();
        });
        this.items().forEach((item, index) => {
            this.sortables.get(item)!.index = index;
        });
        this.updateProjection();
    }

    private onDragMove = (event: DragMoveEvent) => {
        if (event.defaultPrevented) return;

        if (
            this.drag &&
            isKeyboardEvent(event.operation.activatorEvent) &&
            event.by?.x &&
            event.by.y === 0
        ) {
            event.preventDefault();

            this.updateProjection(this.drag.depth + Math.sign(event.by.x));
        }

        this.scheduleProjection();
    };

    private onDragOver = (event: DragOverEvent) => {
        if (event.defaultPrevented) return;
        this.scheduleProjection();
    };

    private onDragEnd = (event: DragEndEvent) => {
        this.cancelFrames();

        const drag = this.drag;

        if (!drag) return;

        const canceled = event.canceled;
        let depthDelta = 0;

        if (canceled) {
            this.setDepth(drag.element, drag.initialDepth);
        } else {
            this.updateProjection();
            depthDelta = drag.depth - drag.initialDepth;
        }

        // Allow dnd-kit to restore the dragged element after its drop animation.
        this.dropTimer = window.setTimeout(() => {
            this.restoreDescendants(depthDelta);
            this.resetDrag();
            this.destroySortables();
            this.buildSortables();
            if (!canceled) this.updateOrder();
            this.dropTimer = undefined;
        }, 300);
    };

    private scheduleProjection() {
        if (this.projectionFrame) cancelAnimationFrame(this.projectionFrame);

        this.projectionFrame = requestAnimationFrame(() => {
            this.projectionFrame = undefined;
            if (!this.drag) return;

            this.updateProjection();

            if (isKeyboardEvent(this.manager.dragOperation.activatorEvent)) {
                this.projectionFrame = requestAnimationFrame(() => {
                    this.projectionFrame = undefined;
                    this.alignKeyboardOverlay();
                });
            }
        });
    }

    private alignKeyboardOverlay() {
        const drag = this.drag;

        if (!drag) return;

        const offset =
            drag.placeholder.getBoundingClientRect().left -
            drag.element.getBoundingClientRect().left;

        if (Math.abs(offset) > 0.5) {
            this.manager.actions.move({
                by: { x: offset, y: 0 },
                propagate: false,
            });
        }
    }

    private updateProjection(requested?: number) {
        const drag = this.drag;

        if (!drag) return;

        requested ??= isKeyboardEvent(this.manager.dragOperation.activatorEvent)
            ? drag.depth
            : drag.initialDepth +
              Math.round(this.manager.dragOperation.transform.x / INDENT_WIDTH);

        const items = this.items();
        const index = items.indexOf(drag.element);

        if (index < 0) return;

        const previous = items[index - 1];
        const next = items[index + 1];
        const maximum = previous
            ? this.depth(previous) +
              (previous.dataset.canHaveChildren === '1' ? 1 : 0)
            : 0;
        const minimum = next ? this.depth(next) : 0;

        drag.depth = Math.max(minimum, Math.min(requested, maximum));

        this.setDepth(drag.element, drag.depth);
        drag.placeholder.style.setProperty(
            '--structure-depth',
            String(drag.depth),
        );
    }

    private descendantsOf(item: HTMLElement): HTMLElement[] {
        const items = this.items();
        const index = items.indexOf(item);
        const depth = this.depth(item);
        const descendants: HTMLElement[] = [];

        for (const candidate of items.slice(index + 1)) {
            if (this.depth(candidate) <= depth) break;
            descendants.push(candidate);
        }

        return descendants;
    }

    private restoreDescendants(depthDelta = 0) {
        if (!this.drag) return;

        let previous = this.drag.element;
        this.drag.descendants.forEach((item) => {
            this.setDepth(item, this.depth(item) + depthDelta);
            previous.insertAdjacentElement('afterend', item);
            previous = item;
        });
    }

    private items(): HTMLElement[] {
        return Array.from(this.containerTarget.children).filter(
            (item): item is HTMLElement =>
                item instanceof HTMLElement &&
                Boolean(item.dataset.id) &&
                !item.hasAttribute('data-dnd-placeholder'),
        );
    }

    private depth(item: HTMLElement): number {
        return Number(item.dataset.depth || 0);
    }

    private setDepth(item: HTMLElement, depth: number) {
        item.dataset.depth = String(depth);
        item.style.setProperty('--structure-depth', String(depth));
    }

    private resetDrag() {
        this.cancelFrames();
        this.drag = undefined;
    }

    private cancelFrames() {
        if (this.dragStartFrame) cancelAnimationFrame(this.dragStartFrame);
        if (this.projectionFrame) cancelAnimationFrame(this.projectionFrame);
        this.dragStartFrame = undefined;
        this.projectionFrame = undefined;
    }

    private updateOrder() {
        if (!this.hasOrderInputTarget) {
            this.dispatch('update');
            return;
        }

        const ancestors: HTMLElement[] = [];
        const positions = new Map<string | null, number>();
        const result = this.items().map((item) => {
            const depth = this.depth(item);
            const parentId = depth ? ancestors[depth - 1].dataset.id! : null;
            const position = positions.get(parentId) ?? 0;

            positions.set(parentId, position + 1);
            ancestors.length = depth;
            ancestors[depth] = item;

            return { id: item.dataset.id, parent_id: parentId, position };
        });

        this.orderInputTarget.value = JSON.stringify(result);
        this.dispatch('update');
    }
}
