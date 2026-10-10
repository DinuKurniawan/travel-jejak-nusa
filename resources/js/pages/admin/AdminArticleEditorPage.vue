<script setup>
import { onMounted, ref } from 'vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import { api } from '../../api.js';

const props = defineProps({
  articleId: { type: [Number, String], default: '' },
});

const emit = defineEmits(['navigate']);

const form = ref({ title: '', category: 'Tips Perjalanan', author: '', image: '', excerpt: '', body: '', status: 'draft' });
const error = ref('');
const saving = ref(false);
const loaded = ref(false);

onMounted(async () => {
  if (!props.articleId) {
    loaded.value = true;
    return;
  }
  try {
    const list = await api.adminList('articles', {});
    const rows = Array.isArray(list) ? list : (list.data || []);
    const found = rows.find((a) => String(a.id) === String(props.articleId));
    if (found) {
      form.value = { title: found.title, category: found.category, author: found.author || '', image: found.image || '', excerpt: found.excerpt || '', body: found.body || '', status: found.status };
    }
  } catch (e) {
    error.value = e.message || 'Gagal memuat artikel.';
  } finally {
    loaded.value = true;
  }
});

async function save() {
  error.value = '';
  if (!form.value.title.trim() || !form.value.category.trim()) {
    error.value = 'Judul dan kategori wajib diisi.';
    return;
  }
  saving.value = true;
  try {
    if (props.articleId) {
      await api.adminUpdate('articles', props.articleId, { ...form.value });
    } else {
      await api.adminCreate('articles', { ...form.value });
    }
    emit('navigate', 'articles');
  } catch (e) {
    error.value = e.message || 'Gagal menyimpan artikel.';
  } finally {
    saving.value = false;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <div v-if="loaded" class="bg-white rounded-xl border border-card-border p-6 flex flex-col gap-4">
      <AdminInput id="a-title" v-model="form.title" label="Judul" required />
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <AdminInput id="a-category" v-model="form.category" label="Kategori" required />
        <AdminInput id="a-author" v-model="form.author" label="Penulis" />
      </div>
      <AdminInput id="a-image" v-model="form.image" label="Path Gambar" placeholder="cth: /images/img-13.jpg" />
      <AdminInput id="a-excerpt" v-model="form.excerpt" label="Ringkasan" />
      <div class="flex flex-col gap-1.5">
        <label for="a-body" class="font-label-md text-label-md text-ink font-semibold">Isi Artikel</label>
        <textarea id="a-body" v-model="form.body" rows="8" class="px-3 py-2.5 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal" placeholder="Tulis isi artikel..." />
      </div>
      <div class="flex flex-col gap-1.5">
        <label for="a-status" class="font-label-md text-label-md text-ink font-semibold">Status</label>
        <select id="a-status" v-model="form.status" class="h-10 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
          <option value="draft">Draft</option>
          <option value="published">Terbit</option>
          <option value="archived">Arsip</option>
        </select>
      </div>
      <div class="flex items-center gap-2 pt-2">
        <button :disabled="saving" class="h-11 px-6 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer disabled:opacity-50" type="button" @click="save">
          {{ saving ? 'Menyimpan...' : (articleId ? 'Simpan Perubahan' : 'Terbitkan Artikel') }}
        </button>
        <button class="h-11 px-6 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-md hover:bg-surface-container transition-colors cursor-pointer" type="button" @click="emit('navigate', 'articles')">
          Batal
        </button>
      </div>
    </div>
  </div>
</template>
