<script setup>
defineProps({
  photo: { type: String, required: true },
  name: { type: String, required: true },
  role: { type: String, default: '' },
  bio: { type: String, default: '' },
  accent: { type: String, default: 'text-teal' },
  hoverBorder: { type: String, default: 'hover:border-teal' },
});
</script>

<template>
  <div :class="['bg-white rounded-xl border border-card-border transition-colors p-6 flex flex-col items-center text-center', hoverBorder]">
    <img :alt="name" :src="photo" :class="['w-24 h-24 rounded-full object-cover border-2 mb-4', accent.replace('text-', 'border-')]" loading="lazy">
    <h3 class="font-title-lg text-title-lg text-on-surface">{{ name }}</h3>
    <span :class="['font-label-sm text-label-sm font-semibold mt-0.5 mb-3', accent]">{{ role }}</span>
    <p class="font-body-md text-body-md text-muted mb-6 flex-grow">{{ bio }}</p>
    <div class="flex items-center gap-2 pt-4 border-t border-card-border w-full justify-center">
      <a :aria-label="'LinkedIn ' + name" :class="['w-8 h-8 rounded-md bg-surface-container text-on-surface transition-colors flex items-center justify-center font-bold text-xs', accent.replace('text-', 'hover:bg-') + ' hover:text-white']" href="#">in</a>
      <a :aria-label="'Instagram ' + name" :class="['w-8 h-8 rounded-md bg-surface-container text-on-surface transition-colors flex items-center justify-center', accent.replace('text-', 'hover:bg-') + ' hover:text-white']" href="#"><span class="material-symbols-outlined text-[16px]">photo_camera</span></a>
      <a :aria-label="'Twitter ' + name" :class="['w-8 h-8 rounded-md bg-surface-container text-on-surface transition-colors flex items-center justify-center font-bold text-xs', accent.replace('text-', 'hover:bg-') + ' hover:text-white']" href="#">𝕏</a>
    </div>
  </div>
</template>
