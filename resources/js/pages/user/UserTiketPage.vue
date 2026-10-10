<script setup>
import { onMounted, ref } from 'vue';
import AppButton from '../../components/AppButton.vue';
import StatusBadge from '../../components/user/StatusBadge.vue';
import { api, formatRupiah } from '../../api.js';
import { bookingStatus } from '../../userStatus.js';

const props = defineProps({
  params: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['navigate']);

const loading = ref(true);
const error = ref('');
const booking = ref(null);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    booking.value = await api.userBookingDetail(props.params.code);
  } catch {
    error.value = `Tiket ${props.params.code || ''} tidak ditemukan atau bukan milikmu.`;
  } finally {
    loading.value = false;
  }
}

onMounted(load);

function printTicket() {
  window.print();
}
</script>

<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <div v-if="loading" class="bg-white border border-card-border rounded-xl h-96 animate-pulse" aria-label="Memuat tiket" />

    <div v-else-if="error" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">search_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Tiket tidak ditemukan</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="emit('navigate', 'pesanan-saya')">Kembali ke Booking Saya</AppButton></div>
    </div>

    <template v-else-if="booking">
      <section class="bg-white border border-card-border rounded-xl p-5 sm:p-8" aria-labelledby="tiket-judul">
        <div class="flex flex-wrap items-start justify-between gap-3 pb-5 border-b-2 border-dashed border-card-border">
          <div>
            <p class="font-label-sm text-label-sm text-teal font-bold uppercase tracking-wider">E-Tiket Perjalanan</p>
            <h2 id="tiket-judul" class="font-jakarta font-bold text-[22px] text-ink mt-1">{{ booking.package?.title || 'Paket Wisata' }}</h2>
            <p class="font-body-md text-body-md text-muted mt-1 tabular-nums">{{ booking.code }}</p>
          </div>
          <StatusBadge :status="bookingStatus(booking)" />
        </div>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 py-5 font-body-md text-body-md">
          <div>
            <dt class="font-label-sm text-label-sm text-muted">Nama tamu</dt>
            <dd class="text-ink font-semibold mt-0.5">{{ booking.guest_name }}{{ (booking.adults || 1) > 1 ? ` + ${booking.adults - 1} traveler` : '' }}</dd>
          </div>
          <div>
            <dt class="font-label-sm text-label-sm text-muted">Keberangkatan</dt>
            <dd class="text-ink font-semibold mt-0.5">{{ booking.departure_date || '—' }}</dd>
          </div>
          <div>
            <dt class="font-label-sm text-label-sm text-muted">Kontak</dt>
            <dd class="text-ink font-semibold mt-0.5">{{ booking.guest_phone || booking.guest_email }}</dd>
          </div>
          <div>
            <dt class="font-label-sm text-label-sm text-muted">Total dibayar</dt>
            <dd class="text-ink font-semibold mt-0.5 tabular-nums">{{ formatRupiah(booking.total) }}</dd>
          </div>
        </dl>
        <div class="flex flex-col items-center py-5 border-t-2 border-dashed border-card-border">
          <div class="w-36 h-36 rounded-lg bg-page border border-card-border flex items-center justify-center" role="img" :aria-label="`Kode QR tiket ${booking.code}`">
            <span class="material-symbols-outlined text-[64px] text-ink" aria-hidden="true">qr_code_2</span>
          </div>
          <p class="font-body-sm text-body-sm text-muted mt-2">Tunjukkan QR ini saat check-in</p>
        </div>
      </section>

      <section class="bg-white border border-card-border rounded-xl p-5 sm:p-6" aria-labelledby="invoice-judul">
        <h2 id="invoice-judul" class="font-jakarta font-bold text-[18px] text-ink">Invoice</h2>
        <dl class="mt-3 flex flex-col gap-2 font-body-md text-body-md">
          <div class="flex justify-between gap-3"><dt class="text-muted">No. invoice</dt><dd class="text-ink tabular-nums">INV-{{ booking.code }}</dd></div>
          <div class="flex justify-between gap-3"><dt class="text-muted">Status bayar</dt><dd class="text-ink capitalize">{{ (booking.payment_status || 'unpaid').replace('_', ' ') }}</dd></div>
          <div class="flex justify-between gap-3 pt-2 border-t border-card-border"><dt class="font-semibold text-ink">Total</dt><dd class="font-bold text-ink tabular-nums">{{ formatRupiah(booking.total) }}</dd></div>
        </dl>
      </section>

      <div class="flex flex-wrap gap-2.5">
        <AppButton variant="primary" @click="printTicket">Unduh / Cetak</AppButton>
        <AppButton variant="outline" @click="emit('navigate', 'detail-pesanan', { code: booking.code })">Kembali ke Detail</AppButton>
      </div>
    </template>
  </div>
</template>
