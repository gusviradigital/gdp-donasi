import { defineConfig } from 'vite'
import { resolve } from 'path'

export default defineConfig({
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'assets/js/app.js'),
      },
      output: {
        entryFileNames: `assets/js/[name].js`,
        chunkFileNames: `assets/js/[name].js`,
        assetFileNames: ({name}) => {
          if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')){
            return 'assets/images/[name][extname]';
          }
          if (/\.css$/.test(name ?? '')) {
            return 'assets/css/[name][extname]';
          }
          return 'assets/[name][extname]';
        },
      },
    },
  },
  plugins: [],
  server: {
    cors: true,
    strictPort: true,
    port: 3000,
    https: false,
    hmr: {
      host: 'localhost',
    },
  },
}) 