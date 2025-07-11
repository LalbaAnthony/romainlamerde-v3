<template>
  <q-card class="my-card" flat bordered>
    <q-card-section horizontal>
      <q-card-section class="q-pt-xs">
        <div class="q-mt-sm q-mb-xs quote-">
          <div class="text-body1"> "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et
            dolore magna aliqua."</div>
          <div class="flex q-gutter-sm justify-end items-center">
            <span class="text-overline">Romain</span>
            <q-avatar class="cursor-pointer" size="30px">
              <img src="https://cdn.quasar.dev/img/boy-avatar.png">
            </q-avatar>
          </div>
          <div class="text-body1"> "Lorem ipsum dolore magna aliqua."</div>
          <div class="flex q-gutter-sm justify-end items-center">
            <span class="text-overline">Maga</span>
            <q-avatar class="cursor-pointer" size="30px">
              <img src="https://cdn.quasar.dev/img/avatar.png">
            </q-avatar>
          </div>
          <div class="text-body1"> ... </div>
        </div>
      </q-card-section>
    </q-card-section>
    <q-separator />
    <q-card-actions align="right">
      <q-btn flat round icon="edit" />
      <q-btn flat round icon="favorite" />
    </q-card-actions>
  </q-card>
</template>

<script setup>

</script>
