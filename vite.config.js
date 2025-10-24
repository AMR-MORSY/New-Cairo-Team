import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";
import legacy from '@vitejs/plugin-legacy';


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
         legacy({
            targets: ['chrome >= 87'], // Adjust to your needs
            additionalLegacyPolyfills: ['regenerator-runtime/runtime']
        })
    ],
    server: {
        cors: true,
    },
});