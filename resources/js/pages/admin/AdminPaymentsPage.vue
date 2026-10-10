<script setup>
import { computed, ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminBadge from '../../components/admin/AdminBadge.vue';
import { api, formatRupiah } from '../../api.js';
import { useAdminResource } from '../../useAdminResource.js';

const cols = [
  { key: 'code', label: 'Kode' },
  { key: 'guest', label: 'Tamu' },
  { key: 'total', label: 'Total' },
  { key: 'payment', label: 'Bayar' },
  { key: 'action', label: 'Aksi' },
];

const { rows: bookings, error } = useAdminResource('bookings', []);
const updating = ref(null);

const pending = computed(() => bookings.value.filter((b) => b.payment_status !== 'paid' && b.status !== 'cancelled'));

async function markPaid(b, paymentStatus) {
  updating.value = b.id;
  try {
    const updated = await api.adminUpdate('bookings', b.id, {
      payment_status: paymentStatus,
      status: paymentStatus === 'paid' ? 'paid' : b.status,
    });
    Object.assign(b, updated);
  } catch (e) {
    alert(e.message || 'Gagal memverifikasi pembayaran.');
  } finally {
    updating.value = null;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Verifikasi pembayaran">
      <tr v-for="b in pending" :key="b.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink whitespace-nowrap">{{ b.code }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink">{{ b.guest_name }}<br><span class="text-xs text-muted">{{ b.guest_email }}</span></td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink tabular-nums whitespace-nowrap">{{ formatRupiah(b.total) }}</td>
        <td class="px-4 py-3"><AdminBadge :status="b.payment_status === 'dp' ? 'pending' : 'draft'" /></td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="h-9 px-3 rounded-lg bg-orange text-white font-label-md text-label-sm hover:bg-[#DE5E26] transition-colors cursor-pointer disabled:opacity-50" type="button" :disabled="updating === b.id" @click="markPaid(b, 'paid')">Lunas</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer disabled:opacity-50" type="button" :disabled="updating === b.id" @click="markPaid(b, 'dp')">DP</button>
          </div>
        </td>
      </tr>
    </AdminTable>
    <p v-if="pending.length === 0" class="text-sm text-muted">Tidak ada pembayaran menunggu verifikasi.</p>
  </div>
</template>
