import { Controller } from '@hotwired/stimulus';
import DragonNest from 'dragon-nest';

/**
 * A controller to hook up a dragon-nest instance.
 *
 *
 */
export default class extends Controller {
    static targets = ['list', 'orderInput'];

    // listTarget?: HTMLElement;
    listTargets?: HTMLElement[];
    orderInputTarget?: HTMLInputElement;

    dragonNest?: DragonNest;
    dragTarget?: Element;

    initialize() {
        this.dragonNest = new DragonNest();
    }

    connect() {
        document.addEventListener('mousedown', this.mousedown);
    }

    disconnect() {
        document.removeEventListener('mousedown', this.mousedown);
    }

    listTargetConnected(el: HTMLElement) {
        this.dragonNest?.addList(el);

        el.addEventListener('dragstart', this.start);
        el.addEventListener('dragend', this.end);
    }

    listTargetDisconnected(el: HTMLElement) {
        this.dragonNest?.removeList(el);

        el.removeEventListener('dragstart', this.start);
        el.removeEventListener('dragend', this.end);
    }

    private mousedown = (e: MouseEvent) => {
        this.dragTarget = e.target as Element;
    };

    private start = (e: DragEvent) => {
        if (this.dragTarget?.closest('[data-handle]')) {
            (e.target as HTMLElement).classList.add('is-dragging');
        } else {
            e.preventDefault();
        }
    };

    private end = (e: DragEvent) => {
        (e.target as HTMLElement).classList.remove('is-dragging');

        const result = this.listTargets!.flatMap((list, i) =>
            Array.from(list.querySelectorAll<HTMLElement>('[data-id]')).map((el) => {
                return { id: el.dataset.id, listIndex: i };
            })
        );

        if (result) {
            this.orderInputTarget!.value = JSON.stringify(result);
        }
    };
}
