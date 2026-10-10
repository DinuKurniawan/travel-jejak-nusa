<script setup>
import { ref } from 'vue';
import { api } from '../../api.js';
import AppButton from '../../components/AppButton.vue';

const passwords = ref({ current: '', next: '', confirm: '' });
const notice = ref('');
const error = ref('');
const saving = ref(false);

const deviceLabel = typeof navigator !== 'undefined'
  ? `${navigator.platform || 'Perangkat ini'} • ${String(navigator.userAgent).includes('Mobile') ? 'Mobile' : 'Desktop'}`
  : 'Perangkat ini';

async function changePassword() {
  error.value = '';
  notice.value = '';
  if (passwords.value.next.length < 8) {
    error.value = 'Kata sandi baru minimal 8 karakter.';
    return;
  }
  if (passwords.value.next !== passwords.value.confirm) {
    error.value = 'Konfirmasi kata sandi tidak cocok.';
    return;
  }
  saving.value = true;
  try {
    await api.userChangePassword(passwords.value);
    notice.value = 'Kata sandi berhasil diganti.';
    passwords.value = { current: '', next: '', confirm: '' };
  } catch (e) {
    error.value = e.message || 'Gagal mengganti kata sandi.';
  } finally {
    saving.value = false;
  }
}
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 max-w-5xl">
    <form class="bg-white border border-card-border rounded-xl p-4 sm:p-6" aria-labelledby="keamanan-sandi" @submit.prevent="changePassword">
      <h2 id="keamanan-sandi" class="font-jakarta font-bold text-[18px] text-ink">Ganti kata sandi</h2>
      <p v-if="notice" role="status" class="mt-3 bg-info-tint border border-teal/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-ink">{{ notice }}</p>
      <p v-if="error" role="alert" class="mt-3 bg-danger-light border border-danger/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-danger">{{ error }}</p>
      <div class="flex flex-col gap-3.5 mt-4">
        <div>
          <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="sandi-lama">Kata sandi saat ini</label>
          <input id="sandi-lama" v-model="passwords.current" type="password" autocomplete="current-password" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
        </div>
        <div>
          <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="sandi-baru">Kata sandi baru</label>
          <input id="sandi-baru" v-model="passwords.next" type="password" autocomplete="new-password" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
        </div>
        <div>
          <label class="block font-label-sm text-label-sm text-ink font-medium mb-1.5" for="sandi-konfirmasi">Konfirmasi kata sandi baru</label>
          <input id="sandi-konfirmasi" v-model="passwords.confirm" type="password" autocomplete="new-password" class="w-full h-11 px-3 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink focus:outline-none focus:border-teal">
        </div>
      </div>
      <div class="mt-5">
        <AppButton variant="primary" type="submit" :disabled="saving">{{ saving ? 'Menyimpan…' : 'Ganti Kata Sandi' }}</AppButton>
      </div>
    </form>

    <section class="bg-white border border-card-border rounded-xl p-4 sm:p-6" aria-labelledby="keamanan-sesi">
      <h2 id="keamanan-sesi" class="font-jakarta font-bold text-[18px] text-ink">Sesi aktif</h2>
      <p class="font-body-sm text-body-sm text-muted mt-1">Perangkat yang sedang masuk ke akunmu saat ini.</p>
      <ul class="mt-4 flex flex-col gap-2.5 list-none m-0 p-0">
        <li class="flex items-center gap-3 border border-card-border rounded-lg px-3.5 py-3">
          <span class="w-10 h-10 rounded-lg bg-page flex items-center justify-center text-muted shrink-0" aria-hidden="true">
            <span class="material-symbols-outlined text-[22px]">devices</span>
          </span>
          <div class="flex-1 min-w-0">
            <p class="font-label-md text-label-md font-semibold text-ink truncate">{{ deviceLabel }}</p>
            <p class="font-body-sm text-body-sm text-muted">Sesi login ini</p>
          </div>
          <span class="px-2.5 py-1 rounded-full bg-success-tint text-success font-label-sm text-label-sm font-semibold">Aktif</span>
        </li>
      </ul>
    </section>
  </div>
</template>
