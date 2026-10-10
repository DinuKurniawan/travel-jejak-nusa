<script setup>
import AdminTable from '../../components/admin/AdminTable.vue';
import { api } from '../../api.js';
import { useAdminResource } from '../../useAdminResource.js';

const cols = [
  { key: 'name', label: 'Pengirim' },
  { key: 'subject', label: 'Subjek' },
  { key: 'message', label: 'Pesan' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Aksi' },
];

const { rows: messages, error } = useAdminResource('messages', []);

async function setStatus(m, status) {
  try {
    const updated = await api.adminUpdate('messages', m.id, { status });
    Object.assign(m, updated);
  } catch (e) {
    alert(e.message || 'Gagal mengubah status pesan.');
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Pesan masuk">
      <tr v-for="m in messages" :key="m.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink">{{ m.name }}<br><span class="text-xs text-muted font-body-md">{{ m.email }}</span></td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap">{{ m.subject }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink max-w-md"><span class="line-clamp-2">{{ m.message }}</span></td>
        <td class="px-4 py-3 font-label-md text-label-sm whitespace-nowrap">{{ m.status }}</td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button v-if="m.status === 'unread'" class="h-9 px-3 rounded-lg bg-teal text-white font-label-md text-label-sm hover:bg-teal-dark transition-colors cursor-pointer" type="button" @click="setStatus(m, 'read')">Tandai Dibaca</button>
            <button v-if="m.status !== 'archived'" class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="setStatus(m, 'archived')">Arsipkan</button>
          </div>
        </td>
      </tr>
    </AdminTable>
  </div>
</template>
