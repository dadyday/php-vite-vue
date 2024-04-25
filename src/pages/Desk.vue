<script setup>
import {defineAsyncComponent} from "vue";
import Tile from "./desk/Tile.vue";
import { GridLayout, GridItem } from 'grid-layout-plus'
import {useAppStore} from "@/store/app";

const $appStore = useAppStore()
const tiles = $appStore.userDesk
function getComponent(item) {
	const comp = item.component
	return defineAsyncComponent(() => import(`@components/cards/${comp}.vue`))
}

const editing = ref(false)
const menu = ref([
	{ label: '...', items: [
			{ label: 'Edit', command: () => useToggle(editing)()}
	]},
])
</script>

<template>
		<VBtn size="x-small" icon="mdi-cancel" @click="$appStore.resetUserDesk()"></VBtn>

	<GridLayout
		v-model:layout="tiles"
		:col-num="6"
		:row-height="75"
		is-draggable
		is-resizable
		vertical-compact
		use-css-transforms
	>
		<GridItem
			v-for="(item, index) in tiles"
			:key="index"
			v-bind="item"
			x-resize="handleResize"
		>
			<Tile :label="'Tile' + index">
				<component :is="getComponent(item)" v-bind="item.props ?? {}" />
				<!--div v-else>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
				liquot. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
				Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
				sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</div-->
			</Tile>
		</GridItem>
	</GridLayout>
</template>

<style scoped lang="scss">
</style>