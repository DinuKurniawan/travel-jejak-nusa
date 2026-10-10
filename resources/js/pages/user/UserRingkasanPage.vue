<script setup>
import { computed, onMounted, ref } from 'vue';
import { api } from '../../api.js';
import AppButton from '../../components/AppButton.vue';
import PackageCard from '../../components/PackageCard.vue';
import StatCard from '../../components/user/StatCard.vue';
import BookingCard from '../../components/user/BookingCard.vue';
import EmptyState from '../../components/EmptyState.vue';
import { normalizeBooking } from '../../userStatus.js';

const emit = defineEmits(['navigate']);

const loading = ref(true);
const error = ref('');
const userName = ref('');
const stats = ref({ finished: 0, pending_payment: 0, vouchers: 0 });
const bookings = ref([]);
const wishlistCount = ref(0);
const recommendations = ref([]);

const statCards = computed(() => ([
  { icon: 'confirmation_number', value: String(stats.value.finished), label: 'Trip selesai', tint: 'bg-info-tint text-teal' },
  { icon: 'schedule', value: String(stats.value.pending_payment), label: 'Menunggu pembayaran', tint: 'bg-warning-tint text-warning' },
  { icon: 'local_offer', value: String(stats.value.vouchers), label: 'Voucher aktif', tint: 'bg-yellow-light text-[#755b00]' },
  { icon: 'favorite', value: String(wishlistCount.value), label: 'Wishlist tersimpan', tint: 'bg-success-tint text-success' },
]));

function toPackageCard(p) {
  return {
    image: p.image || '/images/img-04.jpg',
    badge: p.badge || '',
    badgeVariant: 'promo',
    badgeShape: 'box',
    location: p.location || '',
    title: p.title || '',
    duration: p.duration || '',
    type: p.trip_type === 'private' ? 'Private Trip' : 'Open Trip',
    typeClass: p.trip_type === 'private' ? 'bg-yellow-light text-[#755b00]' : undefined,
    rating: String(p.rating ?? ''),
    reviews: String(p.reviews_count ?? ''),
    price: p.price ? `Rp ${Number(p.price).toLocaleString('id-ID')}` : '',
  };
}

async function load() {
  loading.value = true;
  error.value = '';
  try {
    const [me, dash, wishlist, featured] = await Promise.all([
      api.userProfile(),
      api.userDashboard(),
      api.userWishlist(),
      api.featuredPackages(),
    ]);
    userName.value = me.name || '';
    stats.value = dash.stats || stats.value;
    bookings.value = (dash.bookings || []).map(normalizeBooking);
    wishlistCount.value = wishlist.length;
    recommendations.value = (Array.isArray(featured) ? featured : (featured.data || [])).slice(0, 3).map(toPackageCard);
  } catch (e) {
    error.value = e.message || 'Gagal memuat ringkasan.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);
</script>

<template>
  <div class="flex flex-col gap-4 sm:gap-6">
    <div v-if="loading" class="flex flex-col gap-4" aria-label="Memuat ringkasan">
      <div class="bg-white border border-card-border rounded-xl h-44 animate-pulse" />
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">
        <div v-for="n in 4" :key="n" class="bg-white border border-card-border rounded-xl h-20 animate-pulse" />
      </div>
    </div>

    <div v-else-if="error" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat ringkasan</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <template v-else>
      <section class="bg-teal rounded-xl p-5 sm:p-6 text-white" aria-labelledby="ringkasan-salam">
        <p class="font-label-sm text-label-sm opacity-80">Selamat datang kembali,</p>
        <h2 id="ringkasan-salam" class="font-jakarta font-bold text-[24px] leading-8 mt-1">Halo{{ userName ? `, ${userName.split(' ')[0]}` : '' }}! Petualanganmu dimulai di sini</h2>
        <p v-if="stats.pending_payment > 0" class="text-sm opacity-90 mt-1 max-w-xl">Kamu punya {{ stats.pending_payment }} pembayaran menunggu. Selesaikan biar kursimu aman.</p>
        <p v-else class="text-sm opacity-90 mt-1 max-w-xl">Semua pembayaranmu beres. Saatnya merencanakan petualangan berikutnya.</p>
        <div class="flex flex-wrap gap-2.5 mt-4">
          <button v-if="stats.pending_payment > 0" class="h-10 px-5 rounded-lg bg-orange hover:bg-[#DE5E26] text-white font-label-md text-label-md transition-colors cursor-pointer" type="button" @click="emit('navigate', 'pembayaran')">Bayar Sekarang</button>
          <button class="h-10 px-5 rounded-lg border-[1.5px] border-white text-white hover:bg-white/15 font-label-md text-label-md transition-colors cursor-pointer" type="button" @click="emit('navigate', 'paket-wisata')">Eksplor Paket</button>
        </div>
      </section>

      <section aria-label="Statistik perjalananmu">
        <ul class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4 list-none m-0 p-0">
          <li v-for="s in statCards" :key="s.label">
            <StatCard :icon="s.icon" :value="s.value" :label="s.label" :tint="s.tint" />
          </li>
        </ul>
      </section>

      <section aria-labelledby="ringkasan-aktif">
        <div class="flex items-center justify-between gap-3 mb-3">
          <h2 id="ringkasan-aktif" class="font-jakarta font-bold text-[18px] text-ink">Booking terbaru</h2>
          <button class="font-label-sm text-label-sm text-teal font-semibold hover:underline cursor-pointer" type="button" @click="emit('navigate', 'pesanan-saya')">Lihat Semua</button>
        </div>
        <div v-if="bookings.length > 0" class="flex flex-col gap-3">
          <BookingCard
            v-for="b in bookings"
            :key="b.code"
            :code="b.code"
            :title="b.title"
            :image="b.image"
            :date="b.date"
            :meta="b.meta"
            :status="b.status"
            :total="b.total"
            @detail="emit('navigate', 'detail-pesanan', { code: b.code })"
          />
        </div>
        <EmptyState
          v-else
          icon="confirmation_number"
          title="Belum ada booking"
          description="Yuk mulai petualangan pertamamu bersama Jejak Nusa."
        >
          <AppButton variant="secondary" @click="emit('navigate', 'paket-wisata')">Eksplor Paket Wisata</AppButton>
        </EmptyState>
      </section>

      <section v-if="recommendations.length > 0" aria-labelledby="ringkasan-rekomendasi">
        <div class="flex items-center justify-between gap-3 mb-3">
          <h2 id="ringkasan-rekomendasi" class="font-jakarta font-bold text-[18px] text-ink">Rekomendasi untukmu</h2>
          <button class="font-label-sm text-label-sm text-teal font-semibold hover:underline cursor-pointer" type="button" @click="emit('navigate', 'paket-wisata')">Lihat Semua</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
          <PackageCard
            v-for="p in recommendations"
            :key="p.title"
            :image="p.image"
            :title="p.title"
            :location="p.location"
            :badge="p.badge"
            :badge-variant="p.badgeVariant"
            :badge-shape="p.badgeShape"
            :duration="p.duration"
            :type="p.type"
            :type-class="p.typeClass"
            :rating="p.rating"
            :reviews="p.reviews"
            :price="p.price"
            @detail="emit('navigate', 'paket-wisata')"
          />
        </div>
      </section>
    </template>
  </div>
</template>
