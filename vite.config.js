import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { google } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

const fonts = [
    google('Inter', {
        weights: [400, 500, 600, 700],
        styles: ['normal', 'italic'],
        optimizedFallbacks: false,
    }),
    google('Outfit', { weights: [600, 700], optimizedFallbacks: false }),
    google('Plus Jakarta Sans', {
        weights: [600, 700],
        styles: ['normal'],
        optimizedFallbacks: false,
    }),
];

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // ponytail: public/ assets stay as runtime URLs; flip to bundled imports when hashing/caching needed.
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});