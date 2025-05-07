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
          {{ $route.meta.name }}
        </q-toolbar-title>
        <q-btn flat round dense icon="search" class="q-ml-xs" @click="console.log('WIP')" />
        <q-btn flat round dense icon="add" class="q-ml-xs" @click="console.log('WIP')" />
      </q-toolbar>
      <q-toolbar inset style="padding: 0 !important;">
        <Suggesteds />
      </q-toolbar>
    </q-header>

    <q-drawer v-model="drawerOpened" :width="200" :breakpoint="500">
      <q-scroll-area style="height: calc(100% - 75px); margin-top: 75px;">
        <q-list padding>
          <q-item v-for="menu in menus.drawers" :key="menu.name" :active="route.name === menu.name" clickable
            @click="$router.push({ path: menu.path })">
            <q-item-section avatar>
              <q-icon :name="menu.icon" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ menu.label }}</q-item-label>
            </q-item-section>
          </q-item>
          <q-item clickable @click="toggleDarkMode">
            <q-item-section avatar>
              <q-icon :name="$q.dark.isActive ? 'dark_mode' : 'light_mode'" />
            </q-item-section>
            <q-item-section>
              <q-item-label>{{ $q.dark.isActive ? 'Light Mode' : 'Dark Mode' }}</q-item-label>
            </q-item-section>
          </q-item>
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

    <q-page-sticky position="bottom-right" :offset="[10, 10]">
      <q-btn icon="keyboard_arrow_up" padding="12px" fab color="primary" @click="console.log('WIP')" />
    </q-page-sticky>

    <q-footer :class="[$q.dark.isActive ? 'custom-footer-dark' : 'custom-footer-light']">
      <q-tabs v-model="tab" dense no-caps :indicator-color="$q.dark.isActive ? 'primary' : 'primary'"
        :active-color="$q.dark.isActive ? 'text-grey' : 'primary'" class="text-grey-5">
        <q-tab v-for="t in menus.tabs" :key="t.name" :name="t.name" :icon="t.icon" :label="t.label" :to="t.path">
        </q-tab>
      </q-tabs>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Suggesteds from 'components/SuggestedsComponent.vue'

const $q = useQuasar();
const route = useRoute()
const router = useRouter()

const tab = ref(route.name || 'home')
const drawerOpened = ref(false)

const menus = ref({
  drawers: [
    { name: 'account', icon: 'person', label: 'Mon compte', path: '/account' },
    { name: 'settings', icon: 'settings', label: 'Paramètres', path: '/settings' },
    { name: 'infos', icon: 'info', label: 'Infos', path: '/infos' },
  ],
  tabs: [
    { name: 'home', icon: 'home', label: 'Accueil', path: '/' },
    { name: 'search', icon: 'search', label: 'Recherche', path: '/search' },
    { name: 'favorites', icon: 'favorite', label: 'Favoris', path: '/favorites' },
    { name: 'add', icon: 'add', label: 'Ajouter', path: '/add' },
  ]
})

function toggleDrawer() {
  drawerOpened.value = !drawerOpened.value
}

function toggleDarkMode() {
  $q.dark.set(!$q.dark.isActive); // Toggle
}

watch(() => tab.value, val => {
  if (val !== route.name) {
    router.push({ name: val })
  }
})
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