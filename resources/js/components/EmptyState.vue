<script setup>
defineProps({
  icon: { type: String, default: 'search_off' },
  title: { type: String, default: 'Tidak ada data' },
  description: { type: String, default: '' },
});
</script>

<template>
  <div class="col-span-full py-16 px-6 text-center bg-surface-container-lowest rounded-xl border border-card-border flex flex-col items-center">
    <span class="material-symbols-outlined text-[48px] text-outline mb-2">{{ icon }}</span>
    <h3 class="font-title-lg text-title-lg text-on-surface font-bold">{{ title }}</h3>
    <p v-if="description" class="font-body-md text-body-md text-on-surface-variant mt-1 max-w-md">{{ description }}</p>
    <div class="mt-5"><slot /></div>
  </div>
</template>
