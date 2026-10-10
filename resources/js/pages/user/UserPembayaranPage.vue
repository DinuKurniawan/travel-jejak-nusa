<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { api, formatRupiah } from '../../api.js';
import AppButton from '../../components/AppButton.vue';

defineEmits(['navigate']);

const methods = [
  { id: 'bca', label: 'Transfer Bank BCA', desc: 'Verifikasi otomatis 5–10 menit', icon: 'account_balance' },
  { id: 'qris', label: 'QRIS', desc: 'Semua e-wallet & m-banking', icon: 'qr_code' },
  { id: 'ewallet', label: 'GoPay / OVO / DANA', desc: 'Saldo terpotong langsung', icon: 'wallet' },
];

const loading = ref(true);
const error = ref('');
const payments = ref([]);
const selected = ref('bca');
const proofName = ref('');
const notice = ref('');
const formError = ref('');
const saving = ref(false);
const now = ref(Date.now());
let timer = null;

async function load() {
  loading.value = true;
  error.value = '';
  try {
    payments.value = await api.userPayments();
  } catch (e) {
    error.value = e.message || 'Gagal memuat pembayaran.';
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  load();
  timer = setInterval(() => { now.value = Date.now(); }, 1000);
});

onUnmounted(() => {
  if (timer) {
    clearInterval(timer);
  }
});

const unpaid = computed(() => payments.value.filter((p) => p.payment_status === 'unpaid' && p.status !== 'cancelled'));
const totalUnpaid = computed(() => unpaid.value.reduce((s, p) => s + Number(p.total || 0), 0));
const target = computed(() => unpaid.value[0] || null);

const countdown = computed(() => {
  if (!target.value?.created_at) {
    return '—';
  }
  const deadline = new Date(target.value.created_at).getTime() + 24 * 3600 * 1000;
  const diff = Math.max(0, deadline - now.value);
  const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
  const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
  const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');

  return `${h}:${m}:${s}`;
});

function statusLabel(p) {
  if (p.payment_status === 'paid') {
    return 'Lunas';
  }
  if (p.payment_status === 'dp') {
    return 'Menunggu Verifikasi';
  }

  return 'Belum Dibayar';
}

function onFile(e) {
  const f = e.target.files?.[0];
  proofName.value = f ? f.name : '';
}

