<script setup>
import { ref } from 'vue';

const props = defineProps({
  modelValue: { type: String, default: '' },
  id: { type: String, default: 'image-upload' },
  label: { type: String, default: 'Gambar' },
  folder: { type: String, default: 'packages' },
});

const emit = defineEmits(['update:modelValue']);

const uploading = ref(false);
const error = ref('');
const preview = ref(props.modelValue);

function csrf() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function onFile(event) {
  const file = event.target.files?.[0];
  if (!file) {
    return;
  }
  error.value = '';
  uploading.value = true;
  try {
    const fd = new FormData();
    fd.append('image', file);
    fd.append('folder', props.folder);
    const res = await fetch('/api/v1/admin/uploads', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
      body: fd,
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      throw new Error(data.message || 'Upload gagal.');
    }
    preview.value = data.url;
    emit('update:modelValue', data.url);
  } catch (e) {
    error.value = e.message || 'Upload gagal.';
  } finally {
    uploading.value = false;
    event.target.value = '';
  }
}
</script>

<template>
  <div class="flex flex-col gap-1.5">
    <label :for="id" class="font-label-md text-label-md text-ink font-semibold">{{ label }}</label>
    <div class="flex items-center gap-3">
      <img v-if="preview || modelValue" :alt="label" :src="preview || modelValue" class="w-16 h-16 rounded-lg object-cover border border-card-border">
      <div class="flex flex-col gap-1">
        <input :id="id" accept="image/jpeg,image/png,image/webp" class="text-sm" type="file" @change="onFile">
        <span v-if="uploading" class="text-xs text-muted">Mengunggah...</span>
        <span v-if="modelValue" class="text-[11px] text-muted break-all">{{ modelValue }}</span>
      </div>
    </div>
    <p v-if="error" role="alert" class="text-xs text-error">{{ error }}</p>
  </div>
</template>
