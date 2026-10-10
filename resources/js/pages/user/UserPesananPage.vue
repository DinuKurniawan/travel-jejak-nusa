<script setup>
import { computed, onMounted, ref } from 'vue';
import { api } from '../../api.js';
import AdminTabs from '../../components/admin/AdminTabs.vue';
import BookingCard from '../../components/user/BookingCard.vue';
import EmptyState from '../../components/EmptyState.vue';
import AppButton from '../../components/AppButton.vue';
import { normalizeBooking } from '../../userStatus.js';

defineProps({
  params: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['navigate']);

const tabs = [
  { id: 'semua', label: 'Semua' },
  { id: 'menunggu_pembayaran', label: 'Menunggu Pembayaran' },
  { id: 'terkonfirmasi', label: 'Terkonfirmasi' },
  { id: 'selesai', label: 'Selesai' },
  { id: 'dibatalkan', label: 'Dibatalkan' },
];

const activeTab = ref('semua');
const keyword = ref('');
const loading = ref(true);
const error = ref('');
const source = ref([]);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.userBookings({ per_page: 50 });
    const list = Array.isArray(res) ? res : (res.data || []);
    source.value = list.map(normalizeBooking);
  } catch (e) {
    error.value = e.message || 'Gagal memuat booking.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

const filtered = computed(() => source.value.filter((b) => {
  const matchTab = activeTab.value === 'semua' || b.status === activeTab.value;
  const q = keyword.value.trim().toLowerCase();
  const matchQ = !q || b.title.toLowerCase().includes(q) || b.code.toLowerCase().includes(q);

  return matchTab && matchQ;
}));
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="bg-white border border-card-border rounded-xl p-4 sm:p-5">
      <label class="block font-label-sm text-label-sm text-ink font-medium mb-2" for="pesanan-cari">Cari booking</label>
      <div class="relative">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-muted text-[20px]" aria-hidden="true">search</span>
        <input
          id="pesanan-cari"
          v-model="keyword"
          type="search"
          placeholder="Cari kode booking atau nama trip…"
          class="w-full h-11 pl-10 pr-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal"
        >
      </div>
      <AdminTabs :tabs="tabs" :active="activeTab" class="mt-4" @select="activeTab = $event" />
    </div>

    <div v-if="loading" class="flex flex-col gap-3" aria-label="Memuat booking">
      <div v-for="n in 3" :key="n" class="bg-white border border-card-border rounded-xl h-32 animate-pulse" />
    </div>

    <div v-else-if="error" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat booking</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <div v-else-if="filtered.length > 0" class="flex flex-col gap-3" role="list">
      <BookingCard
        v-for="b in filtered"
        :key="b.code"
        role="listitem"
        :code="b.code"
        :title="b.title"
        :image="b.image"
        :date="b.date"
        :meta="b.meta"
        :status="b.status"
        :total="b.total"
        :action-label="b.status === 'menunggu_pembayaran' ? 'Bayar' : 'Lihat Detail'"
        @detail="emit('navigate', b.status === 'menunggu_pembayaran' ? 'pembayaran' : 'detail-pesanan', { code: b.code })"
      />
    </div>
    <EmptyState
      v-else
      icon="confirmation_number"
      title="Tidak ada booking di sini"
      description="Coba ubah filter atau kata kunci pencarianmu."
    >
      <AppButton variant="secondary" @click="emit('navigate', 'paket-wisata')">Eksplor Paket Wisata</AppButton>
    </EmptyState>
  </div>
</template>
