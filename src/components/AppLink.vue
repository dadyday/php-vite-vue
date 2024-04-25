<script setup>
defineProps(['to'])
</script>

<template>
<Link :href="to"><slot /></Link>
</template>

<style scoped lang="scss">

</style>