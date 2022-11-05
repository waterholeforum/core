import hljs from 'highlight.js/lib/common';

document.addEventListener('turbo:load', () => hljs.highlightAll());
document.addEventListener('turbo:frame-load', () => hljs.highlightAll());
