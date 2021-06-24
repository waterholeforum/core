import hljs from 'highlight.js/lib/common';

(window as any).hljs = hljs;

document.addEventListener('turbo:load', () => hljs.highlightAll());
document.addEventListener('turbo:frame-load', () => hljs.highlightAll());
