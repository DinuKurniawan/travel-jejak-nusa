<script setup>
import { computed, onMounted, ref } from 'vue';
import { api } from '../../api.js';
import AdminTabs from '../../components/admin/AdminTabs.vue';
import EmptyState from '../../components/EmptyState.vue';
import AppButton from '../../components/AppButton.vue';

const tabs = [
  { id: 'semua', label: 'Semua' },
  { id: 'belum', label: 'Belum Dibaca' },
  { id: 'promo', label: 'Promo' },
];

const activeTab = ref('semua');
const loading = ref(true);
const error = ref('');
const items = ref([]);

function kindStyle(kind) {
  if (kind === 'promo') {
    return { icon: 'local_offer', tint: 'bg-orange-light text-orange' };
  }
  if (kind === 'pembayaran') {
    return { icon: 'payments', tint: 'bg-warning-tint text-warning' };
  }
  if (kind === 'ulasan') {
    return { icon: 'reviews', tint: 'bg-info-tint text-teal' };
  }

  return { icon: 'notifications', tint: 'bg-info-tint text-teal' };
}

async function load() {
  loading.value = true;
  error.value = '';
  try {
    items.value = (await api.userNotifications()).map((n) => ({
      id: n.id,
      ...kindStyle(n.kind),
      title: n.title,
      desc: n.body || '',
      time: n.created_at || '',
      read: Boolean(n.read_at),
      kind: n.kind,
    }));
  } catch (e) {
    error.value = e.message || 'Gagal memuat notifikasi.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

const filtered = computed(() => items.value.filter((i) => {
  if (activeTab.value === 'belum') {
    return !i.read;
  }
  if (activeTab.value === 'promo') {
    return i.kind === 'promo';
  }

  return true;
}));

async function markAll() {
  try {
    await Promise.all(items.value.filter((i) => !i.read).map((i) => api.userReadNotification(i.id)));
    items.value = items.value.map((i) => ({ ...i, read: true }));
  } catch (e) {
    error.value = e.message || 'Gagal menandai notifikasi.';
  }
}

async function markOne(id) {
  try {
    await api.userReadNotification(id);
  } catch { /* tetap tandai lokal */ } finally {
    items.value = items.value.map((i) => (i.id === id ? { ...i, read: true } : i));
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="bg-white border border-card-border rounded-xl p-4 sm:p-5 flex flex-wrap items-center justify-between gap-3">
      <AdminTabs :tabs="tabs" :active="activeTab" @select="activeTab = $event" />
      <button class="font-label-sm text-label-sm text-teal font-semibold hover:underline cursor-pointer" type="button" @click="markAll">Tandai semua dibaca</button>
    </div>

    <div v-if="loading" class="flex flex-col gap-2.5" aria-label="Memuat notifikasi">
      <div v-for="n in 3" :key="n" class="bg-white border border-card-border rounded-xl h-20 animate-pulse" />
    </div>

    <div v-else-if="error && items.length === 0" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat notifikasi</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <ul v-else-if="filtered.length > 0" class="flex flex-col gap-2.5 list-none m-0 p-0">
      <li v-for="n in filtered" :key="n.id">
        <button
          class="w-full text-left bg-white border rounded-xl p-4 flex gap-3.5 transition-colors cursor-pointer"
          :class="n.read ? 'border-card-border' : 'border-teal/50 bg-info-tint/40'"
          type="button"
          @click="markOne(n.id)"
        >
          <span :class="n.tint" class="w-11 h-11 rounded-lg flex items-center justify-center shrink-0" aria-hidden="true">
            <span class="material-symbols-outlined text-[24px]">{{ n.icon }}</span>
          </span>
          <span class="min-w-0 flex-1">
            <span class="flex items-center gap-2">
              <span class="font-label-md text-label-md font-semibold text-ink truncate">{{ n.title }}</span>
              <span v-if="!n.read" class="w-2 h-2 rounded-full bg-teal shrink-0" aria-label="Belum dibaca" />
            </span>
            <span class="block font-body-sm text-body-sm text-muted mt-0.5">{{ n.desc }}</span>
            <span class="block font-body-sm text-body-sm text-muted mt-1">{{ n.time }}</span>
          </span>
        </button>
      </li>
    </ul>
    <EmptyState
      v-else
      icon="notifications_off"
      title="Notifikasi bersih"
      description="Semua kabar sudah kamu baca. Selamat jalan-jalan!"
    />
  </div>
</template>
