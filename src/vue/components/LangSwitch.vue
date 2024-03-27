<script setup>
import IconFlagDe from 'virtual:icons/circle-flags/de'
import IconFlagGb from 'virtual:icons/circle-flags/gb'

const locales = [
	{ value: 'de', title: 'Deutsch', icon: IconFlagDe },
	{ value: 'en', title: 'English', icon: IconFlagGb },
]
// :value="props.value" :key="props.key"
</script>

<template>
	<VSelect
		v-model="$i18n.locale"
		:items="locales"
		density="compact"
		class="borderless flex-grow-0"
	>
		<template #selection="{item}">
			<VSheet class="d-flex align-center">
				<component :is="item.raw.icon" />
			</VSheet>
		</template>
		<template v-slot:item="{props, item}">
			<VListItem v-bind="props">
				<template #title>
					<VSheet class="d-flex align-center">
						<component :is="item.raw.icon" />
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
// caret looks up
.rollup {
	&:deep(*) {
		div.v-field__append-inner {
			transform: rotate(180deg) !important;
		}
	}
}
</style>
