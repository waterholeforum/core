class DocumentTitle {
    private title: string = '';
    private count: number = 0;

    public initialize(): void {
        this.title = document.title;
        this.count = 0;
    }

    public increment(): void {
        this.count++;
        this.update();
    }

    public reset(): void {
        this.count = 0;
        this.update();
    }

    private update(): void {
        document.title = (this.count ? `(${this.count}) ` : '') + this.title;
    }
}

Waterhole.documentTitle = new DocumentTitle();

document.addEventListener('turbo:load', () => {
    Waterhole.documentTitle.initialize();
});

window.addEventListener('focus', () => {
    Waterhole.documentTitle.reset();
});
