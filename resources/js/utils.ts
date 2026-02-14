/**
 * Determine whether the user is trying to open a link in a new tab.
 */
export function shouldOpenInNewTab(e: MouseEvent): boolean {
    return (
        e.altKey ||
        e.ctrlKey ||
        e.metaKey ||
        e.shiftKey ||
        (e.button !== undefined && e.button !== 0)
    );
}

/**
 * Determine if an element is currently visible in the viewport.
 */
export function isElementInViewport(
    el: HTMLElement,
    proportion: number = 1,
): boolean {
    const rect = el.getBoundingClientRect();

    return (
        -rect.top / rect.height < proportion &&
        (rect.bottom - window.innerHeight) / rect.height < proportion
    );
}

/**
 * Get the height of the page header.
 */
export function getHeaderHeight(): number {
    return document.getElementById('header')?.offsetHeight || 0;
}

/**
 * Create a slug out of the given string. Non-alphanumeric characters are
 * converted to hyphens.
 */
export function slug(string: string): string {
    return string
        .toLowerCase()
        .replace(/[^a-z0-9]/gi, '-')
        .replace(/-+/g, '-')
        .replace(/-$|^-/g, '');
}

/**
 * Clone a <template> element's content.
 */
export function cloneFromTemplate(id: string): HTMLElement {
    const template = document.getElementById(id) as HTMLTemplateElement;
    return template?.content?.firstElementChild?.cloneNode(true) as HTMLElement;
}

/**
 * Get a cookie by name.
 */
export function getCookie(name: string): string | null {
    const match = document.cookie.match(
        new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'),
    );
    return match ? decodeURIComponent(match[3]) : null;
}

/**
 * Build Stimulus definitions from an eager import.meta.glob map.
 */
export function buildStimulusDefinitions(
    controllers: Record<string, { default: any }>,
): { identifier: string; controllerConstructor: any }[] {
    return Object.entries(controllers).map(([path, module]) => {
        const identifier = path
            .match(/\.\/controllers\/(.*)\.ts$/)![1]
            .replace(/\//g, '--')
            .replace(/_/g, '-')
            .replace(/-controller$/, '');

        return { identifier, controllerConstructor: module.default };
    });
}

export function nextFrame() {
    return new Promise((resolve) => requestAnimationFrame(resolve));
}

export function promiseTimeout(ms: number) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}
