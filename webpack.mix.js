const mix = require('laravel-mix');

mix.ts('resources/js/index.ts', 'resources/dist/index.js')
    .ts('resources/js/admin/index.ts', 'resources/dist/admin.js')
.less('resources/less/forum/app.less', 'resources/dist/index.css')
.less('resources/less/admin/app.less', 'resources/dist/admin.css');
