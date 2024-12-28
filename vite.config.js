import {defineConfig} from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import Components from 'unplugin-vue-components/vite';
import {PrimeVueResolver} from '@primevue/auto-import-resolver';
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            hotFile: 'public/vendor/ninshiki/ninshiki.hot',
            buildDirectory: '/vendor/ninshiki',
            input: ['resources/js/app.js', 'resources/css/ninshiki.css'],
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            resolvers: [
                PrimeVueResolver()
            ]
        })
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`
            }
        }
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname,'resources/js/Inertia'),
            '@util': path.resolve(__dirname,'resources/js/utils'),
            '@core': path.resolve(__dirname,'resources/js/ninshiki.js'),
        }
    }
})
