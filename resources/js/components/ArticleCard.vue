<script setup>
defineProps({
  image: { type: String, required: true },
  title: { type: String, required: true },
  excerpt: { type: String, default: '' },
  category: { type: String, default: '' },
  categoryClass: { type: String, default: '' },
  author: { type: String, default: '' },
  readTime: { type: String, default: '' },
});

const emit = defineEmits(['open']);
</script>

<template>
  <article
    class="bg-surface-container-lowest rounded-xl flex flex-col justify-between overflow-hidden border border-transparent hover:border-teal transition-colors cursor-pointer group"
    @click="emit('open')"
  >
    <div>
      <div class="w-full h-44 overflow-hidden rounded-t-xl relative bg-surface-container">
        <img :alt="title" :src="image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
        <span v-if="category" :class="categoryClass" class="absolute top-3 left-3 font-label-sm text-label-sm px-2.5 py-1 rounded-full">{{ category }}</span>
      </div>
      <div class="p-4 flex flex-col gap-2">
        <h3 class="font-title-lg text-title-lg text-on-surface group-hover:text-teal transition-colors line-clamp-2">{{ title }}</h3>
        <p v-if="excerpt" class="font-body-md text-body-md text-on-surface-variant line-clamp-2">{{ excerpt }}</p>
      </div>
    </div>
    <div class="px-4 pb-4 pt-2 flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm border-t border-surface-container/50">
      <span>{{ author }}</span>
      <span class="flex items-center gap-1 bg-surface-container px-2 py-0.5 rounded">
        <span class="material-symbols-outlined text-[14px]">schedule</span>
        {{ readTime }}
      </span>
    </div>
  </article>
</template>
