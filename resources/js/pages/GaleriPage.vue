<script setup>
import { ref, computed } from 'vue';
import PageHero from '../components/PageHero.vue';
import AppButton from '../components/AppButton.vue';
import { api } from '../api.js';
import { useApiList } from '../useApiList.js';

function adaptGallery(g) {
  if (g.img) {
    return g;
  }

  return {
    id: g.id,
    region: g.region || '',
    tag: g.tag || '',
    tagStyle: 'text-secondary bg-secondary-fixed',
    photographer: g.photographer || '',
    title: g.title,
    desc: g.description || '',
    hoverSub: g.region || '',
    hoverTitle: g.title,
    lightboxTitle: g.title,
    lightboxCaption: g.description || '',
    lightboxLocation: g.region || '',
    category: (g.tag || '').toLowerCase(),
    img: g.image,
  };
}

const emit = defineEmits(['navigate']);

const navigate = (page) => {
  emit('navigate', page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ── Filter state ──────────────────────────────────────────────────────────────
const filterOptions = ['Semua', 'Pantai', 'Gunung', 'Budaya', 'Kuliner', 'Bahari', 'Kegiatan Peserta'];
const activeFilter = ref('Semua');

// ── Gallery items data (live API only) ────────────────────────────────────────
const { items: apiGallery } = useApiList(() => api.gallery({ per_page: 24 }), []);
// ── Filtered gallery (live API only) ──────────────────────────────────────────
const sourceGallery = computed(() => apiGallery.value.map(adaptGallery));
const filteredItems = computed(() => {
  if (activeFilter.value === 'Semua') {
    return sourceGallery.value;
  }
  const map = {
    Pantai: 'pantai',
    Gunung: 'gunung',
    Budaya: 'budaya',
    Kuliner: 'kuliner',
    Bahari: 'bahari',
    'Kegiatan Peserta': 'peserta',
  };

  return sourceGallery.value.filter((item) => item.category === map[activeFilter.value]);
});

// ── Lightbox ──────────────────────────────────────────────────────────────────
const lightboxOpen = ref(false);
const lightboxIndex = ref(0);

const lightboxItem = computed(() => filteredItems.value[lightboxIndex.value] ?? sourceGallery.value[0]);

const openLightbox = (index) => {
  lightboxIndex.value = index;
  lightboxOpen.value = true;
};

const closeLightbox = () => {
  lightboxOpen.value = false;
};

const prevLightbox = () => {
  lightboxIndex.value = (lightboxIndex.value - 1 + filteredItems.value.length) % filteredItems.value.length;
};

const nextLightbox = () => {
  lightboxIndex.value = (lightboxIndex.value + 1) % filteredItems.value.length;
};

// Keyboard navigation
const handleKey = (e) => {
  if (!lightboxOpen.value) return;
  if (e.key === 'Escape') closeLightbox();
  if (e.key === 'ArrowRight') nextLightbox();
  if (e.key === 'ArrowLeft') prevLightbox();
};

import { onMounted, onUnmounted } from 'vue';
onMounted(() => window.addEventListener('keydown', handleKey));
onUnmounted(() => window.removeEventListener('keydown', handleKey));
</script>

<template>
  <div class="flex flex-col w-full">

    <!-- 1. PAGE HEADER ───────────────────────────────────────────────────── -->
    <PageHero
      current="Galeri"
      title="Galeri Perjalanan"
      subtitle="Dokumentasi otentik penjelajahan pelancong Jejak Nusa di berbagai surga tersembunyi Indonesia."
      @home="navigate('beranda')"
    />

    <!-- 2. FILTER STRIP ──────────────────────────────────────────────────── -->
    <section class="w-full bg-surface-container-lowest py-space-md border-b border-surface-container-highest">
      <div class="max-w-7xl mx-auto px-space-lg flex flex-col md:flex-row md:items-center justify-between gap-space-md">
        <!-- Filter chips -->
        <div class="flex items-center gap-space-xs overflow-x-auto pb-1 md:pb-0">
          <button
            v-for="opt in filterOptions"
            :key="opt"
            :class="activeFilter === opt
              ? 'bg-primary text-on-primary border-primary'
              : 'bg-surface text-on-surface border-outline-variant hover:border-primary'"
            class="shrink-0 px-space-md py-2 rounded-lg font-label-md text-label-md border-2 transition-colors"
            type="button"
            @click="activeFilter = opt; lightboxIndex = 0"
          >
            {{ opt }}
          </button>
        </div>
        <!-- Count -->
        <div class="shrink-0 flex items-center gap-space-xs font-label-sm text-label-sm text-on-surface-variant">
          <span class="material-symbols-outlined text-[18px] text-primary">photo_library</span>
          <span>
            Menampilkan
            <strong class="text-on-surface font-label-md">{{ filteredItems.length }} Destinasi Terpilih</strong>
          </span>
        </div>
      </div>
    </section>

    <!-- 3. PHOTO GRID ────────────────────────────────────────────────────── -->
    <section class="w-full bg-surface-container-low py-space-xl">
      <div class="max-w-7xl mx-auto px-space-lg flex flex-col gap-space-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-lg">
          <article
            v-for="(item, idx) in filteredItems"
            :key="item.id"
            class="group bg-surface-container-lowest rounded-xl overflow-hidden border border-surface-container-highest flex flex-col transition-all cursor-pointer"
            @click="openLightbox(idx)"
          >
            <div class="relative w-full aspect-[4/5] overflow-hidden bg-surface-container">
              <img
                :alt="item.title"
                :src="item.img"
                class="w-full h-full object-cover rounded-t-xl group-hover:scale-105 transition-transform duration-300"
              >
              <!-- Region badge -->
              <div class="absolute top-3 left-3 bg-surface-container-lowest px-space-xs py-1 rounded font-label-sm text-label-sm text-on-surface border border-outline-variant">
                {{ item.region }}
              </div>
              <!-- Hover overlay -->
              <div class="absolute inset-0 bg-primary/90 opacity-0 group-hover:opacity-100 flex flex-col justify-between p-space-md transition-opacity">
                <div class="flex justify-end">
                  <span class="w-10 h-10 rounded-full border-2 border-on-primary text-on-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                  </span>
                </div>
                <div class="text-on-primary">
                  <p class="font-label-sm text-label-sm text-primary-fixed-dim">{{ item.hoverSub }}</p>
                  <h4 class="font-headline-sm text-headline-sm">{{ item.hoverTitle }}</h4>
                </div>
              </div>
            </div>
            <div class="p-space-md flex flex-col gap-space-xs bg-surface-container-lowest">
              <div class="flex items-center justify-between gap-space-xs">
                <span :class="item.tagStyle" class="font-label-sm text-label-sm px-2 py-0.5 rounded font-semibold">
                  {{ item.tag }}
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">photo_camera</span>
                  {{ item.photographer }}
                </span>
              </div>
              <h3 class="font-title-lg text-title-lg text-on-surface group-hover:text-primary transition-colors">
                {{ item.title }}
              </h3>
              <p class="font-body-md text-body-md text-on-surface-variant line-clamp-1">{{ item.desc }}</p>
            </div>
          </article>
        </div>

        <!-- Empty state -->
        <div v-if="filteredItems.length === 0" class="text-center py-16 text-on-surface-variant font-body-lg text-body-lg">
          Belum ada foto untuk kategori ini.
        </div>

        <!-- Load More -->
        <div class="flex flex-col items-center justify-center gap-space-sm pt-space-md">
          <AppButton variant="outline" size="lg">
            <span class="material-symbols-outlined text-[20px] mr-2" aria-hidden="true">sync</span>
            Muat Lebih Banyak Foto
          </AppButton>
          <span class="font-label-sm text-label-sm text-on-surface-variant">Menampilkan {{ filteredItems.length }} dari {{ sourceGallery.length }} arsip dokumentasi resmi</span>
        </div>
      </div>
    </section>

    <!-- 4. LIGHTBOX MODAL ────────────────────────────────────────────────── -->
    <Teleport to="body">
      <div
        v-if="lightboxOpen"
        class="fixed inset-0 z-50 bg-inverse-surface/90 flex items-center justify-center p-4"
        @click.self="closeLightbox"
      >
        <div class="relative w-full max-w-4xl bg-surface-container-lowest rounded-xl border border-surface-container-highest overflow-hidden flex flex-col">
          <!-- Top bar -->
          <div class="h-14 px-space-md bg-surface-container-lowest border-b border-surface-container-highest flex items-center justify-between">
            <div class="flex items-center gap-space-xs">
              <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
              <span class="font-label-md text-label-md text-on-surface">{{ lightboxItem.lightboxLocation }}</span>
            </div>
            <button
              aria-label="Tutup"
              class="w-9 h-9 rounded-lg border border-outline-variant text-on-surface hover:bg-surface-container flex items-center justify-center transition-colors"
              type="button"
              @click="closeLightbox"
            >
              <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
          </div>
          <!-- Image -->
          <div class="relative w-full bg-inverse-surface flex items-center justify-center aspect-[16/10] sm:aspect-[16/9]">
            <img
              :alt="lightboxItem.lightboxTitle"
              :src="lightboxItem.img"
              class="w-full h-full object-cover"
            >
            <button
              aria-label="Sebelumnya"
              class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-surface-container-lowest text-on-surface border-2 border-on-surface hover:bg-surface-container flex items-center justify-center transition-colors"
              type="button"
              @click="prevLightbox"
            >
              <span class="material-symbols-outlined text-[22px]">chevron_left</span>
            </button>
            <button
              aria-label="Berikutnya"
              class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-surface-container-lowest text-on-surface border-2 border-on-surface hover:bg-surface-container flex items-center justify-center transition-colors"
              type="button"
              @click="nextLightbox"
            >
              <span class="material-symbols-outlined text-[22px]">chevron_right</span>
            </button>
          </div>
          <!-- Caption bar -->
          <div class="p-space-md bg-surface-container-lowest border-t border-surface-container-highest flex flex-col md:flex-row md:items-center justify-between gap-space-sm">
            <div class="flex flex-col gap-1">
              <h3 class="font-title-lg text-title-lg text-on-surface">{{ lightboxItem.lightboxTitle }}</h3>
              <p class="font-body-md text-body-md text-on-surface-variant">{{ lightboxItem.lightboxCaption }}</p>
            </div>
            <div class="flex items-center gap-space-xs shrink-0">
              <span class="font-label-sm text-label-sm bg-surface-container px-space-xs py-1 rounded text-on-surface font-semibold">
                {{ lightboxIndex + 1 }} / {{ filteredItems.length }}
              </span>
              <button
                class="h-9 px-space-md rounded bg-primary text-on-primary font-label-sm text-label-sm flex items-center gap-1"
                type="button"
              >
                <span class="material-symbols-outlined text-[16px]">share</span>
                Bagikan
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- 5. VIDEO DOKUMENTASI ─────────────────────────────────────────────── -->
    <section class="w-full bg-surface-container-lowest py-space-xl border-t border-surface-container-highest">
      <div class="max-w-7xl mx-auto px-space-lg flex flex-col gap-space-lg">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-xs">
          <div>
            <span class="font-label-sm text-label-sm text-primary uppercase font-bold tracking-wider">Sinematografi Ekspedisi</span>
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">
              Video Dokumentasi Ekspedisi
            </h2>
          </div>
          <p class="font-body-md text-body-md text-on-surface-variant max-w-md">
            Saksikan langsung atmosfer otentik petualangan darat dan bahari tim kami bersama penjelajah di seluruh pelosok Indonesia.
          </p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-space-lg">
          <!-- Video Card 1 -->
          <article class="group bg-surface rounded-xl overflow-hidden border border-surface-container-highest flex flex-col">
            <div class="relative w-full aspect-video overflow-hidden bg-surface-container">
              <img
                alt="Aftermovie Ekspedisi Raja Ampat"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                src="/images/img-48.jpg"
              >
              <div class="absolute top-4 right-4 bg-inverse-surface text-inverse-on-surface px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold">
                03:45
              </div>
              <div class="absolute inset-0 flex items-center justify-center">
                <button
                  aria-label="Putar Video Raja Ampat"
                  class="w-16 h-16 rounded-full bg-primary text-on-primary flex items-center justify-center hover:bg-primary-container transition-colors border-2 border-on-primary"
                  type="button"
                >
                  <span class="material-symbols-outlined text-[34px] ml-1">play_arrow</span>
                </button>
              </div>
            </div>
            <div class="p-space-lg flex flex-col gap-space-xs bg-surface-container-lowest">
              <div class="flex items-center gap-space-xs font-label-sm text-label-sm text-primary font-semibold">
                <span class="material-symbols-outlined text-[16px]">videocam</span>
                <span>4K Sinematik</span>
              </div>
              <h3 class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors">
                Aftermovie Ekspedisi Raja Ampat 4D3N
              </h3>
              <p class="font-body-md text-body-md text-on-surface-variant">
                Rangkuman serunya berenang di Laguna Wayag, snorkeling di Arborek, dan menapaki puncak Pianemo bersama rombongan kloter Mei 2025.
              </p>
            </div>
          </article>
          <!-- Video Card 2 -->
          <article class="group bg-surface rounded-xl overflow-hidden border border-surface-container-highest flex flex-col">
            <div class="relative w-full aspect-video overflow-hidden bg-surface-container">
              <img
                alt="Eksplorasi Lembah Baliem"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                src="/images/img-49.jpg"
              >
              <div class="absolute top-4 right-4 bg-inverse-surface text-inverse-on-surface px-space-xs py-1 rounded font-label-sm text-label-sm font-semibold">
                05:12
              </div>
              <div class="absolute inset-0 flex items-center justify-center">
                <button
                  aria-label="Putar Video Lembah Baliem"
                  class="w-16 h-16 rounded-full bg-primary text-on-primary flex items-center justify-center hover:bg-primary-container transition-colors border-2 border-on-primary"
                  type="button"
                >
                  <span class="material-symbols-outlined text-[34px] ml-1">play_arrow</span>
                </button>
              </div>
            </div>
            <div class="p-space-lg flex flex-col gap-space-xs bg-surface-container-lowest">
              <div class="flex items-center gap-space-xs font-label-sm text-label-sm text-primary font-semibold">
                <span class="material-symbols-outlined text-[16px]">videocam</span>
                <span>Dokumenter Budaya</span>
              </div>
              <h3 class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors">
                Eksplorasi Lembah Baliem &amp; Desa Adat
              </h3>
              <p class="font-body-md text-body-md text-on-surface-variant">
                Perjumpaan mendalam dengan kearifan suku Dani di pegunungan tengah Papua, tradisi bakar batu, dan kehangatan honai.
              </p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- 6. INSTAGRAM STRIP ───────────────────────────────────────────────── -->
    <section class="w-full bg-surface-container-low py-space-xl border-t border-surface-container-highest">
      <div class="max-w-7xl mx-auto px-space-lg flex flex-col md:flex-row items-center justify-between gap-space-lg">
        <div class="flex items-start gap-space-md">
          <div class="w-12 h-12 rounded-lg bg-surface-container-highest text-primary flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[28px]">photo_camera_front</span>
          </div>
          <div class="flex flex-col">
            <h4 class="font-title-lg text-title-lg text-on-surface">Punya Foto Bersama Jejak Nusa?</h4>
            <p class="font-body-md text-body-md text-on-surface-variant">
              Tag akun Instagram <span class="text-primary font-semibold">@jejaknusa.id</span> atau gunakan tagar
              <span class="font-semibold text-on-surface">#JejakNusaStory</span> agar karyamu dipamerkan di galeri kurasi resmi kami.
            </p>
          </div>
        </div>
        <a
          class="shrink-0 inline-flex items-center justify-center h-11 px-space-lg rounded-lg border-2 border-outline-variant text-on-surface hover:border-primary font-label-md text-label-md transition-colors"
          href="https://instagram.com"
          rel="noopener noreferrer"
          target="_blank"
        >
          Kunjungi Instagram Kami
        </a>
      </div>
    </section>

    <!-- 7. CTA BANNER ───────────────────────────────────────────────────── -->
    <section class="w-full bg-secondary-container text-on-secondary py-space-xl px-space-lg">
      <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-between gap-space-lg text-center md:text-left">
        <div class="flex flex-col gap-space-xs max-w-2xl">
          <h2 class="font-headline-lg text-headline-lg font-bold text-on-secondary tracking-tight">
            Ingin Jadi Bagian dari Cerita Berikutnya?
          </h2>
          <p class="font-body-lg text-body-lg text-on-secondary-container">
            Pilih ekspedisi impianmu sekarang dan ciptakan album kenangan berharga melintasi bentang alam Nusantara bersama pemandu lokal berlisensi.
          </p>
        </div>
        <div class="shrink-0">
          <a
            class="inline-flex items-center justify-center h-12 px-space-xl rounded-lg bg-surface-container-lowest text-on-surface font-label-md text-label-md hover:bg-surface-container transition-colors cursor-pointer"
            @click.prevent="navigate('paket-wisata')"
          >
            Lihat Paket Wisata
          </a>
        </div>
      </div>
    </section>

  </div>
</template>
