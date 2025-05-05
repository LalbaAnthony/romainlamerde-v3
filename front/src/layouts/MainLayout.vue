<template>
  <q-layout view="lHh Lpr lFf">
    <q-header :class="[$q.dark.isActive ? 'custom-header-dark' : 'custom-header-light']">
      <q-toolbar class="flex justify-between">
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleDrawer" />
        <h1 class="text-h6">
          Quasar App
        </h1>
        <q-avatar size="36px">
          <img src="https://cdn.quasar.dev/img/avatar.png">
        </q-avatar>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="drawer" show-if-above>
      <h6 class="q-my-none q-pa-md">
        Quasar App
      </h6>

      <q-separator />

      <q-list>
        <q-item clickable tag="a" href="#">
          <q-item-section avatar>
            <q-icon name="settings" size="24px" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Settings</q-item-label>
          </q-item-section>
        </q-item>

        <q-item clickable tag="a" href="#">
          <q-item-section avatar>
            <q-icon name="help" size="24px" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Help</q-item-label>
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

    <q-page-sticky position="bottom-right" :offset="[ 12, 12 ]">
      <q-fab icon="add" direction="up" color="primary">
        <q-fab-action @click="console.log('test')" color="accent" icon="person_add" />
        <q-fab-action @click="console.log('test')" color="accent" icon="mail" />
      </q-fab>
    </q-page-sticky>

    <q-footer :class="[$q.dark.isActive ? 'custom-footer-dark' : 'custom-footer-light']">
      <q-tabs v-model="tab" :indicator-color="[$q.dark.isActive ? 'primary' : 'primary']"
        :active-color="[$q.dark.isActive ? 'text-grey' : 'primary']" class="text-grey-5">
        <q-tab name="quotes" icon="chat" label="Citations" />
        <q-tab name="favorits" icon="star" label="Favoris" />
      </q-tabs>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar';

const $q = useQuasar();

const drawer = ref(false)
const tab = ref('alarms')

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