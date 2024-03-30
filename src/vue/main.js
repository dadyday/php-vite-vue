/* eslint-disable import/order */
import {createInertiaApp, Head, Link} from '@inertiajs/vue3'
import {createApp, h} from 'vue' // 'vue/dist/vue.esm-bundler'
import Layout from "@layouts/Dashboard.vue";
import Error from "@pages/Error.vue";

// import router from '@/router'
import {createPinia} from 'pinia'
import vuetify from '@/plugins/vuetify'
import {loadFonts} from '@/plugins/webfontloader'
import i18n from '@/i18n'

import '@/@iconify/icons-bundle'
import '@core-scss/template/index.scss'
import '@layouts/styles/index.scss'
import '@styles/styles.scss'

loadFonts()


createInertiaApp({
	resolve: (name) => {
		const pages = import.meta.glob('./pages/*.vue', {eager: true})
		const dirs =	import.meta.glob('./pages/*/_index.vue', {eager: true})
		let page =
			pages[`./pages/${name}.vue`]?.default ??
			dirs[`./pages/${name}/_index.vue`]?.default
		if (!page) {
			console.error(`resolving page '${name}' failed`)
			page = Error
		}
		page.layout ??= Layout
		return page
	},
	setup({el, App, props, plugin}) {
		createApp({render: () => h(App, props)})
			.use(plugin)
			//.use(router)
			.use(createPinia())
			.use(i18n)
			.use(vuetify)
			.component('Link', Link)
			.component('Head', Head)
			.mount(el)
	},
	title: (title) => `${title} - Demo`,
})