import { defineConfig, type UserConfig } from 'tsdown';

const dev = process.env.DEV === '1';

function defineBundle(
    name: string,
    path: string,
    options: Partial<UserConfig> = {},
): UserConfig {
    return {
        name,
        entry: { [name]: path },
        platform: 'browser',
        target: false,
        format: 'iife',
        deps: { onlyBundle: false },
        minify: !dev,
        clean: !dev,
        outDir:
            process.env.DIST === '1' ? 'resources/dist' : 'resources/dist-dev',
        outputOptions: { entryFileNames: '[name].js' },
        css: {
            transformer: 'postcss',
            fileName: `${name}.css`,
            minify: !dev,
        },
        ...options,
    };
}

export default defineConfig([
    defineBundle('global', 'resources/js/index.ts', { watch: dev }),
    defineBundle('cp', 'resources/js/cp/index.ts', { watch: dev }),
    defineBundle('emoji', 'resources/js/emoji.ts'),
    defineBundle('highlight', 'resources/js/highlight.ts'),
    defineBundle('lightbox', 'resources/js/lightbox.ts'),
]);
