<script setup>
defineProps({
  label: { type: String, default: 'Tambah ke wishlist' },
  active: { type: Boolean, default: false },
  size: { type: String, default: 'md' },
});

const emit = defineEmits(['toggle']);
</script>

<template>
  <button
    :aria-label="label"
    :aria-pressed="active"
    :class="[active ? 'bg-orange text-white' : 'bg-white/90 text-muted hover:text-error', size === 'sm' ? 'w-8 h-8' : 'w-9 h-9']"
    class="absolute top-3 right-3 w-9 h-9 rounded-full flex items-center justify-center border border-card-border transition-colors cursor-pointer"
    type="button"
    @click.stop="emit('toggle')"
  >
    <span
      class="material-symbols-outlined text-[18px]"
      :style="active ? `font-variation-settings: 'FILL' 1` : `font-variation-settings: 'FILL' 0`"
    >favorite</span>
  </button>
</template>
