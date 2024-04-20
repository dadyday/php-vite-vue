<script setup>
import {useAppStore} from "@/store/app";

const $appStore = useAppStore()
</script>

<template>
	<component :is="$appStore.layout" :key="$appStore.layoutName">
		<slot />
	</component>
</template>
