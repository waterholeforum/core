import { Controller } from '@hotwired/stimulus';
import { Sortable, SortableDragEvent, verticalListSorting } from 'inclusive-sort';

/**
 * A controller to hook up an inclusive-sort instance.
 *
 *
 */
export default class extends Controller {
    static targets = ['container', 'orderInput'];

    containerTargets?: HTMLElement[];
    orderInputTarget?: HTMLInputElement;

    sortable?: Sortable;

    initialize() {
        this.sortable = new Sortable({
            filter: (item) => !!item.querySelector('[data-handle]'),
            activator: (item) => item.querySelector('[data-handle]'),
            strategy: verticalListSorting,
        });

        this.sortable.addEventListener('dragstart', this.start);
        this.sortable.addEventListener('drop', this.beforeEnd);
        this.sortable.addEventListener('dragcancel', this.beforeEnd);
        this.sortable.addEventListener('dragend', this.end);
    }

    containerTargetConnected(el: HTMLElement) {
        this.sortable?.addContainer(el);
    }

    containerTargetDisconnected(el: HTMLElement) {
        this.sortable?.removeContainer(el);
    }

    private start = (e: SortableDragEvent) => {
        e.detail.activeItem.style.opacity = '0';
        e.detail.overlay.classList.add('drag-overlay', 'drag-overlay-active');
    };

    private beforeEnd = (e: SortableDragEvent) => {
        e.detail.overlay.classList.remove('drag-overlay-active');
    };

    private end = (e: SortableDragEvent) => {
        e.detail.activeItem.style.opacity = '';

        const result = this.containerTargets!.flatMap((list, i) =>
            Array.from(list.querySelectorAll<HTMLElement>('[data-id]')).map((el) => {
                return { id: el.dataset.id, listIndex: i };
            })
        );

        if (result) {
            this.orderInputTarget!.value = JSON.stringify(result);
            this.dispatch('update');
        }
    };
}
