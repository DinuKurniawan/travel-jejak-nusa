<script setup>
defineProps({
  icon: { type: String, default: 'confirmation_number' },
  value: { type: String, default: '-' },
  label: { type: String, default: '' },
  tint: { type: String, default: 'bg-info-tint text-teal' },
});
</script>

<template>
  <div class="bg-white border border-card-border rounded-xl p-4 sm:p-5 flex items-center gap-4">
    <span :class="tint" class="w-11 h-11 rounded-lg flex items-center justify-center shrink-0" aria-hidden="true">
      <span class="material-symbols-outlined text-[24px]">{{ icon }}</span>
    </span>
    <div class="min-w-0">
      <p class="font-jakarta font-bold text-[22px] leading-7 text-ink tabular-nums truncate">{{ value }}</p>
      <p class="font-label-sm text-label-sm text-muted truncate">{{ label }}</p>
    </div>
  </div>
</template>
