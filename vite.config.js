import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default {
    css: {
      postcss: {},
    },
    server: {
      hmr: {
        overlay: false, // Disable overlay for errors
      },
    },
  };