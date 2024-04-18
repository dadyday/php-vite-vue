/* eslint-disable import/order */
import {createInertiaApp, Head, Link} from '@inertiajs/vue3'
import {Tooltip} from "chart.js";
import BadgeDirective from "primevue/badgedirective";
import Ripple from "primevue/ripple";
import StyleClass from "primevue/styleclass";
import {createApp, h} from 'vue' // 'vue/dist/vue.esm-bundler'
import {createPinia} from 'pinia'
import i18n from '@/i18n'
import router from '@/router'

import vuetify from '@plugins/vuetify'
import primevue from '@plugins/primevue';


//import '@styles/main.scss'
//import '@core-scss/template/index.scss'
//import '@layouts/styles/index.scss'
//import '@styles/styles.scss'

import {loadFonts} from '@plugins/webfontloader'
loadFonts()

//import Layout from "@layouts/Sneat.vue";
import Layout from "@layouts/Sakai.vue";
import Error from "@pages/Error.vue";

/*
// import '@/@iconify/icons-bundle'
// import '@core-scss/template/index.scss'
// import '@layouts/styles/index.scss'
*/

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
			.use(router)
			.use(createPinia())
			.use(i18n)
			.use(vuetify)
			.use(primevue, {
				ripple: true,
				inputStyle: "filled",
			})
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