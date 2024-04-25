/* eslint-disable import/order */
import {createInertiaApp, Head, Link} from '@inertiajs/vue3'
import {Tooltip} from "chart.js";
import BadgeDirective from "primevue/badgedirective";
import Ripple from "primevue/ripple";
import StyleClass from "primevue/styleclass";
import {createApp, h} from 'vue' // 'vue/dist/vue.esm-bundler'
import i18n from '@/i18n'
import router from '@/router'

import vuetify from '@plugins/vuetify'
import {primeVue, primeOptions} from '@plugins/primevue';
// import Demo from '@/presets/demo';

import {createPinia} from 'pinia'
const store = createPinia()

import {loadFonts} from '@plugins/webfontloader'
loadFonts()

import Error from "@pages/Error.vue";
import Default from "@layouts/Default.vue";


/*
import '@/@iconify/icons-bundle'
*/

createInertiaApp({
	resolve: (name) => {
		const pages = import.meta.glob('./pages/**/*.vue', {eager: true})
		const dirs =	import.meta.glob('./pages/*/_index.vue', {eager: true})
		let page =
			pages[`./pages/${name}.vue`]?.default ??
			dirs[`./pages/${name}/_index.vue`]?.default
		if (!page) {
			console.error(`resolving page '${name}' failed`)
			page = Error
		}

		page.layout ??= Default
		return page
	},
	setup({el, App, props, plugin}) {
		createApp({render: () => h(App, props)})
			.use(plugin)
			.use(store)
			.use(router)
			.use(i18n)
			.use(vuetify)
			.use(primeVue, primeOptions)
			.directive('tooltip', Tooltip)
			.directive('badge', BadgeDirective)
			.directive('ripple', Ripple)
			.directive('styleclass', StyleClass)

			.component('Link', Link)
			.component('Head', Head)
			.mount(el)
	},
	title: (title) => `${title} - Demo`,
})