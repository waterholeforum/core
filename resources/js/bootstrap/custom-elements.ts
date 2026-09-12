import '@github/text-expander-element';
import {
    AlertsElement,
    MenuElement,
    ModalElement,
    PopupElement,
    TabsElement,
    ToolbarElement,
    TooltipElement,
} from 'inclusive-elements';
import { ComboboxElement } from 'inclusive-elements-next';

window.customElements.define('ui-alerts', AlertsElement);
window.customElements.define('ui-menu', MenuElement);
window.customElements.define('ui-modal', ModalElement);
window.customElements.define('ui-popup', PopupElement);
window.customElements.define('ui-tabs', TabsElement);
window.customElements.define('ui-toolbar', ToolbarElement);
window.customElements.define('ui-tooltip', TooltipElement);

// Keep native controls when the positioning in _combobox.css is unavailable.
if (
    CSS.supports('position-area', 'block-end span-inline-start') &&
    CSS.supports('width', 'anchor-size(width)')
) {
    window.customElements.define('ui-combobox', ComboboxElement);
}
