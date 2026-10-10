<script setup>
import { computed, onMounted, ref } from 'vue';
import PackageCard from '../components/PackageCard.vue';
import EmptyState from '../components/EmptyState.vue';
import AppButton from '../components/AppButton.vue';
import { api, formatRupiah } from '../api.js';

const props = defineProps({
  initialParams: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['navigate']);

const navigate = (page, params = {}) => {
  emit('navigate', page, params);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const goBack = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    navigate('paket-wisata');
  }
};

// ── Live package ─────────────────────────────────────────────────────────────
const loading = ref(true);
const error = ref('');
const pkg = ref(null);
const similar = ref([]);

const today = new Date().toISOString().slice(0, 10);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    const slug = props.initialParams?.slug;
    if (!slug) {
      throw new Error('Tautan paket tidak valid.');
    }
    pkg.value = await api.packageDetail(slug);
    const all = await api.packages({ per_page: 12 });
    const list = Array.isArray(all) ? all : (all.data || []);
    similar.value = list
      .filter((p) => p.slug !== slug)
      .slice(0, 3)
      .map((p) => ({
        slug: p.slug,
        image: p.image || '/images/img-04.jpg',
        badge: p.trip_type === 'private' ? 'Private Trip' : 'Open Trip',
        badgeVariant: p.trip_type === 'private' ? 'promo' : 'teal',
        location: p.location || '',
        title: p.title || '',
        chips: [{ icon: 'schedule', label: p.duration || '' }],
        rating: String(p.rating ?? ''),
        reviews: String(p.reviews_count ?? ''),
        ratingStyle: 'icon',
        price: p.price ? `Rp ${Number(p.price).toLocaleString('id-ID')}` : '',
      }));
  } catch (e) {
    error.value = e.message || 'Gagal memuat paket wisata.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

// ── Pricing & booking ────────────────────────────────────────────────────────
const adultPrice = computed(() => Number(pkg.value?.price || 0));
const childPrice = computed(() => Math.round(adultPrice.value * 0.8));

const adultCount = ref(2);
const childCount = ref(0);
const discountAmount = ref(0);
const promoInput = ref('');
const promoMsg = ref('');
const promoMsgType = ref('');
const selectedDate = ref('');

const incAdult = () => {
  if (adultCount.value < 10) {
    adultCount.value++;
  }
};
const decAdult = () => {
  if (adultCount.value > 1) {
    adultCount.value--;
  }
};
const incChild = () => {
  if (childCount.value < 6) {
    childCount.value++;
  }
};
const decChild = () => {
  if (childCount.value > 0) {
    childCount.value--;
  }
};

const totalPax = computed(() => adultCount.value + childCount.value);
const subtotal = computed(() => adultCount.value * adultPrice.value + childCount.value * childPrice.value);
const grandTotal = computed(() => Math.max(0, subtotal.value - discountAmount.value));
const dpTotal = computed(() => Math.round(grandTotal.value * 0.3));
const breakdownText = computed(() => {
  const parts = [];
  if (adultCount.value > 0) {
    parts.push(`${adultCount.value}x Dewasa`);
  }
  if (childCount.value > 0) {
    parts.push(`${childCount.value}x Anak`);
  }

  return parts.length ? parts.join(' + ') : '0 Peserta';
});

const descriptionParas = computed(() => String(pkg.value?.description || pkg.value?.excerpt || '')
  .split(/\n+/)
  .map((s) => s.trim())
  .filter(Boolean));

const applyPromo = () => {
  const code = promoInput.value.trim().toUpperCase();
  if (code === 'KOMODOHEMAT' || code === 'JEJAKNUSA') {
    discountAmount.value = 200000;
    promoMsg.value = 'Kode promo berhasil digunakan! Diskon Rp 200.000 diterapkan.';
    promoMsgType.value = 'success';
  } else if (code === '') {
    discountAmount.value = 0;
    promoMsg.value = 'Harap isi kode promo terlebih dahulu.';
    promoMsgType.value = 'error';
  } else {
    discountAmount.value = 0;
    promoMsg.value = 'Kode promo tidak valid atau telah kedaluwarsa.';
    promoMsgType.value = 'error';
  }
};

// ── Booking submit ───────────────────────────────────────────────────────────
const bookingModalOpen = ref(false);
const bookingError = ref('');
const bookingSuccessCode = ref('');
const bookingLoading = ref(false);
const guestName = ref('');
const guestEmail = ref('');
const guestPhone = ref('');

const handleBooking = async () => {
  bookingError.value = '';
  if (!guestName.value.trim() || !guestEmail.value.trim() || !guestPhone.value.trim()) {
    bookingError.value = 'Nama, email, dan telepon wajib diisi sebelum memesan.';
    return;
  }
  if (!selectedDate.value) {
    bookingError.value = 'Pilih tanggal keberangkatan dulu ya.';
    return;
  }
  if (!pkg.value) {
    bookingError.value = 'Data paket belum termuat. Coba lagi.';
    return;
  }
  bookingLoading.value = true;
  try {
    const res = await api.createBooking({
      package_id: pkg.value.id,
      guest_name: guestName.value.trim(),
      guest_email: guestEmail.value.trim(),
      guest_phone: guestPhone.value.trim(),
      departure_date: selectedDate.value,
      adults: adultCount.value,
      children: childCount.value,
      promo_code: promoInput.value || undefined,
    });
    discountAmount.value = res.discount || 0;
    bookingSuccessCode.value = res.code || '';
    bookingModalOpen.value = true;
  } catch (err) {
    bookingError.value = err.message || 'Pemesanan gagal. Coba lagi.';
  } finally {
    bookingLoading.value = false;
  }
};

const similarWished = ref(new Set());
const toggleSimilarWished = (i) => {
  const next = new Set(similarWished.value);
  if (next.has(i)) {
    next.delete(i);
  } else {
    next.add(i);
  }
  similarWished.value = next;
};
</script>

<template>
  <div class="flex flex-col w-full">
    <!-- Breadcrumb & Back Navigation -->
    <nav aria-label="Breadcrumb" class="w-full bg-[#FFFFFF] border-b border-[#E5E9ED]">
      <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center flex-wrap gap-3">
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E5E9ED] bg-[#F3F6F8] hover:bg-[#E6F5F7] hover:border-[#0E9AA7] text-[#121C2A] hover:text-[#0E9AA7] font-label-md text-xs font-semibold transition-all cursor-pointer group select-none shrink-0"
            @click="goBack"
            title="Kembali ke halaman sebelumnya"
          >
            <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
            <span>Kembali</span>
          </button>
          <span class="text-[#CBD5E1] hidden sm:inline">|</span>
          <ol class="flex items-center flex-wrap gap-2 font-label-md text-label-md text-[#6B7280]">
            <li>
              <a
                class="hover:text-[#0E9AA7] transition-colors flex items-center gap-1.5 cursor-pointer"
                @click.prevent="navigate('beranda')"
              >
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>Beranda</span>
              </a>
            </li>
            <li aria-hidden="true">/</li>
            <li>
              <a class="hover:text-[#0E9AA7] transition-colors cursor-pointer" @click.prevent="navigate('paket-wisata')">Paket Wisata</a>
            </li>
            <li aria-hidden="true">/</li>
            <li aria-current="page" class="text-[#121C2A] font-semibold">{{ pkg?.title || 'Detail Paket' }}</li>
          </ol>
        </div>
      </div>
    </nav>

    <div v-if="loading" class="w-full bg-[#F3F6F8] py-8" aria-label="Memuat detail paket">
      <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-4">
        <div class="h-[340px] rounded-[12px] bg-white border border-[#E5E9ED] animate-pulse" />
        <div class="h-10 w-2/3 rounded bg-white border border-[#E5E9ED] animate-pulse" />
      </div>
    </div>

    <div v-else-if="error || !pkg" class="w-full bg-[#F3F6F8] py-16">
      <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <EmptyState
          icon="search_off"
          title="Paket tidak ditemukan"
          :description="error || 'Tautan paket tidak valid atau paket sudah tidak tersedia.'"
        >
          <AppButton variant="secondary" @click="navigate('paket-wisata')">Lihat Paket Lain</AppButton>
        </EmptyState>
      </div>
    </div>

    <template v-else>
      <!-- Hero Image -->
      <section class="w-full bg-[#F3F6F8] py-8">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
          <div class="h-[340px] sm:h-[420px] lg:h-[480px] rounded-[12px] border border-[#E5E9ED] overflow-hidden bg-[#FFFFFF] relative">
            <img
              :alt="pkg.title"
              class="w-full h-full object-cover"
              :src="pkg.image || '/images/img-04.jpg'"
            >
            <div v-if="pkg.badge" class="absolute top-4 left-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#0E9AA7] text-[#FFFFFF] font-label-sm text-label-sm">
              <span class="material-symbols-outlined text-[16px]">sailing</span>
              <span>{{ pkg.badge }}</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Main Two-Column Layout Section -->
      <section class="w-full bg-[#FFFFFF] py-10">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left Column: Content -->
            <div class="lg:col-span-8 flex flex-col gap-10">
              <div class="flex flex-col gap-4">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#0E9AA7] text-[#FFFFFF] font-label-sm text-label-sm">
                    {{ pkg.trip_type === 'private' ? 'Private Trip' : 'Open Trip' }}
                  </span>
                  <span v-if="pkg.duration" class="inline-flex items-center px-3 py-1 rounded-full bg-[#F3F6F8] border border-[#E5E9ED] text-[#1F2937] font-label-sm text-label-sm">
                    {{ pkg.duration }}
                  </span>
                  <span v-if="pkg.seat_badge" class="inline-flex items-center px-3 py-1 rounded-full bg-[#F4743B] text-[#FFFFFF] font-label-sm text-label-sm font-semibold">
                    {{ pkg.seat_badge }}
                  </span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-[#1F2937] tracking-tight">
                  {{ pkg.title }}
                </h1>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-[#6B7280] font-body-md text-body-md border-b border-[#E5E9ED] pb-6">
                  <div class="flex items-center gap-1.5 text-[#1F2937]">
                    <span class="material-symbols-outlined text-[18px] text-[#0E9AA7]">location_on</span>
                    <span>{{ pkg.location }}</span>
                  </div>
                  <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px] text-[#F6C945]" style="font-variation-settings: 'FILL' 1;">star</span>
                    <span class="font-semibold text-[#1F2937]">{{ pkg.rating }}</span>
                    <span>({{ pkg.reviews_count }} ulasan)</span>
                  </div>
                </div>
              </div>

              <!-- Section: Ringkasan -->
              <section v-if="descriptionParas.length > 0" class="flex flex-col gap-4 scroll-mt-28" id="ringkasan" aria-label="Tentang paket ini">
                <h2 class="font-headline-md text-headline-md text-[#1F2937]">Tentang Paket Ini</h2>
                <p v-for="(para, i) in descriptionParas" :key="i" class="font-body-md text-body-md text-[#3D494A] leading-relaxed">{{ para }}</p>
              </section>
            </div>

            <!-- Right Column: Sticky Booking Widget -->
            <div class="lg:col-span-4 w-full">
              <div class="sticky top-24 p-6 rounded-[16px] bg-[#FFFFFF] border border-[#E5E9ED] flex flex-col gap-6">
                <div class="flex flex-col gap-1 border-b border-[#E5E9ED] pb-4">
                  <span class="font-label-sm text-label-sm text-[#6B7280]">Harga Mulai Dari</span>
                  <div class="flex items-baseline gap-2">
                    <span class="text-[32px] leading-tight font-bold text-[#0E9AA7] tabular-nums">{{ formatRupiah(pkg.price) }}</span>
                    <span class="font-body-md text-body-md text-[#6B7280]">{{ pkg.price_suffix || '/ orang' }}</span>
                  </div>
                </div>

                <div class="flex flex-col gap-2">
                  <label class="font-label-md text-label-md text-[#1F2937] font-semibold" for="departure-date">Pilih Tanggal Berangkat</label>
                  <input
                    id="departure-date"
                    v-model="selectedDate"
                    :min="today"
                    class="h-11 px-3.5 rounded-[8px] bg-[#FFFFFF] border border-[#E5E9ED] text-[#1F2937] font-body-md text-body-md focus:outline-none focus:border-[#0E9AA7]"
                    type="date"
                  >
                </div>

                <!-- Participant Counters -->
                <div class="flex flex-col gap-4 border-t border-[#E5E9ED] pt-4">
                  <span class="font-label-md text-label-md text-[#1F2937] font-semibold">Jumlah Peserta</span>
                  <div class="flex items-center justify-between">
                    <div>
                      <span class="font-body-md text-body-md text-[#1F2937] font-medium block">Dewasa</span>
                      <span class="font-label-sm text-label-sm text-[#6B7280]">Usia ≥ 10 tahun</span>
                    </div>
                    <div class="flex items-center gap-3">
                      <button
                        class="w-9 h-9 rounded-[8px] bg-[#F3F6F8] border border-[#E5E9ED] text-[#1F2937] font-semibold flex items-center justify-center hover:bg-[#E5E9ED] transition-colors disabled:opacity-40"
                        type="button"
                        :disabled="adultCount <= 1"
                        @click="decAdult"
                      >-</button>
                      <span class="font-title-lg text-title-lg text-[#1F2937] w-6 text-center">{{ adultCount }}</span>
                      <button
                        class="w-9 h-9 rounded-[8px] bg-[#0E9AA7] text-[#FFFFFF] font-semibold flex items-center justify-center hover:bg-[#0B7A85] transition-colors disabled:opacity-40"
                        type="button"
                        :disabled="adultCount >= 10"
                        @click="incAdult"
                      >+</button>
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <div>
                      <span class="font-body-md text-body-md text-[#1F2937] font-medium block">Anak-anak</span>
                      <span class="font-label-sm text-label-sm text-[#6B7280]">Usia 3 - 9 tahun (Diskon 20%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                      <button
                        class="w-9 h-9 rounded-[8px] bg-[#F3F6F8] border border-[#E5E9ED] text-[#1F2937] font-semibold flex items-center justify-center hover:bg-[#E5E9ED] transition-colors disabled:opacity-40"
                        type="button"
                        :disabled="childCount <= 0"
                        @click="decChild"
                      >-</button>
                      <span class="font-title-lg text-title-lg text-[#1F2937] w-6 text-center">{{ childCount }}</span>
                      <button
                        class="w-9 h-9 rounded-[8px] bg-[#0E9AA7] text-[#FFFFFF] font-semibold flex items-center justify-center hover:bg-[#0B7A85] transition-colors disabled:opacity-40"
                        type="button"
                        :disabled="childCount >= 6"
                        @click="incChild"
                      >+</button>
                    </div>
                  </div>
                </div>

                <!-- Kode Voucher / Promo -->
                <div class="flex flex-col gap-2">
                  <label class="font-label-sm text-label-sm text-[#6B7280]" for="promo-code">Punya Kode Voucher?</label>
                  <div class="flex items-center gap-2">
                    <input
                      v-model="promoInput"
                      class="flex-1 h-11 px-3.5 rounded-[8px] bg-[#FFFFFF] border border-[#E5E9ED] text-[#1F2937] font-body-md text-body-md placeholder-[#9CA3AF] focus:outline-none focus:border-[#0E9AA7]"
                      id="promo-code"
                      placeholder="Masukkan kode promo..."
                      type="text"
                      @keyup.enter="applyPromo"
                    >
                    <button
                      class="h-11 px-4 rounded-[8px] bg-[#0E9AA7] text-[#FFFFFF] font-label-md text-label-md hover:bg-[#0B7A85] transition-colors whitespace-nowrap"
                      type="button"
                      @click="applyPromo"
                    >
                      Terapkan
                    </button>
                  </div>
                  <p
                    v-if="promoMsg"
                    :class="promoMsgType === 'success' ? 'text-[#0E9AA7]' : 'text-[#DC2626]'"
                    class="text-label-sm font-label-sm"
                  >
                    {{ promoMsg }}
                  </p>
                </div>

                <!-- Total Price Breakdown -->
                <div class="p-4 rounded-[12px] bg-[#F3F6F8] border border-[#E5E9ED] flex flex-col gap-2">
                  <div class="flex justify-between font-body-md text-body-md text-[#3D494A]">
                    <span>{{ breakdownText }}</span>
                    <span class="font-medium text-[#1F2937] tabular-nums">{{ formatRupiah(subtotal) }}</span>
                  </div>
                  <div v-if="discountAmount > 0" class="flex justify-between font-body-md text-body-md text-[#0E9AA7]">
                    <span>Diskon Promo Voucher</span>
                    <span class="font-medium tabular-nums">- {{ formatRupiah(discountAmount) }}</span>
                  </div>
                  <div class="border-t border-[#E5E9ED] pt-2 mt-1 flex justify-between items-baseline">
                    <span class="font-title-lg text-title-lg text-[#1F2937]">Total Bayar:</span>
                    <span class="font-headline-sm text-headline-sm text-[#1F2937] font-bold tabular-nums">{{ formatRupiah(grandTotal) }}</span>
                  </div>
                  <div class="p-2.5 rounded-[6px] bg-[#FFFFFF] border border-[#E5E9ED] text-center mt-1">
                    <p class="font-label-sm text-label-sm text-[#0E9AA7] font-semibold">
                      Cukup bayar DP 30% (<span class="tabular-nums">{{ formatRupiah(dpTotal) }}</span>) untuk amankan kursi hari ini!
                    </p>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                  <div class="grid grid-cols-1 gap-2">
                    <label class="font-label-sm text-label-sm text-[#6B7280]" for="booking-name">Nama Lengkap</label>
                    <input
                      id="booking-name"
                      v-model="guestName"
                      class="h-11 px-3.5 rounded-[8px] bg-[#FFFFFF] border border-[#E5E9ED] text-[#1F2937] font-body-md text-body-md placeholder-[#9CA3AF] focus:outline-none focus:border-[#0E9AA7]"
                      placeholder="cth: Dian Rahmawati"
                      type="text"
                    >
                  </div>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div class="flex flex-col gap-1">
                      <label class="font-label-sm text-label-sm text-[#6B7280]" for="booking-email">Email</label>
                      <input
                        id="booking-email"
                        v-model="guestEmail"
                        class="h-11 px-3.5 rounded-[8px] bg-[#FFFFFF] border border-[#E5E9ED] text-[#1F2937] font-body-md text-body-md placeholder-[#9CA3AF] focus:outline-none focus:border-[#0E9AA7]"
                        placeholder="nama@email.id"
                        type="email"
                      >
                    </div>
                    <div class="flex flex-col gap-1">
                      <label class="font-label-sm text-label-sm text-[#6B7280]" for="booking-phone">Telepon</label>
                      <input
                        id="booking-phone"
                        v-model="guestPhone"
                        class="h-11 px-3.5 rounded-[8px] bg-[#FFFFFF] border border-[#E5E9ED] text-[#1F2937] font-body-md text-body-md placeholder-[#9CA3AF] focus:outline-none focus:border-[#0E9AA7]"
                        placeholder="+62 ..."
                        type="tel"
                      >
                    </div>
                  </div>
                  <p v-if="bookingError" role="alert" class="text-label-sm font-label-sm text-[#DC2626]">{{ bookingError }}</p>
                  <button
                    :disabled="bookingLoading"
                    class="w-full h-12 rounded-[8px] bg-[#F4743B] text-[#FFFFFF] font-label-md text-label-md font-semibold hover:bg-[#DE5E26] transition-colors flex items-center justify-center gap-2 disabled:opacity-50"
                    type="button"
                    @click="handleBooking"
                  >
                    <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    <span>{{ bookingLoading ? 'Memproses...' : 'Pesan Sekarang (Amankan Kursi)' }}</span>
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <!-- Similar Packages Section -->
      <section v-if="similar.length > 0" class="w-full bg-[#F3F6F8] py-16 border-t border-[#E5E9ED]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-8">
          <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
              <span class="font-label-sm text-label-sm text-[#0E9AA7] uppercase tracking-wider font-semibold">Rekomendasi Lain</span>
              <h2 class="font-headline-md text-headline-md text-[#1F2937]">Paket Wisata Terkait Lainnya</h2>
            </div>
            <a
              class="inline-flex items-center gap-1.5 text-[#0E9AA7] font-label-md text-label-md hover:underline font-semibold cursor-pointer"
              @click.prevent="navigate('paket-wisata')"
            >
              <span>Lihat Semua Paket</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <PackageCard
              v-for="(s, i) in similar"
              :key="s.slug"
              :image="s.image"
              :title="s.title"
              :location="s.location"
              :badge="s.badge"
              :badge-variant="s.badgeVariant"
              :chips="s.chips"
              :rating="s.rating"
              :reviews="s.reviews"
              rating-style="icon"
              :price="s.price"
              :wished="similarWished.has(i)"
              @wishlist="toggleSimilarWished(i)"
              @detail="navigate('detail-paket', { slug: s.slug })"
            />
          </div>
        </div>
      </section>

      <!-- Mobile Sticky Bottom Booking CTA Bar -->
      <div class="lg:hidden fixed bottom-0 left-0 w-full bg-[#FFFFFF] border-t border-[#E5E9ED] p-4 z-40 flex items-center justify-between">
        <div class="flex flex-col">
          <span class="font-label-sm text-label-sm text-[#6B7280]">Total ({{ totalPax }} Pax)</span>
          <span class="font-title-lg text-title-lg text-[#0E9AA7] font-bold tabular-nums">{{ formatRupiah(grandTotal) }}</span>
        </div>
        <button
          class="h-11 px-6 rounded-[8px] bg-[#F4743B] text-[#FFFFFF] font-label-md text-label-md font-semibold hover:bg-[#DE5E26] transition-colors"
          type="button"
          @click="handleBooking"
        >
          Pesan Sekarang
        </button>
      </div>

      <!-- Modal Booking Confirmation -->
      <Teleport to="body">
        <div
          v-if="bookingModalOpen"
          class="fixed inset-0 z-50 bg-[#121C2A]/80 flex items-center justify-center p-4"
          @click.self="bookingModalOpen = false"
        >
          <div class="bg-white rounded-[16px] max-w-md w-full p-6 flex flex-col gap-4 border border-[#E5E9ED]">
            <div class="w-14 h-14 rounded-full bg-[#E6F5F7] text-[#0E9AA7] flex items-center justify-center mx-auto">
              <span class="material-symbols-outlined text-[32px]">check_circle</span>
            </div>
            <div class="text-center flex flex-col gap-1">
              <h3 class="font-headline-sm text-headline-sm text-[#121C2A]">Pemesanan Diproses!</h3>
              <p class="font-body-md text-body-md text-[#6B7280]">
                Terima kasih! Pemesanan paket <strong>{{ pkg.title }}</strong> untuk <strong>{{ totalPax }} Pax</strong> telah kami catat.
              </p>
            </div>
            <div class="bg-[#F3F6F8] rounded-[10px] p-4 flex flex-col gap-2 font-body-md text-body-md">
              <div v-if="bookingSuccessCode" class="flex justify-between">
                <span class="text-[#6B7280]">Kode Booking:</span>
                <span class="font-semibold text-[#121C2A] tabular-nums">{{ bookingSuccessCode }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-[#6B7280]">Tanggal:</span>
                <span class="font-semibold text-[#121C2A]">{{ selectedDate }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-[#6B7280]">Total Biaya:</span>
                <span class="font-semibold text-[#121C2A] tabular-nums">{{ formatRupiah(grandTotal) }}</span>
              </div>
              <div class="flex justify-between border-t border-[#E5E9ED] pt-2">
                <span class="text-[#0E9AA7] font-medium">Uang Muka (DP 30%):</span>
                <span class="font-bold text-[#0E9AA7] tabular-nums">{{ formatRupiah(dpTotal) }}</span>
              </div>
            </div>
            <p class="text-xs text-[#6B7280] text-center">
              Simpan kode booking di atas. Selesaikan pembayaran agar kursimu aman.
            </p>
            <div class="flex flex-col gap-2 pt-2">
              <button
                class="w-full h-10 rounded-[8px] bg-[#E6F5F7] text-[#0E9AA7] font-semibold font-label-md text-label-md hover:bg-[#d2eff2] transition-colors cursor-pointer"
                type="button"
                @click="bookingModalOpen = false; navigate('paket-wisata')"
              >
                Jelajahi Paket Wisata Lainnya
              </button>
              <button
                class="w-full h-10 rounded-[8px] border border-[#E5E9ED] text-[#121C2A] hover:bg-[#F3F6F8] font-label-md text-label-md transition-colors cursor-pointer"
                type="button"
                @click="bookingModalOpen = false"
              >
                Tutup
              </button>
            </div>
          </div>
        </div>
      </Teleport>
    </template>
  </div>
</template>