async function uploadProof() {
  if (!target.value) {
    return;
  }
  saving.value = true;
  notice.value = '';
  formError.value = '';
  try {
    await api.userUploadProof(target.value.code, { method: selected.value, proof: proofName.value });
    notice.value = `Bukti untuk ${target.value.code} terkirim. Verifikasi maksimal 1x24 jam.`;
    proofName.value = '';
    await load();
  } catch (e) {
    formError.value = e.message || 'Gagal mengirim bukti pembayaran.';
  } finally {
    saving.value = false;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div v-if="loading" class="flex flex-col gap-4" aria-label="Memuat pembayaran">
      <div class="bg-white border border-card-border rounded-xl h-14 animate-pulse" />
      <div class="bg-white border border-card-border rounded-xl h-64 animate-pulse" />
    </div>

    <div v-else-if="error" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat pembayaran</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <template v-else>
      <p v-if="notice" role="status" class="bg-info-tint border border-teal/30 text-ink rounded-xl px-4 py-3 font-body-md text-body-md">{{ notice }}</p>
      <p v-if="formError" role="alert" class="bg-danger-light border border-danger/30 text-danger rounded-xl px-4 py-3 font-body-md text-body-md">{{ formError }}</p>

      <section v-if="target" class="bg-warning-tint border border-warning/40 rounded-xl px-4 py-3 flex items-center gap-3" aria-label="Batas pembayaran">
        <span class="material-symbols-outlined text-warning text-[24px]" aria-hidden="true">schedule</span>
        <p class="font-body-md text-body-md text-ink">Selesaikan pembayaran <strong class="tabular-nums">{{ formatRupiah(totalUnpaid) }}</strong> ({{ unpaid.length }} booking) sebelum <strong class="tabular-nums">{{ countdown }}</strong></p>
      </section>
      <section v-else class="bg-success-tint border border-success/40 rounded-xl px-4 py-3 flex items-center gap-3" aria-label="Tidak ada tagihan">
        <span class="material-symbols-outlined text-success text-[24px]" aria-hidden="true">check_circle</span>
        <p class="font-body-md text-body-md text-ink">Tidak ada tagihan menunggu. Semua pembayaranmu beres.</p>
      </section>

      <div v-if="target" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <section class="bg-white border border-card-border rounded-xl p-4 sm:p-5" aria-labelledby="bayar-metode">
          <h2 id="bayar-metode" class="font-jakarta font-bold text-[18px] text-ink">Pilih metode <span class="font-body-sm text-body-sm text-muted font-normal tabular-nums">• {{ target.code }}</span></h2>
          <ul class="mt-3 flex flex-col gap-2.5 list-none m-0 p-0" role="radiogroup" aria-label="Metode pembayaran">
            <li v-for="m in methods" :key="m.id">
              <button
                :aria-checked="selected === m.id"
                :class="selected === m.id ? 'border-teal bg-info-tint' : 'border-card-border hover:border-teal/50'"
                class="w-full text-left border-[1.5px] rounded-xl p-3.5 flex items-center gap-3 transition-colors cursor-pointer"
                role="radio"
                type="button"
                @click="selected = m.id"
              >
                <span :class="selected === m.id ? 'bg-teal text-white' : 'bg-page text-muted'" class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" aria-hidden="true">
                  <span class="material-symbols-outlined text-[22px]">{{ m.icon }}</span>
                </span>
                <span>
                  <span class="block font-label-md text-label-md font-semibold text-ink">{{ m.label }}</span>
                  <span class="block font-body-sm text-body-sm text-muted">{{ m.desc }}</span>
                </span>
              </button>
            </li>
          </ul>
        </section>

        <section class="bg-white border border-card-border rounded-xl p-4 sm:p-5" aria-labelledby="bayar-bukti">
          <h2 id="bayar-bukti" class="font-jakarta font-bold text-[18px] text-ink">Upload bukti bayar</h2>
          <label for="bukti-file" class="mt-3 flex flex-col items-center justify-center gap-2 border-[1.5px] border-dashed border-card-border rounded-xl px-4 py-8 text-center cursor-pointer hover:border-teal transition-colors">
            <span class="material-symbols-outlined text-[32px] text-muted" aria-hidden="true">upload_file</span>
            <span class="font-label-md text-label-md text-ink font-semibold">{{ proofName || 'Pilih foto / PDF bukti transfer' }}</span>
            <span class="font-body-sm text-body-sm text-muted">Maks 5 MB • JPG, PNG, PDF</span>
          </label>
          <input id="bukti-file" type="file" accept=".jpg,.jpeg,.png,.pdf" class="sr-only" @change="onFile">
          <AppButton variant="primary" block class="mt-3" :disabled="saving" @click="uploadProof">{{ saving ? 'Mengirim…' : 'Kirim Bukti Pembayaran' }}</AppButton>
        </section>
      </div>

      <section class="bg-white border border-card-border rounded-xl p-4 sm:p-5" aria-labelledby="bayar-riwayat">
        <h2 id="bayar-riwayat" class="font-jakarta font-bold text-[18px] text-ink">Riwayat transaksi</h2>
        <ul v-if="payments.length > 0" class="mt-3 flex flex-col gap-2.5 list-none m-0 p-0">
          <li v-for="h in payments" :key="h.code" class="flex flex-wrap items-center justify-between gap-2 border border-card-border rounded-lg px-3.5 py-3">
            <div>
              <p class="font-label-md text-label-md font-semibold text-ink tabular-nums">{{ h.code }}</p>
              <p class="font-body-sm text-body-sm text-muted">{{ h.created_at || '' }}</p>
            </div>
            <div class="text-right">
              <p class="font-label-md text-label-md font-bold text-ink tabular-nums">{{ formatRupiah(h.total) }}</p>
              <p class="font-body-sm text-body-sm" :class="h.payment_status === 'paid' ? 'text-success' : h.payment_status === 'dp' ? 'text-warning' : 'text-danger'">{{ statusLabel(h) }}</p>
            </div>
          </li>
        </ul>
        <p v-else class="font-body-md text-body-md text-muted mt-2">Belum ada transaksi.</p>
      </section>
    </template>
  </div>
</template>
