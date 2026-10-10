<script setup>
import { onMounted, ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import { api } from '../../api.js';

const cols = [
  { key: 'name', label: 'Nama' },
  { key: 'role', label: 'Peran' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Aksi' },
];

const users = ref([]);
const error = ref('');
const updating = ref(null);

async function load() {
  try {
    users.value = await api.adminList('users');
  } catch (e) {
    error.value = e.message || 'Gagal memuat pengguna.';
  }
}

async function setField(u, field, value) {
  updating.value = u.id;
  try {
    const updated = await api.adminUpdate('users', u.id, { [field]: value });
    Object.assign(u, updated);
  } catch (e) {
    error.value = e.message || 'Gagal mengubah pengguna.';
  } finally {
    updating.value = null;
  }
}

onMounted(load);
</script>

<template>
  <div class="flex flex-col gap-4">
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Daftar pengguna">
      <tr v-for="u in users" :key="u.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink">{{ u.name }}<br><span class="text-xs text-muted font-body-md">{{ u.email }}</span></td>
        <td class="px-4 py-3">
          <select :value="u.role" :disabled="updating === u.id" aria-label="Peran pengguna" class="h-9 px-2 bg-white border-[1.5px] border-card-border rounded-lg text-sm" @change="setField(u, 'role', $event.target.value)">
            <option value="superadmin">Superadmin</option>
            <option value="admin">Admin</option>
            <option value="editor">Editor</option>
          </select>
        </td>
        <td class="px-4 py-3 font-label-md text-label-sm">{{ u.status }}</td>
        <td class="px-4 py-3">
          <button :disabled="updating === u.id" class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer disabled:opacity-50" type="button" @click="setField(u, 'status', u.status === 'active' ? 'inactive' : 'active')">
            {{ u.status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
          </button>
        </td>
      </tr>
    </AdminTable>
  </div>
</template>
