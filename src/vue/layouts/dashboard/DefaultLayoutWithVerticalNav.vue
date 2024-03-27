<script setup>
import {usePage} from "@inertiajs/vue3";
import { useTheme } from 'vuetify'
import VerticalNavSectionTitle from './VerticalNavSectionTitle.vue'
import VerticalNavLayout from './VerticalNavLayout.vue'
import VerticalNavLink from './VerticalNavLink.vue'
import NavbarThemeSwitcher from './NavbarThemeSwitcher.vue'
import UserProfile from './UserProfile.vue'
import Footer from './Footer.vue'
import LangSwitch from "@/components/LangSwitch.vue";

const vuetifyTheme = useTheme()

const menu = [
	{ title: 'Foo'             , icon: 'bx-home'                , to: '/foo', roles: [] },
	{ title: 'Bar'             , icon: 'mdi-account-cog-outline', to: '/bar', roles: ['admin'] },
	{ heading: 'Pages' },
	{ title: 'Login'           , icon: 'bx-log-in'              , to: '/login' },
	{ title: 'Register'        , icon: 'bx-user-plus'           , to: '/register' },
	{ title: 'Error'           , icon: 'bx-info-circle'         , to: '/no-existence' },
	{ heading: 'User' },
	{ title: 'Typography'      , icon: 'mdi-alpha-t-box-outline', to: '/typography' },
	{ title: 'Icons'           , icon: 'bx-show'                , to: '/icons' },
	{ title: 'Cards'           , icon: 'bx-credit-card'         , to: '/cards' },
	{ title: 'Tables'          , icon: 'bx-table'               , to: '/tables' },
	{ title: 'Form Layouts'    , icon: 'mdi-form-select'        , to: '/form-layouts' },
];

const user = usePage().props.user
const userMenu = computed(() => menu.filter((item) => {
	let ok = !item.roles?.length // allow if no role defined
	if (!ok) item.roles.forEach((role) => ok |= user.roles.includes(role))
	return ok
}))
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="bx-menu" />
        </IconBtn>

        <!-- 👉 Search -->
        <div
          class="d-flex align-center cursor-pointer"
          style="user-select: none;"
        >
          <!-- 👉 Search Trigger button -->
          <IconBtn>
            <VIcon icon="bx-search" />
          </IconBtn>

          <span class="d-none d-md-flex align-center text-disabled">
            <span class="me-3">Search</span>
            <span class="meta-key">&#8984;K</span>
          </span>
        </div>

        <VSpacer />

	      <LangSwitch />

        <IconBtn
          class="me-2"
          href="https://github.com/themeselection/sneat-vuetify-vuejs-laravel-admin-template-free"
          target="_blank"
          rel="noopener noreferrer"
        >
          <VIcon icon="bxl-github" />
        </IconBtn>

        <IconBtn class="me-2">
          <VIcon icon="bx-bell" />
        </IconBtn>

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>

    <template #vertical-nav-content>
	    <template
				v-for="(item, index) in userMenu"
				:key="index"
			>
				<VerticalNavLink v-if="item.to" :item="item" />
				<VerticalNavSectionTitle v-else :item="item" />
			</template>
    </template>

    <template #after-vertical-nav-items>
      <!-- 👉 illustration -->
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>
