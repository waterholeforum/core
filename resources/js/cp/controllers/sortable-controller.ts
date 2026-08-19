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
    private sortables: Sortable[] = [];
    private drag?: DragState;
    private dragStartFrame?: number;
    private projectionFrame?: number;
    private cancelTimer?: number;

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
        if (this.cancelTimer) clearTimeout(this.cancelTimer);
        this.restoreDescendants();
        this.resetDrag();
        this.destroySortables();
        this.stopMonitoring.forEach((stop) => stop());
        this.stopMonitoring = [];
        this.manager.destroy();
    }

    private buildSortables() {
        this.sortables = this.items().map(
            (item, index) =>
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
    }

    private destroySortables() {
        this.sortables.forEach((sortable) => sortable.destroy());
        this.sortables = [];
    }

    private onDragStart: DragStartEvent = (event) => {
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

        const descendants = new Set(this.drag.descendants);
        this.sortables = this.sortables.filter((sortable) => {
            if (!(sortable.element instanceof HTMLElement)) return true;
            if (!descendants.has(sortable.element)) return true;

            sortable.destroy();
            return false;
        });
        this.drag.descendants.forEach((item) => item.remove());
        this.reindexSortables();
        this.updateProjection();
    }

    private onDragMove: DragMoveEvent = (event) => {
        if (event.defaultPrevented) return;

        if (
            this.drag &&
            isKeyboardEvent(event.operation.activatorEvent) &&
            event.by?.x &&
            event.by.y === 0
        ) {
            event.preventDefault();

            this.updateProjection(this.drag.depth + Math.sign(event.by.x));
            this.scheduleProjection();

            return;
        }

        this.scheduleProjection();
    };

    private onDragOver: DragOverEvent = (event) => {
        if (event.defaultPrevented) return;
        this.scheduleProjection();
    };

    private onDragEnd: DragEndEvent = (event) => {
        if (this.dragStartFrame) cancelAnimationFrame(this.dragStartFrame);
        if (this.projectionFrame) cancelAnimationFrame(this.projectionFrame);

        const drag = this.drag;

        if (!drag) {
            this.resetDrag();
            return;
        }

        const canceled = event.canceled;
        let depthDelta = 0;

        if (canceled) {
            this.setDepth(drag.element, drag.initialDepth);
        } else {
            this.updateProjection();
            depthDelta = drag.depth - drag.initialDepth;
        }

        this.cancelTimer = window.setTimeout(() => {
            this.restoreDescendants(depthDelta);
            this.resetDrag();
            this.destroySortables();
            this.buildSortables();
            if (!canceled) this.updateOrder();
            this.cancelTimer = undefined;
        }, 300);
    };

    private scheduleProjection() {
        if (this.projectionFrame) cancelAnimationFrame(this.projectionFrame);

        this.projectionFrame = requestAnimationFrame(() => {
            if (!this.drag) {
                this.projectionFrame = undefined;
                return;
            }

            this.updateProjection();

            if (isKeyboardEvent(this.manager.dragOperation.activatorEvent)) {
                this.projectionFrame = requestAnimationFrame(() => {
                    this.projectionFrame = undefined;
                    this.alignKeyboardOverlay();
                });
            } else {
                this.projectionFrame = undefined;
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
            ? this.depth(previous) + (this.canHaveChildren(previous) ? 1 : 0)
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

    private reindexSortables() {
        const byElement = new Map(
            this.sortables.map((sortable) => [sortable.element, sortable]),
        );

        this.items().forEach((item, index) => {
            const sortable = byElement.get(item);
            if (sortable) sortable.index = index;
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

    private canHaveChildren(item: HTMLElement): boolean {
        return item.dataset.canHaveChildren === '1';
    }

    private resetDrag() {
        if (this.dragStartFrame) cancelAnimationFrame(this.dragStartFrame);
        if (this.projectionFrame) cancelAnimationFrame(this.projectionFrame);
        this.drag = undefined;
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
