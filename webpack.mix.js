const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

mix.setPublicPath('resources/dist');

mix.ts('resources/js/index.ts', 'resources/dist');
mix.ts('resources/js/highlight.ts', 'resources/dist');
mix.ts('resources/js/emoji.ts', 'resources/dist');
mix.ts('resources/js/cp/index.ts', 'resources/dist/cp.js');

mix.css('resources/css/global/app.css', 'resources/dist/global.css');
mix.css('resources/css/cp/app.css', 'resources/dist/cp.css');

if (process.env.ANALYZE) {
    mix.webpackConfig({
        plugins: [new BundleAnalyzerPlugin()],
    });
}
