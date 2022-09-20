const mix = require('laravel-mix');

mix.ts('resources/js/index.ts', 'resources/dist/index.js');
mix.ts('resources/js/admin/index.ts', 'resources/dist/admin.js');
