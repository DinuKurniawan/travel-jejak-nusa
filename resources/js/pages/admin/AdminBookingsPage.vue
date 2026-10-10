<script setup>
import { ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminBadge from '../../components/admin/AdminBadge.vue';
import { api, formatRupiah } from '../../api.js';
import { useAdminResource } from '../../useAdminResource.js';

const emit = defineEmits(['navigate']);

const cols = [
  { key: 'code', label: 'Kode' },
  { key: 'package', label: 'Paket' },
  { key: 'guest', label: 'Tamu' },
  { key: 'total', label: 'Total' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Aksi' },
];

const { rows: bookings, error } = useAdminResource('bookings', []);
const updating = ref(null);

const statusOf = (b) => b.status === 'paid' ? 'paid' : (b.status === 'cancelled' ? 'cancelled' : (b.status === 'verified' ? 'verified' : 'pending'));

async function setStatus(b, status) {
  updating.value = b.id;
  try {
    const updated = await api.adminUpdate('bookings', b.id, { status });
    Object.assign(b, updated);
  } catch (e) {
    alert(e.message || 'Gagal mengubah status.');
  } finally {
    updating.value = null;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Daftar pemesanan">
      <tr v-for="b in bookings" :key="b.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink whitespace-nowrap">{{ b.code }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink">{{ b.package?.title || '#' + b.package_id }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap">{{ b.guest_name }} ({{ b.adults + b.children }} pax)</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink tabular-nums whitespace-nowrap">{{ formatRupiah(b.total) }}</td>
        <td class="px-4 py-3"><AdminBadge :status="statusOf(b)" /></td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer disabled:opacity-50" type="button" :disabled="updating === b.id" @click="emit('navigate', 'booking-detail', { id: b.id })">Detail</button>
            <button v-if="b.status === 'pending'" class="h-9 px-3 rounded-lg bg-teal text-white font-label-md text-label-sm hover:bg-teal-dark transition-colors cursor-pointer disabled:opacity-50" type="button" :disabled="updating === b.id" @click="setStatus(b, 'verified')">Verifikasi</button>
            <button v-if="b.status !== 'cancelled'" class="h-9 px-3 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-sm hover:bg-[#FCECED] transition-colors cursor-pointer disabled:opacity-50" type="button" :disabled="updating === b.id" @click="setStatus(b, 'cancelled')">Batalkan</button>
          </div>
        </td>
      </tr>
    </AdminTable>
  </div>
</template>
