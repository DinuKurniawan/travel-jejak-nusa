<script setup>
import { onMounted, ref } from 'vue';
import AdminBadge from '../../components/admin/AdminBadge.vue';
import { api, formatRupiah } from '../../api.js';

const props = defineProps({
  bookingId: { type: [Number, String], default: '' },
});

const emit = defineEmits(['navigate']);

const booking = ref(null);
const loading = ref(true);
const error = ref('');
const updating = ref(false);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    booking.value = await api.adminShow('bookings', props.bookingId);
  } catch (e) {
    error.value = e.message || 'Gagal memuat detail.';
  } finally {
    loading.value = false;
  }
}

async function setStatus(status) {
  updating.value = true;
  try {
    booking.value = await api.adminUpdate('bookings', props.bookingId, { status });
  } catch (e) {
    error.value = e.message || 'Gagal mengubah status.';
  } finally {
    updating.value = false;
  }
}

onMounted(load);
</script>

<template>
  <div class="flex flex-col gap-4">
    <button class="w-fit h-10 px-4 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-md hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="emit('navigate', 'bookings')">
      ← Kembali ke Pemesanan
    </button>
    <p v-if="loading" class="text-sm text-muted">Memuat detail...</p>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <div v-if="booking" class="bg-white rounded-xl border border-card-border p-6 flex flex-col gap-4 max-w-2xl">
      <div class="flex items-center justify-between gap-3 flex-wrap">
        <h2 class="font-headline-sm text-headline-sm text-ink">{{ booking.code }}</h2>
        <AdminBadge :status="booking.status === 'paid' ? 'paid' : (booking.status === 'cancelled' ? 'cancelled' : (booking.status === 'verified' ? 'verified' : 'pending'))" />
      </div>
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 font-body-md text-body-md">
        <div><dt class="text-xs text-muted">Paket</dt><dd class="text-ink font-medium">{{ booking.package?.title }}</dd></div>
        <div><dt class="text-xs text-muted">Tamu</dt><dd class="text-ink font-medium">{{ booking.guest_name }} ({{ booking.adults + booking.children }} pax)</dd></div>
        <div><dt class="text-xs text-muted">Kontak</dt><dd class="text-ink font-medium">{{ booking.guest_email }} / {{ booking.guest_phone }}</dd></div>
        <div><dt class="text-xs text-muted">Berangkat</dt><dd class="text-ink font-medium tabular-nums">{{ booking.departure_date }}</dd></div>
        <div><dt class="text-xs text-muted">Total</dt><dd class="text-ink font-medium tabular-nums">{{ formatRupiah(booking.total) }} (diskon {{ formatRupiah(booking.discount) }})</dd></div>
        <div><dt class="text-xs text-muted">Bayar</dt><dd class="text-ink font-medium">{{ booking.payment_status }}</dd></div>
      </dl>
      <div class="flex items-center gap-2 pt-2 border-t border-card-border">
        <button :disabled="updating" class="h-10 px-4 rounded-lg bg-teal text-white font-label-md text-label-md hover:bg-teal-dark transition-colors cursor-pointer disabled:opacity-50" type="button" @click="setStatus('verified')">Verifikasi</button>
        <button :disabled="updating" class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer disabled:opacity-50" type="button" @click="setStatus('paid')">Tandai Lunas</button>
        <button :disabled="updating" class="h-10 px-4 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-md hover:bg-[#FCECED] transition-colors cursor-pointer disabled:opacity-50" type="button" @click="setStatus('cancelled')">Batalkan</button>
      </div>
    </div>
  </div>
</template>
