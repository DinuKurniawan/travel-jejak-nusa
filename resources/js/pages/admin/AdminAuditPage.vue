<script setup>
import { onMounted, ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import { api } from '../../api.js';

const cols = [
  { key: 'when', label: 'Waktu' },
  { key: 'user', label: 'Admin' },
  { key: 'action', label: 'Aksi' },
  { key: 'subject', label: 'Subjek' },
];

const rows = ref([]);
const error = ref('');

onMounted(async () => {
  try {
    const data = await api.adminList('audit');
    rows.value = Array.isArray(data) ? data : (data.data || []);
  } catch (e) {
    error.value = e.message || 'Gagal memuat audit log.';
  }
});

function fmt(ts) {
  try {
    return new Date(ts).toLocaleString('id-ID');
  } catch {
    return ts;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Audit log">
      <tr v-for="r in rows" :key="r.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap tabular-nums">{{ fmt(r.created_at) }}</td>
        <td class="px-4 py-3 font-label-md text-label-md text-ink whitespace-nowrap">{{ r.user?.name || '—' }}</td>
        <td class="px-4 py-3 font-label-md text-label-sm text-ink whitespace-nowrap">{{ r.action }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap">{{ r.subject_type ? r.subject_type.split('\\').pop() + ' #' + r.subject_id : '—' }}</td>
      </tr>
    </AdminTable>
    <p v-if="rows.length === 0" class="text-sm text-muted">Belum ada aktivitas tercatat.</p>
  </div>
</template>
