import { Controller } from '@hotwired/stimulus';
import { debounce } from 'lodash-es';

export default class extends Controller {
    input(e: InputEvent) {
        if ((e.target as HTMLInputElement).value) {
            this.debouncedSubmit();
        } else {
            this.submit();
        }
    }

    submit() {
        (this.element as HTMLInputElement).form?.requestSubmit();
    }

    debouncedSubmit = debounce(this.submit, 250);
}
