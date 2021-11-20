import { Controller } from '@hotwired/stimulus';
import DragonNest from 'dragon-nest';

export default class extends Controller {
    static targets = ['list', 'orderInput'];

    listTarget?: HTMLElement;
    orderInputTarget?: HTMLInputElement;

    dragonNest?: DragonNest;
    dragTarget?: Element;

    connect() {
        this.dragonNest = new DragonNest(this.listTarget!);

        this.listTarget!.addEventListener('dragstart', this.start);
        this.listTarget!.addEventListener('dragend', this.end);

        document.addEventListener('mousedown', this.mousedown);
    }

    disconnect() {
        this.dragonNest?.destroy();

        this.listTarget!.removeEventListener('dragstart', this.start);
        this.listTarget!.removeEventListener('dragend', this.end);

        document.removeEventListener('mousedown', this.mousedown);
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

        const nodes = this.listTarget!.querySelectorAll<HTMLElement>('[data-id]');
        const result = Array.from(nodes).map((el, i) => {
            return el.dataset.id;
        });

        this.orderInputTarget!.value = JSON.stringify(result);
    }
}
