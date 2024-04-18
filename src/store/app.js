import {usePage} from "@inertiajs/vue3";
import {defineStore} from "pinia";
import {computed, ref} from "vue";

const menu = [
	{ title: 'Entities'    , icon: 'bx-atom'                , to: '/entities' },
	{ title: 'Foo'         , icon: 'bx-home'                , to: '/foo', roles: [] },
	// { title: 'Bar'      , icon: 'mdi-account-cog-outline', to: '/bar', roles: ['admin'] },
	{ heading: 'Pages' },
	{ title: 'Login'       , icon: 'bx-log-in'              , to: '/login' },
	{ title: 'Register'    , icon: 'bx-user-plus'           , to: '/register' },
	{ title: 'Error'       , icon: 'bx-info-circle'         , to: '/no-existence' },
	{ heading: 'User' },
	{ title: 'Typography'  , icon: 'mdi-alpha-t-box-outline', to: '/typography' },
	{ title: 'Icons'       , icon: 'bx-show'                , to: '/icons' },
	{ title: 'Cards'       , icon: 'bx-credit-card'         , to: '/cards' },
	{ title: 'Tables'      , icon: 'bx-table'               , to: '/tables' },
	{ title: 'Form Layouts', icon: 'mdi-form-select'        , to: '/form-layouts' },
];

export const useAppStore = defineStore('app', () => {

	const user = usePage().props.user
	const userMenu = computed(() => menu.filter((item) => {
		let ok = !item.roles?.length // allow if no role defined
		if (!ok) item.roles.forEach((role) => ok |= user.roles.includes(role))
		return ok
	}))

	return {
		user, userMenu
	}
})
