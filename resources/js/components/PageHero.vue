<script setup>
defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
  current: { type: String, default: '' },
  align: { type: String, default: 'left' },
});

const emit = defineEmits(['home']);
</script>

<template>
  <section class="w-full bg-teal text-white">
    <div class="max-w-[1200px] mx-auto px-margin pt-12 pb-14" :class="align === 'center' ? 'flex flex-col items-center text-center' : ''">
      <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-white/80 font-label-sm text-label-sm mb-4">
        <a class="hover:text-white transition-colors cursor-pointer" @click.prevent="emit('home')">Beranda</a>
        <span class="text-white/50">/</span>
        <span aria-current="page" class="text-white font-medium">{{ current }}</span>
      </nav>
      <h1 class="font-display-lg text-display-lg-mobile lg:text-display-lg tracking-tight text-white max-w-3xl">{{ title }}</h1>
      <p v-if="subtitle" class="font-body-lg text-body-lg text-white/90 leading-relaxed mt-4 max-w-3xl">{{ subtitle }}</p>
      <slot />
    </div>
    <slot name="bottom" />
  </section>
</template>
