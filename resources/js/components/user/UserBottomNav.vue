<script setup>
defineProps({
  active: { type: String, default: 'ringkasan' },
});

const emit = defineEmits(['navigate']);

const items = [
  { id: 'ringkasan', label: 'Beranda', icon: 'home' },
  { id: 'pesanan-saya', label: 'Pesanan', icon: 'confirmation_number' },
  { id: 'notifikasi', label: 'Notifikasi', icon: 'notifications' },
  { id: 'profil', label: 'Akun', icon: 'person' },
];
</script>

<template>
  <nav aria-label="Navigasi bawah akun" class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-card-border px-2 pb-[env(safe-area-inset-bottom)]">
    <ul class="grid grid-cols-4">
      <li v-for="item in items" :key="item.id">
        <button
          :aria-current="active === item.id ? 'page' : undefined"
          :class="active === item.id ? 'text-teal' : 'text-muted'"
          class="w-full py-2 flex flex-col items-center gap-0.5 font-label-sm text-label-sm cursor-pointer"
          type="button"
          @click="emit('navigate', item.id)"
        >
          <span class="material-symbols-outlined text-[22px]" aria-hidden="true">{{ item.icon }}</span>
          <span>{{ item.label }}</span>
        </button>
      </li>
    </ul>
  </nav>
</template>
