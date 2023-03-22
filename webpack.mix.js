const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

mix.setPublicPath('resources/dist');

mix.ts('resources/js/index.ts', 'resources/dist');
mix.ts('resources/js/highlight.ts', 'resources/dist');
mix.ts('resources/js/emoji.ts', 'resources/dist');
mix.ts('resources/js/admin/index.ts', 'resources/dist/admin.js');

mix.css('resources/css/forum/app.css', 'resources/dist/index.css');
mix.css('resources/css/admin/app.css', 'resources/dist/admin.css');

if (process.env.ANALYZE) {
    mix.webpackConfig({
        plugins: [new BundleAnalyzerPlugin()],
    });
}
