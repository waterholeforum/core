declare module 'textarea-editor' {
    export default class TextareaEditor {
        constructor(el: HTMLElement);
        range(): [number, number];
        range(range: [number, number]): TextareaEditor;
        insert(text: string): TextareaEditor;
        toggle(format: string | object, ...args: any): TextareaEditor;
    }
}
