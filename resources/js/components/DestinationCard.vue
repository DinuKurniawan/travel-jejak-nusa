<script setup>
defineProps({
  image: { type: String, required: true },
  name: { type: String, required: true },
  tripCount: { type: String, default: '' },
  ctaLabel: { type: String, default: 'Eksplor' },
});

const emit = defineEmits(['explore']);
</script>

<template>
  <article class="bg-surface-container-lowest border border-card-border rounded-xl overflow-hidden hover:border-teal transition-colors group">
    <div class="h-48 bg-surface-container overflow-hidden">
      <img :alt="name" :src="image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
    </div>
    <div class="p-5 flex items-center justify-between gap-3">
      <div>
        <h3 class="font-title-lg text-title-lg text-on-surface font-semibold">{{ name }}</h3>
        <p v-if="tripCount" class="font-body-md text-body-md text-muted">{{ tripCount }}</p>
      </div>
      <button
        class="h-9 px-3 rounded-lg border border-teal text-teal hover:bg-teal hover:text-white inline-flex items-center font-label-sm text-label-sm transition-colors cursor-pointer shrink-0"
        type="button"
        @click="emit('explore')"
      >
        {{ ctaLabel }}
      </button>
    </div>
  </article>
</template>
