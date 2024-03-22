/* eslint-disable import/order */
import {createInertiaApp, Head, Link} from '@inertiajs/vue3'
import {createApp, h} from 'vue'
import Layout from "@/layouts/default.vue";

import router from '@/router'
import { createPinia } from 'pinia'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'

import '@/@iconify/icons-bundle'
import '@core-scss/template/index.scss'
import './layouts/styles/index.scss'
import '@styles/styles.scss'

loadFonts()


createInertiaApp({
    resolve: (name) => {
        return import(`./pages/${name}.vue`).then((page) => {
            page.default.layout ??= Layout
            return page
        })
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(router)
            .use(createPinia())
            .use(vuetify)
            .component('Link', Link)
            .component('Head', Head)
            .mount(el)
    },
    title: (title) => `${title} - Demo`,
})