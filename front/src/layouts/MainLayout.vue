<template>
  <q-layout view="lHh Lpr lFf">
    <q-header :class="[$q.dark.isActive ? 'custom-header-dark' : 'custom-header-light']">
      <q-toolbar>
        <q-btn flat dense round @click="toggleDrawer">
          <q-avatar class="cursor-pointer" size="40px">
            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
          </q-avatar>
        </q-btn>
        <q-toolbar-title>
          <transition name="q-transition--fade">
            <span :key="$route.meta.title">
              {{ $route.meta.title }}
            </span>
          </transition>
        </q-toolbar-title>
        <q-btn flat round dense icon="shuffle" class="q-ml-xs" @click="console.log('WIP')" />
        <q-btn flat round dense icon="search" class="q-ml-xs" @click="console.log('WIP')" />
        <q-btn flat round dense icon="add" class="q-ml-xs" @click="console.log('WIP')" />
      </q-toolbar>
      <q-toolbar inset style="padding: 0 !important;">
        <Suggesteds />
      </q-toolbar>
    </q-header>

    <q-drawer v-model="drawerOpened" :width="200" :breakpoint="500" show-if-above>
      <q-scroll-area style="height: calc(100% - 75px); margin-top: 75px;">
        <q-list padding>
          <q-item v-for="m in menus.drawers" :key="m.name" :active="route.name === m.route.name" clickable
            @click="$router.push({ path: m.route.path })">
            <q-item-section avatar>
              <q-icon :name="m.icon" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ m.label }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-separator />
          <q-item clickable @click="toggleDarkMode">
            <q-item-section avatar>
              <q-icon :name="$q.dark.isActive ? 'light_mode' : 'dark_mode'" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $q.dark.isActive ? 'Light Mode' : 'Dark Mode' }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-separator />
        </q-list>
      </q-scroll-area>
      <div class="absolute-top" style="height: 75px">
        <div class="absolute-bottom flex bg-transparent">
          <q-avatar size="56px" class="q-mb-sm q-mr-sm">
            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
          </q-avatar>
          <div>
            <div class="text-weight-bold">Razvan Stoenescu</div>
            <div class="text-blue-3 cursor-pointer" @click="console.log('WIP')">@rstoenescu</div>
          </div>
        </div>
      </div>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-page-sticky position="bottom-right" :offset="[16, 16]">
      <q-btn v-if="$route.name !== menus.fabs[0].route.name" :icon="menus.fabs[0].icon" size="sm" fab
        color="primary" :to="menus.fabs[0].route.path" />
    </q-page-sticky>

    <q-footer :class="[$q.dark.isActive ? 'custom-footer-dark' : 'custom-footer-light']">
      <q-tabs v-model="tab" dense no-caps :indicator-color="$q.dark.isActive ? 'primary' : 'primary'"
        :active-color="$q.dark.isActive ? 'text-grey' : 'primary'" class="text-grey-5 q-pt-sm">
        <!-- <q-tab v-for="t in menus.tabs" :key="t.name" :name="t.name" :icon="t.icon" :label="t.label" /> -->
        <q-route-tab v-for="t in menus.tabs" :key="t.name" :name="t.name" :icon="t.icon" :label="t.label"
          :to="t.route.path" />
      </q-tabs>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useRoute } from 'vue-router'
import Suggesteds from 'components/SuggestedsComponent.vue'

const $q = useQuasar();
const route = useRoute()

const tab = ref(route.name || 'home')
const drawerOpened = ref(false)

const menus = ref({
  drawers: [
    { name: 'account', icon: 'person', label: 'Moi', route: { name: 'account.base', path: '/account' } },
    { name: 'infos', icon: 'info', label: 'Infos', route: { name: 'infos', path: '/infos' } },
    { name: 'settings', icon: 'settings', label: 'Paramètres', route: { name: 'account.settings', path: '/account/settings' } },
  ],
  tabs: [
    { name: 'home', icon: 'home', label: 'Accueil', route: { name: 'home', path: '/' } },
    { name: 'search', icon: 'search', label: 'Recherche', route: { name: 'search', path: '/search' } },
    { name: 'favorites', icon: 'favorite', label: 'Favoris', route: { name: 'quotes.favorites', path: '/quotes/favorites' } },
    { name: 'account', icon: 'person', label: 'Moi', route: { name: 'account.base', path: '/account' } },
  ],
  fabs: [
    { name: 'add', icon: 'add', label: 'Ajouter', route: { name: 'quotes.add', path: '/quotes/add' } },
  ]
})

function toggleDrawer() {
  drawerOpened.value = !drawerOpened.value
}

function toggleDarkMode() {
  $q.dark.set(!$q.dark.isActive);
}

</script>

<style scoped>
.custom-header-light {
  background-color: white;
  color: black;
}

.custom-header-dark {
  background-color: #121212;
  color: white;
}

.custom-footer-light {
  background-color: #f6f6f6;
  color: black;
}

.custom-footer-dark {
  background-color: #1D1D1D;
  color: white;
}
</style>