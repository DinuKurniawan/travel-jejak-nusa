<script setup>
import { ref } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import AdminModal from '../../components/admin/AdminModal.vue';
import { useAdminResource } from '../../useAdminResource.js';

const cols = [
  { key: 'question', label: 'Pertanyaan' },
  { key: 'category', label: 'Kategori' },
  { key: 'action', label: 'Aksi' },
];

const { rows: items, error, save, remove } = useAdminResource('faqs', []);
const modalOpen = ref(false);
const form = ref({ category: 'pemesanan', question: '', answer: '' });
const formError = ref('');
const editingId = ref(null);

function openCreate() {
  editingId.value = null;
  form.value = { category: 'pemesanan', question: '', answer: '' };
  formError.value = '';
  modalOpen.value = true;
}

function openEdit(f) {
  editingId.value = f.id;
  form.value = { category: f.category, question: f.question, answer: f.answer };
  formError.value = '';
  modalOpen.value = true;
}

async function onSave() {
  formError.value = '';
  if (!form.value.question.trim() || !form.value.answer.trim()) {
    formError.value = 'Pertanyaan dan jawaban wajib diisi.';
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
  if (!confirm('Hapus FAQ ini?')) {
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
        + Tambah FAQ
      </button>
    </div>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Daftar FAQ">
      <tr v-for="f in items" :key="f.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink max-w-xl">{{ f.question }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap">{{ f.category }}</td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="openEdit(f)">Ubah</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-sm hover:bg-[#FCECED] transition-colors cursor-pointer" type="button" @click="onDelete(f.id)">Hapus</button>
          </div>
        </td>
      </tr>
    </AdminTable>
    <AdminModal :open="modalOpen" :title="editingId ? 'Ubah FAQ' : 'Tambah FAQ'" @close="modalOpen = false" @confirm="onSave">
      <div class="flex flex-col gap-4">
        <AdminInput id="f-question" v-model="form.question" label="Pertanyaan" required />
        <AdminInput id="f-answer" v-model="form.answer" label="Jawaban" required />
        <p v-if="formError" class="text-xs text-error">{{ formError }}</p>
      </div>
    </AdminModal>
  </div>
</template>
