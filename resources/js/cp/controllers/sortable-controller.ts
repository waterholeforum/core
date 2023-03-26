import { Controller } from '@hotwired/stimulus';
import {
    getAccessibleLabel,
    KeyboardSensor,
    PointerSensor,
    Sortable,
    SortableContext,
    SortableDragEvent,
    verticalListSorting,
} from 'inclusive-sort';

function translate(message: string, { activeItem, overIndex, container }: SortableContext) {
    const activeLabel = getAccessibleLabel(activeItem) || '';
    const containerLabel = getAccessibleLabel(container) || '';
    return message
        .replace('{$activeLabel}', activeLabel)
        .replace('{$overPosition}', String(overIndex + 1))
        .replace('{$containerLabel}', containerLabel);
}

/**
 * A controller to hook up an inclusive-sort instance.
 */
export default class extends Controller {
    static targets = ['container', 'orderInput'];

    static values = {
        instructions: String,
        dragStartAnnouncement: String,
        dragOverAnnouncement: String,
        dropAnnouncement: String,
        dragCancelAnnouncement: String,
    };

    declare readonly containerTargets: HTMLElement[];
    declare readonly orderInputTarget: HTMLInputElement;
    declare readonly instructionsValue: string;
    declare readonly dragStartAnnouncementValue: string;
    declare readonly dragOverAnnouncementValue: string;
    declare readonly dropAnnouncementValue: string;
    declare readonly dragCancelAnnouncementValue: string;

    sortable?: Sortable;

    initialize() {
        this.sortable = new Sortable({
            filter: (item) => !!item.querySelector('[data-handle]'),
            activator: (item) => item.querySelector('[data-handle]'),
            strategy: verticalListSorting,
            sensors: [
                new PointerSensor(),
                new KeyboardSensor({ instructions: this.instructionsValue }),
            ],
            announcements: {
                onDragStart: translate.bind(undefined, this.dragStartAnnouncementValue),
                onDragOver: translate.bind(undefined, this.dragOverAnnouncementValue),
                onDrop: translate.bind(undefined, this.dropAnnouncementValue),
                onDragCancel: translate.bind(undefined, this.dragCancelAnnouncementValue),
            },
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

        const result = this.containerTargets.flatMap((list, i) =>
            Array.from(list.querySelectorAll<HTMLElement>('[data-id]')).map((el) => {
                return { id: el.dataset.id, listIndex: i };
            })
        );

        if (result) {
            this.orderInputTarget.value = JSON.stringify(result);
            this.dispatch('update');
        }
    };
}
