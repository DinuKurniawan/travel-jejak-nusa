<script setup>
defineProps({
  icon: { type: String, default: '' },
  title: { type: String, default: '' },
  footer: { type: String, default: '' },
  iconClass: { type: String, default: 'bg-teal-light text-teal' },
  titleClass: { type: String, default: '' },
});
</script>

<template>
  <div class="bg-surface-container-lowest border border-card-border rounded-xl p-6 flex flex-col justify-between hover:border-teal transition-colors">
    <div>
      <div v-if="icon" :class="['w-12 h-12 rounded-lg flex items-center justify-center mb-5', iconClass]" aria-hidden="true">
        <span class="material-symbols-outlined text-[24px]">{{ icon }}</span>
      </div>
      <h3 :class="['font-title-lg text-title-lg text-on-surface font-semibold mb-2', titleClass]">{{ title }}</h3>
      <div class="font-body-md text-body-md text-on-surface-variant"><slot /></div>
    </div>
    <div v-if="footer || $slots.footer" class="pt-4 mt-4 border-t border-card-border">
      <slot name="footer">
        <span class="font-label-sm text-label-sm text-teal font-medium">{{ footer }}</span>
      </slot>
    </div>
  </div>
</template>
