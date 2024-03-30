<script setup>
import {router} from "@inertiajs/vue3";
import {useDebouncedRefHistory, watchDebounced} from "@vueuse/core";

const path = '/entities'
defineProps(['items', 'pagination'])

// const { history, undo, redo, canUndo, canRedo } = useDebouncedRefHistory($$(filters.search), { debounce: 1500 })

//watchDebounced($$(filters), () => loadPage(1), {maxWait: 1500})
//watch($$(pagination), () => {
//	loadPage(pagination.currentPage)
//})

// loading

let loading = $ref(false)
function loadPage(page) {
	router.get(path, { page }, {
		replace: true,
		preserveState: true,
		preserveScroll: true,
		onStart: () => loading = true,
		onCancelToken: (token) => loading = token,
		onFinish: () => loading = false,
	})
}

function cancelLoading() {
	loading?.cancel()
}
/*
		x-search="filters.search"

		@update:options="loadPage(1)"

		item-value="name"
		density="comfortable"
		:show-current-page="true"
*/
</script>

<template>
	<VDataTableServer
		:items="items"
		:itemsLength="pagination.total"
		:page="pagination.page"
		@update:page="loadPage($event)"
		:items-per-page="pagination.perPage"
		x-update:options="loadPage($event)"
		:loading
	/>
</template>

<style scoped lang="scss">

</style>