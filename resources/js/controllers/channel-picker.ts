import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['instructions', 'instructionsContent'];

    instructionsTarget?: HTMLElement;
    instructionsContentTarget?: HTMLElement;

    connect() {
        this.instructionsTarget!.hidden = true;
    }

    update(e: Event) {
        const select = e.target as HTMLSelectElement;
        const instructions = select.options[select.selectedIndex].dataset.instructions || '';

        this.instructionsTarget!.hidden = ! instructions;
        this.instructionsContentTarget!.innerHTML = instructions;
    }
}
