import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';

export default defineConfig({
  base: '/punch-wp-angelathetton/wp-content/themes/sage-angelathetton-theme/public/build/',
  plugins: [
    laravel({
      input: [
        'resources/css/app.scss',
        'resources/js/app.js',
        'resources/css/editor.scss',
        'resources/js/editor.js',
      ],
      refresh: true,
    }),

    wordpressPlugin(),
    // Generate the theme.json file in the public/build/assets directory
    // based on the Tailwind config and the theme.json file from base theme folder
    wordpressThemeJson({
      disableTailwindColors: true,
      disableTailwindFonts: true,
      disableTailwindFontSizes: true,
    }),
  ],
  resolve: {
    alias: {
      '@scripts': '/resources/js',
      '@styles': '/resources/css',
      '@fonts': '/resources/fonts',
      '@images': '/resources/images',
    },
  },
  // ✅ ADD THIS SECTION
  css: {
    preprocessorOptions: {
      scss: {
        quietDeps: true, // hides bootstrap sass warnings
      },
    },
  },
  /*server: {
    host: '192.168.10.147',
    port: 5173,
    cors: true,
  },*/
})
