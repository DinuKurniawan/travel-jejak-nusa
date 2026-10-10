<script setup>
defineProps({
  active: { type: String, default: 'dashboard' },
  open: { type: Boolean, default: false },
});

const emit = defineEmits(['navigate', 'close', 'logout']);

const groups = [
  {
    label: 'Utama',
    items: [
      { id: 'dashboard', label: 'Dashboard', icon: 'dashboard' },
      { id: 'bookings', label: 'Pemesanan', icon: 'confirmation_number' },
      { id: 'payments', label: 'Verifikasi Bayar', icon: 'payments' },
      { id: 'messages', label: 'Pesan Masuk', icon: 'inbox' },
    ],
  },
  {
    label: 'Konten',
    items: [
      { id: 'packages', label: 'Paket Wisata', icon: 'luggage' },
      { id: 'articles', label: 'Artikel Blog', icon: 'article' },
      { id: 'gallery', label: 'Media & Galeri', icon: 'photo_library' },
      { id: 'testimonials', label: 'Testimoni', icon: 'reviews' },
      { id: 'faqs', label: 'FAQ', icon: 'help' },
      { id: 'banners', label: 'Banner', icon: 'web_asset' },
    ],
  },
  {
    label: 'Kelola Situs',
    items: [
      { id: 'home', label: 'Kelola Beranda', icon: 'home' },
      { id: 'about', label: 'Tentang Kami', icon: 'info' },
      { id: 'services', label: 'Layanan', icon: 'explore' },
      { id: 'navigation', label: 'Navigasi & Footer', icon: 'menu' },
      { id: 'contact', label: 'Kontak', icon: 'contact_support' },
      { id: 'seo', label: 'SEO', icon: 'search' },
      { id: 'settings', label: 'Pengaturan', icon: 'settings' },
    ],
  },
  {
    label: 'Sistem',
    items: [
      { id: 'users', label: 'Pengguna', icon: 'group' },
      { id: 'roles', label: 'Hak Akses', icon: 'admin_panel_settings' },
      { id: 'team', label: 'Tim', icon: 'diversity_3' },
      { id: 'audit', label: 'Audit Log', icon: 'history' },
      { id: 'profile', label: 'Profil Saya', icon: 'person' },
    ],
  },
];
</script>

<template>
  <div v-if="open" class="fixed inset-0 z-30 bg-ink/60 lg:hidden" @click="emit('close')" />
  <aside
    :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:sticky top-0 left-0 z-40 h-screen w-[260px] shrink-0 bg-ink text-white flex flex-col transition-transform"
  >
    <div class="h-16 px-5 flex items-center gap-2.5 border-b border-white/10">
      <img alt="Logo Jejak Nusa" class="h-8 w-auto" src="/images/logo.svg">
      <div>
        <p class="font-headline-sm text-headline-sm leading-none">Jejak Nusa</p>
        <p class="text-[11px] text-white/60 mt-0.5">Admin CMS</p>
      </div>
    </div>
    <nav aria-label="Navigasi admin" class="flex-1 overflow-y-auto px-3 py-4 flex flex-col gap-5">
      <div v-for="group in groups" :key="group.label">
        <p class="px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-white/50">{{ group.label }}</p>
        <ul class="flex flex-col gap-0.5">
          <li v-for="item in group.items" :key="item.id">
            <button
              :aria-current="active === item.id ? 'page' : undefined"
              :class="active === item.id ? 'bg-teal text-white' : 'text-white/70 hover:bg-white/10 hover:text-white'"
              class="w-full h-10 px-3 rounded-lg flex items-center gap-3 font-label-md text-label-md transition-colors cursor-pointer"
              type="button"
              @click="emit('navigate', item.id)"
            >
              <span class="material-symbols-outlined text-[20px]">{{ item.icon }}</span>
              <span>{{ item.label }}</span>
            </button>
          </li>
        </ul>
      </div>
    </nav>
    <div class="p-3 border-t border-white/10">
      <button
        class="w-full h-10 px-3 rounded-lg flex items-center gap-3 text-white/70 hover:bg-white/10 hover:text-white font-label-md text-label-md transition-colors cursor-pointer"
        type="button"
        @click="emit('logout')"
      >
        <span class="material-symbols-outlined text-[20px]">logout</span>
        <span>Keluar</span>
      </button>
    </div>
  </aside>
</template>
