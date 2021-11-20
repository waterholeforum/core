import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    private disabled?: HTMLInputElement[];

    connect() {
        this.disabled = Array.from(this.element.querySelectorAll('input:disabled'));
        this.update();
    }

    mouseover(e: MouseEvent) {
        const target = e.target as HTMLElement;
        if (target.matches('thead th, thead th *')) {
            const index = Array.from(target.parentElement!.children).indexOf(target);
            this.element.querySelector('colgroup')!.children[index].classList.add('is-highlighted');
           (this.element as HTMLElement).style.cursor = 'pointer';
        }
        if (target.matches('tbody th, tbody th *')) {
            target.closest('tr')!.classList.add('is-highlighted');
            (this.element as HTMLElement).style.cursor = 'pointer';
        }
    }

    reset(e: MouseEvent) {
        this.element.querySelectorAll('col, tr').forEach(el => el.classList.remove('is-highlighted'));
        (this.element as HTMLElement).style.cursor = '';
    }

    click(e: MouseEvent) {
        const target = e.target as HTMLElement;
        if (target.matches('thead th, thead th *')) {
            const index = Array.from(target.parentElement!.children).indexOf(target);
            const checkboxes = Array.from(this.element.querySelectorAll<HTMLInputElement>(`tbody tr td:nth-child(${index + 1}) input[type="checkbox"]`))
                .filter(checkbox => ! this.disabled?.includes(checkbox));

            const checked = ! checkboxes.find(checkbox => ! checkbox.disabled && checkbox.getAttribute('aria-disabled') !== 'true')?.checked;
            checkboxes.forEach(el => el.checked = checked);
        }

        if (target.matches('tbody th, tbody th *')) {
            const checkboxes = Array.from(target.closest('tr')!.querySelectorAll<HTMLInputElement>(`td input[type="checkbox"]`))
                .filter(checkbox => ! this.disabled?.includes(checkbox));

            const checked = ! checkboxes.find(checkbox => ! checkbox.disabled && checkbox.getAttribute('aria-disabled') !== 'true')?.checked;
            checkboxes.forEach(el => el.checked = checked);
        }

        this.update();
    }

    update() {
        this.element.querySelectorAll<HTMLInputElement>('tbody td input[type="checkbox"]').forEach(checkbox => {
            if (! this.disabled?.includes(checkbox)) {
                checkbox.disabled = false;//setAttribute('aria-disabled', 'false');
            }
        });

        this.element.querySelectorAll<HTMLInputElement>('[data-implied-by], [data-depends-on]').forEach(el => {
            if (el.dataset.impliedBy) {
                el.dataset.impliedBy.trim().split(/\s+/).filter(Boolean).forEach(name => {
                    const ref = document.querySelector<HTMLInputElement>(`[name="${name}"]:last-of-type`);
                    if (ref && ref.checked) {
                        el.checked = true;
                        el.disabled = true;//setAttribute('aria-disabled', 'true');
                    }
                });
            }

            if (el.dataset.dependsOn) {
                el.dataset.dependsOn.trim().split(/\s+/).filter(Boolean).forEach(name => {
                    const ref = document.querySelector<HTMLInputElement>(`[name="${name}"]:last-of-type`);
                    if (ref && ! ref.checked) {
                        el.checked = false;
                        el.disabled = true;//setAttribute('aria-disabled', 'true');
                    }
                });
            }
        });



        // [1, 2].forEach((id, i) => {
        //     Array.from(this.element.querySelectorAll<HTMLInputElement>(`[data-group-id="${id}"] td input`))
        //         .filter(checkbox => ! this.disabled?.includes(checkbox))
        //         .forEach(checkbox => {
        //             const cell = checkbox.closest('td')!;
        //             const index = Array.from(cell.parentElement!.children).indexOf(cell);
        //             const rows = Array.from(this.element.querySelectorAll<HTMLInputElement>('tbody tr')).slice(i + 1);
        //             rows.forEach(row => {
        //                 const input = row.querySelector<HTMLInputElement>(`td:nth-child(${index + 1}) input`)!;
        //                 if (checkbox.checked) {
        //                     input.checked = true;
        //                 }
        //             });
        //         });
        // });
        //
        // this.element.querySelectorAll('tbody tr').forEach(row => {
        //     const inputs = Array.from(row.querySelectorAll<HTMLInputElement>('td input'));
        //     if (! inputs[0].checked) {
        //         inputs.slice(1).forEach(checkbox => {
        //             checkbox.checked = false;
        //         });
        //     }
        // });
        //

        //
        // [1, 2].forEach((id, i) => {
        //     Array.from(this.element.querySelectorAll<HTMLInputElement>(`[data-group-id="${id}"] td input`))
        //         .filter(checkbox => ! this.disabled?.includes(checkbox))
        //         .forEach(checkbox => {
        //             const cell = checkbox.closest('td')!;
        //             const index = Array.from(cell.parentElement!.children).indexOf(cell);
        //             const rows = Array.from(this.element.querySelectorAll<HTMLInputElement>('tbody tr')).slice(i + 1);
        //             rows.forEach(row => {
        //                 const input = row.querySelector<HTMLInputElement>(`td:nth-child(${index + 1}) input`)!;
        //                 if (checkbox.checked) {
        //                     input.setAttribute('aria-disabled', 'true');
        //                 }
        //             });
        //         });
        // });
        //
        // this.element.querySelectorAll('tbody tr').forEach(row => {
        //     const inputs = Array.from(row.querySelectorAll<HTMLInputElement>('td input'));
        //     if (! inputs[0].checked) {
        //         inputs.slice(1).forEach(checkbox => {
        //             checkbox.setAttribute('aria-disabled', 'true');
        //         });
        //     }
        // });
    }
}
