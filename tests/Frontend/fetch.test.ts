import { afterEach, beforeEach, describe, expect, test, vi } from 'vitest';

const url = 'https://waterhole.test/request';
const fetch = vi.fn<typeof globalThis.fetch>();
const show = vi.fn((alert: HTMLElement) => {
    document.getElementById('alerts')!.append(alert);
});

beforeEach(async () => {
    vi.resetModules();
    vi.clearAllMocks();
    document.body.innerHTML = '<div id="alerts"></div>';

    for (const id of [
        'forbidden-alert',
        'session-expired-alert',
        'too-many-requests-alert',
        'fatal-error-alert',
        'template-alert-danger',
    ]) {
        const template = document.createElement('template');
        template.id = id;
        template.innerHTML = `<div class="alert"><span class="alert__message">${id}</span></div>`;
        document.body.append(template);
    }

    document.cookie = 'XSRF-TOKEN=test-token; path=/';
    vi.stubGlobal('fetch', fetch);
    vi.stubGlobal('Waterhole', { alerts: { show } });
    await import('../../resources/js/bootstrap/fetch');
});

afterEach(() => {
    vi.unstubAllGlobals();
    document.cookie = 'XSRF-TOKEN=; max-age=0; path=/';
});

describe('Waterhole.fetch', () => {
    test('returns successful responses and sends the CSRF token', async () => {
        fetch.mockResolvedValueOnce(Response.json({ saved: true }));

        await expect(Waterhole.fetch(url).json()).resolves.toEqual({
            saved: true,
        });

        const request = new Request(...fetch.mock.calls[0]);
        expect(request.headers.get('X-XSRF-TOKEN')).toBe('test-token');
        expect(show).not.toHaveBeenCalled();
    });

    test.each([
        [401, 'forbidden-alert'],
        [403, 'forbidden-alert'],
        [419, 'session-expired-alert'],
        [429, 'too-many-requests-alert'],
        [500, 'fatal-error-alert'],
    ])('rejects HTTP %i and displays %s', async (status, template) => {
        fetch.mockResolvedValueOnce(
            new Response(null, { status: Number(status) }),
        );

        await expect(Waterhole.fetch(url, { retry: 0 })).rejects.toMatchObject({
            name: 'HTTPError',
            response: { status },
        });
        expect(
            document.querySelector('#alerts .alert__message')?.textContent,
        ).toBe(template);
        expect(show).toHaveBeenCalledExactlyOnceWith(expect.any(HTMLElement), {
            key: 'fetchError',
        });
    });

    test('rejects validation failures and displays the server message as text', async () => {
        const message = 'Invalid <strong>file</strong>.';
        fetch.mockResolvedValueOnce(
            Response.json({ message }, { status: 422 }),
        );

        await expect(Waterhole.fetch(url)).rejects.toMatchObject({
            name: 'HTTPError',
        });
        expect(
            document.querySelector('#alerts .alert__message')?.textContent,
        ).toBe(message);
        expect(document.querySelector('#alerts strong')).toBeNull();
    });

    test('uses the generic alert when a validation response cannot be parsed', async () => {
        fetch.mockResolvedValueOnce(
            new Response('not json', {
                status: 422,
                headers: { 'Content-Type': 'application/json' },
            }),
        );

        await expect(Waterhole.fetch(url)).rejects.toMatchObject({
            name: 'HTTPError',
        });
        expect(
            document.querySelector('#alerts .alert__message')?.textContent,
        ).toBe('fatal-error-alert');
    });

    test.each([
        [
            'network failure',
            () => new TypeError('Failed to fetch'),
            'NetworkError',
        ],
        [
            'cancellation',
            () => new DOMException('Cancelled', 'AbortError'),
            'AbortError',
        ],
    ])(
        'keeps %s silent while rejecting the request',
        async (_, error, name) => {
            fetch.mockRejectedValueOnce(error());

            await expect(
                Waterhole.fetch(url, { retry: 0 }),
            ).rejects.toMatchObject({ name });
            expect(show).not.toHaveBeenCalled();
        },
    );
});

describe('Waterhole.fetchError', () => {
    test('displays validation messages from raw responses', async () => {
        await Waterhole.fetchError(
            Response.json(
                { message: 'The title is required.' },
                { status: 422 },
            ),
        );

        expect(
            document.querySelector('#alerts .alert__message')?.textContent,
        ).toBe('The title is required.');
    });
});
