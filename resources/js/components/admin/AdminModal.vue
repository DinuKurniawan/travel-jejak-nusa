<script setup>
defineProps({
  open: { type: Boolean, default: false },
  title: { type: String, default: '' },
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
  <Teleport to="body">
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-ink/60" @click="emit('close')" />
      <div role="dialog" aria-modal="true" :aria-label="title" class="relative bg-white rounded-xl border-[1.5px] border-card-border w-full max-w-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-card-border flex items-center justify-between">
          <h2 class="font-headline-sm text-headline-sm text-ink font-semibold">{{ title }}</h2>
          <button aria-label="Tutup dialog" class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="emit('close')">
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>
        <div class="px-6 py-5"><slot /></div>
        <div class="px-6 py-4 border-t border-card-border bg-surface-container-low flex justify-end gap-3">
          <slot name="footer">
            <button class="h-10 px-4 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-md hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="emit('close')">Batal</button>
            <button class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer" type="button" @click="emit('confirm')">Simpan</button>
          </slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>
