import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig(({ mode }) => {
  const targets = {
    index: { entry: 'resources/js/index.ts', name: 'Waterhole', css: 'global' },
    cp: { entry: 'resources/js/cp/index.ts', name: 'WaterholeCP', css: 'cp' },
    highlight: { entry: 'resources/js/highlight.ts', name: 'WH_Highlight' },
    emoji: { entry: 'resources/js/emoji.ts', name: 'WH_Emoji' },
  };

  const targetName = targets[mode] ? mode : 'index';
  const target = targets[targetName];

  return {
    publicDir: false,
    resolve: {
      alias: { '@': path.resolve(__dirname, 'resources/js') },
    },
    build: {
      outDir: 'resources/dist',
      emptyOutDir: false,
      lib: {
        entry: path.resolve(__dirname, target.entry),
        name: target.name,
        formats: ['iife'],
        fileName: () => `${targetName}.js`,
        cssFileName: target.css || targetName,
      },
      rollupOptions: {
        output: {
          inlineDynamicImports: false,
        },
      },
    },
  };
});
