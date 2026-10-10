<script setup>
import { computed, onMounted, ref } from 'vue';
import { api } from '../../api.js';
import AppButton from '../../components/AppButton.vue';
import { initialsOf } from '../../userStatus.js';

const loading = ref(true);
const error = ref('');
const form = ref({ name: '', email: '', phone: '' });
const notice = ref('');
const formError = ref('');
const saving = ref(false);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    const me = await api.userProfile();
    form.value = { name: me.name || '', email: me.email || '', phone: me.phone || '' };
  } catch (e) {
    error.value = e.message || 'Gagal memuat profil.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

const initial = computed(() => initialsOf(form.value.name));

async function save() {
  saving.value = true;
  notice.value = '';
  formError.value = '';
  try {
    const me = await api.userUpdateProfile(form.value);
    form.value = { name: me.name || '', email: me.email || '', phone: me.phone || '' };
    notice.value = 'Profil berhasil disimpan.';
  } catch (e) {
    formError.value = e.message || 'Gagal menyimpan profil.';
  } finally {
    saving.value = false;
  }
}
</script>

<template>
  <div class="max-w-5xl">
    <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-3 gap-4" aria-label="Memuat profil">
      <div class="bg-white border border-card-border rounded-xl h-64 animate-pulse" />
      <div class="lg:col-span-2 bg-white border border-card-border rounded-xl h-64 animate-pulse" />
    </div>

    <div v-else-if="error && !form.name" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat profil</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <section class="bg-white border border-card-border rounded-xl p-5 flex flex-col items-center text-center" aria-labelledby="profil-avatar">
        <span class="w-20 h-20 rounded-full bg-teal text-white flex items-center justify-center font-jakarta font-bold text-[32px]" aria-hidden="true">{{ initial }}</span>
        <h2 id="profil-avatar" class="font-jakarta font-bold text-[18px] text-ink mt-3">{{ form.name }}</h2>
        <p class="font-body-sm text-body-sm text-muted">{{ form.email }}</p>
      </section>

      <form class="lg:col-span-2 bg-white border border-card-border rounded-xl p-4 sm:p-6" aria-labelledby="profil-form" @submit.prevent="save">
        <h2 id="profil-form" class="font-jakarta font-bold text-[18px] text-ink">Data diri</h2>
        <p v-if="notice" role="status" class="mt-3 bg-info-tint border border-teal/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-ink">{{ notice }}</p>
        <p v-if="formError" role="alert" class="mt-3 bg-danger-light border border-danger/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-danger">{{ formError }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mt-4">
          <div>
            <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="profil-nama">Nama lengkap</label>
            <input id="profil-nama" v-model="form.name" type="text" autocomplete="name" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
          </div>
          <div>
            <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="profil-email">Email</label>
            <input id="profil-email" v-model="form.email" type="email" autocomplete="email" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
          </div>
          <div>
            <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="profil-telp">No. WhatsApp</label>
            <input id="profil-telp" v-model="form.phone" type="tel" autocomplete="tel" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
          </div>
        </div>
        <div class="mt-5">
          <AppButton variant="primary" type="submit" :disabled="saving">{{ saving ? 'Menyimpan…' : 'Simpan Perubahan' }}</AppButton>
        </div>
      </form>
    </div>
  </div>
</template>
