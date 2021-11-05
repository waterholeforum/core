export function shouldOpenInNewTab(e: MouseEvent): boolean {
    return e.altKey || e.ctrlKey || e.metaKey || e.shiftKey
        || (e.button !== undefined && e.button !== 0);
}

export function isElementInViewport(el: HTMLElement, proportion: number = 1): boolean {
    const rect = el.getBoundingClientRect();

    return -rect.top / rect.height < proportion
        && (rect.bottom - window.innerHeight) / rect.height < proportion;
}

export function getHeaderHeight(): number {
    return document.querySelector<HTMLElement>('.header')?.offsetHeight || 0;
}
