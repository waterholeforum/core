import { Controller } from '@hotwired/stimulus';
import { PopupElement } from 'inclusive-elements';
import twemoji from 'twemoji';

/**
 *
 */
export default class extends Controller<PopupElement> {
    connect() {
        const picker = this.element.querySelector('emoji-picker')!;
        const pickerRoot = picker.shadowRoot!;

        picker.addEventListener('focusout', (e) => e.stopPropagation());

        this.element.addEventListener('open', () => {
            pickerRoot.querySelector('input')?.focus();
        });

        const style = document.createElement('style');
        style.textContent = `.twemoji {
          width: var(--emoji-size);
          height: var(--emoji-size);
          pointer-events: none;
        }`;
        pickerRoot.appendChild(style);

        if (Waterhole.twemojiBase) {
            const observer = new MutationObserver(() => {
                for (const emoji of pickerRoot.querySelectorAll<HTMLElement>('.emoji')) {
                    if (!emoji.querySelector('.twemoji')) {
                        twemoji.parse(emoji, {
                            base: Waterhole.twemojiBase!,
                            className: 'twemoji',
                        });
                    }
                }
            });

            observer.observe(pickerRoot, {
                subtree: true,
                childList: true,
            });
        }
    }
}
