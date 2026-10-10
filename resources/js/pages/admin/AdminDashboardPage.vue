<script setup>
import { computed, onMounted, ref } from 'vue';
import MetricCard from '../../components/admin/MetricCard.vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminBadge from '../../components/admin/AdminBadge.vue';
import AdminTabs from '../../components/admin/AdminTabs.vue';
import { api } from '../../api.js';

const emit = defineEmits(['navigate']);

const summary = ref(null);
const error = ref('');

onMounted(async () => {
  try {
    summary.value = await api.adminList('dashboard');
  } catch (e) {
    error.value = e.message || 'Gagal memuat ringkasan.';
  }
});

const metrics = computed(() => [
  { label: 'Total Pemesanan', value: String(summary.value?.bookings_total ?? '—'), delta: '', icon: 'confirmation_number', iconClass: 'bg-teal-light text-teal' },
  { label: 'Menunggu Verifikasi', value: String(summary.value?.bookings_pending ?? '—'), delta: '', icon: 'hourglass_top', iconClass: 'bg-[#FEF6E9] text-[#F5A524]' },
  { label: 'Paket Aktif', value: String(summary.value?.packages_active ?? '—'), delta: '', icon: 'luggage', iconClass: 'bg-[#EAF8F2] text-[#2EB67D]' },
  { label: 'Pesan Belum Dibaca', value: String(summary.value?.messages_unread ?? '—'), delta: '', icon: 'inbox', iconClass: 'bg-[#FCECED] text-[#E5484D]' },
]);

const recent = computed(() => (summary.value?.recent || []).map((b) => ({
  code: b.code,
  package: b.package?.title || '',
  guest: `${b.guest_name} (${b.adults + b.children} pax)`,
  date: b.departure_date || '',
  status: b.status === 'paid' ? 'paid' : (b.status === 'cancelled' ? 'cancelled' : (b.status === 'verified' ? 'verified' : 'pending')),
})));

const cols = [
  { key: 'code', label: 'Kode' },
  { key: 'package', label: 'Paket' },
  { key: 'guest', label: 'Tamu' },
  { key: 'date', label: 'Berangkat' },
  { key: 'status', label: 'Status' },
];

const tabs = [
  { id: 'all', label: 'Semua' },
  { id: 'pending', label: 'Menunggu' },
  { id: 'paid', label: 'Lunas' },
];
const activeTab = ref('all');

const visibleRecent = computed(() => {
  if (activeTab.value === 'all') {
    return recent.value;
  }

  return recent.value.filter((r) => r.status === activeTab.value);
});
</script>

<template>
  <div class="flex flex-col gap-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
      <MetricCard
        v-for="m in metrics"
        :key="m.label"
        :label="m.label"
        :value="m.value"
        :icon="m.icon"
        :delta="m.delta"
        :icon-class="m.iconClass"
      />
    </div>

    <section class="flex flex-col gap-4">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h2 class="font-headline-sm text-headline-sm text-ink">Pemesanan Terbaru</h2>
        <button class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer w-fit" type="button" @click="emit('navigate', 'bookings')">
          Lihat Semua
        </button>
      </div>
      <AdminTabs :tabs="tabs" :active="activeTab" @select="activeTab = $event" />
      <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
      <AdminTable :columns="cols" caption="Daftar pemesanan terbaru">
        <tr v-for="r in visibleRecent" :key="r.code" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
          <td class="px-4 py-3 font-label-md text-label-md text-ink whitespace-nowrap">{{ r.code }}</td>
          <td class="px-4 py-3 font-body-md text-body-md text-ink">{{ r.package }}</td>
          <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap">{{ r.guest }}</td>
          <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap tabular-nums">{{ r.date }}</td>
          <td class="px-4 py-3"><AdminBadge :status="r.status" /></td>
        </tr>
      </AdminTable>
    </section>
  </div>
</template>
