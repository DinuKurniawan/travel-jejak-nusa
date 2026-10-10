<script setup>
defineProps({
  image: { type: String, required: true },
  title: { type: String, required: true },
  category: { type: String, default: '' },
  date: { type: String, default: '' },
  excerpt: { type: String, default: '' },
  ctaLabel: { type: String, default: 'Baca Selengkapnya' },
});

const emit = defineEmits(['open']);
</script>

<template>
  <article class="flex flex-col bg-surface rounded-xl border border-surface-container-highest overflow-hidden hover:border-primary transition-colors">
    <img :alt="title" :src="image" class="w-full h-48 object-cover" loading="lazy">
    <div class="p-space-md flex flex-col flex-1 justify-between gap-space-md">
      <div>
        <div class="flex items-center justify-between gap-space-xs mb-space-xs">
          <span class="px-2 py-0.5 rounded-full bg-surface-container-highest text-on-surface font-label-sm text-[11px] font-medium">{{ category }}</span>
          <span class="font-label-sm text-[12px] text-on-surface-variant">{{ date }}</span>
        </div>
        <h3 class="font-title-lg text-title-lg text-on-surface font-bold hover:text-primary transition-colors cursor-pointer" @click.prevent="emit('open')">
          {{ title }}
        </h3>
        <p class="font-body-md text-body-md text-on-surface-variant mt-space-xs line-clamp-2">{{ excerpt }}</p>
      </div>
      <a
        class="font-label-md text-label-md text-primary inline-flex items-center gap-1 hover:underline cursor-pointer"
        @click.prevent="emit('open')"
      >
        <span>{{ ctaLabel }}</span>
        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
      </a>
    </div>
  </article>
</template>
