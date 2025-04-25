import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // Seulement si tu utilises Vue

export default defineConfig({
    plugins: [
        laravel({
            // Fichiers d'entrée CSS/JS
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true, // Active le rechargement automatique
        }),
        // vue() // Décommente si tu utilises Vue
    ],
    // Optimisation pour la production
    build: {
        chunkSizeWarningLimit: 1600, // Augmente la limite des chunks
    },
});