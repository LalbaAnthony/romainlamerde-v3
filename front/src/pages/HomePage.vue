<template>
  <q-page>
    <q-toolbar inset style="padding: 0 !important;">
      <Suggesteds />
    </q-toolbar>
    <div class="grid q-pa-md">
      <QuoteItem />
      <QuoteItem />
      <QuoteItem />
      <QuoteItem />
    </div>
  </q-page>
</template>

<script setup>
import Suggesteds from 'components/SuggestedsComponent.vue'
import QuoteItem from 'components/QuoteItemComponent.vue'

</script>
