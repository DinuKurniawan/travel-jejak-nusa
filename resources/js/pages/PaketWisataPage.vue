<script setup>
import { ref, computed, watch } from 'vue';
import PackageCard from '../components/PackageCard.vue';
import Pagination from '../components/Pagination.vue';
import EmptyState from '../components/EmptyState.vue';
import AppButton from '../components/AppButton.vue';
import { api, formatRupiah } from '../api.js';
import { useApiList } from '../useApiList.js';

// API shape -> card shape adapter (fallback static data keeps pixel-identical offline).
function adaptPackage(p) {
  if (p.tipe) {
    return p;
  }

  return {
    id: p.id,
    slug: p.slug,
    badge: p.badge || '',
    badgeColor: p.status === 'published' ? 'bg-primary text-on-primary' : 'bg-tertiary-container text-on-tertiary-container',
    seatBadge: p.seat_badge || null,
    location: p.location,
    title: p.title,
    durasi: p.duration || '',
    tipe: p.trip_type === 'private' ? 'Private Trip' : 'Open Trip',
    rating: Number(p.rating || 0),
    ulasan: p.reviews_count ?? 0,
    harga: formatRupiah(p.price),
    img: p.image || '/images/img-54.jpg',
  };
}

const badgeVariants = {
  'bg-secondary text-on-secondary': { variant: 'promo', shape: 'pill' },
  'bg-primary text-on-primary': { variant: 'teal', shape: 'pill' },
  'bg-tertiary-container text-on-tertiary-container': { variant: 'accent', shape: 'pill' },
};

const badgeFor = (pkg) => badgeVariants[pkg.badgeColor] || { variant: 'promo', shape: 'pill' };
const typeClassFor = (pkg) => pkg.tipe.toLowerCase().includes('private')
  ? 'bg-yellow-light text-[#755b00]'
  : 'bg-surface-container-high text-primary';

const props = defineProps({
  initialParams: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(['navigate']);

const navigate = (page, params = {}) => {
  emit('navigate', page, params);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ── Search ──────────────────────────────────────────────────────────────────
const searchQuery = ref('');

// ── Filter state ─────────────────────────────────────────────────────────────
const selectedWilayah = ref('');

const applyInitialParams = (params) => {
  if (!params) return;
  if (params.destinasi) {
    const map = {
      'labuan-bajo': 'ntt',
      'raja-ampat': 'papua',
      'bromo-semeru': 'jatim',
      'bali-nusa': 'bali-lombok',
      'derawan': 'kalimantan',
      'danau-toba': 'sumatera',
    };
    selectedWilayah.value = map[params.destinasi] || params.destinasi;
  }
  if (params.search) {
    searchQuery.value = params.search;
  }
  if (params.kategori) {
    const mapCat = {
      'open-trip': 'bahari',
      'private-tour': 'bahari',
      'honeymoon': 'bahari',
      'outing-kantor': 'petualangan',
    };
    const cat = mapCat[params.kategori] || params.kategori;
    if (kategoriOptions.some(o => o.value === cat)) {
      selectedKategori.value = [cat];
    }
  }
};

watch(() => props.initialParams, (newVal) => {
  applyInitialParams(newVal);
}, { immediate: true, deep: true });

let searchTimer = null;
watch([searchQuery, selectedWilayah], () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    api.packages({ search: searchQuery.value, region: selectedWilayah.value, per_page: 50 })
      .then((data) => {
        const list = Array.isArray(data) ? data : (data.data || []);
        apiPackages.value = list;
      })
      .catch(() => {});
  }, 350);
});

const kategoriOptions = [
  { label: 'Bahari & Kepulauan', value: 'bahari' },
  { label: 'Gunung & Trekking', value: 'gunung' },
  { label: 'Seni & Budaya Tradisi', value: 'budaya' },
  { label: 'Petualangan Liar', value: 'petualangan' },
  { label: 'Kuliner Khas Nusantara', value: 'kuliner' },
  { label: 'Ziarah & Religi', value: 'religi' },
];
const selectedKategori = ref(['bahari']);

