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
        // Bind IPv4 explicitly. Left to itself Vite listens on [::1] only, and
        // the browser is on http://127.0.0.1:8000 -- a different origin, so the
        // module scripts never load and Livewire never boots. The symptom is a
        // form falling back to a native POST.
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: '127.0.0.1',
        },
    },
});
