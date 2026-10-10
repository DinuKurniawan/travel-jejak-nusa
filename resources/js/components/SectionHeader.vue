<script setup>
defineProps({
  eyebrow: { type: String, default: '' },
  title: { type: String, default: '' },
  description: { type: String, default: '' },
  align: { type: String, default: 'left' },
  linkText: { type: String, default: '' },
});

const emit = defineEmits(['link']);
</script>

<template>
  <div :class="align === 'center' ? 'text-center max-w-2xl mx-auto' : 'flex flex-col md:flex-row md:items-end justify-between gap-4'">
    <div :class="align === 'center' ? '' : 'max-w-2xl'">
      <p v-if="eyebrow" class="font-label-sm text-label-sm text-teal font-bold uppercase tracking-wider">{{ eyebrow }}</p>
      <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface mt-2">{{ title }}</h2>
      <p v-if="description" class="font-body-md text-body-md text-on-surface-variant mt-3">{{ description }}</p>
    </div>
    <slot>
      <a
        v-if="linkText"
        class="inline-flex items-center gap-1.5 font-label-md text-label-md text-teal hover:text-teal-dark font-semibold transition-colors cursor-pointer shrink-0"
        @click.prevent="emit('link')"
      >
        <span>{{ linkText }}</span>
        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
      </a>
    </slot>
  </div>
</template>
