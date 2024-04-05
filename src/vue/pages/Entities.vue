<script setup>
import {router} from "@inertiajs/vue3";
import _ from "lodash";
import moment from "moment";
import Pagination from "./entities/Pagination.vue";
import {debouncedRef, refDebounced, useDebounce} from "@vueuse/core";

const path = '/entities'
const props = defineProps(['items', 'pagination', 'search', 'filters', 'orders', 'user'])

let focusses = reactive({})

// search
//let searchText = $ref(props.search)

// filter
// let createdDate = $computed({
// 	get: () => filters.created,
// 	set: (v) => filters.created = v,
// })
// let createdFocus = $ref()

// loading
let page = $ref(props.pagination.page)
let perPage = $ref(props.pagination.perPage)
let search = $ref(props.search ?? '')
let filters = $ref(props.filters ?? {})
let orders = $ref(props.orders ?? [])
let sleep = $ref(0)

let loading = $ref(null)
function loadPage(params) {
	//if (loading === null) return;
	router.get(path, params, {
		replace: true,
		preserveState: true,
		preserveScroll: true,
		onStart: () => loading = true,
		onFinish: () => loading = false,
		// onCancelToken: (token) => loading = token.cancel,
	})
}

watch([
	$$(page),
	$$(perPage),
	refDebounced($$(search), 1000),
	filters,
	$$(orders),
], (...args) => {
	console.log(args)
	const order = _.map(orders, ({key, order}) => order === 'asc' || !order ? key : '!'+key).join(',')
	const filter = _.map(filters, (value, key) => value ? key+':'+value : undefined).join(';')
	loadPage({ page, perPage, search, filter, order, sleep })
})

onMounted(() => {
	loading = false
})

// items
const headers = [
	{
		title: 'Name',
		value: 'name',
		sortable: true,
		fixed: true,
		width: '15em'
	},
	{
		title: 'Created',
		key: 'created',
		value: (item) => moment(item.created).format('dd, D. MMM YY'),
		align: 'end',
		sortable: true
	},
	{
		title: 'State',
		value: 'state',
		sortable: true,
		align: 'center',
		width: '5em'
	},
]

const stateColors = {
	open: '#080',
	waiting: '#f80',
	closed: '#f00',
}

// const { history, undo, redo, canUndo, canRedo } = useDebouncedRefHistory($$(filters.search), { debounce: 1500 })

</script>

<template>
	<Head :title="`Entities - Seite ${page}` + (search ? ` - Suche '${search}'` : '')"/>
	<div>{{ orders }}</div>
	<div>{{ filters }}</div>
	<VContainer>
		<VRow>
			<VSpacer />
			<VCol :cols="focusses.created || filters.created ? 3 : 2" class="smooth-grow">
				<VTextField
					type="date"
					v-model="filters.created"
					v-model:focused="focusses.created"
					prependInnerIcon="bx-calendar"
					label="Filter"
					placeholder=""
					clearable
				/>

			</VCol>
			<!--VCol :cols="createdFocus || createdDate ? 4 : 2" class="smooth-grow">
				<VTextField
					v-model="createdDate"
					:x-focused="createdFocus"
					label="Filter"
					prependInnerIcon="bx-calendar"
					density="compact"
					x-readonly
					clearable
				>
					<VMenu
						v-model="createdFocus"
						activator="parent"
						transition="scale-transition"
					>
						<VDatePicker
							v-if="createdFocus"
							v-model="createdDate"
							hideHeader
							showWeek
							showAdjacentMonths
							tile
							x-landscape
							x-fullWidth
						></VDatePicker>
					</VMenu>
				</VTextField>
			</VCol -->
			<VCol :cols="focusses.search || search ? 4 : 2" class="smooth-grow">
				<VTextField
					v-model="search"
					v-model:focused="focusses.search"
					label="Suche"
					prependInnerIcon="bx-search"
					density="compact"
					x-autofocus
					clearable
				/>
			</VCol>
			<VCol cols="1">
				<VSlider
					v-model="sleep"
					min="0" max="5" step="1"
				></VSlider>
			</VCol>
		</VRow>
		<Pagination v-model="page" :length="pagination.pageCount" />
	</VContainer>

	<VDataTableServer
		:headers
		:items="items"
		:itemsLength="pagination.total"
		v-model:page="page"
		v-model:itemsPerPage="perPage"
		v-model:sortBy="orders"
		:loading
		showCurrentPage
		density="comfortable"
		hover
		loadingText="Please wait ..."
		:itemsPerPageOptions="[5,10,25,50,100]"
		filter-keys="state"

		sort-asc-icon="mdi-menu-down-outline"
		sort-desc-icon="mdi-menu-up-outline"

		first-icon="bx-skip-previous"
		prev-icon="bx-caret-left"
		next-icon="bx-caret-right"
		last-icon="bx-skip-next"

	>
		<template v-slot:item.state="{ value }">
			<VChip :color="stateColors[value]">
				{{ value }}
			</VChip>
		</template>
	</VDataTableServer>

</template>

<style scoped lang="scss">
.smooth-grow {
	transition: max-width 0.5s ease;
	flex: none !important;
}

:deep(input[type="date"]::-webkit-calendar-picker-indicator) {
	position: absolute;
	// z-index: 100;
	// top: 0;
	// bottom: 0;
	// left: 0;
	right: 0;
	// height: auto;
	// width: auto;
}
</style>