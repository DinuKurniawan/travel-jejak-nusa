<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  href: { type: String, default: '' },
  type: { type: String, default: 'button' },
  block: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
});

const emit = defineEmits(['click']);

const variants = {
  primary: 'bg-orange hover:bg-[#DE5E26] text-white',
  secondary: 'bg-teal hover:bg-teal-dark text-white',
  outline: 'border-2 border-teal bg-transparent text-teal hover:bg-teal-light',
  white: 'bg-surface-container-lowest text-on-surface hover:bg-surface-container-low',
  outlineWhite: 'border-2 border-white bg-transparent text-white hover:bg-white/15',
  danger: 'bg-error text-on-error hover:brightness-95',
};

const sizes = {
  sm: 'h-10 px-4 text-sm',
  md: 'h-11 px-6',
  lg: 'h-12 px-7',
};

const cls = computed(() => [
  'inline-flex items-center justify-center gap-2 rounded-lg font-label-md text-label-md transition-colors cursor-pointer select-none',
  sizes[props.size] || sizes.md,
  variants[props.variant] || variants.primary,
  props.block ? 'w-full' : '',
  props.disabled ? 'opacity-50 pointer-events-none' : '',
].join(' '));

const onClick = (e) => {
  if (props.disabled) {
    e.preventDefault();
    return;
  }
  emit('click', e);
};
</script>

<template>
  <a v-if="href" :href="href" :class="cls" @click="onClick"><slot /></a>
  <button v-else :type="type" :class="cls" :disabled="disabled" @click="onClick"><slot /></button>
</template>
