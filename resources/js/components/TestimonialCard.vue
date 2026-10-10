<script setup>
defineProps({
  quote: { type: String, required: true },
  initials: { type: String, required: true },
  name: { type: String, required: true },
  meta: { type: String, default: '' },
  avatarClass: { type: String, default: 'bg-teal-light text-teal' },
  stars: { type: Number, default: 5 },
});
</script>

<template>
  <figure class="bg-surface-container-lowest border border-card-border rounded-xl p-6 flex flex-col justify-between hover:border-teal transition-colors">
    <div>
      <div class="flex items-center gap-1 text-yellow mb-4" role="img" :aria-label="stars + ' dari 5 bintang'">
        <span v-for="s in stars" :key="s" aria-hidden="true">★</span>
      </div>
      <blockquote class="font-body-md text-body-md text-on-surface italic mb-6">“{{ quote }}”</blockquote>
    </div>
    <figcaption class="flex items-center gap-3 pt-4 border-t border-card-border">
      <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold font-label-md text-label-md', avatarClass]" aria-hidden="true">{{ initials }}</div>
      <div>
        <p class="font-title-lg text-title-lg text-on-surface font-semibold text-sm">{{ name }}</p>
        <p v-if="meta" class="font-label-sm text-label-sm text-muted">{{ meta }}</p>
      </div>
    </figcaption>
  </figure>
</template>
