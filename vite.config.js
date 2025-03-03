import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { terser } from 'rollup-plugin-terser';

export default defineConfig(({ mode }) => ({
    plugins: [
        laravel({
            input: [
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: ['bootstrap', '@popperjs/core'],
    },
    build: {
        minify: mode === 'production' ? 'terser' : false,  // Use Terser in production
        rollupOptions: {
            plugins: [
                terser({
                    compress: {
                        drop_console: true, // Optionally remove console logs
                    },
                    format: {
                        comments: false,  // Remove comments
                    },
                }),
            ],
        },
    },
}));
