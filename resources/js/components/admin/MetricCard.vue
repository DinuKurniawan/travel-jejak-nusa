<script setup>
defineProps({
  label: { type: String, required: true },
  value: { type: String, required: true },
  icon: { type: String, default: '' },
  delta: { type: String, default: '' },
  iconClass: { type: String, default: 'bg-teal-light text-teal' },
});
</script>

<template>
  <div class="bg-white rounded-xl border border-card-border p-5 flex items-start justify-between gap-4">
    <div>
      <p class="font-label-sm text-label-sm text-muted font-medium">{{ label }}</p>
      <p class="font-headline-md text-headline-md text-ink font-semibold mt-1">{{ value }}</p>
      <p v-if="delta" class="font-label-sm text-label-sm text-[#2EB67D] mt-1">{{ delta }}</p>
    </div>
    <div v-if="icon" :class="['w-11 h-11 rounded-lg flex items-center justify-center shrink-0', iconClass]" aria-hidden="true">
      <span class="material-symbols-outlined text-[22px]">{{ icon }}</span>
    </div>
  </div>
</template>
