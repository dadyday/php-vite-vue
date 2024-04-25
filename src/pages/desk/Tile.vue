<script setup>

</script>

<template>
	<div class="tile-card">
		<slot />
	</div>
</template>

<style scoped lang="scss">
.tile-card {
	height: inherit;
	.v-card {
		height: inherit !important;
		overflow: hidden;
		&:hover {
			overflow: auto !important;
			@apply
		}
	}
}
</style>