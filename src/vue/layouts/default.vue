<script setup>
import { useTheme } from 'vuetify'
import {hexToRgb} from "@/layouts/utils";
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'

const { global } = useTheme()
</script>

<template>
	<VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
		<DefaultLayoutWithVerticalNav>
			<RouterView />
		</DefaultLayoutWithVerticalNav>
	</VApp>

</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
