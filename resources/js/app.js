// import * as Turbo from '@hotwired/turbo';

// import './bootstrap';
// import './elements/turbo-echo-stream-tag';

// Turbo.start();
//
// window.Turbo = Turbo;
//
// document.addEventListener('turbo:submit-start', e => {
//     e.detail.formSubmission.submitter.disabled = true;
// });

import { PopupElement, MenuElement, ModalElement, TooltipElement, AlertsElement } from 'inclusive-elements';

window.customElements.define('ui-popup', PopupElement);
window.customElements.define('ui-menu', MenuElement);
window.customElements.define('ui-modal', ModalElement);
window.customElements.define('ui-tooltip', TooltipElement);
window.customElements.define('ui-alerts', AlertsElement);

const header = document.querySelector('.header');
const scroll = () => header.classList.toggle('is-sticky', pageYOffset > 0);
window.addEventListener('scroll', scroll);
scroll();
