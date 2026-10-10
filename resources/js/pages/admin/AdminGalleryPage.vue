<script setup>
import { ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import AdminModal from '../../components/admin/AdminModal.vue';
import ImageUploader from '../../components/admin/ImageUploader.vue';
import { useAdminResource } from '../../useAdminResource.js';

const cols = [
  { key: 'title', label: 'Judul' },
  { key: 'tag', label: 'Tag' },
  { key: 'action', label: 'Aksi' },
];

const { rows: items, error, save, remove } = useAdminResource('gallery', []);
const modalOpen = ref(false);
const form = ref({ title: '', image: '', tag: 'Bahari', region: '' });
const formError = ref('');
const editingId = ref(null);

function openCreate() {
  editingId.value = null;
  form.value = { title: '', image: '', tag: 'Bahari', region: '' };
  formError.value = '';
  modalOpen.value = true;
}

function openEdit(g) {
  editingId.value = g.id;
  form.value = { title: g.title, image: g.image, tag: g.tag || '', region: g.region || '' };
  formError.value = '';
  modalOpen.value = true;
}

async function onSave() {
  formError.value = '';
  if (!form.value.title.trim() || !form.value.image.trim()) {
    formError.value = 'Judul dan path gambar wajib diisi.';
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
  if (!confirm('Hapus foto ini?')) {
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
        + Tambah Foto
      </button>
    </div>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Daftar galeri">
      <tr v-for="g in items" :key="g.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink">
          <span class="flex items-center gap-3">
            <img :alt="g.title" :src="g.image" class="w-12 h-12 rounded-lg object-cover border border-card-border" loading="lazy">
            {{ g.title }}
          </span>
        </td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap">{{ g.tag }}</td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="openEdit(g)">Ubah</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-sm hover:bg-[#FCECED] transition-colors cursor-pointer" type="button" @click="onDelete(g.id)">Hapus</button>
          </div>
        </td>
      </tr>
    </AdminTable>
    <AdminModal :open="modalOpen" :title="editingId ? 'Ubah Foto' : 'Tambah Foto'" @close="modalOpen = false" @confirm="onSave">
      <div class="flex flex-col gap-4">
        <AdminInput id="g-title" v-model="form.title" label="Judul" required />
        <ImageUploader id="g-image" v-model="form.image" label="Foto" folder="gallery" />
        <AdminInput id="g-tag" v-model="form.tag" label="Tag" />
        <AdminInput id="g-region" v-model="form.region" label="Region" />
        <p v-if="formError" class="text-xs text-error">{{ formError }}</p>
      </div>
    </AdminModal>
  </div>
</template>
