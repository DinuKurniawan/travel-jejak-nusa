<script setup>
defineProps({
  page: { type: Number, default: 1 },
  total: { type: Number, default: 1 },
  label: { type: String, default: 'Navigasi Halaman' },
});

const emit = defineEmits(['go']);

const visible = (p, total) => p <= 4 || p === total;
</script>

<template>
  <nav v-if="total > 1" :aria-label="label" class="flex items-center justify-center gap-1.5 py-6">
    <button
      :disabled="page === 1"
      aria-label="Halaman sebelumnya"
      class="h-10 px-3 rounded bg-surface-container text-on-surface font-label-md text-label-sm flex items-center gap-1 hover:bg-surface-variant transition-colors disabled:opacity-40 cursor-pointer"
      type="button"
      @click="emit('go', page - 1)"
    >
      <span class="material-symbols-outlined text-[16px]">arrow_back</span>
      <span class="hidden sm:inline">Sebelumnya</span>
    </button>
    <template v-for="p in total" :key="p">
      <button
        v-if="visible(p, total)"
        :class="page === p ? 'bg-teal text-white' : 'bg-surface-container text-on-surface hover:bg-surface-variant'"
        class="w-10 h-10 rounded font-label-md text-label-sm transition-colors cursor-pointer"
        type="button"
        @click="emit('go', p)"
      >
        {{ p }}
      </button>
      <span v-else-if="p === 5" class="w-8 h-10 flex items-center justify-center text-outline">...</span>
    </template>
    <button
      :disabled="page === total"
      aria-label="Halaman berikutnya"
      class="h-10 px-3 rounded bg-surface-container text-on-surface font-label-md text-label-sm flex items-center gap-1 hover:bg-surface-variant transition-colors disabled:opacity-40 cursor-pointer"
      type="button"
      @click="emit('go', page + 1)"
    >
      <span class="hidden sm:inline">Berikutnya</span>
      <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
    </button>
  </nav>
</template>
