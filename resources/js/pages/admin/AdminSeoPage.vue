<script setup>
const emit = defineEmits(['navigate']);
</script>

<template>
  <div class="bg-white rounded-xl border border-card-border p-8 flex flex-col items-center text-center gap-3">
    <p class="font-body-md text-body-md text-muted max-w-md">Modul ini dikelola via Pengaturan → SEO agar satu sumber kebenaran.</p>
    <button class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer" type="button" @click="emit('navigate', 'settings')">
      Buka Pengaturan → SEO
    </button>
  </div>
</template>
