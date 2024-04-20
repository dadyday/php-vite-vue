<script setup>
const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})
</script>

<template>
  <li class="nav-section-title">
    <div class="title-wrapper">
      <span
        class="title-text"
        v-text="item.label"
      />
    </div>
  </li>
</template>
