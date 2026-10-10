<script setup>
import { ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import AdminModal from '../../components/admin/AdminModal.vue';
import ImageUploader from '../../components/admin/ImageUploader.vue';
import { useAdminResource } from '../../useAdminResource.js';

const cols = [
  { key: 'name', label: 'Nama' },
  { key: 'role', label: 'Peran' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Aksi' },
];

const { rows: items, error, save, remove } = useAdminResource('team', []);
const modalOpen = ref(false);
const form = ref({ name: '', role: '', bio: '', photo: '', status: 'active' });
const formError = ref('');
const editingId = ref(null);

function openCreate() {
  editingId.value = null;
  form.value = { name: '', role: '', bio: '', photo: '', status: 'active' };
  formError.value = '';
  modalOpen.value = true;
}

function openEdit(m) {
  editingId.value = m.id;
  form.value = { name: m.name, role: m.role || '', bio: m.bio || '', photo: m.photo || '', status: m.status };
  formError.value = '';
  modalOpen.value = true;
}

async function onSave() {
  formError.value = '';
  if (!form.value.name.trim()) {
    formError.value = 'Nama wajib diisi.';
    return;
  }
  try {
    await save({ ...form.value }, editingId.value);
    modalOpen.value = false;
  } catch (e) {
    formError.value = e.message || 'Gagal menyimpan.';
  }
}

async function onDelete(id) {
  if (!confirm('Hapus anggota tim ini?')) {
    return;
  }
  try {
    await remove(id);
  } catch (e) {
    alert(e.message || 'Gagal menghapus.');
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="flex justify-end">
      <button class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer w-fit" type="button" @click="openCreate">
        + Tambah Anggota
      </button>
    </div>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Daftar tim">
      <tr v-for="m in items" :key="m.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink">
          <span class="flex items-center gap-3">
            <img v-if="m.photo" :alt="m.name" :src="m.photo" class="w-10 h-10 rounded-full object-cover border border-card-border" loading="lazy">
            {{ m.name }}
          </span>
        </td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink">{{ m.role }}</td>
        <td class="px-4 py-3 font-label-md text-label-sm text-ink">{{ m.status }}</td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="openEdit(m)">Ubah</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-sm hover:bg-[#FCECED] transition-colors cursor-pointer" type="button" @click="onDelete(m.id)">Hapus</button>
          </div>
        </td>
      </tr>
    </AdminTable>
    <AdminModal :open="modalOpen" :title="editingId ? 'Ubah Anggota' : 'Tambah Anggota'" @close="modalOpen = false" @confirm="onSave">
      <div class="flex flex-col gap-4">
        <AdminInput id="m-name" v-model="form.name" label="Nama" required />
        <AdminInput id="m-role" v-model="form.role" label="Peran" />
        <ImageUploader id="m-photo" v-model="form.photo" label="Foto" folder="team" />
        <AdminInput id="m-bio" v-model="form.bio" label="Bio Singkat" />
        <p v-if="formError" class="text-xs text-error">{{ formError }}</p>
      </div>
    </AdminModal>
  </div>
</template>
