<script setup>
import { onMounted, ref } from 'vue';
import { api } from '../../api.js';
import AppButton from '../../components/AppButton.vue';
import AdminModal from '../../components/admin/AdminModal.vue';
import EmptyState from '../../components/EmptyState.vue';

const loading = ref(true);
const error = ref('');
const travelers = ref([]);

const showForm = ref(false);
const draft = ref({ name: '', id_number: '', phone: '', tag: '' });
const notice = ref('');
const formError = ref('');
const saving = ref(false);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    travelers.value = await api.userTravelers();
  } catch (e) {
    error.value = e.message || 'Gagal memuat data traveler.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

function openAdd() {
  draft.value = { name: '', id_number: '', phone: '', tag: '' };
  formError.value = '';
  showForm.value = true;
}

async function save() {
  if (!draft.value.name.trim()) {
    formError.value = 'Nama traveler wajib diisi.';
    return;
  }
  saving.value = true;
  formError.value = '';
  try {
    await api.userSaveTraveler(draft.value);
    showForm.value = false;
    notice.value = 'Data traveler tersimpan.';
    await load();
  } catch (e) {
    formError.value = e.message || 'Gagal menyimpan traveler.';
  } finally {
    saving.value = false;
  }
}

async function remove(id) {
  try {
    await api.userDeleteTraveler(id);
    await load();
  } catch (e) {
    error.value = e.message || 'Gagal menghapus traveler.';
  }
}
</script>

<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <p v-if="notice" role="status" class="bg-info-tint border border-teal/30 text-ink rounded-xl px-4 py-3 font-body-md text-body-md">{{ notice }}</p>

    <div class="flex flex-wrap items-center justify-between gap-3">
      <p class="font-body-md text-body-md text-muted">{{ travelers.length }} traveler tersimpan</p>
      <AppButton variant="secondary" size="sm" @click="openAdd">+ Tambah Traveler</AppButton>
    </div>

    <div v-if="loading" class="flex flex-col gap-2.5" aria-label="Memuat data traveler">
      <div v-for="n in 2" :key="n" class="bg-white border border-card-border rounded-xl h-20 animate-pulse" />
    </div>

    <div v-else-if="error && travelers.length === 0" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat data traveler</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <ul v-else-if="travelers.length > 0" class="flex flex-col gap-2.5 list-none m-0 p-0">
      <li v-for="t in travelers" :key="t.id" class="bg-white border border-card-border rounded-xl p-4 flex flex-wrap items-center gap-3">
        <span class="w-11 h-11 rounded-full bg-info-tint text-teal flex items-center justify-center font-bold shrink-0" aria-hidden="true">{{ (t.name || '?').charAt(0) }}</span>
        <div class="flex-1 min-w-44">
          <p class="font-label-md text-label-md font-semibold text-ink">{{ t.name }} <span v-if="t.tag" class="ml-1 px-2 py-0.5 rounded-full bg-page border border-card-border font-label-sm text-label-sm text-muted font-medium">{{ t.tag }}</span></p>
          <p class="font-body-sm text-body-sm text-muted tabular-nums mt-0.5">{{ t.id_number || '—' }} • {{ t.phone || '—' }}</p>
        </div>
        <button class="h-10 px-3 rounded-lg text-danger hover:bg-danger-light font-label-sm text-label-sm font-semibold transition-colors cursor-pointer" type="button" @click="remove(t.id)">Hapus</button>
      </li>
    </ul>
    <EmptyState v-else icon="group" title="Belum ada traveler" description="Tambahkan data teman seperjalanan biar checkout lebih cepat." />

    <AdminModal :open="showForm" title="Tambah traveler" @close="showForm = false" @confirm="save">
      <p v-if="formError" role="alert" class="mb-3 bg-danger-light border border-danger/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-danger">{{ formError }}</p>
      <div class="flex flex-col gap-3.5">
        <div>
          <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="traveler-nama">Nama sesuai KTP / paspor</label>
          <input id="traveler-nama" v-model="draft.name" type="text" placeholder="Nama lengkap" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal">
        </div>
        <div>
          <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="traveler-nik">NIK / No. paspor</label>
          <input id="traveler-nik" v-model="draft.id_number" type="text" inputmode="numeric" placeholder="16 digit NIK" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal">
        </div>
        <div class="grid grid-cols-2 gap-3.5">
          <div>
            <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="traveler-telp">No. HP</label>
            <input id="traveler-telp" v-model="draft.phone" type="tel" placeholder="08xx" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal">
          </div>
          <div>
            <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="traveler-relasi">Relasi</label>
            <input id="traveler-relasi" v-model="draft.tag" type="text" placeholder="Pasangan, Anak…" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal">
          </div>
        </div>
      </div>
      <template #footer>
        <button class="h-10 px-4 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-md hover:bg-page transition-colors cursor-pointer" type="button" @click="showForm = false">Urungkan</button>
        <button :disabled="saving" class="h-10 px-4 rounded-lg bg-teal text-white font-label-md text-label-md hover:bg-teal-dark transition-colors cursor-pointer disabled:opacity-50" type="button" @click="save">Simpan</button>
      </template>
    </AdminModal>
  </div>
</template>
