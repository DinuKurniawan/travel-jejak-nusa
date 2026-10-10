<script setup>
import { ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminBadge from '../../components/admin/AdminBadge.vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import AdminModal from '../../components/admin/AdminModal.vue';
import { api } from '../../api.js';
import { useAdminResource } from '../../useAdminResource.js';

const cols = [
  { key: 'name', label: 'Nama' },
  { key: 'quote', label: 'Ulasan' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Aksi' },
];

const { rows: items, error, save, remove } = useAdminResource('testimonials', []);
const modalOpen = ref(false);
const form = ref({ name: '', meta: '', quote: '', stars: 5, status: 'approved' });
const formError = ref('');
const editingId = ref(null);

function openCreate() {
  editingId.value = null;
  form.value = { name: '', meta: '', quote: '', stars: 5, status: 'approved' };
  formError.value = '';
  modalOpen.value = true;
}

function openEdit(t) {
  editingId.value = t.id;
  form.value = { name: t.name, meta: t.meta || '', quote: t.quote, stars: t.stars, status: t.status };
  formError.value = '';
  modalOpen.value = true;
}

async function onSave() {
  formError.value = '';
  if (!form.value.name.trim() || !form.value.quote.trim()) {
    formError.value = 'Nama dan ulasan wajib diisi.';
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
  if (!confirm('Hapus testimoni ini?')) {
    return;
  }
  try {
    await remove(id);
  } catch (e) {
    alert(e.message || 'Gagal menghapus.');
  }
}

async function approve(t) {
  try {
    const updated = await api.adminUpdate('testimonials', t.id, { status: 'approved' });
    Object.assign(t, updated);
  } catch (e) {
    alert(e.message || 'Gagal menyetujui.');
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="flex justify-end">
      <button class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer w-fit" type="button" @click="openCreate">
        + Tambah Testimoni
      </button>
    </div>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Daftar testimoni">
      <tr v-for="t in items" :key="t.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink">{{ t.name }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink max-w-md"><span class="line-clamp-2">{{ t.quote }}</span></td>
        <td class="px-4 py-3"><AdminBadge :status="t.status === 'approved' ? 'verified' : (t.status === 'rejected' ? 'rejected' : 'pending')" /></td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button v-if="t.status !== 'approved'" class="h-9 px-3 rounded-lg bg-teal text-white font-label-md text-label-sm hover:bg-teal-dark transition-colors cursor-pointer" type="button" @click="approve(t)">Setujui</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="openEdit(t)">Ubah</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-sm hover:bg-[#FCECED] transition-colors cursor-pointer" type="button" @click="onDelete(t.id)">Hapus</button>
          </div>
        </td>
      </tr>
    </AdminTable>
    <AdminModal :open="modalOpen" :title="editingId ? 'Ubah Testimoni' : 'Tambah Testimoni'" @close="modalOpen = false" @confirm="onSave">
      <div class="flex flex-col gap-4">
        <AdminInput id="t-name" v-model="form.name" label="Nama" required />
        <AdminInput id="t-meta" v-model="form.meta" label="Keterangan" placeholder="cth: Traveler • Jakarta" />
        <AdminInput id="t-quote" v-model="form.quote" label="Ulasan" required />
        <p v-if="formError" class="text-xs text-error">{{ formError }}</p>
      </div>
    </AdminModal>
  </div>
</template>
