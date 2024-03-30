<script setup>
import { router } from '@inertiajs/vue3'

function bar(n) {
	router.get('/bar?'+n, {}, {replace: true})
}
</script>

<template>
	Ich bin Foo
	<div @click="bar(42)">42</div>
	<div @click="bar(73)">73</div>
	<pre>{{ $props }}</pre>
	<pre>{{ $attrs }}</pre>

</template>

<style scoped lang="scss">

</style>