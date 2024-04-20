<script setup>
import {useAppStore} from "@/store/app";
import AppMenuItem from "./AppMenuItem.vue";

const $appStore = useAppStore()
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in $appStore.userMenu" :key="item">
            <AppMenuItem v-if="!item.separator" :item="item" :index="i"></AppMenuItem>
            <li v-if="item.heading" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
