<script setup>
import { ref, computed, watch } from 'vue';
import FaqAccordion from '../components/FaqAccordion.vue';
import InfoCard from '../components/InfoCard.vue';
import { api } from '../api.js';
import { useApiList } from '../useApiList.js';

const emit = defineEmits(['navigate']);

const navigate = (page) => {
  emit('navigate', page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ── Search & Filter State ──────────────────────────────────────────────────
const searchQuery = ref('');
const activeCategory = ref('semua');

const categories = [
  { id: 'semua', label: 'Semua Topik' },
  { id: 'pemesanan', label: 'Pemesanan' },
  { id: 'pembayaran', label: 'Pembayaran' },
  { id: 'pembatalan', label: 'Pembatalan & Refund' },
  { id: 'perjalanan', label: 'Perjalanan' },
  { id: 'akun', label: 'Akun & Keanggotaan' },
];

const selectCategory = (catId) => {
  activeCategory.value = catId;
};

// ── Accordion State ────────────────────────────────────────────────────────
const accordionRef = ref(null);

// ── FAQs Data (live API only) ────────────────────────────────────────────────
const { items: apiFaqs, loading: faqsLoading } = useApiList(
  () => api.faqs({ search: searchQuery.value }),
  [],
);
const sourceFaqs = computed(() => apiFaqs.value.map((f) => ({ id: f.id, category: f.category, question: f.question, type: 'paragraphs', paragraphs: [f.answer] })));
const filteredFaqs = computed(() => {
  const query = searchQuery.value.toLowerCase().trim();

  return sourceFaqs.value.filter((faq) => {
    const matchesCategory =
      activeCategory.value === 'semua' || faq.category === activeCategory.value;

    if (!query) {
      return matchesCategory;
    }

    const textToMatch = `${faq.question} ${(faq.paragraphs || []).join(' ')}`.toLowerCase();

    return matchesCategory && textToMatch.includes(query);
  });
});
const clearSearch = () => {
  searchQuery.value = '';
};

let faqTimer = null;
watch(searchQuery, () => {
  clearTimeout(faqTimer);
  faqTimer = setTimeout(() => {
    api.faqs({ search: searchQuery.value })
      .then((data) => {
        apiFaqs.value = Array.isArray(data) ? data : (data.data || []);
      })
      .catch(() => {});
  }, 350);
});
</script>

<template>
  <div class="flex flex-col w-full">
    <!-- Page Header: Teal Solid Band -->
    <section class="w-full bg-primary py-12 px-space-md lg:px-space-lg">
      <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-surface-variant font-label-sm text-label-sm mb-4">
          <a
            class="hover:text-on-primary transition-colors cursor-pointer"
            @click.prevent="navigate('beranda')"
          >
            Beranda
          </a>
          <span class="text-surface-variant/60">/</span>
          <span class="text-on-primary font-label-md text-label-md">FAQ</span>
        </nav>

        <!-- Main Headline -->
        <h1 class="font-headline-lg text-headline-lg text-on-primary mb-3">
          Pertanyaan yang Sering Diajukan
        </h1>
        <p class="font-body-lg text-body-lg text-surface-variant max-w-3xl mb-8">
          Temukan jawaban transparan seputar pemesanan tiket, kebijakan pembatalan, pembayaran, hingga persiapan perjalanan bersama Jejak Nusa.
        </p>

        <!-- Search Bar -->
        <div class="w-full max-w-2xl bg-surface-container-lowest p-1.5 rounded-lg flex flex-col sm:flex-row items-center gap-2">
          <div class="flex items-center gap-space-xs flex-1 px-3 w-full">
            <span class="material-symbols-outlined text-outline">search</span>
            <input
              v-model="searchQuery"
              class="w-full bg-transparent text-on-surface font-body-md text-body-md outline-none placeholder:text-outline py-2"
              placeholder="Ketik pertanyaan atau kata kunci (contoh: refund, DP, jadwal)..."
              type="text"
            >
            <button
              v-if="searchQuery"
              class="text-outline hover:text-on-surface text-sm p-1 cursor-pointer"
              title="Hapus pencarian"
              type="button"
              @click="clearSearch"
            >
              <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
          </div>
          <button
            class="w-full sm:w-auto h-11 px-6 rounded-lg bg-secondary-container text-on-primary font-label-md text-label-md hover:bg-secondary transition-colors flex items-center justify-center gap-2 cursor-pointer shrink-0"
            type="button"
          >
            <span>Cari Jawaban</span>
          </button>
        </div>
      </div>
    </section>

    <!-- Interactive Content Wrapper -->
    <section class="w-full bg-surface-container-low py-12 px-space-md lg:px-space-lg">
      <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <!-- Category Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
          <button
            v-for="cat in categories"
            :key="cat.id"
            :class="[
              'px-5 py-2.5 rounded-lg font-label-md text-label-md transition-colors whitespace-nowrap cursor-pointer',
              activeCategory === cat.id
                ? 'bg-primary text-on-primary font-semibold'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-primary'
            ]"
            type="button"
            @click="selectCategory(cat.id)"
          >
            {{ cat.label }}
          </button>
        </div>

        <!-- Search Results Count Banner (if searched) -->
        <div
          v-if="searchQuery"
          class="flex items-center justify-between p-3.5 bg-surface-container-lowest rounded-lg border border-surface-container-highest text-sm text-on-surface-variant"
        >
          <span>
            Menampilkan hasil untuk kata kunci <strong class="text-primary">"{{ searchQuery }}"</strong>: {{ filteredFaqs.length }} pertanyaan ditemukan
          </span>
          <button
            class="text-primary font-label-sm font-semibold hover:underline cursor-pointer"
            type="button"
            @click="clearSearch"
          >
            Reset Pencarian
          </button>
        </div>

        <!-- Main Layout: Accordion (Left 8 cols) + Side Panels (Right 4 cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
          <!-- Accordion Container (Left 8 cols) -->
          <div class="lg:col-span-8 flex flex-col gap-4">
            <!-- Loading skeleton -->
            <div v-if="faqsLoading" class="flex flex-col gap-3" aria-label="Memuat FAQ">
              <div v-for="n in 4" :key="n" class="bg-surface-container-lowest border border-card-border rounded-xl h-16 animate-pulse" />
            </div>
            <!-- No Results Message -->
            <div
              v-else-if="filteredFaqs.length === 0"
              class="p-8 text-center bg-surface-container-lowest rounded-xl border border-surface-container-highest flex flex-col items-center gap-3"
            >
              <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[28px]">search_off</span>
              </div>
              <h3 class="font-title-lg text-title-lg text-on-surface font-semibold">
                Pertanyaan tidak ditemukan
              </h3>
              <p class="font-body-md text-body-md text-on-surface-variant max-w-md">
                Tidak ada FAQ yang cocok dengan kata kunci "{{ searchQuery }}". Anda dapat bertanya langsung kepada tim konsultan kami.
              </p>
              <button
                class="mt-2 px-5 py-2.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors cursor-pointer"
                type="button"
                @click="clearSearch(); selectCategory('semua')"
              >
                Tampilkan Semua Pertanyaan
              </button>
            </div>

            <!-- Accordion Items Loop -->
            <FaqAccordion ref="accordionRef" :items="filteredFaqs">
              <template #default="{ item }">
                <div class="flex flex-col gap-4">
                <!-- Type: Steps (FAQ 1) -->
                <template v-if="item.type === 'steps'">
                  <p>{{ item.intro }}</p>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div
                      v-for="(st, idx) in item.steps"
                      :key="idx"
                      class="p-4 bg-surface-container-low rounded-lg flex flex-col gap-1"
                    >
                      <span class="font-label-md text-label-md text-primary font-semibold">{{ st.num }}</span>
                      <span class="font-label-md text-label-md text-on-surface font-semibold">{{ st.title }}</span>
                      <p class="text-on-surface-variant font-body-md text-body-md">{{ st.desc }}</p>
                    </div>
                  </div>
                  <p v-if="item.note" class="text-on-surface-variant text-label-sm font-label-sm pt-1 italic">
                    {{ item.note }}
                  </p>
                </template>

                <!-- Type: List (FAQ 2) -->
                <template v-else-if="item.type === 'list'">
                  <p>{{ item.intro }}</p>
                  <ul class="flex flex-col gap-2.5 list-none">
                    <li
                      v-for="(li, lIdx) in item.listItems"
                      :key="lIdx"
                      class="flex items-start gap-2"
                    >
                      <span class="material-symbols-outlined text-primary text-[20px] mt-0.5 shrink-0">check_circle</span>
                      <span><strong>{{ li.title }}</strong> {{ li.desc }}</span>
                    </li>
                  </ul>
                </template>

                <!-- Type: Paragraphs (FAQ 3, 6, 7, 8, 9, 10) -->
                <template v-else-if="item.type === 'paragraphs'">
                  <p
                    v-for="(para, pIdx) in item.paragraphs"
                    :key="pIdx"
                    class="leading-relaxed"
                  >
                    {{ para }}
                  </p>
                </template>

                <!-- Type: DP Card (FAQ 4) -->
                <template v-else-if="item.type === 'dp_card'">
                  <p>{{ item.intro }}</p>
                  <div class="p-4 bg-surface-container-low rounded-lg flex flex-col gap-2">
                    <div class="flex items-center gap-2 text-on-surface font-label-md text-label-md font-semibold">
                      <span class="material-symbols-outlined text-secondary text-[20px]">event_note</span>
                      <span>{{ item.noteTitle }}</span>
                    </div>
                    <p>{{ item.noteDesc }}</p>
                  </div>
                </template>

                <!-- Type: Table (FAQ 5) -->
                <template v-else-if="item.type === 'table'">
                  <p>{{ item.intro }}</p>
                  <div class="w-full overflow-x-auto rounded-lg bg-surface-container-low p-2">
                    <table class="w-full text-left font-body-md text-body-md min-w-[500px]">
                      <thead>
                        <tr class="bg-surface-container-high text-on-surface font-label-md text-label-md">
                          <th class="p-3 rounded-l-lg font-semibold">Waktu Pembatalan</th>
                          <th class="p-3 font-semibold">Persentase Pengembalian</th>
                          <th class="p-3 rounded-r-lg font-semibold">Ketentuan Administrasi</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-surface-variant/40">
                        <tr
                          v-for="(row, rIdx) in item.tableRows"
                          :key="rIdx"
                          class="hover:bg-surface-container/50 transition-colors"
                        >
                          <td class="p-3 font-medium text-on-surface">{{ row.time }}</td>
                          <td :class="['p-3 font-label-md text-label-md font-bold', row.percentStyle]">{{ row.percent }}</td>
                          <td class="p-3">{{ row.terms }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <p v-if="item.note" class="text-label-sm font-label-sm text-on-surface-variant italic">
                    {{ item.note }}
                  </p>
                </template>
                </div>
              </template>
            </FaqAccordion>
          </div>

          <!-- Side Column: Info Panels (Right 4 cols) -->
          <div class="lg:col-span-4 flex flex-col gap-6">
            <!-- Side Card: Bank Transfer Manual Guide -->
            <div class="bg-surface-container rounded-xl p-6 flex flex-col gap-5">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary text-on-primary flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[24px]">account_balance</span>
                </div>
                <div>
                  <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Pembayaran Transfer Bank</h3>
                  <span class="font-label-sm text-label-sm text-on-surface-variant">Kanal Rekening Resmi Korporasi</span>
                </div>
              </div>

              <!-- Steps -->
              <div class="flex flex-col gap-3">
                <div class="flex items-start gap-3">
                  <span class="w-6 h-6 rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md flex items-center justify-center shrink-0 mt-0.5 font-bold">1</span>
                  <p class="font-body-md text-body-md text-on-surface-variant">Pilih menu Transfer Antar Bank / Virtual Account pada ATM atau Mobile Banking Anda.</p>
                </div>
                <div class="flex items-start gap-3">
                  <span class="w-6 h-6 rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md flex items-center justify-center shrink-0 mt-0.5 font-bold">2</span>
                  <p class="font-body-md text-body-md text-on-surface-variant">Masukkan nomor rekening tujuan atau kode bayar yang tertera pada lembar konfirmasi pemesanan.</p>
                </div>
                <div class="flex items-start gap-3">
                  <span class="w-6 h-6 rounded-full bg-primary-container text-on-primary-container font-label-md text-label-md flex items-center justify-center shrink-0 mt-0.5 font-bold">3</span>
                  <p class="font-body-md text-body-md text-on-surface-variant">Simpan struk atau tangkapan layar bukti sukses untuk diunggah pada dashboard pemesanan.</p>
                </div>
              </div>

              <!-- Bank Logo Badges -->
              <div class="pt-2">
                <span class="font-label-sm text-label-sm text-on-surface-variant block mb-2 font-semibold">Mendukung Pembayaran Seluruh Bank:</span>
                <div class="grid grid-cols-2 gap-2">
                  <div class="p-2.5 bg-surface-container-lowest rounded-lg text-center font-label-md text-label-md text-on-surface font-semibold">
                    Bank BCA
                  </div>
                  <div class="p-2.5 bg-surface-container-lowest rounded-lg text-center font-label-md text-label-md text-on-surface font-semibold">
                    Bank Mandiri
                  </div>
                  <div class="p-2.5 bg-surface-container-lowest rounded-lg text-center font-label-md text-label-md text-on-surface font-semibold">
                    Bank BNI
                  </div>
                  <div class="p-2.5 bg-surface-container-lowest rounded-lg text-center font-label-md text-label-md text-on-surface font-semibold">
                    Bank BRI
                  </div>
                </div>
              </div>

              <!-- Note Box -->
              <div class="p-3 bg-surface-container-high rounded-lg flex items-start gap-2">
                <span class="material-symbols-outlined text-secondary text-[20px] shrink-0 mt-0.5">info</span>
                <p class="font-label-sm text-label-sm text-on-surface-variant leading-relaxed">
                  Batas waktu penyelesaian pembayaran adalah <strong>24 jam</strong> terhitung sejak invoice diterbitkan untuk menghindari pembatalan sistem.
                </p>
              </div>
            </div>

            <!-- Side Card: Masih Punya Pertanyaan? -->
            <div class="bg-surface-container-lowest rounded-xl p-6 flex flex-col gap-4 border border-surface-container-highest">
              <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[28px]">support_agent</span>
              </div>
              <div class="flex flex-col gap-1">
                <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Butuh Bantuan Langsung?</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Tim travel consultant kami siap membantu merancang perjalanan dan menjawab kebutuhan khusus Anda.
                </p>
              </div>
              <div class="flex flex-col gap-2 pt-2">
                <a
                  class="w-full h-11 px-4 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center justify-center gap-2 cursor-pointer font-semibold"
                  href="https://wa.me/6281234567890"
                  rel="noopener noreferrer"
                  target="_blank"
                >
                  <span class="material-symbols-outlined text-[20px]">chat</span>
                  <span>Chat WhatsApp</span>
                </a>
                <a
                  class="w-full h-11 px-4 rounded-lg bg-surface-container-lowest border border-primary text-primary font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center gap-2 cursor-pointer font-semibold"
                  @click.prevent="navigate('kontak')"
                >
                  <span class="material-symbols-outlined text-[20px]">mail</span>
                  <span>Kirim Pesan ke Tim</span>
                </a>
              </div>
              <div class="pt-2 flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
                <span class="w-2 h-2 rounded-full bg-secondary"></span>
                <span>Waktu tanggap rata-rata: &lt; 5 menit</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Help Topics Grid -->
    <section class="w-full bg-surface py-16 px-space-md lg:px-space-lg">
      <div class="max-w-7xl mx-auto flex flex-col gap-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <span class="font-label-md text-label-md text-primary uppercase tracking-wider block mb-1 font-semibold">Panduan Tambahan</span>
            <h2 class="font-headline-md text-headline-md text-on-surface font-bold">Eksplorasi Topik Bantuan Populer</h2>
          </div>
          <p class="font-body-md text-body-md text-on-surface-variant max-w-md">
            Bekali petualangan Anda dengan pemahaman mendalam tentang keselamatan, perlengkapan, dan etika berwisata.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Card 1: Panduan Packing -->
          <div
            class="bg-surface-container-lowest rounded-xl p-6 flex flex-col justify-between gap-6 hover:bg-surface-container-low transition-all duration-300 border border-surface-container-highest hover:border-primary cursor-pointer"
            @click="navigate('detail-blog')"
          >
            <div class="flex flex-col gap-4">
              <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[28px]">backpack</span>
              </div>
              <div>
                <h3 class="font-title-lg text-title-lg text-on-surface mb-2 font-bold">Panduan Packing Nusantara</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Daftar bawaan esensial untuk ekspedisi liveaboard, trekking bukit savana, hingga perlengkapan snorkel ramah terumbu karang.
                </p>
              </div>
            </div>
            <a
              class="inline-flex items-center gap-2 font-label-md text-label-md text-primary hover:text-on-surface transition-colors font-semibold"
              @click.stop.prevent="navigate('detail-blog')"
            >
              <span>Baca Checklist Lengkap</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
          </div>

          <!-- Card 2: Prosedur Darurat & Asuransi -->
          <div
            class="bg-surface-container-lowest rounded-xl p-6 flex flex-col justify-between gap-6 hover:bg-surface-container-low transition-all duration-300 border border-surface-container-highest hover:border-primary cursor-pointer"
            @click="navigate('tentang')"
          >
            <div class="flex flex-col gap-4">
              <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[28px]">health_and_safety</span>
              </div>
              <div>
                <h3 class="font-title-lg text-title-lg text-on-surface mb-2 font-bold">Prosedur Darurat &amp; Asuransi</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Cakupan asuransi perjalanan kecelakaan diri, protokol medis di laut lepas, dan kesiapsiagaan kru bersertifikasi BNSP.
                </p>
              </div>
            </div>
            <a
              class="inline-flex items-center gap-2 font-label-md text-label-md text-primary hover:text-on-surface transition-colors font-semibold"
              @click.stop.prevent="navigate('tentang')"
            >
              <span>Pelajari Protokol Medis</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
          </div>

          <!-- Card 3: Kemitraan Komunitas -->
          <div
            class="bg-surface-container-lowest rounded-xl p-6 flex flex-col justify-between gap-6 hover:bg-surface-container-low transition-all duration-300 border border-surface-container-highest hover:border-primary cursor-pointer"
            @click="navigate('tentang')"
          >
            <div class="flex flex-col gap-4">
              <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[28px]">diversity_3</span>
              </div>
              <div>
                <h3 class="font-title-lg text-title-lg text-on-surface mb-2 font-bold">Kemitraan Komunitas Lokal</h3>
                <p class="font-body-md text-body-md text-on-surface-variant">
                  Program pemberdayaan pemandu lokal, pelestarian hutan bakau, dan komitmen jejak nol sampah di setiap destinasi singgah.
                </p>
              </div>
            </div>
            <a
              class="inline-flex items-center gap-2 font-label-md text-label-md text-primary hover:text-on-surface transition-colors font-semibold"
              @click.stop.prevent="navigate('tentang')"
            >
              <span>Kenali Dampak Nyata</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
