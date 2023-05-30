import { StreamElement } from '@hotwired/turbo';
import hljs from 'highlight.js/lib/common';

(window as any).hljs = hljs;

document.addEventListener('turbo:load', () => hljs.highlightAll());
document.addEventListener('turbo:frame-load', () => hljs.highlightAll());
document.addEventListener('turbo:before-stream-render', (e) => {
    const { detail } = e as CustomEvent;
    const fallback = detail.render;

    detail.render = (stream: StreamElement) => {
        fallback(stream);
        hljs.highlightAll();
    };
});
