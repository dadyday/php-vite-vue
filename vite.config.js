import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import { fileURLToPath } from 'node:url'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import Icons from 'unplugin-icons/vite'
import IconsResolver from 'unplugin-icons/resolver'
import { defineConfig } from 'vite'
import vuetify from 'vite-plugin-vuetify'
import laravel from 'laravel-vite-plugin'
import vueI18n from '@intlify/unplugin-vue-i18n/vite'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    laravel({
      input: ['src/vue/main.js'],
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
		vueI18n({
			runtimeOnly: false,
			include: 'src/vue/i18n/locales/**'
			// resolve(dirname(fileURLToPath(import.meta.url)), './src/vue/i18n/locales/**')
		}),
    vueJsx(),

    // Docs: https://github.com/vuetifyjs/vuetify-loader/tree/master/packages/vite-plugin
    vuetify({
      styles: {
        configFile: 'src/styles/variables/_vuetify.scss',
      },
    }),
    Components({
      dirs: ['src/vue/@core/components'],
      dts: true,
			resolvers: [
				IconsResolver({
					prefix: 'icon',
					alias: {
						flag: 'circle-flags',
					}
				})
			],
    }),

		Icons({
			compiler: 'vue3',
			autoInstall: true,
		}),

    // Docs: https://github.com/antfu/unplugin-auto-import#unplugin-auto-import
    AutoImport({
      eslintrc: {
        enabled: true,
        filepath: './.eslintrc-auto-import.json',
      },
      imports: ['vue', 'vue-router', '@vueuse/core', '@vueuse/math', 'pinia'],
      vueTemplate: true,
    }),
  ],
  define: { 'process.env': {} },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src/vue', import.meta.url)),
      '@core-scss': fileURLToPath(new URL('./src/styles/@core', import.meta.url)),
      '@core': fileURLToPath(new URL('./src/vue/@core', import.meta.url)),
      '@layouts': fileURLToPath(new URL('./src/vue/layouts', import.meta.url)),
      '@pages': fileURLToPath(new URL('./src/vue/pages', import.meta.url)),
      '@images': fileURLToPath(new URL('./src/images/', import.meta.url)),
      '@styles': fileURLToPath(new URL('./src/styles/', import.meta.url)),
      '@configured-variables': fileURLToPath(new URL('./src/styles/variables/_template.scss', import.meta.url)),
      '@axios': fileURLToPath(new URL('./src/vue/plugins/axios', import.meta.url)),
      'apexcharts': fileURLToPath(new URL('node_modules/apexcharts-clevision', import.meta.url)),
    },
  },
  build: {
    chunkSizeWarningLimit: 5000,
  },
  optimizeDeps: {
    exclude: ['vuetify'],
    entries: [
      './src/vue/**/*.vue',
    ],
  },
})
