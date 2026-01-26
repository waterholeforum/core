import { Controller } from '@hotwired/stimulus';
import { DragDropManager } from '@dnd-kit/dom';
import { Sortable } from '@dnd-kit/dom/sortable';
import { generateUniqueId } from '@dnd-kit/dom/utilities';
import { RestrictToVerticalAxis } from '@dnd-kit/abstract/modifiers';

/**
 * A controller to hook up a dnd-kit sortable instance.
 */
export default class extends Controller<HTMLElement> {
    static targets = ['container', 'orderInput'];

    declare readonly containerTargets: HTMLElement[];

    declare readonly hasOrderInputTarget: boolean;
    declare readonly orderInputTarget: HTMLInputElement;

    private manager?: DragDropManager;
    private sortables: Sortable[] = [];

    connect() {
        this.manager = new DragDropManager({
            // @ts-ignore
            modifiers: [RestrictToVerticalAxis],
        });
        this.manager.monitor.addEventListener('dragend', this.onDragEnd);

        this.refreshSortables();
    }

    disconnect() {
        this.teardownSortables();

        this.manager?.monitor.removeEventListener('dragend', this.onDragEnd);
        this.manager?.destroy?.();
        this.manager = undefined;
    }

    containerTargetConnected() {
        this.refreshSortables();
    }

    containerTargetDisconnected() {
        this.refreshSortables();
    }

    private refreshSortables() {
        this.teardownSortables();

        if (!this.manager) return;

        this.containerTargets.forEach((container, group) => {
            const items = Array.from(
                container.querySelectorAll<HTMLElement>('[data-id]'),
            );

            items.forEach((item, index) => {
                const sortable = new Sortable(
                    {
                        id: item.dataset.id || generateUniqueId('item'),
                        index,
                        group,
                        element: item,
                        handle:
                            item.querySelector<HTMLElement>('[data-handle]') ||
                            undefined,
                    },
                    this.manager,
                );

                this.sortables.push(sortable);
            });
        });
    }

    private teardownSortables() {
        this.sortables.forEach((sortable) => sortable.destroy());
        this.sortables = [];
    }

    private onDragEnd = () => this.updateOrder();

    private updateOrder() {
        if (!this.hasOrderInputTarget) {
            this.dispatch('update');
            return;
        }

        const result = this.containerTargets.flatMap((list, i) =>
            Array.from(list.querySelectorAll<HTMLElement>('[data-id]')).map(
                (el) => {
                    return { id: el.dataset.id, listIndex: i };
                },
            ),
        );

        if (result) {
            this.orderInputTarget.value = JSON.stringify(result);
            this.dispatch('update');
        }
    }
}
