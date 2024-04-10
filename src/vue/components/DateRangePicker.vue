<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const dates = defineModel()
const focused = defineModel('focused')
</script>

<template>
	<VueDatePicker v-bind="$attrs"
		v-model="dates"
		format="d. MMM yy"
		:enable-time-picker="false"
		range

		auto-apply
		six-weeks="center"

		@focus="focused = true"
		@blur="focused = false"

	>
	</VueDatePicker>
</template>

<style scoped lang="scss">

</style>