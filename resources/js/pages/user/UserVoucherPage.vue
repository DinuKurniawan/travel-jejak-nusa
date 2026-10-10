<script setup>
import { computed, onMounted, ref } from 'vue';
import { api } from '../../api.js';
import AdminTabs from '../../components/admin/AdminTabs.vue';
import EmptyState from '../../components/EmptyState.vue';
import AppButton from '../../components/AppButton.vue';

const tabs = [
  { id: 'semua', label: 'Semua' },
  { id: 'aktif', label: 'Aktif' },
  { id: 'terpakai', label: 'Terpakai' },
  { id: 'kedaluwarsa', label: 'Kedaluwarsa' },
];

const activeTab = ref('semua');
const code = ref('');
const loading = ref(true);
const error = ref('');
const notice = ref('');
const formError = ref('');
const vouchers = ref([]);

function toCard(uv) {
  const v = uv.voucher || {};

  return {
    code: v.code || '',
    title: v.title || 'Voucher',
    desc: `${v.min_spend ? `Min. transaksi Rp ${Number(v.min_spend).toLocaleString('id-ID')}` : 'Tanpa min. transaksi'}${v.expires_at ? ` • s.d. ${v.expires_at}` : ''}`,
    state: uv.state || 'aktif',
    tint: uv.state === 'aktif' ? 'bg-orange-light' : 'bg-page',
    accent: uv.state === 'aktif' ? 'text-orange' : 'text-muted',
  };
}

async function load() {
  loading.value = true;
  error.value = '';
  try {
    vouchers.value = (await api.userVouchers()).map(toCard);
  } catch (e) {
    error.value = e.message || 'Gagal memuat voucher.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

const filtered = computed(() => vouchers.value.filter((v) => activeTab.value === 'semua' || v.state === activeTab.value));

async function claim() {
  if (!code.value.trim()) {
    return;
  }
  notice.value = '';
  formError.value = '';
  try {
    await api.userClaimVoucher({ code: code.value.trim() });
    notice.value = `Voucher ${code.value.trim().toUpperCase()} berhasil diklaim.`;
    code.value = '';
    await load();
  } catch (e) {
    formError.value = e.message || 'Kode voucher tidak valid.';
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <p v-if="notice" role="status" class="bg-info-tint border border-teal/30 text-ink rounded-xl px-4 py-3 font-body-md text-body-md">{{ notice }}</p>
    <p v-if="formError" role="alert" class="bg-danger-light border border-danger/30 text-danger rounded-xl px-4 py-3 font-body-md text-body-md">{{ formError }}</p>

    <form class="bg-white border border-card-border rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row gap-2.5 sm:items-end" @submit.prevent="claim">
      <div class="flex-1">
        <label class="block font-label-sm text-label-sm text-ink font-medium mb-2" for="voucher-kode">Punya kode voucher?</label>
        <input id="voucher-kode" v-model="code" type="text" placeholder="Contoh: KOMODOHEMAT" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink uppercase placeholder:normal-case placeholder:text-muted focus:outline-none focus:border-teal">
      </div>
      <button class="h-11 px-6 rounded-lg bg-teal hover:bg-teal-dark text-white font-label-md text-label-md transition-colors cursor-pointer" type="submit">Klaim</button>
    </form>

    <div class="bg-white border border-card-border rounded-xl p-4 sm:p-5">
      <AdminTabs :tabs="tabs" :active="activeTab" @select="activeTab = $event" />
    </div>

    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-4" aria-label="Memuat voucher">
      <div v-for="n in 2" :key="n" class="bg-white border border-card-border rounded-xl h-32 animate-pulse" />
    </div>

    <div v-else-if="error && vouchers.length === 0" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat voucher</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <div v-else-if="filtered.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <article v-for="v in filtered" :key="v.code" class="bg-white border border-card-border rounded-xl p-4 sm:p-5 flex gap-4">
        <span :class="[v.tint, v.accent]" class="w-12 h-12 rounded-lg flex items-center justify-center shrink-0" aria-hidden="true">
          <span class="material-symbols-outlined text-[26px]">local_offer</span>
        </span>
        <div class="min-w-0 flex-1">
          <p class="font-mono font-bold text-ink tracking-wide tabular-nums">{{ v.code }}</p>
          <h3 class="font-label-md text-label-md font-semibold text-ink mt-0.5">{{ v.title }}</h3>
          <p class="font-body-sm text-body-sm text-muted mt-0.5">{{ v.desc }}</p>
          <p class="font-label-sm text-label-sm font-semibold mt-2 capitalize" :class="v.state === 'aktif' ? 'text-success' : 'text-muted'">{{ v.state }}</p>
        </div>
      </article>
    </div>
    <EmptyState
      v-else
      icon="local_offer"
      title="Tidak ada voucher di sini"
      description="Klaim kode promo dari email atau media sosial Jejak Nusa."
    />
  </div>
</template>
