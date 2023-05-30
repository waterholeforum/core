import { Controller } from '@hotwired/stimulus';
import 'emoji-picker-element';
import { PopupElement } from 'inclusive-elements';

class EmojiPickerController extends Controller<PopupElement> {
    connect() {
        const picker = this.element.querySelector('emoji-picker')!;
        const pickerRoot = picker.shadowRoot!;

        picker.addEventListener('focusout', (e) => e.stopPropagation());

        this.element.addEventListener('open', () => {
            pickerRoot.querySelector('input')?.focus();
        });
    }
}

window.Stimulus.register('emoji-picker', EmojiPickerController);
