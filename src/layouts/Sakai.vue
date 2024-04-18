<script setup>
import AppLayout from "./sakai/AppLayout.vue";
</script>

<template>
	<AppLayout>
		<slot />
	</AppLayout>
</template>

<style lang="scss">
@import './sakai/styles';
</style>