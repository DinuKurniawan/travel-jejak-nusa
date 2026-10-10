<script setup>
import { onMounted, ref } from 'vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import AdminTabs from '../../components/admin/AdminTabs.vue';
import { api } from '../../api.js';

const tabs = [
  { id: 'general', label: 'Umum' },
  { id: 'contact', label: 'Kontak' },
  { id: 'seo', label: 'SEO' },
  { id: 'home', label: 'Beranda' },
  { id: 'navigation', label: 'Navigasi' },
];
const active = ref('general');
const values = ref({});
const loading = ref(true);
const error = ref('');
const saved = ref('');

const FIELDS = {
  general: [
    ['site_name', 'Nama Situs', 'Jejak Nusa'],
  ],
  contact: [
    ['whatsapp', 'WhatsApp', '6281234567890'],
    ['contact_email', 'Email', 'info@jejaknusa.id'],
    ['contact_phone', 'Telepon', '+62 812-3456-7890'],
    ['address', 'Alamat', ''],
    ['hours', 'Jam Operasional', '08.00 - 20.00 WIB'],
  ],
  seo: [
    ['meta_title', 'Meta Title', ''],
    ['meta_description', 'Meta Description', ''],
  ],
  home: [
    ['home_hero_title', 'Judul Hero', ''],
    ['home_hero_subtitle', 'Subjudul Hero', ''],
  ],
  navigation: [
    ['nav_announcement', 'Pengumuman Header', ''],
  ],
};

async function load() {
  loading.value = true;
  error.value = '';
  try {
    const data = await api.adminList('settings', { group: active.value });
    values.value = Object.fromEntries((Array.isArray(data) ? data : []).map((s) => [s.key, s.value || '']));
  } catch (e) {
    error.value = e.message || 'Gagal memuat pengaturan.';
  } finally {
    loading.value = false;
  }
}

async function save() {
  error.value = '';
  saved.value = '';
  try {
    await api.adminCreate('settings', {
      settings: FIELDS[active.value].map(([key, , fallback]) => ({
        key,
        value: values.value[key] ?? fallback,
        group: active.value,
      })),
    });
    saved.value = 'Pengaturan tersimpan.';
  } catch (e) {
    error.value = e.message || 'Gagal menyimpan.';
  }
}

onMounted(load);

async function select(id) {
  active.value = id;
  await load();
}
</script>

<template>
  <div class="flex flex-col gap-4 max-w-3xl">
    <AdminTabs :tabs="tabs" :active="active" @select="select" />
    <p v-if="loading" class="text-sm text-muted">Memuat...</p>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <p v-if="saved" role="status" class="text-xs text-[#2EB67D]">{{ saved }}</p>
    <div v-if="!loading" class="bg-white rounded-xl border border-card-border p-6 flex flex-col gap-4">
      <AdminInput
        v-for="[key, label, fallback] in FIELDS[active]"
        :key="key"
        :id="'set-' + key"
        v-model="values[key]"
        :label="label"
        :placeholder="fallback"
      />
      <div>
        <button class="h-11 px-6 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer" type="button" @click="save">
          Simpan Pengaturan
        </button>
      </div>
    </div>
  </div>
</template>
