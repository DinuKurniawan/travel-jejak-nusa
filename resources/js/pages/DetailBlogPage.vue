<script setup>
import { computed, onMounted, ref } from 'vue';
import RelatedArticleCard from '../components/RelatedArticleCard.vue';
import EmptyState from '../components/EmptyState.vue';
import AppButton from '../components/AppButton.vue';
import { api, formatRupiah } from '../api.js';
import { initialsOf } from '../userStatus.js';

const props = defineProps({
  initialParams: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['navigate']);

const navigate = (page, params = {}) => {
  emit('navigate', page, params);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Live article by slug.
const loading = ref(true);
const error = ref('');
const article = ref(null);
const related = ref([]);
const featuredPackage = ref(null);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    const slug = props.initialParams?.slug;
    if (!slug) {
      throw new Error('Tautan artikel tidak valid.');
    }
    article.value = await api.articleDetail(slug);
    const all = await api.articles({ per_page: 12 });
    const list = Array.isArray(all) ? all : (all.data || []);
    related.value = list.filter((a) => a.slug !== slug).slice(0, 3);
    const pkgs = await api.featuredPackages();
    const pkgList = Array.isArray(pkgs) ? pkgs : (pkgs.data || []);
    featuredPackage.value = pkgList[0] || null;
  } catch (e) {
    error.value = e.message || 'Gagal memuat artikel.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

const bodyParas = computed(() => String(article.value?.body || '')
  .split(/\n+/)
  .map((s) => s.trim())
  .filter(Boolean));

// ── Social Share & Clipboard ───────────────────────────────────────────────
const copyToastVisible = ref(false);
let toastTimeout = null;

const shareTitle = () => `${article.value?.title || 'Artikel'} | Jejak Nusa`;

const shareWhatsApp = () => {
  const url = encodeURIComponent(window.location.href);
  const text = encodeURIComponent(shareTitle());
  window.open(`https://api.whatsapp.com/send?text=${text}%20${url}`, '_blank');
};

const shareSocial = (network) => {
  const url = encodeURIComponent(window.location.href);
  if (network === 'facebook') {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
  } else if (network === 'x') {
    const text = encodeURIComponent(shareTitle());
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
  }
};

const copyArticleUrl = () => {
  const done = () => {
    copyToastVisible.value = true;
    if (toastTimeout) {
      clearTimeout(toastTimeout);
    }
    toastTimeout = setTimeout(() => {
      copyToastVisible.value = false;
    }, 2500);
  };
  if (navigator.clipboard) {
    navigator.clipboard.writeText(window.location.href).then(done).catch(done);
  } else {
    done();
  }
};
</script>

<template>
  <div class="flex flex-col w-full">
    <div v-if="loading" class="w-full bg-surface-container-lowest border-b border-surface-container-highest" aria-label="Memuat artikel">
      <div class="max-w-7xl mx-auto px-space-lg pt-space-xl pb-space-lg flex flex-col gap-4">
        <div class="h-8 w-2/3 rounded bg-surface-container animate-pulse" />
        <div class="h-6 w-1/3 rounded bg-surface-container animate-pulse" />
      </div>
    </div>

    <div v-else-if="error || !article" class="w-full bg-surface py-16">
      <div class="max-w-7xl mx-auto px-space-lg">
        <EmptyState
          icon="search_off"
          title="Artikel tidak ditemukan"
          :description="error || 'Tautan artikel tidak valid atau artikel sudah tidak tersedia.'"
        >
          <AppButton variant="secondary" @click="navigate('blog')">Kembali ke Blog</AppButton>
        </EmptyState>
      </div>
    </div>

    <template v-else>
      <!-- Article Header & Hero Section -->
      <section class="w-full bg-surface-container-lowest border-b border-surface-container-highest">
        <div class="max-w-7xl mx-auto px-space-lg pt-space-xl pb-space-lg">
          <nav aria-label="Breadcrumb" class="flex items-center gap-space-xs font-label-sm text-label-sm text-on-surface-variant mb-space-md">
            <a class="hover:text-primary transition-colors cursor-pointer" @click.prevent="navigate('beranda')">Beranda</a>
            <span>/</span>
            <a class="hover:text-primary transition-colors cursor-pointer" @click.prevent="navigate('blog')">Blog</a>
            <span>/</span>
            <span class="text-on-surface-variant/70">{{ article.category }}</span>
            <span>/</span>
            <span class="text-on-surface font-semibold truncate max-w-[200px] sm:max-w-none">{{ article.title }}</span>
          </nav>

          <div class="inline-flex items-center px-space-md py-1 rounded-full bg-secondary-container text-on-primary font-label-sm text-label-sm tracking-wide mb-space-sm font-semibold">
            {{ article.category }}
          </div>

          <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight max-w-4xl mb-space-lg leading-tight">
            {{ article.title }}
          </h1>

          <div class="flex flex-wrap items-center justify-between gap-space-md pt-space-md border-t border-surface-container-highest">
            <div class="flex items-center gap-space-md">
              <span class="w-12 h-12 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-label-md text-label-md font-bold" aria-hidden="true">{{ initialsOf(article.author) }}</span>
              <div class="flex flex-col">
                <span class="font-title-lg text-title-lg text-on-surface leading-tight font-semibold">{{ article.author }}</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Kontributor Jejak Nusa</span>
              </div>
            </div>
            <div class="flex items-center gap-space-lg text-on-surface-variant font-label-md text-label-md">
              <div class="flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                <span>{{ article.created_at ? String(article.created_at).slice(0, 10) : '' }}</span>
              </div>
              <div v-if="article.read_time" class="flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[18px] text-primary">schedule</span>
                <span>{{ article.read_time }}</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Main Content Layout with Sticky Share Sidebar -->
      <section class="w-full bg-surface py-space-xl">
        <div class="max-w-7xl mx-auto px-space-lg">
          <div v-if="article.image" class="w-full mb-space-xl">
            <div class="rounded-xl overflow-hidden border border-surface-container-highest bg-surface-container-low">
              <img
                class="w-full h-[360px] md:h-[520px] object-cover"
                :alt="article.title"
                :src="article.image"
              >
            </div>
          </div>

          <div class="relative grid grid-cols-1 lg:grid-cols-12 gap-space-xl">
            <aside class="hidden lg:block lg:col-span-2">
              <div class="sticky top-28 flex flex-col items-center gap-space-sm p-space-sm bg-surface-container-lowest border border-surface-container-highest rounded-full w-14 mx-auto">
                <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider font-semibold py-1">Share</span>
                <button
                  aria-label="Bagikan ke WhatsApp"
                  class="w-10 h-10 rounded-full border-2 border-primary text-primary flex items-center justify-center hover:bg-surface-container-low transition-colors cursor-pointer"
                  title="Bagikan ke WhatsApp"
                  @click="shareWhatsApp"
                >
                  <span class="material-symbols-outlined text-[20px]">chat</span>
                </button>
                <button
                  aria-label="Bagikan ke Facebook"
                  class="w-10 h-10 rounded-full border-2 border-primary text-primary flex items-center justify-center hover:bg-surface-container-low transition-colors cursor-pointer"
                  title="Bagikan ke Facebook"
                  @click="shareSocial('facebook')"
                >
                  <span class="material-symbols-outlined text-[20px]">thumb_up</span>
                </button>
                <button
                  aria-label="Bagikan ke X"
                  class="w-10 h-10 rounded-full border-2 border-primary text-primary flex items-center justify-center hover:bg-surface-container-low transition-colors cursor-pointer"
                  title="Bagikan ke X"
                  @click="shareSocial('x')"
                >
                  <span class="material-symbols-outlined text-[20px]">tag</span>
                </button>
                <button
                  aria-label="Salin Tautan Artikel"
                  class="w-10 h-10 rounded-full border-2 border-primary text-primary flex items-center justify-center hover:bg-surface-container-low transition-colors relative cursor-pointer"
                  title="Salin Tautan"
                  @click="copyArticleUrl"
                >
                  <span class="material-symbols-outlined text-[20px]">link</span>
                </button>
                <transition
                  enter-active-class="transition duration-200 ease-out"
                  enter-from-class="opacity-0 scale-90"
                  enter-to-class="opacity-100 scale-100"
                  leave-active-class="transition duration-150 ease-in"
                  leave-from-class="opacity-100 scale-100"
                  leave-to-class="opacity-0 scale-90"
                >
                  <span v-if="copyToastVisible" class="text-[10px] text-primary font-label-sm text-center font-bold">Tersalin!</span>
                </transition>
              </div>
            </aside>

            <article class="lg:col-span-8 lg:col-start-3 max-w-[720px] mx-auto flex flex-col gap-space-lg font-body-lg text-body-lg text-on-surface leading-[1.75]">
              <p v-if="article.excerpt" class="text-on-surface text-base md:text-lg leading-relaxed font-medium">
                {{ article.excerpt }}
              </p>
              <p v-for="(para, i) in bodyParas" :key="i" class="text-on-surface-variant">
                {{ para }}
              </p>
              <p v-if="bodyParas.length === 0 && !article.excerpt" class="text-on-surface-variant">
                Isi artikel sedang disiapkan redaksi.
              </p>

              <div class="lg:hidden flex items-center justify-between p-space-md rounded-lg bg-surface-container-lowest border border-surface-container-highest mt-space-md">
                <span class="font-label-md text-label-md text-on-surface font-semibold">Bagikan artikel ini:</span>
                <div class="flex items-center gap-space-xs">
                  <button
                    class="w-9 h-9 rounded-full border border-primary text-primary flex items-center justify-center cursor-pointer hover:bg-surface-container-low transition-colors"
                    title="WhatsApp"
                    @click="shareWhatsApp"
                  >
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                  </button>
                  <button
                    class="w-9 h-9 rounded-full border border-primary text-primary flex items-center justify-center cursor-pointer hover:bg-surface-container-low transition-colors"
                    title="Facebook"
                    @click="shareSocial('facebook')"
                  >
                    <span class="material-symbols-outlined text-[18px]">thumb_up</span>
                  </button>
                  <button
                    class="w-9 h-9 rounded-full border border-primary text-primary flex items-center justify-center cursor-pointer hover:bg-surface-container-low transition-colors relative"
                    title="Salin Tautan"
                    @click="copyArticleUrl"
                  >
                    <span class="material-symbols-outlined text-[18px]">link</span>
                  </button>
                </div>
              </div>

              <div v-if="copyToastVisible" class="lg:hidden p-2 text-center text-xs font-semibold text-primary bg-surface-container-lowest border border-primary rounded-lg">
                Tautan berhasil disalin ke clipboard!
              </div>

              <!-- Related Tour Package Card (live) -->
              <div v-if="featuredPackage" class="mt-space-md p-space-md sm:p-space-lg bg-surface-container-lowest rounded-xl border border-surface-container-highest flex flex-col md:flex-row items-center justify-between gap-space-lg">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-space-md w-full md:w-auto">
                  <img
                    class="w-full sm:w-28 h-28 rounded-lg object-cover flex-shrink-0"
                    :alt="featuredPackage.title"
                    :src="featuredPackage.image || '/images/img-04.jpg'"
                  >
                  <div class="flex flex-col gap-space-xs">
                    <div class="flex items-center gap-space-xs">
                      <span v-if="featuredPackage.badge" class="px-2 py-0.5 rounded-full bg-secondary text-on-secondary font-label-sm text-[11px] font-semibold">{{ featuredPackage.badge }}</span>
                      <span class="font-label-sm text-[11px] text-on-surface-variant font-medium">{{ featuredPackage.duration }}</span>
                    </div>
                    <h4 class="font-title-lg text-title-lg text-on-surface font-bold">{{ featuredPackage.title }}</h4>
                    <div class="flex items-baseline gap-space-xs">
                      <span class="font-label-sm text-label-sm text-on-surface-variant">Mulai dari</span>
                      <span class="font-headline-sm text-headline-sm text-primary font-bold tabular-nums">{{ formatRupiah(featuredPackage.price) }}</span>
                      <span class="font-label-sm text-label-sm text-on-surface-variant">/ pax</span>
                    </div>
                  </div>
                </div>
                <a
                  class="w-full md:w-auto px-space-lg py-3 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md font-semibold text-center hover:bg-on-secondary-container transition-colors flex-shrink-0 cursor-pointer"
                  @click.prevent="navigate('detail-paket', { slug: featuredPackage.slug })"
                >
                  Lihat Paket Ini
                </a>
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- Related Articles Row -->
      <section v-if="related.length > 0" class="w-full bg-surface-container-lowest border-t border-surface-container-highest py-space-xl">
        <div class="max-w-7xl mx-auto px-space-lg">
          <div class="flex flex-col md:flex-row md:items-end justify-between mb-space-lg gap-space-sm">
            <div>
              <span class="font-label-sm text-label-sm text-primary font-semibold uppercase tracking-wider">Inspirasi Lainnya</span>
              <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight mt-1 font-bold">Artikel Terkait</h2>
            </div>
            <a
              class="inline-flex items-center gap-space-xs font-label-md text-label-md text-primary hover:underline cursor-pointer"
              @click.prevent="navigate('blog')"
            >
              <span>Lihat Semua Artikel</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-lg">
            <RelatedArticleCard
              v-for="a in related"
              :key="a.slug || a.id"
              :image="a.image || '/images/img-13.jpg'"
              :title="a.title"
              :category="a.category"
              :date="a.created_at ? String(a.created_at).slice(0, 10) : ''"
              :excerpt="a.excerpt"
              @open="navigate('detail-blog', { slug: a.slug })"
            />
          </div>
        </div>
      </section>
    </template>
  </div>
</template>
