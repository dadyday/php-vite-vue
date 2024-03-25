<script setup>
import {router} from "@inertiajs/vue3";
import AuthProvider from '@pages/login/AuthProvider.vue'
import logo from '@images/logo.svg?raw'
import Blank from "@layouts/Blank.vue";

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
	required: value => !!value || 'Field is required',
}

function login() {
	router.post('/login', form.value)
}

const isPasswordVisible = ref(false)
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

        <VCardTitle class="text-2xl font-weight-bold">Demo Framework</VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          Welcome 👋🏻
        </h5>
        <p class="mb-0">
          Please sign-in to your account and start the adventure
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
                placeholder="johndoe@email.com"
                label="Email or Username"
	              :rules="[rules.required]"
	              :error-messages="error"
              />
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                label="Password"
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
                  label="Remember me"
                />

                <Link
                  class="text-primary ms-2 mb-1"
                  to="/forgotten"
                >
                  Forgot Password?
                </Link>
              </div>

              <!-- login button -->
              <VBtn
                block
                type="submit"
	              @click="login"
              >
                Login
              </VBtn>
            </VCol>

            <!-- create account -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>New on our platform?</span>
              <Link
                class="text-primary ms-2"
                to="/register"
              >
                Create an account
              </Link>
            </VCol>

            <VCol
              cols="12"
              class="d-flex align-center"
            >
              <VDivider />
              <span class="mx-4">or</span>
              <VDivider />
            </VCol>

            <!-- auth providers -->
            <VCol
              cols="12"
              class="text-center"
            >
              <AuthProvider />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
