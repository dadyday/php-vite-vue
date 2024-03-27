<script setup>
import ErrorHeader from './error/ErrorHeader.vue'
import misc404 from '@images/pages/404.png'
import Blank from "@layouts/Blank.vue";
defineOptions({
	layout: Blank,
})
defineProps(['code', 'message', 'desc'])
</script>

<template>
  <div class="misc-wrapper">
    <ErrorHeader
      :error-code="code ?? 404"
      :error-title="(message ?? 'Page Not Found') + '⚠️'"
      :error-description="desc ?? `We couldn't find the page you are looking for.`"
    />

    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg
        :src="misc404"
        :alt="$t('coming_soon')"
        :max-height="200"
        class="mx-auto"
      />
      <VBtn
        href="/"
        class="mt-10"
      >
        {{ $t('error.back_to_home') }}
      </VBtn>
    </div>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
