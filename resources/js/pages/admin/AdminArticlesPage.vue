<script setup>
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminBadge from '../../components/admin/AdminBadge.vue';
import { useAdminResource } from '../../useAdminResource.js';

const emit = defineEmits(['navigate']);

const cols = [
  { key: 'title', label: 'Judul' },
  { key: 'category', label: 'Kategori' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Aksi' },
];

const { rows: articles, error, remove } = useAdminResource('articles', []);

const statusOf = (a) => a.status === 'published' ? 'published' : (a.status === 'archived' ? 'archived' : 'draft');

async function onDelete(id) {
  if (!confirm('Hapus artikel ini?')) {
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
      <button class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer w-fit" type="button" @click="emit('navigate', 'article-editor')">
        + Tulis Artikel
      </button>
    </div>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <AdminTable :columns="cols" caption="Daftar artikel">
      <tr v-for="a in articles" :key="a.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink">{{ a.title }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink whitespace-nowrap">{{ a.category }}</td>
        <td class="px-4 py-3"><AdminBadge :status="statusOf(a)" /></td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="emit('navigate', 'article-editor', { id: a.id })">Ubah</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-sm hover:bg-[#FCECED] transition-colors cursor-pointer" type="button" @click="onDelete(a.id)">Hapus</button>
          </div>
        </td>
      </tr>
    </AdminTable>
  </div>
</template>
