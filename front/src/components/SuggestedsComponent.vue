<template>
  <div class="q-mt-sm flex">
    <q-btn v-for="i in [1, 2, 3, 4]" :key="i" class="q-ml-xs q-my-xs" :color="selected === i ? 'primary' : ($q.dark.isActive ? 'dark' : 'grey-3')"
      :text-color="$q.dark.isActive || selected === i ? 'white' : 'black'" size="sm" rounded unelevated @click="selected = i">
      {{ `Lorem ${i}` }}
    </q-btn>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const selected = ref(null)

</script>