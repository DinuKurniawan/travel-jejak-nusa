<script setup>
import { onMounted, ref } from 'vue';
import AdminInput from '../../components/admin/AdminInput.vue';
import { api } from '../../api.js';

const form = ref({ name: '', email: '', password: '', password_confirmation: '' });
const error = ref('');
const saved = ref('');
const saving = ref(false);

onMounted(async () => {
  try {
    const me = await api.adminProfile();
    form.value.name = me.name || '';
    form.value.email = me.email || '';
  } catch (e) {
    error.value = e.message || 'Gagal memuat profil.';
  }
});

async function save() {
  error.value = '';
  saved.value = '';
  const payload = { name: form.value.name, email: form.value.email };
  if (form.value.password) {
    payload.password = form.value.password;
    payload.password_confirmation = form.value.password_confirmation;
  }
  saving.value = true;
  try {
    await api.adminUpdateProfile(payload);
    saved.value = 'Profil tersimpan.';
  } catch (e) {
    error.value = e.message || 'Gagal menyimpan profil.';
  } finally {
    saving.value = false;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4 max-w-2xl">
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
    <p v-if="saved" role="status" class="text-xs text-[#2EB67D]">{{ saved }}</p>
    <div class="bg-white rounded-xl border border-card-border p-6 flex flex-col gap-4">
      <AdminInput id="p-name" v-model="form.name" label="Nama" />
      <AdminInput id="p-email" v-model="form.email" label="Email" type="email" />
      <AdminInput id="p-pass" v-model="form.password" label="Kata Sandi Baru (opsional)" type="password" />
      <AdminInput id="p-pass2" v-model="form.password_confirmation" label="Konfirmasi Sandi" type="password" />
      <div>
        <button :disabled="saving" class="h-11 px-6 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer disabled:opacity-50" type="button" @click="save">
          {{ saving ? 'Menyimpan...' : 'Simpan Profil' }}
        </button>
      </div>
    </div>
  </div>
</template>
