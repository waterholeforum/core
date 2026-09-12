import { defineConfig } from 'vitest/config';

export default defineConfig({
    test: {
        environment: 'jsdom',
        include: ['tests/Frontend/**/*.test.ts'],
        environmentOptions: {
            jsdom: { url: 'https://waterhole.test' },
        },
    },
});