const tipeOptions = ['Semua', 'Open Trip', 'Private'];
const selectedTipe = ref('Semua');

const durasiOptions = ['1-2 Hari', '3-4 Hari', '5-7 Hari', '> 7 Hari'];
const selectedDurasi = ref('3-4 Hari');

const ratingFilter = ref({ bintang5: false, bintang4: true });

const sortOptions = ['Terpopuler', 'Harga: Termurah', 'Harga: Termahal', 'Rating Tertinggi', 'Trip Terbaru'];
const selectedSort = ref('Terpopuler');

// ── Active filter chips ───────────────────────────────────────────────────────
const activeChips = computed(() => {
  const chips = [];
  if (selectedKategori.value.length > 0) {
    selectedKategori.value.forEach((v) => {
      const opt = kategoriOptions.find((o) => o.value === v);
      if (opt) chips.push({ label: opt.label.split(' ')[0], key: 'kategori', value: v });
    });
  }
  if (selectedTipe.value !== 'Semua') chips.push({ label: selectedTipe.value, key: 'tipe', value: selectedTipe.value });
  if (selectedDurasi.value) chips.push({ label: selectedDurasi.value, key: 'durasi', value: selectedDurasi.value });
  return chips;
});

const removeChip = (chip) => {
  if (chip.key === 'kategori') {
    selectedKategori.value = selectedKategori.value.filter((v) => v !== chip.value);
  } else if (chip.key === 'tipe') {
    selectedTipe.value = 'Semua';
  } else if (chip.key === 'durasi') {
    selectedDurasi.value = '';
  }
};

const resetFilters = () => {
  selectedWilayah.value = '';
  selectedKategori.value = ['bahari'];
  selectedTipe.value = 'Semua';
  selectedDurasi.value = '';
  ratingFilter.value = { bintang5: false, bintang4: false };
  searchQuery.value = '';
};

// ── Packages data (live API only) ─────────────────────────────────────────────
const { items: apiPackages, loading: packagesLoading } = useApiList(
  () => api.packages({ search: searchQuery.value, region: selectedWilayah.value, per_page: 50 }),
  [],
);
const packages = computed(() => apiPackages.value.map(adaptPackage));
const filteredPackages = computed(() => {
  return packages.value.filter((pkg) => {
    // 1. Search Query
    if (searchQuery.value.trim()) {
      const q = searchQuery.value.toLowerCase().trim();
      const matchTitle = pkg.title.toLowerCase().includes(q);
      const matchLoc = pkg.location.toLowerCase().includes(q);
      if (!matchTitle && !matchLoc) return false;
    }

    // 2. Wilayah
    if (selectedWilayah.value) {
      const w = selectedWilayah.value.toLowerCase();
      const loc = pkg.location.toLowerCase();
      if (w === 'ntt' && !loc.includes('ntt') && !loc.includes('bajo') && !loc.includes('flores')) return false;
      if (w === 'papua' && !loc.includes('papua') && !loc.includes('raja ampat')) return false;
      if (w === 'jatim' && !loc.includes('jatim') && !loc.includes('bromo') && !loc.includes('banyuwangi') && !loc.includes('jawa')) return false;
      if (w === 'maluku' && !loc.includes('maluku') && !loc.includes('banda') && !loc.includes('morotai')) return false;
      if (w === 'sulawesi' && !loc.includes('sulawesi') && !loc.includes('wakatobi') && !loc.includes('toraja')) return false;
      if (w === 'sumatera' && !loc.includes('sumatera') && !loc.includes('toba')) return false;
      if (w === 'kalimantan' && !loc.includes('kalimantan') && !loc.includes('berau') && !loc.includes('derawan')) return false;
      if (w === 'bali-lombok' && !loc.includes('bali') && !loc.includes('lombok')) return false;
    }

    // 3. Tipe
    if (selectedTipe.value !== 'Semua') {
      if (selectedTipe.value === 'Open Trip' && !pkg.tipe.toLowerCase().includes('open')) return false;
      if (selectedTipe.value === 'Private' && !pkg.tipe.toLowerCase().includes('private')) return false;
    }

    // 4. Durasi
    if (selectedDurasi.value) {
      if (selectedDurasi.value === '1-2 Hari' && !pkg.durasi.includes('1 Hari') && !pkg.durasi.includes('2 Hari')) return false;
      if (selectedDurasi.value === '3-4 Hari' && !pkg.durasi.includes('3 Hari') && !pkg.durasi.includes('4 Hari')) return false;
      if (selectedDurasi.value === '5-7 Hari' && !pkg.durasi.includes('5 Hari') && !pkg.durasi.includes('6 Hari') && !pkg.durasi.includes('7 Hari')) return false;
    }

    return true;
  }).sort((a, b) => {
    if (selectedSort.value === 'Harga: Termurah') {
      const pA = parseInt(a.harga.replace(/[^0-9]/g, '')) || 0;
      const pB = parseInt(b.harga.replace(/[^0-9]/g, '')) || 0;
      return pA - pB;
    }
    if (selectedSort.value === 'Harga: Termahal') {
      const pA = parseInt(a.harga.replace(/[^0-9]/g, '')) || 0;
      const pB = parseInt(b.harga.replace(/[^0-9]/g, '')) || 0;
      return pB - pA;
    }
    if (selectedSort.value === 'Rating Tertinggi') {
      return b.rating - a.rating;
    }
    return 0;
  });
});

