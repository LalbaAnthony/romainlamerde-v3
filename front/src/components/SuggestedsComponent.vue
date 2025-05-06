<template>
  <div class="q-mt-md flex">
    <q-btn v-for="i in [1, 2, 3, 4]" :key="i" class="q-ml-xs" color="primary" size="sm" rounded @click="console.log('WIP')">
        <template v-slot:default>
          <span class="text-lg">{{`Test ${i}`}}</span>
        </template>
    </q-btn>
  </div>
</template>

<script setup>

</script>