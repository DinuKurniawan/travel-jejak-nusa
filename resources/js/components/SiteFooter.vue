<script setup>
const emit = defineEmits(['navigate']);

const mainMenu = [
  { id: 'beranda', label: 'Beranda' },
  { id: 'tentang', label: 'Tentang Kami' },
  { id: 'paket-wisata', label: 'Paket Wisata' },
  { id: 'galeri', label: 'Galeri Foto' },
  { id: 'blog', label: 'Blog & Cerita' },
  { id: 'faq', label: 'FAQ' },
  { id: 'kontak', label: 'Hubungi Kami' },
];

const services = [
  { id: 'paket-wisata', label: 'Open Trip Nusantara' },
  { id: 'layanan', label: 'Private Tour Tematik' },
  { id: 'layanan', label: 'Honeymoon Package' },
  { id: 'layanan', label: 'Corporate Outing' },
  { id: 'layanan', label: 'Sewa Kendaraan & Kapal' },
];

const socials = [
  { icon: 'photo_camera', label: 'Instagram Jejak Nusa' },
  { icon: 'smart_display', label: 'YouTube Jejak Nusa' },
  { icon: 'public', label: 'Situs Jejak Nusa' },
  { icon: 'music_note', label: 'TikTok Jejak Nusa' },
];
</script>

<template>
  <footer class="w-full bg-ink text-[#9CA3AF] border-t border-[#374151]">
    <div class="w-full px-margin py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-xl">
      <div class="flex flex-col gap-space-md">
        <p class="font-headline-sm text-headline-sm text-white">Jejak Nusa</p>
        <p class="text-body-md font-body-md text-[#E5E7EB] italic font-medium">Satu jejak, seribu cerita Nusantara.</p>
        <p class="text-body-md font-body-md text-[#9CA3AF]">Platform kurasi tur dan ekspedisi autentik menjelajahi keindahan bahari, budaya, dan alam tropis kepulauan Indonesia secara aman dan transparan.</p>
        <div class="flex items-center gap-space-sm pt-space-xs">
          <a
            v-for="s in socials"
            :key="s.label"
            :aria-label="s.label"
            class="w-9 h-9 rounded-lg bg-[#374151] flex items-center justify-center text-[#E5E7EB] hover:bg-teal hover:text-white transition-colors"
            href="#"
          ><span class="material-symbols-outlined text-[18px]">{{ s.icon }}</span></a>
        </div>
      </div>
      <nav aria-label="Menu utama" class="flex flex-col gap-space-md">
        <h2 class="font-title-lg text-title-lg text-white">Menu Utama</h2>
        <ul class="flex flex-col gap-space-xs font-body-md text-body-md">
          <li v-for="m in mainMenu" :key="m.id + m.label">
            <a class="text-[#9CA3AF] hover:text-white transition-colors cursor-pointer" @click.prevent="emit('navigate', m.id)">{{ m.label }}</a>
          </li>
        </ul>
      </nav>
      <nav aria-label="Layanan wisata" class="flex flex-col gap-space-md">
        <h2 class="font-title-lg text-title-lg text-white">Layanan Wisata</h2>
        <ul class="flex flex-col gap-space-xs font-body-md text-body-md">
          <li v-for="s in services" :key="s.label">
            <a class="text-[#9CA3AF] hover:text-white transition-colors cursor-pointer" @click.prevent="emit('navigate', s.id)">{{ s.label }}</a>
          </li>
        </ul>
      </nav>
      <div class="flex flex-col gap-space-md">
        <h2 class="font-title-lg text-title-lg text-white">Hubungi Kami</h2>
        <address class="flex flex-col gap-space-xs font-body-md text-body-md not-italic">
          <p class="text-[#D1D5DB]"><strong class="text-white font-medium">Kantor:</strong> Jakarta &amp; Denpasar, Bali</p>
          <p class="text-[#D1D5DB]"><strong class="text-white font-medium">Telepon:</strong> +62 812-3456-7890</p>
          <p class="text-[#D1D5DB]"><strong class="text-white font-medium">Email:</strong> info@jejaknusa.id</p>
          <p class="text-[#D1D5DB]"><strong class="text-white font-medium">Operasional:</strong> 08.00 - 20.00 WIB</p>
        </address>
      </div>
    </div>
    <div class="w-full border-t border-[#374151]">
      <div class="w-full px-margin py-6 flex flex-col sm:flex-row items-center justify-between gap-space-xs font-label-sm text-label-sm text-[#9CA3AF]">
        <span>© 2026 Jejak Nusa Tour &amp; Travel.</span>
        <div class="flex items-center gap-space-md">
          <a class="hover:text-white transition-colors cursor-pointer text-[#0E9AA7] font-semibold" @click.prevent="emit('navigate', 'status-error')">Pusat Status Sistem</a>
          <span>|</span>
          <a class="hover:text-white transition-colors cursor-pointer" @click.prevent="emit('navigate', 'faq')">Syarat &amp; Ketentuan</a>
          <span>|</span>
          <a class="hover:text-white transition-colors cursor-pointer" @click.prevent="emit('navigate', 'faq')">Kebijakan Privasi</a>
        </div>
      </div>
    </div>
  </footer>
</template>
