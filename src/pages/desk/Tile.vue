<script setup>

</script>

<template>
	<div class="tile-card">
		<slot />
	</div>
</template>

<style lang="scss" scoped>
.tile-card {
	height: inherit;
	:deep(.v-card) {
		height: inherit !important;
		overflow: hidden;
		&:hover {
			//overflow: auto !important;
		}
	}
}
</style>