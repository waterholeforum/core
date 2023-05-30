import TextExpanderElement from '@github/text-expander-element';
import { Controller } from '@hotwired/stimulus';

interface UserLookupResult {
    id: number;
    name: string;
    html: string;
}

/**
 * Controller to enable @mention suggestions on a <text-expander> element.
 *
 * @internal
 */
export default class extends Controller<TextExpanderElement> {
    static values = {
        userLookupUrl: String,
    };

    declare readonly userLookupUrlValue: string;

    connect() {
        this.element.addEventListener('text-expander-change', this.onTextExpanderChange);
        this.element.addEventListener('text-expander-value', this.onTextExpanderValue);
    }

    disconnect() {
        this.element.removeEventListener('text-expander-change', this.onTextExpanderChange);
        this.element.removeEventListener('text-expander-value', this.onTextExpanderValue);
    }

    private onTextExpanderChange = ((event: CustomEvent) => {
        const { provide, text } = event.detail;

        const url = new URL(this.userLookupUrlValue);
        url.searchParams.append('q', text);

        provide(
            Waterhole.fetch(url)
                .json<UserLookupResult[]>()
                .then((json) => {
                    const listbox = document.createElement('ul');
                    listbox.setAttribute('role', 'listbox');
                    listbox.className = 'menu';
                    listbox.style.position = 'absolute';
                    listbox.style.marginTop = '24px';

                    listbox.append(
                        ...json.map(({ name, html }) => {
                            const option = document.createElement('li');
                            option.setAttribute('role', 'option');
                            option.id = `suggestion-${Math.floor(
                                Math.random() * 100000
                            ).toString()}`;
                            option.className = 'menu-item';
                            option.dataset.value = name;
                            option.innerHTML = html;
                            return option;
                        })
                    );

                    const observer = new MutationObserver(() => {
                        if (listbox.getBoundingClientRect().bottom > window.innerHeight) {
                            listbox.style.transform = 'translateY(-100%)';
                            listbox.style.marginTop = '-12px';
                        }
                    });

                    observer.observe(listbox, {
                        attributes: true,
                        attributeFilter: ['style'],
                    });

                    return {
                        matched: Boolean(json.length),
                        fragment: listbox,
                    };
                })
        );
    }) as EventListener;

    private onTextExpanderValue = ((event: CustomEvent) => {
        const { item } = event.detail;
        event.detail.value = '@' + item.getAttribute('data-value').replace(/ /g, '\xa0');
    }) as EventListener;
}
