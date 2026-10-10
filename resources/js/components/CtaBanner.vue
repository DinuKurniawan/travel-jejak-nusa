<script setup>
import AppButton from './AppButton.vue';

defineProps({
  variant: { type: String, default: 'teal' },
  badge: { type: String, default: '' },
  title: { type: String, default: '' },
  description: { type: String, default: '' },
  primaryLabel: { type: String, default: '' },
  primaryHref: { type: String, default: '' },
  secondaryLabel: { type: String, default: '' },
});

const emit = defineEmits(['primary', 'secondary']);
</script>

<template>
  <section class="w-full bg-surface-container-lowest py-16 md:py-20">
    <div class="w-full px-4 sm:px-6 md:px-margin">
      <div :class="variant === 'orange' ? 'bg-orange text-white' : 'bg-teal text-white'" class="rounded-xl p-8 md:p-12 flex flex-col lg:flex-row items-center justify-between gap-8">
        <div class="max-w-2xl flex flex-col gap-3 text-center lg:text-left">
          <span v-if="badge" class="inline-block px-3 py-1 rounded-full bg-white text-teal font-label-sm text-label-sm font-bold w-fit mx-auto lg:mx-0">{{ badge }}</span>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-white">{{ title }}</h2>
          <p v-if="description" class="font-body-lg text-body-lg text-white/90">{{ description }}</p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-4 shrink-0 w-full lg:w-auto justify-center">
          <AppButton v-if="primaryLabel" variant="primary" size="lg" :href="primaryHref" class="w-full sm:w-auto" @click="emit('primary')">{{ primaryLabel }}</AppButton>
          <button
            v-if="secondaryLabel"
            class="w-full sm:w-auto inline-flex items-center justify-center h-12 px-7 rounded-lg border-2 border-white bg-transparent hover:bg-white hover:text-teal text-white font-label-md text-label-md transition-colors cursor-pointer"
            type="button"
            @click="emit('secondary')"
          >
            {{ secondaryLabel }}
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
