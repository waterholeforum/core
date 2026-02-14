import TextExpanderElement from '@github/text-expander-element';
import { Controller } from '@hotwired/stimulus';
import * as Turbo from '@hotwired/turbo';

interface UserLookupResult {
    id: number;
    name: string;
    value: string;
    html: string;
    commentUrl?: string;
    frameId?: string;
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
        this.element.addEventListener(
            'text-expander-change',
            this.onTextExpanderChange,
        );
        this.element.addEventListener(
            'text-expander-value',
            this.onTextExpanderValue,
        );
        this.element.addEventListener(
            'text-expander-committed',
            this.onTextExpanderCommitted,
        );
    }

    disconnect() {
        this.element.removeEventListener(
            'text-expander-change',
            this.onTextExpanderChange,
        );
        this.element.removeEventListener(
            'text-expander-value',
            this.onTextExpanderValue,
        );
        this.element.removeEventListener(
            'text-expander-committed',
            this.onTextExpanderCommitted,
        );
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
                        ...json.map(
                            ({ name, value, html, commentUrl, frameId }) => {
                                const option = document.createElement('li');
                                option.setAttribute('role', 'option');
                                option.id = `suggestion-${Math.floor(
                                    Math.random() * 100000,
                                ).toString()}`;
                                option.className = 'menu-item';
                                option.dataset.value = value || name;
                                option.dataset.commentUrl = commentUrl || '';
                                option.dataset.frameId = frameId || '';
                                option.innerHTML = html;

                                return option;
                            },
                        ),
                    );

                    const observer = new MutationObserver(() => {
                        if (
                            listbox.getBoundingClientRect().bottom >
                            window.innerHeight
                        ) {
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
                }),
        );
    }) as EventListener;

    private onTextExpanderValue = ((event: CustomEvent) => {
        const { item } = event.detail;

        event.detail.value = '@' + item.dataset.value.replace(/ /g, '\xa0');

        const { commentUrl, frameId } = item.dataset;
        if (commentUrl) {
            Turbo.visit(commentUrl, { frame: frameId });
        }
    }) as EventListener;

    // Trigger an input event after inserting a mention, so that the text editor
    // preview is triggered to update.
    private onTextExpanderCommitted = ((event: CustomEvent) => {
        const { input } = event.detail;
        input.dispatchEvent(new CustomEvent('input'));
    }) as EventListener;
}
