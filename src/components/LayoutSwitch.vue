<script setup>
import {useAppStore} from "@/store/app";
import LogoSneat from '@images/logo.svg?raw';
import LogoSakai from '@sakai/images/logo.svg?raw';
import {useTheme} from "vuetify";

const $appStore = useAppStore()
const theme = useTheme().name

const items = computed(() => [
	{ value: 'sakai', title: 'Sakai', logo: LogoSakai, color: theme.value === 'dark' ? 'red' : 'black'},
	{ value: 'sneat', title: 'Sneat', logo: LogoSneat, color: 'rgb(var(--v-theme-primary))'},
])
</script>

<template>
	<VSelect
		v-model="$appStore.layoutName"
		:items
		density="compact"
		class="borderless flex-grow-0"
	>
		<template #selection="{item}">
			<VSheet class="d-flex align-center">
				<div v-html="item.raw.logo" class="icon" :style="{ color: item.raw.color}" />
			</VSheet>
		</template>
		<template v-slot:item="{props, item}">
			<VListItem v-bind="props">
				<template #title>
					<VSheet class="d-flex align-center">
						<div v-html="item.raw.logo" class="icon" :style="{ color: item.raw.color}" />
						<span class="mx-2">{{ item.raw.title }}</span>
					</VSheet>
				</template>
			</VListItem>
		</template>
	</VSelect>
</template>

<style lang="scss" scoped>
.borderless {
	&:deep(*) {
		border-color: transparent !important;
	}
}

.icon {
	position: relative;
	height: 2em;
	width: 2rem;
	vertical-align: center;

}
</style>
