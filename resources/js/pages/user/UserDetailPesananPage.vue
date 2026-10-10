<script setup>
import { computed, onMounted, ref } from 'vue';
import { api, formatRupiah } from '../../api.js';
import AppButton from '../../components/AppButton.vue';
import AdminModal from '../../components/admin/AdminModal.vue';
import StatusBadge from '../../components/user/StatusBadge.vue';
import { bookingStatus } from '../../userStatus.js';

const props = defineProps({
  params: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['navigate']);

const loading = ref(true);
const error = ref('');
const booking = ref(null);

const status = computed(() => bookingStatus(booking.value));

const timeline = computed(() => {
  if (!booking.value) {
    return [];
  }
  const b = booking.value;
  const paid = b.status === 'paid' || b.payment_status === 'paid';
  const cancelled = b.status === 'cancelled';

  return [
    { label: 'Booking dibuat', desc: b.created_at || b.code, done: true },
    {
      label: cancelled ? 'Booking dibatalkan' : paid ? 'Pembayaran terverifikasi' : 'Menunggu pembayaran',
      desc: cancelled ? (b.notes || 'Dibatalkan atas permintaanmu') : paid ? 'Terima kasih, kursimu aman' : `Total ${formatRupiah(b.total)}`,
      done: cancelled || paid || b.status === 'verified',
      current: !cancelled && !paid,
    },
    { label: 'Terkonfirmasi', desc: 'Otomatis setelah pembayaran terverifikasi', done: paid },
    { label: 'Trip berjalan', desc: b.departure_date ? `Berangkat ${b.departure_date}` : 'E-tiket terbit H-3 keberangkatan', done: false },
  ];
});

const travelers = computed(() => {
  if (!booking.value) {
    return [];
  }
  const list = [booking.value.guest_name || 'Tamu utama'];
  const extra = (booking.value.adults || 1) - 1 + (booking.value.children || 0);
  for (let i = 0; i < extra; i++) {
    list.push(`Traveler ${i + 2}`);
  }

  return list;
});

async function load() {
  loading.value = true;
  error.value = '';
  try {
    booking.value = await api.userBookingDetail(props.params.code);
  } catch {
    error.value = `Booking ${props.params.code || ''} tidak ditemukan atau bukan milikmu.`;
  } finally {
    loading.value = false;
  }
}

onMounted(load);

const showCancel = ref(false);
const showReschedule = ref(false);
const cancelReason = ref('');
const newDate = ref('');
const saving = ref(false);
const notice = ref('');
const formError = ref('');

async function submitCancel() {
  saving.value = true;
  formError.value = '';
  try {
    booking.value = await api.userCancelBooking(booking.value.code, { reason: cancelReason.value });
    showCancel.value = false;
    notice.value = 'Booking berhasil dibatalkan. Refund (jika ada) diproses maksimal 7 hari kerja.';
  } catch (e) {
    formError.value = e.message || 'Gagal membatalkan booking.';
  } finally {
    saving.value = false;
  }
}

async function submitReschedule() {
  if (!newDate.value) {
    return;
  }
  saving.value = true;
  formError.value = '';
  try {
    booking.value = await api.userRescheduleBooking(booking.value.code, { departure_date: newDate.value });
    showReschedule.value = false;
    notice.value = 'Tanggal keberangkatan berhasil diubah.';
  } catch (e) {
    formError.value = e.message || 'Gagal mengajukan reschedule.';
  } finally {
    saving.value = false;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div v-if="loading" class="bg-white border border-card-border rounded-xl h-96 animate-pulse" aria-label="Memuat detail booking" />

    <div v-else-if="error" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">search_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Booking tidak ditemukan</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="emit('navigate', 'pesanan-saya')">Kembali ke Booking Saya</AppButton></div>
    </div>

    <template v-else-if="booking">
      <p v-if="notice" role="status" class="bg-info-tint border border-teal/30 text-ink rounded-xl px-4 py-3 font-body-md text-body-md">{{ notice }}</p>

      <section class="bg-white border border-card-border rounded-xl overflow-hidden" aria-labelledby="detail-judul">
        <img :alt="booking.package?.title || 'Paket wisata'" :src="booking.package?.image || '/images/img-04.jpg'" class="w-full h-48 sm:h-64 object-cover bg-page">
        <div class="p-4 sm:p-6">
          <div class="flex flex-wrap items-center gap-2">
            <StatusBadge :status="status" />
            <span class="font-body-sm text-body-sm text-muted tabular-nums">{{ booking.code }}</span>
          </div>
          <h2 id="detail-judul" class="font-jakarta font-bold text-[22px] sm:text-[28px] leading-8 sm:leading-9 text-ink mt-2">{{ booking.package?.title || 'Paket Wisata' }}</h2>
          <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
            <div class="border border-card-border rounded-lg p-3">
              <dt class="font-label-sm text-label-sm text-muted">Tanggal berangkat</dt>
              <dd class="font-label-md text-label-md text-ink font-semibold mt-0.5">{{ booking.departure_date || '—' }}</dd>
            </div>
            <div class="border border-card-border rounded-lg p-3">
              <dt class="font-label-sm text-label-sm text-muted">Peserta</dt>
              <dd class="font-label-md text-label-md text-ink font-semibold mt-0.5">{{ booking.adults || 1 }} Dewasa{{ booking.children ? ` + ${booking.children} Anak` : '' }}</dd>
            </div>
            <div class="border border-card-border rounded-lg p-3">
              <dt class="font-label-sm text-label-sm text-muted">Kontak</dt>
              <dd class="font-label-md text-label-md text-ink font-semibold mt-0.5">{{ booking.guest_phone || booking.guest_email }}</dd>
            </div>
          </dl>
          <div class="flex flex-wrap gap-2.5 mt-5">
            <AppButton v-if="status === 'menunggu_pembayaran'" variant="primary" @click="emit('navigate', 'pembayaran', { code: booking.code })">Bayar Sekarang</AppButton>
            <AppButton v-if="status !== 'dibatalkan'" variant="outline" @click="showReschedule = true">Reschedule</AppButton>
            <AppButton variant="outline" @click="emit('navigate', 'tiket-invoice', { code: booking.code })">Tiket & Invoice</AppButton>
            <button v-if="status !== 'dibatalkan' && status !== 'selesai'" class="h-11 px-6 rounded-lg border border-card-border text-danger font-label-md text-label-md hover:bg-danger-light transition-colors cursor-pointer" type="button" @click="showCancel = true">Batalkan</button>
          </div>
        </div>
      </section>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <section class="bg-white border border-card-border rounded-xl p-4 sm:p-5" aria-labelledby="detail-timeline">
          <h2 id="detail-timeline" class="font-jakarta font-bold text-[18px] text-ink">Status perjalanan</h2>
          <ol class="mt-4 flex flex-col gap-0 list-none m-0 p-0">
            <li v-for="(t, i) in timeline" :key="t.label" class="flex gap-3">
              <div class="flex flex-col items-center" aria-hidden="true">
                <span :class="t.done ? 'bg-teal text-white' : 'bg-page text-muted border border-card-border'" class="w-7 h-7 rounded-full flex items-center justify-center">
                  <span class="material-symbols-outlined text-[16px]">{{ t.done ? 'check' : 'circle' }}</span>
                </span>
                <span v-if="i < timeline.length - 1" :class="t.done ? 'bg-teal' : 'bg-card-border'" class="w-0.5 flex-1 min-h-6" />
              </div>
              <div class="pb-5">
                <p class="font-label-md text-label-md font-semibold" :class="t.current ? 'text-teal' : 'text-ink'">{{ t.label }}</p>
                <p class="font-body-sm text-body-sm text-muted">{{ t.desc }}</p>
              </div>
            </li>
          </ol>
        </section>

        <section class="bg-white border border-card-border rounded-xl p-4 sm:p-5" aria-labelledby="detail-rincian">
          <h2 id="detail-rincian" class="font-jakarta font-bold text-[18px] text-ink">Rincian biaya</h2>
          <dl class="mt-4 flex flex-col gap-2.5 font-body-md text-body-md">
            <div class="flex justify-between gap-3">
              <dt class="text-muted">Subtotal</dt>
              <dd class="text-ink tabular-nums">{{ formatRupiah(booking.subtotal) }}</dd>
            </div>
            <div class="flex justify-between gap-3">
              <dt class="text-muted">Diskon voucher</dt>
              <dd class="text-success tabular-nums">−{{ formatRupiah(booking.discount) }}</dd>
            </div>
            <div class="flex justify-between gap-3 pt-2.5 border-t border-card-border">
              <dt class="font-semibold text-ink">Total</dt>
              <dd class="font-bold text-ink tabular-nums">{{ formatRupiah(booking.total) }}</dd>
            </div>
          </dl>
          <h3 class="font-label-md text-label-md font-semibold text-ink mt-5 mb-2">Traveler</h3>
          <ul class="flex flex-col gap-2 list-none m-0 p-0">
            <li v-for="t in travelers" :key="t" class="flex items-center gap-2.5 border border-card-border rounded-lg px-3 py-2.5 font-body-md text-body-md text-ink">
              <span class="w-8 h-8 rounded-full bg-info-tint text-teal flex items-center justify-center font-bold" aria-hidden="true">{{ t.charAt(0) }}</span>
              {{ t }}
            </li>
          </ul>
        </section>
      </div>
    </template>

    <AdminModal :open="showCancel" title="Batalkan booking?" @close="showCancel = false" @confirm="submitCancel">
      <p v-if="formError" role="alert" class="mb-3 bg-danger-light border border-danger/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-danger">{{ formError }}</p>
      <p class="font-body-md text-body-md text-muted">Yakin mau membatalkan <strong class="text-ink">{{ booking?.code }}</strong>? Biaya yang sudah dibayar akan direfund maksimal 7 hari kerja.</p>
      <label class="block font-label-sm text-label-sm text-ink font-medium mt-4 mb-2" for="batal-alasan">Alasan pembatalan</label>
      <textarea id="batal-alasan" v-model="cancelReason" rows="3" placeholder="Ceritakan alasanmu…" class="w-full px-3 py-2.5 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal" />
      <template #footer>
        <button class="h-10 px-4 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-md hover:bg-page transition-colors cursor-pointer" type="button" @click="showCancel = false">Urungkan</button>
        <button :disabled="saving" class="h-10 px-4 rounded-lg bg-danger text-white font-label-md text-label-md hover:brightness-95 transition-colors cursor-pointer disabled:opacity-50" type="button" @click="submitCancel">Ya, Batalkan</button>
      </template>
    </AdminModal>

    <AdminModal :open="showReschedule" title="Reschedule perjalanan" @close="showReschedule = false" @confirm="submitReschedule">
      <p v-if="formError" role="alert" class="mb-3 bg-danger-light border border-danger/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-danger">{{ formError }}</p>
      <p class="font-body-md text-body-md text-muted">Pilih tanggal baru. Reschedule gratis maksimal H-7 keberangkatan.</p>
      <label class="block font-label-sm text-label-sm text-ink font-medium mt-4 mb-2" for="reschedule-tanggal">Tanggal baru</label>
      <input id="reschedule-tanggal" v-model="newDate" type="date" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
      <template #footer>
        <button class="h-10 px-4 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-md hover:bg-page transition-colors cursor-pointer" type="button" @click="showReschedule = false">Urungkan</button>
        <button :disabled="saving || !newDate" class="h-10 px-4 rounded-lg bg-teal text-white font-label-md text-label-md hover:bg-teal-dark transition-colors cursor-pointer disabled:opacity-50" type="button" @click="submitReschedule">Kirim Permintaan</button>
      </template>
    </AdminModal>
  </div>
</template>
