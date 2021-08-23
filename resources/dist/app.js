/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/inclusive-elements/dist/index.js":
/*!*******************************************************!*\
  !*** ./node_modules/inclusive-elements/dist/index.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "AlertsElement": () => (/* binding */ m),
/* harmony export */   "MenuElement": () => (/* binding */ c),
/* harmony export */   "ModalElement": () => (/* binding */ u),
/* harmony export */   "PopupElement": () => (/* binding */ d),
/* harmony export */   "TooltipElement": () => (/* binding */ l)
/* harmony export */ });
const t={x:{start:"left",Start:"Left",end:"right",End:"Right",size:"width",Size:"Width"},y:{start:"top",Start:"Top",end:"bottom",End:"Bottom",size:"height",Size:"Height"}};function e(e,i,n){var s;const o=i.style;Object.assign(o,{position:"absolute",maxWidth:"",maxHeight:""});let[r="bottom",a="center"]=n.placement.split("-");const h=["top","bottom"].includes(r)?"y":"x";let d=r===t[h].start?t[h].end:t[h].start;const l="x"===h?"y":"x",c=e.getBoundingClientRect(),u=(null===(s=function(t){for(;(t=t.parentNode)&&t instanceof Element;){const e=getComputedStyle(t).overflow;if(["auto","scroll"].includes(e))return t}}(i))||void 0===s?void 0:s.getBoundingClientRect())||new DOMRect(0,0,window.innerWidth,window.innerHeight),m=i.offsetParent||document.body,p=m===document.body?new DOMRect(-pageXOffset,-pageYOffset,window.innerWidth,window.innerHeight):m.getBoundingClientRect(),f=getComputedStyle(m),v=getComputedStyle(i);if(n.flip||void 0===n.flip){const e=t=>Math.abs(c[t]-u[t]),n=e(r);i["offset"+t[h].Size]>n&&e(d)>n&&([r,d]=[d,r])}if(i.dataset.placement=`${r}-${a}`,n.cap||void 0===n.cap){const e=(e,n)=>{const s=v["max"+t[e].Size];n-=parseInt(v["margin"+t[e].Start])+parseInt(v["margin"+t[e].End]),("none"===s||n<parseInt(s))&&(i.style["max"+t[e].Size]=n+"px")};e(h,Math.abs(u[r]-c[r])),e(l,u[t[l].size])}Object.assign(o,{[r]:"auto",[d]:(r===t[h].start?p[t[h].end]-c[t[h].start]:c[t[h].end]-p[t[h].start])-parseInt(f["border"+t[h].Start+"Width"])+"px"});const b="end"===a?"end":"start",E="end"===a?"start":"end",g=c[l]-p[l],w=c[t[l].size],y=i["offset"+t[l].Size];let L="end"===a?p[t[l].size]-g-w:g+("start"!==a?w/2-y/2:0);if(n.bound||void 0===n.bound){const e="end"===a?-1:1;L=Math.max(e*(u[t[l][b]]-p[t[l][b]]),Math.min(L,e*(u[t[l][E]]-p[t[l][b]])-y))}Object.assign(o,{[t[l][E]]:"auto",[t[l][b]]:L-parseInt(f["border"+t[l].Start+"Width"])+"px"})}function i(t,e,i={}){const n=new WeakMap,s=h(i);t instanceof HTMLCollection&&(t=Array.from(t)),t.forEach((t=>{"none"!==getComputedStyle(t).display&&n.set(t,t.getBoundingClientRect())})),e(),t.forEach((t=>{const e=n.get(t);if(!e)return;const i=e.left-t.getBoundingClientRect().left,o=e.top-t.getBoundingClientRect().top;if(!i&&!o)return;const r=t.style;r.transitionDuration="0s",r.transform=`translate(${i}px, ${o}px)`,document.body.offsetWidth,r.transitionDuration=r.transform="",t.classList.add(s+"move"),a(t,(()=>{t.classList.remove(s+"move")}))}))}function n(t){t._currentTransition&&(t.classList.remove(...["active","from","to"].map((e=>t._currentTransition+e))),t._currentTransition=null)}function s(t,e,i={}){const s=h(i)+e+"-",o=t.classList;var r;n(t),t._currentTransition=s,o.add(s+"active",s+"from"),r=()=>{o.add(s+"to"),o.remove(s+"from"),a(t,(()=>{o.remove(s+"to",s+"active"),t._currentTransition===s&&(i.finish&&i.finish(),t._currentTransition=null)}))},requestAnimationFrame((()=>{requestAnimationFrame(r)}))}function o(t,e={}){s(t,"enter",e)}function r(t,e={}){s(t,"leave",e)}function a(t,e){if(getComputedStyle(t).transitionDuration.startsWith("0s"))e();else{const i=()=>{e(),t.removeEventListener("transitionend",i),t.removeEventListener("transitioncancel",i)};t.addEventListener("transitionend",i),t.addEventListener("transitioncancel",i)}}function h(t){return t.prefix?t.prefix+"-":""}class d extends HTMLElement{constructor(){super();let t=this.attachShadow({mode:"open"});const e=document.createElement("template");e.innerHTML='<div part="backdrop" hidden style="position: fixed; top: 0; left: 0; right: 0; bottom: 0"></div><slot></slot>',t.appendChild(e.content.cloneNode(!0)),this.shadowRoot.firstElementChild.onclick=()=>this.open=!1}static get observedAttributes(){return["open"]}connectedCallback(){this.shadowRoot.firstElementChild.hidden=!0,this.menu.hidden=!0,this.button.setAttribute("aria-haspopup","true"),this.button.setAttribute("aria-expanded","false"),this.button.addEventListener("click",(()=>{this.open=!0})),this.button.addEventListener("keydown",(t=>{"ArrowDown"===t.key&&(t.preventDefault(),this.open=!0)})),this.addEventListener("keydown",(t=>{"Escape"===t.key&&this.open&&(t.preventDefault(),t.stopPropagation(),this.open=!1,this.button.focus())})),this.menu.addEventListener("click",(t=>{const e=t.target instanceof Element?t.target:null;("menuitem"===(null==e?void 0:e.getAttribute("role"))||"menuitemradio"===(null==e?void 0:e.getAttribute("role"))||(null==e?void 0:e.closest("[role=menuitem], [role=menuitemradio]")))&&(this.open=!1)})),this.open=!1}disconnectedCallback(){n(this.shadowRoot.firstElementChild),n(this.menu)}get button(){return this.querySelector("button, [role=button]")}get menu(){return this.children[1]}get open(){return this.hasAttribute("open")}set open(t){t?this.setAttribute("open",""):this.removeAttribute("open")}attributeChangedCallback(t,i,n){if("open"===t)if(null!==n){if(!this.menu.hidden)return;this.menu.hidden=!1,o(this.menu),this.button.setAttribute("aria-expanded","true"),e(this.button,this.menu,{placement:this.getAttribute("placement")||"bottom"});const t=this.shadowRoot.firstElementChild;t.hidden=!1,o(t),this.dispatchEvent(new Event("open"))}else if(!this.menu.hidden){this.button.setAttribute("aria-expanded","false");const t=this.shadowRoot.firstElementChild;r(t,{finish:()=>t.hidden=!0}),r(this.menu,{finish:()=>this.menu.hidden=!0}),this.dispatchEvent(new Event("close"))}}}class l extends HTMLElement{constructor(){super(...arguments),this.handleMouseEnter=this.afterDelay.bind(this,this.show),this.handleFocus=this.show.bind(this),this.handleMouseLeave=this.afterDelay.bind(this,this.hide),this.handleBlur=this.hide.bind(this),this.handleTouch=this.touched.bind(this),this.wasTouched=!1}connectedCallback(){this.parent&&(this.parent.tabIndex=this.parent.tabIndex||0,this.parent.addEventListener("touchstart",this.handleTouch),this.parent.addEventListener("mouseenter",this.handleMouseEnter),this.parent.addEventListener("focus",this.handleFocus),this.parent.addEventListener("mouseleave",this.handleMouseLeave),this.parent.addEventListener("blur",this.handleBlur),this.parent.addEventListener("click",this.handleBlur),this.observer=new MutationObserver((t=>{t.forEach((t=>{"disabled"===t.attributeName&&this.hide()}))})),this.observer.observe(this.parent,{attributes:!0})),document.addEventListener("touchstart",this.handleBlur)}disconnectedCallback(){this.hide(),this.observer.disconnect(),this.parent&&(this.parent.removeEventListener("touchstart",this.handleTouch),this.parent.removeEventListener("mouseenter",this.handleMouseEnter),this.parent.removeEventListener("focus",this.handleFocus),this.parent.removeEventListener("mouseleave",this.handleMouseLeave),this.parent.removeEventListener("blur",this.handleBlur),this.parent.removeEventListener("click",this.handleBlur)),document.removeEventListener("touchstart",this.handleBlur)}get parent(){return this.parentNode}touched(t){t.stopPropagation(),this.show(),this.wasTouched=!0}show(){const t=this.createTooltip();clearTimeout(this.timeout),t.hidden=!1,o(t),t.innerHTML=this.innerHTML,e(this.parent,t,{placement:this.getAttribute("placement")||l.placement})}hide(){this.wasTouched?this.wasTouched=!1:(clearTimeout(this.timeout),this.tooltip&&r(this.tooltip,{finish:()=>{this.tooltip&&(this.tooltip.hidden=!0)}}))}afterDelay(t){clearTimeout(this.timeout);const e=parseInt(this.getAttribute("delay")||"");this.timeout=window.setTimeout(t.bind(this),isNaN(e)?l.delay:e)}createTooltip(){return this.tooltip||(this.tooltip=document.createElement("div"),this.tooltip.className=this.getAttribute("tooltip-class")||l.tooltipClass,this.tooltip.hidden=!0,document.body.appendChild(this.tooltip)),this.tooltip}}l.delay=100,l.placement="top",l.tooltipClass="tooltip";class c extends HTMLElement{constructor(){super(...arguments),this.search="",this.handleKeydown=t=>{if(!this.hidden)if("ArrowUp"===t.key)this.navigate(-1),t.preventDefault();else if("ArrowDown"===t.key)this.navigate(1),t.preventDefault();else{if(t.key.length>1)return;if(t.ctrlKey||t.metaKey||t.altKey)return;t.preventDefault(),this.search+=t.key.toLowerCase(),clearTimeout(this.searchTimeout),this.searchTimeout=window.setTimeout((()=>{this.search=""}),c.searchDelay),this.focusableItems.some((t=>{var e;if(0===(null===(e=t.textContent)||void 0===e?void 0:e.trim().toLowerCase().indexOf(this.search)))return t.focus(),!0}))}}}connectedCallback(){this.setAttribute("role","menu"),Array.from(this.focusableItems).forEach((t=>{t.setAttribute("tabindex","-1")})),document.addEventListener("keydown",this.handleKeydown)}disconnectedCallback(){document.removeEventListener("keydown",this.handleKeydown)}navigate(t){const e=this.focusableItems;let i=document.activeElement instanceof HTMLElement?e.indexOf(document.activeElement):-1;i+=t,i<0&&(i=e.length-1),i>=e.length&&(i=0),e[i]&&e[i].focus()}get focusableItems(){return Array.from(this.querySelectorAll("[role^=menuitem]"))}}c.searchDelay=800;class u extends HTMLElement{constructor(){var t;super(),this.inertCache=new Map;let e=this.attachShadow({mode:"open"});const i=document.createElement("template");i.innerHTML='<div part="backdrop" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0"></div><div part="content" style="position: relative"><slot></slot></div>',e.appendChild(i.content.cloneNode(!0)),null===(t=e.querySelector("[part=backdrop]"))||void 0===t||t.addEventListener("click",(()=>{this.hasAttribute("static")?u.attention&&u.attention(e.children[1]):this.close()}))}static get observedAttributes(){return["open"]}connectedCallback(){this.firstElementChild&&(this.firstElementChild.setAttribute("role","dialog"),this.firstElementChild.setAttribute("aria-modal","true"),this.firstElementChild.setAttribute("tabindex","-1")),this.addEventListener("keydown",(t=>{"Escape"!==t.key||this.hidden||(t.preventDefault(),t.stopPropagation(),this.close())}))}disconnectedCallback(){this.undoInert()}get open(){return this.hasAttribute("open")}set open(t){t?this.setAttribute("open",""):this.removeAttribute("open")}close(){if(!this.open)return;const t=new Event("beforeclose",{cancelable:!0});this.dispatchEvent(t)&&(this.open=!1)}undoInert(){this.inertCache.forEach(((t,e)=>{e.inert=!1})),this.inertCache.clear()}attributeChangedCallback(t,e,i){var n,s,a;if("open"===t)if(null!==i){this.trigger=document.activeElement,this.hidden=!1,o(this);const t=this.querySelector("[autofocus]");t?t.focus():null===(n=this.firstElementChild)||void 0===n||n.focus(),Array.from(document.body.children).filter((t=>t!==this)).forEach((t=>{this.inertCache.set(t,t.inert),t.inert=!0})),this.dispatchEvent(new Event("open"))}else this.undoInert(),this.trigger&&("menuitem"===this.trigger.getAttribute("role")?null===(a=null===(s=this.trigger.parentElement)||void 0===s?void 0:s.previousElementSibling)||void 0===a||a.focus():this.trigger.focus()),r(this,{finish:()=>this.hidden=!0}),this.dispatchEvent(new Event("close"))}}u.attention=t=>t.animate([{transform:"scale(1)"},{transform:"scale(1.1)"},{transform:"scale(1)"}],300);class m extends HTMLElement{constructor(){super(...arguments),this.timeouts=new Map,this.index=0}connectedCallback(){this.setAttribute("role","status"),this.setAttribute("aria-live","polite"),this.setAttribute("aria-relevant","additions")}show(t,e={}){const n=e.key||String(this.index++);this.dismiss(n),t.dataset.key=n,i(this.children,(()=>{this.append(t),o(t)}));const s=void 0!==e.duration?Number(e.duration):m.duration;return s>0&&(this.startTimeout(t,s),t.addEventListener("mouseenter",this.clearTimeout.bind(this,t)),t.addEventListener("focusin",this.clearTimeout.bind(this,t)),t.addEventListener("mouseleave",this.startTimeout.bind(this,t,s)),t.addEventListener("focusout",this.startTimeout.bind(this,t,s))),n}dismiss(t){if("string"!=typeof t)i(this.children,(()=>{r(t,{finish:()=>this.removeChild(t)})})),this.clearTimeout(t);else{const e=this.querySelector(`[data-key="${t}"]`);e&&this.dismiss(e)}}speak(t){const e=document.createElement("div");Object.assign(e.style,{clip:"rect(0 0 0 0)",clipPath:"inset(50%)",height:"1px",overflow:"hidden",position:"absolute",whiteSpace:"nowrap",width:"1px"}),e.textContent=t,this.show(e)}startTimeout(t,e){this.clearTimeout(t),this.timeouts.set(t,window.setTimeout((()=>{this.dismiss(t)}),e))}clearTimeout(t){this.timeouts.has(t)&&clearTimeout(this.timeouts.get(t))}}m.duration=1e4;


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var inclusive_elements__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! inclusive-elements */ "./node_modules/inclusive-elements/dist/index.js");
// import * as Turbo from '@hotwired/turbo';
// import './bootstrap';
// import './elements/turbo-echo-stream-tag';
// Turbo.start();
// window.Turbo = Turbo;

window.customElements.define('ui-popup', inclusive_elements__WEBPACK_IMPORTED_MODULE_0__.PopupElement);
window.customElements.define('ui-menu', inclusive_elements__WEBPACK_IMPORTED_MODULE_0__.MenuElement);
window.customElements.define('ui-modal', inclusive_elements__WEBPACK_IMPORTED_MODULE_0__.ModalElement);
window.customElements.define('ui-tooltip', inclusive_elements__WEBPACK_IMPORTED_MODULE_0__.TooltipElement);
window.customElements.define('ui-alerts', inclusive_elements__WEBPACK_IMPORTED_MODULE_0__.AlertsElement);
var header = document.querySelector('.header');
window.addEventListener('scroll', function () {
  header.classList.toggle('is-sticky', pageYOffset > 0);
});
})();

/******/ })()
;