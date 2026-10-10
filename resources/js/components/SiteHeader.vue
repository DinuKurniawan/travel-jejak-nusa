<script setup>
import { ref } from 'vue';

defineProps({
  currentPage: { type: String, default: 'beranda' },
});

const emit = defineEmits(['navigate']);

const isMobileMenuOpen = ref(false);

const links = [
  { id: 'beranda', label: 'Beranda', icon: 'home' },
  { id: 'tentang', label: 'Tentang', icon: 'info' },
  { id: 'layanan', label: 'Layanan', icon: 'explore' },
  { id: 'paket-wisata', label: 'Paket Wisata', icon: 'luggage' },
  { id: 'galeri', label: 'Galeri', icon: 'photo_library' },
  { id: 'blog', label: 'Blog', icon: 'article' },
  { id: 'faq', label: 'FAQ', icon: 'help' },
  { id: 'kontak', label: 'Kontak', icon: 'contact_support' },
];

function go(page) {
  isMobileMenuOpen.value = false;
  emit('navigate', page);
}

function isActive(link, current) {
  if (link === 'blog') {
    return current === 'blog' || current === 'detail-blog';
  }
  return current === link;
}

defineExpose({ close: () => { isMobileMenuOpen.value = false; } });
</script>

<template>
  <header class="fixed top-0 left-0 w-full z-50">
    <div class="h-[72px] w-full bg-surface-container-lowest border-b border-card-border">
      <div class="w-full h-full px-margin flex items-center justify-between">
        <div class="flex items-center gap-space-sm cursor-pointer" @click="go('beranda')">
          <img alt="Logo Jejak Nusa" class="h-8 w-auto object-contain" src="/images/logo.svg">
          <span class="font-headline-sm text-headline-sm text-teal tracking-tight">Jejak Nusa</span>
        </div>
        <nav aria-label="Navigasi utama" class="hidden lg:flex items-center gap-gutter h-full">
          <a
            v-for="link in links"
            :key="link.id"
            :class="isActive(link.id, currentPage)
              ? 'text-teal font-label-md border-b-[3px] border-teal'
              : 'text-on-surface-variant hover:text-teal font-body-md text-body-md'"
            class="h-full flex items-center transition-colors cursor-pointer"
            @click.prevent="go(link.id)"
          >{{ link.label }}</a>
        </nav>
        <div class="flex items-center gap-space-md">
          <a
            class="hidden sm:inline-flex items-center gap-1.5 h-11 px-4 rounded-lg border border-teal text-teal hover:bg-teal-light font-label-md text-label-md font-semibold transition-colors cursor-pointer"
            @click.prevent="go('login')"
          >
            <span class="material-symbols-outlined text-[18px]">login</span>
            <span>Login</span>
          </a>
          <a
            class="hidden sm:inline-flex items-center justify-center h-11 px-5 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer"
            @click.prevent="go('kontak')"
          >Hubungi Kami</a>
          <div
            class="w-8 h-8 rounded-full bg-teal flex items-center justify-center cursor-pointer hover:opacity-90 transition-opacity"
            title="Login Akun Admin"
            @click="go('login')"
          >
            <span class="material-symbols-outlined text-white text-[18px]">person</span>
          </div>
          <button
            :aria-expanded="isMobileMenuOpen"
            aria-label="Buka menu navigasi"
            class="lg:hidden w-10 h-10 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container transition-colors cursor-pointer"
            type="button"
            @click="isMobileMenuOpen = !isMobileMenuOpen"
          >
            <span class="material-symbols-outlined text-[26px]">{{ isMobileMenuOpen ? 'close' : 'menu' }}</span>
          </button>
        </div>
      </div>
    </div>

    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <nav v-if="isMobileMenuOpen" aria-label="Navigasi seluler" class="lg:hidden w-full bg-white border-b border-card-border px-4 py-4 flex flex-col gap-1">
        <a
          v-for="link in links"
          :key="link.id"
          :class="isActive(link.id, currentPage) ? 'text-teal font-bold bg-teal-light' : 'text-on-surface-variant hover:text-teal'"
          class="px-3.5 py-2.5 rounded-lg transition-colors cursor-pointer flex items-center gap-2"
          @click.prevent="go(link.id)"
        >
          <span class="material-symbols-outlined text-[18px]">{{ link.icon }}</span>
          <span>{{ link.label }}</span>
        </a>
        <div class="pt-2 mt-1 border-t border-card-border flex flex-col gap-2">
          <a
            class="w-full h-10 px-4 rounded-lg border border-teal text-teal hover:bg-teal-light font-label-md text-sm font-semibold flex items-center justify-center gap-2 cursor-pointer"
            @click.prevent="go('login')"
          >
            <span class="material-symbols-outlined text-[18px]">login</span>
            <span>Login Admin</span>
          </a>
          <a
            class="w-full h-10 px-4 rounded-lg bg-orange text-white font-label-md text-sm hover:bg-[#DE5E26] font-semibold flex items-center justify-center gap-2 cursor-pointer"
            @click.prevent="go('kontak')"
          >
            <span class="material-symbols-outlined text-[18px]">chat</span>
            <span>Hubungi Kami</span>
          </a>
        </div>
      </nav>
    </transition>
  </header>
</template>
