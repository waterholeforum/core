import { defineConfig, UserConfig } from 'tsdown';
import postcss from 'rollup-plugin-postcss';

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
        format: 'iife',
        inlineOnly: false,
        minify: !dev,
        clean: !dev,
        outDir: 'resources/dist',
        outputOptions: { entryFileNames: '[name].js' },
        plugins: [postcss({ extract: true, minimize: !dev })],
        ...options,
    };
}

export default defineConfig([
    defineBundle('global', 'resources/js/index.ts', { watch: dev }),
    defineBundle('cp', 'resources/js/cp/index.ts', { watch: dev }),
    defineBundle('emoji', 'resources/js/emoji.ts'),
    defineBundle('highlight', 'resources/js/highlight.ts'),
]);
