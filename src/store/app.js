import {usePage} from "@inertiajs/vue3";
import {useSessionStorage} from "@vueuse/core";
import {defineStore} from "pinia";
import {computed, ref} from "vue";
import menu from "./app/appMenu"; // demoMenu
import desk from "./app/appDesk"; // demoDesk

import Sneat from "@layouts/Sneat.vue";
import Sakai from "@layouts/Sakai.vue";


export const useAppStore = defineStore('app', () => {

	// user related
	const user = usePage().props.user
	const userMenu = computed(() => menu.filter((item) => {
		let ok = !item.roles?.length // allow if no role defined
		if (!ok) item.roles.forEach((role) => ok |= user.roles.includes(role))
		return ok
	}))
	const userDesk = useSessionStorage('default-desk', desk)
	//const userDesk = ref(desk)
	function resetUserDesk() {
		userDesk.value = null
	}

	// layout
	const layouts = {
		sneat: Sneat,
		sakai: Sakai,
	}
	const layoutName = useSessionStorage('default-layout', 'sneat')
	const layout = computed(() => layouts[layoutName.value])

	return {
		user, userMenu, userDesk, resetUserDesk,
		layouts, layoutName, layout
	}
})
