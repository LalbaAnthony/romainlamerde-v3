<template>
  <q-layout view="lHh Lpr lFf">
    <q-header :class="[$q.dark.isActive ? 'custom-header-dark' : 'custom-header-light']">
      <q-toolbar class="flex justify-between">
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleDrawer" />
        <q-avatar size="24px">
          <img src="https://cdn.quasar.dev/img/avatar.png">
        </q-avatar>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="drawer" show-if-above>
      <h6 class="q-ma-none q-pa-md text-h6">
        romainlamerde.com
      </h6>
      <q-separator />
      <q-list>
        <q-item v-for="menu in menus" :key="menu.name" clickable @click="$router.push({ name: menu.name })">
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
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-page-sticky position="bottom-right" :offset="[12, 12]">
      <q-btn fab icon="add" padding="12px" color="primary" @click="console.log('test')" />
    </q-page-sticky>

    <q-footer :class="[$q.dark.isActive ? 'custom-footer-dark' : 'custom-footer-light']">
      <q-tabs v-model="tab" dense no-caps :indicator-color="[$q.dark.isActive ? 'primary' : 'primary']"
        :active-color="[$q.dark.isActive ? 'text-grey' : 'primary']" class="text-grey-5">
        <q-tab v-for="t in tabs" :key="t.name" :name="t.name" :icon="t.icon" :label="t.label">
        </q-tab>
      </q-tabs>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar';

const $q = useQuasar();

const drawer = ref(false)
const tab = ref('home')

const menus = ref([
  { name: 'settings', icon: 'settings', label: 'Paramètres' },
  { name: 'info', icon: 'info', label: 'Infos' },
])

const tabs = ref([
  { name: 'search', icon: 'search', label: 'Recherche' },
  { name: 'home', icon: 'home', label: 'Accueil' },
  { name: 'favorits', icon: 'favorite', label: 'Favoris' },
])

function toggleDrawer() {
  drawer.value = !drawer.value
}

function toggleDarkMode() {
  $q.dark.set(!$q.dark.isActive); // Toggle
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