<script setup>
import { ref, computed, watch } from 'vue';
import PageHero from '../components/PageHero.vue';
import ArticleCard from '../components/ArticleCard.vue';
import Pagination from '../components/Pagination.vue';
import EmptyState from '../components/EmptyState.vue';
import { api } from '../api.js';
import { useApiList } from '../useApiList.js';
import { initialsOf } from '../userStatus.js';

const categoryClassFor = (style) => style || 'bg-secondary-container text-on-primary';

function adaptArticle(a) {
  if (a.img) {
    return a;
  }

  return {
    id: a.id,
    slug: a.slug || '',
    title: a.title,
    category: a.category,
    categoryStyle: 'bg-secondary-container text-on-primary',
    author: a.author || '',
    readTime: a.read_time || '',
    tag: '',
    excerpt: a.excerpt || '',
    img: a.image || '/images/img-13.jpg',
    views: a.views || 0,
    featured: Boolean(a.featured),
    created_at: a.created_at || '',
  };
}

const emit = defineEmits(['navigate']);

const navigate = (page) => {
  emit('navigate', page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ── Search & Filter ────────────────────────────────────────────────────────
const searchQuery = ref('');
const selectedCategory = ref('Semua');

const categories = [
  'Semua',
  'Tips Perjalanan',
  'Destinasi',
  'Kuliner',
  'Budaya',
  'Panduan',
];

// ── Live articles only ───────────────────────────────────────────────────────
const { items: apiArticles, loading: articlesLoading } = useApiList(
  () => api.articles({ search: searchQuery.value, per_page: 24 }),
  [],
);
const sourceArticles = computed(() => apiArticles.value.map(adaptArticle));

const filteredArticles = computed(() => {
  return sourceArticles.value.filter((article) => {
    const matchesCategory =
      selectedCategory.value === 'Semua' ||
      article.category === selectedCategory.value;

    const query = searchQuery.value.toLowerCase().trim();
    const matchesQuery =
      !query ||
      article.title.toLowerCase().includes(query) ||
      (article.excerpt || '').toLowerCase().includes(query) ||
      (article.author || '').toLowerCase().includes(query);

    return matchesCategory && matchesQuery;
  });
});

// ── Tags ───────────────────────────────────────────────────────────────────
const tags = [
  '#LabuanBajo',
  '#OpenTrip',
  '#RajaAmpat',
  '#SoloTravel',
  '#Bromo',
  '#Konservasi',
  '#BudayaLokal',
];

const selectTag = (tag) => {
  searchQuery.value = tag.replace('#', '');
};

let articleTimer = null;
watch(searchQuery, () => {
  clearTimeout(articleTimer);
  articleTimer = setTimeout(() => {
    api.articles({ search: searchQuery.value, per_page: 24 })
      .then((data) => {
        apiArticles.value = Array.isArray(data) ? data : (data.data || []);
      })
      .catch(() => {});
  }, 350);
});

// ── Featured & popular (derived from live articles) ──────────────────────────
const featured = computed(() => {
  const list = filteredArticles.value;
  const pick = list.find((a) => a.featured) || list[0] || null;
  if (!pick) {
    return null;
  }

  return {
    ...pick,
    avatarText: initialsOf(pick.author),
    date: pick.created_at ? String(pick.created_at).slice(0, 10) : '',
  };
});
const popular = computed(() => [...filteredArticles.value]
  .sort((a, b) => (b.views || 0) - (a.views || 0))
  .slice(0, 4)
  .map((a) => ({
    ...a,
    date: a.created_at ? String(a.created_at).slice(0, 10) : '',
    reads: a.views != null ? `${a.views} dibaca` : '',
  })));

// ── Pagination (real, client-side over live results) ─────────────────────────
const PAGE_SIZE = 6;
const currentPage = ref(1);
const totalPages = computed(() => Math.max(1, Math.ceil(filteredArticles.value.length / PAGE_SIZE)));
const pagedArticles = computed(() => filteredArticles.value.slice(
  (currentPage.value - 1) * PAGE_SIZE,
  currentPage.value * PAGE_SIZE,
));

watch(filteredArticles, () => {
  currentPage.value = 1;
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    window.scrollTo({ top: 380, behavior: 'smooth' });
  }
};

// ── Article detail navigation (live slug) ────────────────────────────────────
const openArticle = (article) => {
  navigate('detail-blog', { slug: article.slug || '' });
};
</script>

<template>
  <div class="flex flex-col w-full">

    <!-- 1. Header Banner -->
    <PageHero
      current="Blog & Cerita"
      title="Cerita & Tips Wisata"
      subtitle="Inspirasi rute, panduan packing, kearifan budaya lokal, dan cerita perjalanan autentik nusantara."
      @home="navigate('beranda')"
    >
      <!-- Flat Search Bar -->
      <form class="w-full max-w-2xl mt-6" @submit.prevent>
        <div class="flex flex-col sm:flex-row items-stretch bg-surface-container-lowest rounded-lg p-1.5 gap-2">
          <div class="flex items-center gap-space-xs flex-1 px-space-sm h-11">
            <span class="material-symbols-outlined text-outline">search</span>
            <input
              v-model="searchQuery"
              class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none"
              placeholder="Cari artikel, destinasi, atau tips..."
              type="text"
            >
            <button
              v-if="searchQuery"
              class="text-outline hover:text-on-surface text-sm"
              type="button"
              @click="searchQuery = ''"
            >
              ✕
            </button>
          </div>
          <button
            class="h-11 px-space-lg bg-secondary-container hover:bg-on-secondary-container text-on-primary font-label-md text-label-md rounded flex items-center justify-center transition-colors cursor-pointer"
            type="button"
          >
            Cari
          </button>
        </div>
      </form>
    </PageHero>

    <!-- Content Container -->
    <div class="w-full bg-surface py-space-xl">
      <div class="w-full px-space-lg flex flex-col gap-space-xl">

        <!-- 2. Featured Article (Horizontal Split) -->
        <article
          v-if="featured"
          class="w-full bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col lg:flex-row transition-shadow cursor-pointer"
          @click="openArticle(featured)"
        >
          <div class="w-full lg:w-1/2 min-h-[300px] lg:min-h-[380px] relative overflow-hidden group">
            <img
              :alt="featured.title"
              :src="featured.img"
              class="w-full h-full object-cover rounded-t-xl lg:rounded-tr-none lg:rounded-l-xl group-hover:scale-105 transition-transform duration-500"
            >
          </div>
          <div class="w-full lg:w-1/2 p-space-lg lg:p-space-xl flex flex-col justify-between gap-space-md">
            <div class="flex flex-col gap-space-sm">
              <div class="flex items-center gap-space-sm flex-wrap">
                <span class="bg-secondary-container text-on-primary font-label-sm text-label-sm px-space-sm py-1 rounded-full uppercase tracking-wider">
                  {{ featured.category }}
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                  <span class="material-symbols-outlined text-[15px]">schedule</span>
                  {{ featured.readTime }}
                </span>
              </div>
              <h2 class="font-headline-md text-headline-md text-on-surface hover:text-primary transition-colors leading-snug">
                <span class="cursor-pointer">{{ featured.title }}</span>
              </h2>
              <p class="font-body-md text-body-md text-on-surface-variant line-clamp-3">
                {{ featured.excerpt }}
              </p>
            </div>
            <div class="pt-space-md flex items-center justify-between flex-wrap gap-space-md">
              <div class="flex items-center gap-space-sm">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-label-md text-label-md font-bold">
                  {{ featured.avatarText }}
                </div>
                <div class="flex flex-col">
                  <span class="font-label-md text-label-md text-on-surface">{{ featured.author }}</span>
                  <span class="font-label-sm text-label-sm text-on-surface-variant">{{ featured.date }}</span>
                </div>
              </div>
              <span class="inline-flex items-center gap-1 font-label-md text-label-md text-primary hover:text-on-primary-fixed-variant transition-colors">
                <span>Baca Selengkapnya</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
              </span>
            </div>
          </div>
        </article>

        <!-- 3. Category Filter Chips -->
        <div class="flex items-center gap-space-xs overflow-x-auto pb-2 scrollbar-none" id="categoryChips">
          <button
            v-for="cat in categories"
            :key="cat"
            :class="selectedCategory === cat
              ? 'bg-primary text-on-primary'
              : 'bg-surface-container-high text-on-surface hover:bg-surface-variant'"
            class="px-space-md h-10 rounded-full font-label-md text-label-md whitespace-nowrap transition-colors cursor-pointer"
            type="button"
            @click="selectedCategory = cat"
          >
            {{ cat }}
          </button>
        </div>

        <!-- 4 & 5. Main Content Split: Article Grid & Desktop Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">

          <!-- Main Column (8 cols): 3-col card grid -->
          <section class="lg:col-span-8 flex flex-col gap-space-xl">
            <!-- Grid cards -->
            <div v-if="articlesLoading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-space-md" aria-label="Memuat artikel">
              <div v-for="n in 6" :key="n" class="bg-surface-container-lowest border border-card-border rounded-xl h-80 animate-pulse" />
            </div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-space-md">
              <ArticleCard
                v-for="article in pagedArticles"
                :key="article.id"
                :image="article.img"
                :title="article.title"
                :excerpt="article.excerpt"
                :category="article.category"
                :category-class="categoryClassFor(article.categoryStyle)"
                :author="article.author"
                :read-time="article.readTime"
                @open="openArticle(article)"
              />
            </div>

            <!-- Empty state -->
            <EmptyState
              v-if="!articlesLoading && filteredArticles.length === 0"
              icon="search_off"
              title="Tidak ada artikel ditemukan"
              description="Coba gunakan kata kunci pencarian atau kategori lain."
            >
              <button
                class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md text-sm cursor-pointer"
                type="button"
                @click="selectedCategory = 'Semua'; searchQuery = '';"
              >
                Reset Filter
              </button>
            </EmptyState>

            <!-- 6. Flat Pagination -->
            <Pagination v-if="filteredArticles.length > 0" :page="currentPage" :total="totalPages" label="Pagination Blog" @go="goToPage" />
          </section>

          <!-- Right Sidebar (4 cols) -->
          <aside class="lg:col-span-4 flex flex-col gap-space-lg w-full">

            <!-- Widget 1: Artikel Populer -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg flex flex-col gap-space-md">
              <div class="flex items-center justify-between pb-space-xs border-b border-surface-container">
                <h3 class="font-title-lg text-title-lg text-on-surface">Artikel Populer</h3>
                <span class="material-symbols-outlined text-primary">trending_up</span>
              </div>
              <div class="flex flex-col gap-space-md">
                <article
                  v-for="pop in popular"
                  :key="pop.id"
                  class="flex items-center gap-space-sm group cursor-pointer"
                  @click="openArticle(pop)"
                >
                  <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-surface-container">
                    <img
                      :alt="pop.title"
                      :src="pop.img"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    >
                  </div>
                  <div class="flex flex-col gap-0.5 min-w-0">
                    <h4 class="font-label-md text-label-md text-on-surface group-hover:text-primary transition-colors line-clamp-2">
                      {{ pop.title }}
                    </h4>
                    <div class="flex items-center gap-space-xs font-label-sm text-label-sm text-on-surface-variant">
                      <span v-if="pop.reads">{{ pop.reads }}</span>
                      <span v-if="pop.reads && pop.date">•</span>
                      <span>{{ pop.date }}</span>
                    </div>
                  </div>
                </article>
              </div>
            </div>

            <!-- Widget 2: Topik & Tag Cloud -->
            <div class="bg-surface-container-lowest rounded-xl p-space-lg flex flex-col gap-space-md">
              <h3 class="font-title-lg text-title-lg text-on-surface pb-space-xs border-b border-surface-container">
                Topik &amp; Tag
              </h3>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="tag in tags"
                  :key="tag"
                  class="px-3 py-1.5 rounded-full bg-surface-container-low hover:bg-surface-container-high text-on-surface font-label-sm text-label-sm transition-colors cursor-pointer"
                  type="button"
                  @click="selectTag(tag)"
                >
                  {{ tag }}
                </button>
              </div>
            </div>

          </aside>

        </div>

      </div>
    </div>

  </div>
</template>
