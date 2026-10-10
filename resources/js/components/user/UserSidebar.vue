<script setup>
defineProps({
  active: { type: String, default: 'ringkasan' },
});

const emit = defineEmits(['navigate', 'close']);

const groups = [
  {
    label: 'Perjalanan',
    items: [
      { id: 'ringkasan', label: 'Ringkasan', icon: 'dashboard' },
      { id: 'pesanan-saya', label: 'Booking Saya', icon: 'confirmation_number' },
      { id: 'tiket-invoice', label: 'Tiket & Invoice', icon: 'receipt_long' },
      { id: 'pembayaran', label: 'Pembayaran', icon: 'payments' },
    ],
  },
  {
    label: 'Simpanan',
    items: [
      { id: 'wishlist', label: 'Wishlist', icon: 'favorite' },
      { id: 'voucher', label: 'Voucher Saya', icon: 'local_offer' },
      { id: 'ulasan', label: 'Ulasan Saya', icon: 'reviews' },
      { id: 'notifikasi', label: 'Notifikasi', icon: 'notifications' },
    ],
  },
  {
    label: 'Akun',
    items: [
      { id: 'profil', label: 'Profil', icon: 'person' },
      { id: 'data-traveler', label: 'Data Traveler', icon: 'group' },
      { id: 'keamanan', label: 'Keamanan', icon: 'lock' },
      { id: 'bantuan', label: 'Bantuan', icon: 'help' },
    ],
  },
];
</script>

<template>
  <div v-if="open" class="fixed inset-0 z-30 bg-ink/40 lg:hidden" @click="emit('close')" />
  <aside
    :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:sticky top-0 left-0 z-40 h-screen w-[260px] shrink-0 bg-white border-r border-card-border flex flex-col transition-transform"
  >
    <div class="h-16 px-5 flex items-center gap-2.5 border-b border-card-border">
      <img alt="Logo Jejak Nusa" class="h-8 w-auto" src="/images/logo.svg">
      <div>
        <p class="font-jakarta font-bold text-[18px] leading-none text-ink">Jejak Nusa</p>
        <p class="text-[11px] text-muted mt-0.5">Akun Traveler</p>
      </div>
    </div>
    <nav aria-label="Navigasi akun traveler" class="flex-1 overflow-y-auto px-3 py-4 flex flex-col gap-5">
      <div v-for="group in groups" :key="group.label">
        <p class="px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-muted">{{ group.label }}</p>
        <ul class="flex flex-col gap-0.5">
          <li v-for="item in group.items" :key="item.id">
            <button
              :aria-current="active === item.id ? 'page' : undefined"
              :class="active === item.id ? 'bg-info-tint text-teal font-semibold' : 'text-ink hover:bg-page'"
              class="w-full h-10 px-3 rounded-lg flex items-center gap-3 font-label-md text-label-md transition-colors cursor-pointer"
              type="button"
              @click="emit('navigate', item.id)"
            >
              <span class="material-symbols-outlined text-[20px]" aria-hidden="true">{{ item.icon }}</span>
              <span>{{ item.label }}</span>
            </button>
          </li>
        </ul>
      </div>
    </nav>
    <div class="p-3 border-t border-card-border">
      <button
        class="w-full h-10 px-3 rounded-lg flex items-center gap-3 text-ink hover:bg-page font-label-md text-label-md transition-colors cursor-pointer"
        type="button"
        @click="emit('navigate', 'beranda')"
      >
        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">explore</span>
        <span>Jelajah Paket</span>
      </button>
    </div>
  </aside>
</template>
