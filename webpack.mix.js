const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

mix.ts('resources/js/index.ts', 'resources/dist/index.js')
    .ts('resources/js/highlight.ts', 'resources/dist/highlight.js')
    .ts('resources/js/emoji.ts', 'resources/dist/emoji.js')
    .ts('resources/js/admin/index.ts', 'resources/dist/admin.js')

    .less('resources/less/forum/app.less', 'resources/dist/index.css')
    .less('resources/less/admin/app.less', 'resources/dist/admin.css');

if (process.env.ANALYZE) {
    mix.webpackConfig({
        plugins: [new BundleAnalyzerPlugin()],
    });
}
