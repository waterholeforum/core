import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    set({ params: { name }}: any) {
        localStorage.setItem('theme', document.documentElement.dataset.theme = name);
    }
}
