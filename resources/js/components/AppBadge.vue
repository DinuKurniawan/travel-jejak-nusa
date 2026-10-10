<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: { type: String, default: 'promo' },
  shape: { type: String, default: 'pill' },
});

const variants = {
  none: '',
  promo: 'bg-orange text-white',
  teal: 'bg-teal text-white',
  info: 'bg-surface-container text-on-surface border border-card-border',
  accent: 'bg-yellow-light text-ink border border-yellow',
  success: 'bg-[#EAF8F2] text-[#2EB67D]',
  warning: 'bg-[#FEF6E9] text-[#F5A524]',
  danger: 'bg-[#FCECED] text-[#E5484D]',
  neutral: 'bg-[#F1F3F5] text-muted',
};

const cls = computed(() => [
  'inline-flex items-center gap-1 px-2.5 py-1 font-semibold whitespace-nowrap',
  props.shape === 'box' ? 'rounded-md text-xs' : 'rounded-full font-label-sm text-label-sm',
  variants[props.variant] || variants.promo,
].join(' '));
</script>

<template>
  <span :class="cls"><slot /></span>
</template>
