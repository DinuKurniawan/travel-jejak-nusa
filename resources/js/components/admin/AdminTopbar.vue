<script setup>
defineProps({
  title: { type: String, default: 'Dashboard' },
  subtitle: { type: String, default: '' },
});

const emit = defineEmits(['menu', 'profile']);
</script>

<template>
  <div class="sticky top-0 z-20 h-16 bg-white border-b border-card-border px-4 sm:px-6 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3 min-w-0">
      <button aria-label="Buka menu admin" class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center text-ink hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="emit('menu')">
        <span class="material-symbols-outlined text-[24px]">menu</span>
      </button>
      <div class="min-w-0">
        <h1 class="font-headline-sm text-headline-sm text-ink truncate">{{ title }}</h1>
        <p v-if="subtitle" class="text-xs text-muted truncate">{{ subtitle }}</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button aria-label="Notifikasi" class="w-10 h-10 rounded-lg flex items-center justify-center text-muted hover:bg-surface-container hover:text-ink transition-colors cursor-pointer" type="button">
        <span class="material-symbols-outlined text-[22px]">notifications</span>
      </button>
      <button class="flex items-center gap-2.5 h-10 pl-1 pr-2 rounded-lg hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="emit('profile')">
        <span class="w-8 h-8 rounded-full bg-teal text-white flex items-center justify-center font-label-md text-label-md font-bold" aria-hidden="true">A</span>
        <span class="hidden sm:block text-left">
          <span class="block font-label-md text-label-md text-ink leading-none">Admin</span>
          <span class="block text-[11px] text-muted mt-0.5">Superadmin</span>
        </span>
      </button>
    </div>
  </div>
</template>
