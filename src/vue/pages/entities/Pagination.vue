<script setup>
const props = defineProps({
	length: null
})
const page = defineModel()
</script>

<template>
	<VRow>
		<VSpacer />
		<VCol cols="auto">
			<VPagination
				v-model="page"
				:length="length"
				:total-visible="4"
				rounded

				first-icon="bx-skip-previous"
				prev-icon="bx-caret-left"
				next-icon="bx-caret-right"
				last-icon="bx-skip-next"
			/>
		</VCol>
	</VRow>
</template>

<style scoped lang="scss">

</style>