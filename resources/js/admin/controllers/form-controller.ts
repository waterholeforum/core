import { Controller } from '@hotwired/stimulus';

/**
 * Controller to submit a form.
 *
 * An example of where this is used is on the Admin Structure page's
 * drag-and-drop ordering interface, in which the order-saving form is
 * targeted, and the submit action is triggered when dragging ends.
 */
export default class extends Controller {
    static targets = ['form'];

    formTarget?: HTMLFormElement;

    submit() {
        // Request submission of the form after a tick so that other event
        // listeners have a chance to run first.
        setTimeout(() => {
            this.formTarget!.requestSubmit();
        });
    }
}
