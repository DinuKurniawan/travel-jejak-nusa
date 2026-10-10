<script setup>
defineProps({
  href: { type: String, default: 'https://wa.me/6281234567890' },
  label: { type: String, default: 'Chat WhatsApp' },
});
</script>

<template>
  <a
    :href="href"
    class="fixed bottom-7 right-7 z-40 h-[50px] px-5 rounded-full bg-[#25D366] text-white flex items-center gap-2 hover:bg-[#1EBE5D] transition-colors"
    rel="noopener noreferrer"
    target="_blank"
  >
    <span class="material-symbols-outlined text-[22px]" aria-hidden="true">chat</span>
    <span class="font-label-md text-label-md text-white tracking-wide">{{ label }}</span>
  </a>
</template>
