import { Controller } from '@hotwired/stimulus';

/**
 * Controller for the <x-waterhole::permission-grid> component.
 *
 * @internal
 */
export default class extends Controller<HTMLElement> {
    private fixedDisabled: HTMLInputElement[] = [];

    connect() {
        this.fixedDisabled = Array.from(
            this.element.querySelectorAll('input:disabled'),
        );
        this.update();

        this.element.addEventListener('click', this.click);
        this.element.addEventListener('mouseover', this.mouseover);
        this.element.addEventListener('mouseout', this.reset);
    }

    disconnect() {
        this.element.removeEventListener('click', this.click);
        this.element.removeEventListener('mouseover', this.mouseover);
        this.element.removeEventListener('mouseout', this.reset);
    }

    private mouseover = (e: MouseEvent) => {
        const target = e.target as HTMLElement;

        if (target.closest('thead th')) {
            const index = Array.from(target.parentElement!.children).indexOf(
                target,
            );
            this.element
                .querySelector('colgroup')!
                .children[index].classList.add('is-targeted');
            this.element.style.cursor = 'pointer';
        }

        if (target.closest('tbody th')) {
            target.closest('tr')!.classList.add('is-targeted');
            this.element.style.cursor = 'pointer';
        }
    };

    private reset = () => {
        this.element
            .querySelectorAll('col, tr')
            .forEach((el) => el.classList.remove('is-targeted'));
        this.element.style.cursor = '';
    };

    private click = (e: MouseEvent) => {
        const target = e.target as HTMLElement;

        if (target.closest('thead th')) {
            const index = Array.from(target.parentElement!.children).indexOf(
                target,
            );
            const checkboxes = Array.from(
                this.element.querySelectorAll<HTMLInputElement>(
                    `tbody tr td:nth-child(${
                        index + 1
                    }) input[type="checkbox"]`,
                ),
            ).filter((checkbox) => !this.fixedDisabled.includes(checkbox));
            this.toggle(checkboxes);
        }

        if (target.closest('tbody th')) {
            const checkboxes = Array.from(
                target
                    .closest('tr')!
                    .querySelectorAll<HTMLInputElement>(
                        `td input[type="checkbox"]`,
                    ),
            ).filter((checkbox) => !this.fixedDisabled.includes(checkbox));
            this.toggle(checkboxes);
        }

        this.update();
    };

    private toggle(checkboxes: HTMLInputElement[]) {
        const checked = !checkboxes.find(
            (checkbox) =>
                !checkbox.disabled &&
                checkbox.getAttribute('aria-disabled') !== 'true',
        )?.checked;

        checkboxes.forEach((checkbox) => (checkbox.checked = checked));
    }

    update() {
        this.element
            .querySelectorAll<HTMLInputElement>(
                'tbody td input[type="checkbox"]',
            )
            .forEach((checkbox) => {
                if (!this.fixedDisabled.includes(checkbox)) {
                    checkbox.disabled = false;
                }
            });

        this.element
            .querySelectorAll<HTMLInputElement>(
                '[data-implied-by], [data-depends-on]',
            )
            .forEach((el) => {
                el.dataset.impliedBy
                    ?.trim()
                    .split(/\s+/)
                    .filter(Boolean)
                    .forEach((name) => {
                        const ref = this.input(name);
                        if (ref && ref.checked) {
                            el.checked = true;
                            el.disabled = true;
                        }
                    });

                el.dataset.dependsOn
                    ?.trim()
                    .split(/\s+/)
                    .filter(Boolean)
                    .forEach((name) => {
                        const ref = this.input(name);
                        if (ref && !ref.checked) {
                            el.checked = false;
                            el.disabled = true;
                        }
                    });
            });
    }

    private input(name: string) {
        return Array.from(document.getElementsByName(name))
            .filter((element): element is HTMLInputElement =>
                element.matches('input'),
            )
            .at(-1);
    }
}
