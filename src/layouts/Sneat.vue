<script setup>
import { useTheme } from 'vuetify'
import {hexToRgb} from "@sneat/utils";
import DefaultLayoutWithVerticalNav from '@sneat/DefaultLayoutWithVerticalNav.vue'

const { global } = useTheme()
const nav = [
	{}
]
</script>

<template>
	<VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
		<DefaultLayoutWithVerticalNav>
			<slot />
		</DefaultLayoutWithVerticalNav>
	</VApp>

</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use '@sneat/@core/template/index.scss';
@use "@sneat/styles/default-layout";
@import '@sneat/styles/index.scss';
</style>
