<script setup>
import {useI18n} from "vue-i18n";
import {router} from "@inertiajs/vue3";
import AuthProvider from '@pages/login/AuthProvider.vue'
import logo from '@images/logo.svg?raw'
import Blank from "@layouts/Blank.vue";
import LangSwitch from "@components/LangSwitch.vue";

const $t = useI18n().t

defineProps({
	error: String
})

defineOptions({
	layout: Blank,
})

const form = ref({
  userId: '',
  password: '',
  remember: false,
})

const rules = {
	required: (value) => !!value || $t('error.validation.field-required'),
}

function login() {
	router.post('/login', form.value)
}

const isPasswordVisible = ref(false)
if (import.meta.env.DEV) {
	form.value.userId = 'admin'
	form.value.password = 'demo123'
}
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <div
              class="d-flex text-primary"
              v-html="logo"
            />
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold">{{ $t('login.title') }}</VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
	        {{ $t('login.welcome') }}
        </h5>
        <p class="mb-0">
          {{ $t('login.pleaseSignin') }}
        </p>
      </VCardText>

      <VCardText>
        <VForm
	        validate-on="blur lazy"
	        @submit.prevent="$router.push('/')"
        >
          <VRow>
            <!-- userId -->
            <VCol cols="12">
              <VTextField
                v-model="form.userId"
                autofocus
                :placeholder="$t('placeholder.email')"
                :label="$t('label.email-or-user')"
	              :rules="[rules.required]"
	              validate-on="submit"
	              :error-messages="error"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                :label="$t('label.password')"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
	              :rules="[rules.required]"
	              :error-messages="error"
              />

              <!-- remember me checkbox -->
              <div class="d-flex align-center justify-space-between flex-wrap mt-1 mb-4">
                <VCheckbox
                  v-model="form.remember"
                  :label="$t('label.remember_me')"
                />

                <Link
                  class="text-primary ms-2 mb-1"
                  href="/forgotten"
                >
                  {{ $t('label.forgot_password') }}
                </Link>
              </div>

              <!-- login button -->
              <VBtn
                block
                type="submit"
	              @click="login"
              >
                {{ $t('label.login') }}
              </VBtn>
            </VCol>

            <!-- create account -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>{{ $t('login.new_on_our_platform') }}</span>
              <Link
                class="text-primary ms-2"
                href="/register"
              >
                {{ $t('label.register') }}
              </Link>
            </VCol>

            <VCol
              cols="12"
              class="d-flex align-center"
            >
              <VDivider />
              <span class="mx-4">{{ $t('common.or') }}</span>
              <VDivider />
            </VCol>

            <!-- auth providers -->
            <VCol
              cols="12"
              class="text-center"
            >
              <AuthProvider />
	            <LangSwitch class="float-right rollup" />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
// @use "@core-scss/template/pages/page-auth.scss";
</style>
