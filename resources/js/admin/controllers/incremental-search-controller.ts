import { Controller } from '@hotwired/stimulus';
import { debounce } from 'lodash-es';

/**
 * Controller to power incremental search.
 *
 * Apply this controller to an <input> element and call the `input` action to
 * debounce a submission of the input's form. The only exception is when the
 * input is empty, in which case the form will submit immediately.
 *
 * For an example usage, see the admin users page.
 */
export default class extends Controller<HTMLInputElement> {
    input(e: InputEvent) {
        if ((e.target as HTMLInputElement).value) {
            this.debouncedSubmit();
        } else {
            this.submit();
        }
    }

    submit() {
        this.element.form?.requestSubmit();
    }

    debouncedSubmit = debounce(this.submit, 250);
}