// ── Pagination (real, client-side over live results) ───────────────────────────
const PAGE_SIZE = 9;
const currentPage = ref(1);
const totalPages = computed(() => Math.max(1, Math.ceil(filteredPackages.value.length / PAGE_SIZE)));
const pagedPackages = computed(() => filteredPackages.value.slice(
  (currentPage.value - 1) * PAGE_SIZE,
  currentPage.value * PAGE_SIZE,
));

watch(filteredPackages, () => {
  currentPage.value = 1;
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
};

// ── Wishlist ──────────────────────────────────────────────────────────────────
const wishlist = ref(new Set());

const toggleWishlist = (id) => {
  const next = new Set(wishlist.value);
  if (next.has(id)) {
    next.delete(id);
  } else {
    next.add(id);
  }
  wishlist.value = next;
};
</script>

<template>
  <div class="flex flex-col w-full">
    <!-- ── TOP HEADER ─────────────────────────────────────────────────────── -->
    <section class="w-full bg-primary py-12 px-margin">
      <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-on-primary font-body-md text-label-sm">
          <a
            class="text-on-primary hover:underline cursor-pointer"
            @click.prevent="navigate('beranda')"
          >Beranda</a>
          <span class="text-on-primary">/</span>
          <span class="text-primary-fixed font-label-md">Paket Wisata</span>
        </nav>
        <!-- Headings -->
        <div class="flex flex-col gap-2">
          <h1 class="font-headline-lg text-headline-lg text-on-primary">
            Paket Wisata Seluruh Indonesia
          </h1>
          <p class="font-body-lg text-body-lg text-on-primary-container max-w-2xl">
            Temukan ekspedisi autentik, pesona pulau terluar, hingga kehangatan budaya nusantara yang terkurasi aman, transparan, dan berkesan.
          </p>
        </div>
        <!-- Search Bar -->
        <div class="w-full bg-surface-container-lowest p-2 rounded-lg flex flex-col md:flex-row items-center gap-2">
          <div class="flex items-center gap-3 w-full px-3 py-1">
            <span class="material-symbols-outlined text-outline">search</span>
            <input
              v-model="searchQuery"
              class="w-full bg-transparent text-on-surface font-body-md text-body-md placeholder:text-outline focus:outline-none"
              placeholder="Cari destinasi atau paket wisata impian..."
              type="text"
            >
          </div>
          <div class="flex w-full md:w-auto items-center gap-2">
            <button
              class="w-full md:w-auto h-11 px-6 rounded bg-secondary text-on-secondary font-label-md text-label-md hover:bg-on-secondary-fixed-variant transition-colors whitespace-nowrap flex items-center justify-center gap-2"
              type="button"
            >
              <span>Cari Paket</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- ── MAIN CONTENT (Sidebar + Results) ──────────────────────────────── -->
    <section class="w-full bg-surface py-12 px-margin">
      <div class="max-w-[1200px] mx-auto flex flex-col lg:flex-row gap-gutter">

        <!-- ── LEFT SIDEBAR: FILTERS ───────────────────────────────────────── -->
        <aside class="w-full lg:w-[320px] flex-shrink-0">
          <div class="bg-surface-container-lowest rounded-xl p-6 flex flex-col gap-6">
            <!-- Filter Header -->
            <div class="flex items-center justify-between pb-4">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">tune</span>
                <h2 class="font-title-lg text-title-lg text-on-surface">Filter Pencarian</h2>
              </div>
              <button
                class="text-secondary font-label-md text-label-sm hover:underline"
                type="button"
                @click="resetFilters"
              >
                Reset Semua
              </button>
            </div>

            <!-- Filter 1: Wilayah -->
            <div class="flex flex-col gap-2">
              <label class="font-label-md text-label-md text-on-surface">Wilayah / Destinasi</label>
              <div class="relative">
                <select
                  v-model="selectedWilayah"
                  class="w-full h-11 px-3 pr-10 bg-surface-container-low text-on-surface font-body-md text-body-md rounded-lg appearance-none cursor-pointer focus:outline-none"
                >
                  <option value="">Semua Wilayah</option>
                  <option value="ntt">Nusa Tenggara Timur</option>
                  <option value="papua">Papua Barat</option>
                  <option value="jatim">Jawa Timur</option>
                  <option value="bali-lombok">Bali &amp; Lombok</option>
                  <option value="maluku">Maluku</option>
                  <option value="sulawesi">Sulawesi</option>
                  <option value="sumatera">Sumatera</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-3 text-outline pointer-events-none text-[20px]">expand_more</span>
              </div>
            </div>

            <!-- Filter 2: Kategori Wisata -->
            <div class="flex flex-col gap-3">
              <label class="font-label-md text-label-md text-on-surface">Kategori Wisata</label>
              <div class="flex flex-col gap-2">
                <label
                  v-for="opt in kategoriOptions"
                  :key="opt.value"
                  class="flex items-center gap-3 cursor-pointer select-none"
                >
                  <input
                    v-model="selectedKategori"
                    :value="opt.value"
                    class="w-4 h-4 rounded accent-primary cursor-pointer"
                    type="checkbox"
                  >
                  <span class="font-body-md text-body-md text-on-surface">{{ opt.label }}</span>
                </label>
              </div>
            </div>

            <!-- Filter 3: Tipe Perjalanan -->
            <div class="flex flex-col gap-2">
              <label class="font-label-md text-label-md text-on-surface">Tipe Perjalanan</label>
              <div class="grid grid-cols-3 gap-1 bg-surface-container p-1 rounded-lg">
                <button
                  v-for="tipe in tipeOptions"
                  :key="tipe"
                  :class="selectedTipe === tipe
                    ? 'bg-surface-container-lowest text-primary font-label-md'
                    : 'text-on-surface-variant font-body-md hover:text-on-surface'"
                  class="h-8 rounded text-label-sm transition-colors"
                  type="button"
                  @click="selectedTipe = tipe"
                >
                  {{ tipe }}
                </button>
              </div>
            </div>

            <!-- Filter 4: Durasi -->
            <div class="flex flex-col gap-2">
              <label class="font-label-md text-label-md text-on-surface">Durasi Perjalanan</label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="dur in durasiOptions"
                  :key="dur"
                  :class="selectedDurasi === dur
                    ? 'bg-primary text-on-primary'
                    : 'bg-surface-container-low text-on-surface hover:bg-surface-variant'"
                  class="px-3 py-1.5 rounded-full font-label-md text-label-sm transition-colors"
                  type="button"
                  @click="selectedDurasi = dur"
                >
                  {{ dur }}
                </button>
              </div>
            </div>

            <!-- Filter 5: Rentang Harga -->
            <div class="flex flex-col gap-3">
              <div class="flex items-center justify-between">
                <label class="font-label-md text-label-md text-on-surface">Rentang Biaya</label>
                <span class="text-primary font-label-sm text-label-sm">Maks. Rp 15.000.000</span>
              </div>
              <div class="w-full bg-surface-container-high h-2 rounded-full relative">
                <div class="absolute left-1/4 right-1/4 h-2 bg-primary rounded-full"></div>
              </div>
              <div class="grid grid-cols-2 gap-2 pt-1">
                <div class="flex flex-col gap-1">
                  <span class="font-label-sm text-label-sm text-outline">Minimum</span>
                  <input
                    class="w-full h-9 px-2 bg-surface-container-low rounded text-on-surface font-body-md text-label-sm"
                    readonly
                    type="text"
                    value="Rp 500.000"
                  >
                </div>
                <div class="flex flex-col gap-1">
                  <span class="font-label-sm text-label-sm text-outline">Maksimum</span>
                  <input
                    class="w-full h-9 px-2 bg-surface-container-low rounded text-on-surface font-body-md text-label-sm"
                    readonly
                    type="text"
                    value="Rp 12.000.000"
                  >
                </div>
              </div>
            </div>

            <!-- Filter 6: Rating -->
            <div class="flex flex-col gap-2">
              <label class="font-label-md text-label-md text-on-surface">Ulasan Pelancong</label>
              <div class="flex flex-col gap-2">
                <label class="flex items-center justify-between cursor-pointer">
                  <span class="flex items-center gap-1.5 font-body-md text-body-md text-on-surface">
                    <span
                      v-for="i in 5"
                      :key="i"
                      class="material-symbols-outlined text-tertiary-container text-[18px]"
                      style="font-variation-settings: 'FILL' 1;"
                    >star</span>
                    <span class="ml-1 text-label-sm font-label-md">5 Bintang</span>
                  </span>
                  <input v-model="ratingFilter.bintang5" class="w-4 h-4 rounded accent-primary" type="checkbox">
                </label>
                <label class="flex items-center justify-between cursor-pointer">
                  <span class="flex items-center gap-1.5 font-body-md text-body-md text-on-surface">
                    <span
                      v-for="i in 4"
                      :key="i"
                      class="material-symbols-outlined text-tertiary-container text-[18px]"
                      style="font-variation-settings: 'FILL' 1;"
                    >star</span>
                    <span class="ml-1 text-label-sm font-label-md">4 Bintang ke atas</span>
                  </span>
                  <input v-model="ratingFilter.bintang4" class="w-4 h-4 rounded accent-primary" type="checkbox">
                </label>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-2 pt-2">
              <button
                class="w-full h-11 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:bg-on-primary-fixed-variant transition-colors"
                type="button"
              >
                Terapkan Filter
              </button>
              <button
                class="w-full h-11 bg-surface-container text-primary font-label-md text-label-md rounded-lg hover:bg-surface-variant transition-colors"
                type="button"
                @click="resetFilters"
              >
                Reset Opsi
              </button>
            </div>
          </div>
        </aside>

        <!-- ── RIGHT COLUMN: RESULTS ───────────────────────────────────────── -->
        <div class="flex-1 flex flex-col gap-6 min-w-0">
          <!-- Results Top Bar -->
          <div class="bg-surface-container-lowest p-4 rounded-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
              <p class="font-title-lg text-title-lg text-on-surface">
                Menampilkan <span class="text-primary font-headline-sm">{{ filteredPackages.length }}</span> paket pilihan
              </p>
              <!-- Active Filter Chips -->
              <div class="flex flex-wrap items-center gap-2">
                <span
                  v-for="chip in activeChips"
                  :key="chip.key + chip.value"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm"
                >
                  {{ chip.label }}
                  <span
                    class="material-symbols-outlined text-[14px] cursor-pointer hover:text-secondary"
                    @click="removeChip(chip)"
                  >close</span>
                </span>
              </div>
            </div>
            <!-- Sort -->
            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
              <span class="text-on-surface-variant font-label-md text-label-sm whitespace-nowrap">Urutkan:</span>
              <div class="relative">
                <select
                  v-model="selectedSort"
                  class="h-9 px-3 pr-8 bg-surface-container-low text-on-surface font-label-md text-label-sm rounded appearance-none cursor-pointer focus:outline-none"
                >
                  <option v-for="opt in sortOptions" :key="opt">{{ opt }}</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Catalog Grid -->
          <div v-if="packagesLoading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" aria-label="Memuat paket wisata">
            <div v-for="n in 6" :key="n" class="bg-surface-container-lowest border border-card-border rounded-xl h-96 animate-pulse" />
          </div>
          <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <PackageCard
              v-for="pkg in pagedPackages"
              :key="pkg.id"
              :image="pkg.img"
              :title="pkg.title"
              :location="pkg.location"
              :badge="pkg.badge"
              :badge-variant="badgeFor(pkg).variant"
              :badge-shape="badgeFor(pkg).shape"
              :seat-badge="pkg.seatBadge || ''"
              seat-class="absolute bottom-3 left-3 bg-tertiary-container text-on-tertiary-container font-label-md text-label-sm px-2.5 py-1 rounded-full"
              :duration="pkg.durasi"
              duration-class="px-2 py-0.5 rounded bg-surface-container text-on-surface font-label-sm text-label-sm"
              :type="pkg.tipe"
              :type-class="typeClassFor(pkg)"
              :rating="pkg.rating"
              :reviews="pkg.ulasan"
              rating-style="numeric"
              :price="pkg.harga"
              price-suffix=""
              title-variant="list"
              wish-size="sm"
              :wished="wishlist.has(pkg.id)"
              cta-class="bg-primary text-on-primary hover:bg-on-primary-fixed-variant"
              @wishlist="toggleWishlist(pkg.id)"
              @detail="navigate('detail-paket', { slug: pkg.slug || '' })"
            />

            <!-- Empty State -->
            <EmptyState
              v-if="!packagesLoading && filteredPackages.length === 0"
              icon="travel_explore"
              title="Tidak ada paket wisata yang cocok"
              description="Coba sesuaikan kata kunci pencarian, pilih wilayah lain, atau reset filter untuk menampilkan kembali semua paket."
            >
              <button class="mt-0 px-6 py-2.5 rounded-lg bg-primary text-white font-label-md text-sm hover:bg-on-primary-fixed-variant transition-colors cursor-pointer" type="button" @click="resetFilters">
                Reset Semua Filter
              </button>
            </EmptyState>
          </div>

          <!-- Pagination -->
          <Pagination
            v-if="filteredPackages.length > 0"
            :page="currentPage"
            :total="totalPages"
            @go="goToPage"
          />
        </div>
      </div>
    </section>

    <!-- ── CONVERSION BANNER ──────────────────────────────────────────────── -->
    <section class="w-full bg-surface-container-low py-12 px-margin">
      <div class="max-w-[1200px] mx-auto bg-surface-container-lowest p-8 lg:p-12 rounded-xl flex flex-col lg:flex-row items-center justify-between gap-8">
        <div class="flex flex-col gap-3 max-w-2xl">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-secondary-fixed text-on-secondary-fixed font-label-md text-label-sm self-start">
            <span class="material-symbols-outlined text-[16px]">edit_calendar</span>
            Perjalanan Terkustomisasi
          </span>
          <h3 class="font-headline-lg text-headline-lg text-on-surface">
            Tidak menemukan paket yang cocok? Rancang perjalananmu sendiri!
          </h3>
          <p class="font-body-lg text-body-lg text-on-surface-variant">
            Konsultasikan rute impian, jumlah rombongan, durasi fleksibel, serta anggaran bersama tim kurator dan Travel Planner Jejak Nusa. Dapatkan rencana perjalanan transparan dalam 1x24 jam.
          </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto shrink-0">
          <a
            class="h-12 px-8 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md flex items-center justify-center hover:bg-on-secondary-fixed-variant transition-colors text-center whitespace-nowrap cursor-pointer"
            @click.prevent="navigate('kontak')"
          >
            Request Custom Trip
          </a>
        </div>
      </div>
    </section>
  </div>
</template>
