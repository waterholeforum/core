declare module 'textarea-editor' {
    export default class TextareaEditor {
        constructor(el: HTMLElement);
        insert(text: string): TextareaEditor;
        toggle(format: string | object, ...args: any): TextareaEditor;
    }
}
