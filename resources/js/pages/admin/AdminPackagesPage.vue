<script setup>
import { ref, computed } from 'vue';
import AdminTable from '../../components/admin/AdminTable.vue';
import AdminBadge from '../../components/admin/AdminBadge.vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import AdminModal from '../../components/admin/AdminModal.vue';
import ImageUploader from '../../components/admin/ImageUploader.vue';
import EmptyState from '../../components/EmptyState.vue';
import { api, formatRupiah } from '../../api.js';
import { useAdminResource } from '../../useAdminResource.js';

const cols = [
  { key: 'title', label: 'Paket' },
  { key: 'location', label: 'Lokasi' },
  { key: 'price', label: 'Harga' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Aksi' },
];

const { rows: packages, save: saveRow, remove: removeRow, load } = useAdminResource('packages', []);

const priceText = (p) => typeof p.price === 'number' ? formatRupiah(p.price) : p.price;

const query = ref('');
const filtered = computed(() => {
  const q = query.value.toLowerCase().trim();
  if (!q) {
    return packages.value;
  }

  return packages.value.filter((p) => (p.title + ' ' + p.location).toLowerCase().includes(q));
});

const modalOpen = ref(false);
const form = ref({ title: '', location: '', price: '', status: 'draft', image: '' });
const formError = ref('');
const editingId = ref(null);

function parsePrice(raw) {
  const n = parseInt(String(raw || '').replace(/[^0-9]/g, ''), 10);

  return Number.isFinite(n) ? n : 0;
}

function openCreate() {
  editingId.value = null;
  form.value = { title: '', location: '', price: '', status: 'draft', image: '' };
  formError.value = '';
  modalOpen.value = true;
}

function openEdit(pkg) {
  editingId.value = pkg.id;
  form.value = { title: pkg.title, location: pkg.location, price: String(pkg.price ?? ''), status: pkg.status, image: pkg.image || '' };
  formError.value = '';
  modalOpen.value = true;
}

async function save() {
  formError.value = '';
  if (!form.value.title.trim() || !form.value.location.trim() || parsePrice(form.value.price) <= 0) {
    formError.value = 'Judul, lokasi, dan harga (angka > 0) wajib diisi.';
    return;
  }
  try {
    await saveRow({
      title: form.value.title.trim(),
      location: form.value.location.trim(),
      price: parsePrice(form.value.price),
      status: form.value.status,
      image: form.value.image || null,
    }, editingId.value);
    modalOpen.value = false;
  } catch (err) {
    formError.value = err.message || 'Gagal menyimpan. Coba lagi.';
  }
}

async function remove(id) {
  try {
    await removeRow(id);
  } catch (err) {
    formError.value = err.message || 'Gagal menghapus.';
  }
}

async function toggleStatus(pkg) {
  const next = pkg.status === 'published' ? 'draft' : 'published';
  try {
    await api.adminUpdate('packages', pkg.id, { status: next });
    pkg.status = next;
  } catch (err) {
    formError.value = err.message || 'Gagal mengubah status.';
  }
}
void load;
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between">
      <div class="w-full sm:max-w-sm">
        <AdminInput id="admin-package-search" v-model="query" placeholder="Cari paket..." label="" />
      </div>
      <button class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer w-fit shrink-0" type="button" @click="openCreate">
        + Tambah Paket
      </button>
    </div>

    <AdminTable :columns="cols" caption="Daftar paket wisata">
      <tr v-for="p in filtered" :key="p.id" class="border-b border-card-border last:border-0 hover:bg-teal-light/40 transition-colors">
        <td class="px-4 py-3 font-label-md text-label-md text-ink">{{ p.title }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink">{{ p.location }}</td>
        <td class="px-4 py-3 font-body-md text-body-md text-ink tabular-nums whitespace-nowrap">{{ priceText(p) }}</td>
        <td class="px-4 py-3"><AdminBadge :status="p.status" /></td>
        <td class="px-4 py-3">
          <div class="flex items-center gap-2">
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="openEdit(p)">Ubah</button>
            <button v-if="live" class="h-9 px-3 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-sm hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="toggleStatus(p)">{{ p.status === 'published' ? 'Arsipkan' : 'Terbitkan' }}</button>
            <button class="h-9 px-3 rounded-lg border-[1.5px] border-error text-error font-label-md text-label-sm hover:bg-[#FCECED] transition-colors cursor-pointer" type="button" @click="remove(p.id)">Hapus</button>
          </div>
        </td>
      </tr>
    </AdminTable>
    <EmptyState
      v-if="filtered.length === 0"
      icon="luggage"
      title="Belum ada paket"
      description="Tambah paket pertama atau ubah kata kunci pencarian."
    >
      <button class="px-6 py-2.5 rounded-lg bg-orange text-white font-label-md text-sm hover:bg-[#DE5E26] transition-colors cursor-pointer" type="button" @click="openCreate">
        Tambah Paket
      </button>
    </EmptyState>

    <AdminModal :open="modalOpen" :title="editingId ? 'Ubah Paket' : 'Tambah Paket'" @close="modalOpen = false" @confirm="save">
      <div class="flex flex-col gap-4">
        <AdminInput id="pkg-title" v-model="form.title" label="Judul Paket" placeholder="cth: Sailing Komodo 3D2N" required :error="formError && !form.title.trim() ? 'Judul wajib diisi.' : ''" />
        <AdminInput id="pkg-location" v-model="form.location" label="Lokasi" placeholder="cth: Labuan Bajo, NTT" required :error="formError && !form.location.trim() ? 'Lokasi wajib diisi.' : ''" />
        <AdminInput id="pkg-price" v-model="form.price" label="Harga (angka)" placeholder="cth: 2850000" required :error="formError && parsePrice(form.price) <= 0 ? 'Harga wajib angka > 0.' : ''" />
        <ImageUploader id="pkg-image" v-model="form.image" label="Foto Paket" folder="packages" />
        <div class="flex flex-col gap-1.5">
          <label for="pkg-status" class="font-label-md text-label-md text-ink font-semibold">Status</label>
          <select id="pkg-status" v-model="form.status" class="h-10 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
            <option value="draft">Draft</option>
            <option value="published">Terbit</option>
            <option value="archived">Arsip</option>
          </select>
        </div>
        <p v-if="formError" class="text-xs text-error">{{ formError }}</p>
      </div>
    </AdminModal>
  </div>
</template>
