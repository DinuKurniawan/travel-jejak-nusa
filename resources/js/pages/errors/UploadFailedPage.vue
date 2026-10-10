<script setup>
import { ref } from 'vue';
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);

const fileInputRef = ref(null);
const retrySuccess = ref(false);

const triggerFileSelect = () => {
  if (fileInputRef.value) {
    fileInputRef.value.click();
  }
};

const handleFileChange = (e) => {
  const files = e.target.files;
  if (files && files[0]) {
    retrySuccess.value = true;
  }
};
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="UPLOAD"
        badge-text="Gagal Mengunggah"
        badge-variant="danger"
        title="Gagal Mengunggah Berkas Dokumen Perjalanan"
        description="Berkas yang kamu kirimkan gagal terunggah ke sistem. Hal ini bisa terjadi karena koneksi terputus saat proses transfer data, ukuran file melebihi batas 2MB, atau format dokumen tidak didukung."
        illustration-src="/images/illustrations/illustration-upload-failed.svg"
        illustration-alt="Ilustrasi Upload Gagal File Terlepas dari Awan"
        primary-action-label="Pilih File Ulang & Coba Lagi"
        primary-action-icon="upload"
        secondary-action-label="Kirim Berkas via WhatsApp"
        secondary-action-icon="chat"
        secondary-action-variant="secondary"
        support-help-text="Ukuran foto terlalu besar dari kamera ponsel?"
        @primary-action="triggerFileSelect"
        @secondary-action="() => window.open('https://wa.me/6281234567890?text=Halo%20Jejak%20Nusa%2C%20saya%20gagal%20upload%20berkas%20lewat%20website%2C%20bisa%20bantu%20terima%20dokumen%20lewat%20WhatsApp%3F')"
      >
        <template #details>
          <div class="p-4 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED] flex flex-col gap-3">
            <input
              ref="fileInputRef"
              type="file"
              accept=".jpg,.jpeg,.png,.pdf"
              class="hidden"
              @change="handleFileChange"
            />

            <div v-if="retrySuccess" class="p-3 rounded-lg bg-[#E6F6F7] border border-[#2EB67D] text-xs font-semibold text-[#0B7A85] flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] text-[#2EB67D]">check_circle</span>
              <span>Berkas berhasil dipilih ulang dan siap diproses!</span>
            </div>

            <!-- Syarat Format Dokumen -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs font-inter text-[#4B5563]">
              <div class="p-2.5 rounded-lg bg-[#F3F6F8] border border-[#E5E9ED]">
                <span class="font-bold block text-[#1F2937]">Format Didukung</span>
                <span class="text-[#6B7280]">JPG, PNG, atau PDF</span>
              </div>
              <div class="p-2.5 rounded-lg bg-[#F3F6F8] border border-[#E5E9ED]">
                <span class="font-bold block text-[#1F2937]">Batas Ukuran</span>
                <span class="text-[#6B7280]">Maksimal 2.0 MB per file</span>
              </div>
              <div class="p-2.5 rounded-lg bg-[#F3F6F8] border border-[#E5E9ED]">
                <span class="font-bold block text-[#1F2937]">Koneksi Stabil</span>
                <span class="text-[#6B7280]">Hindari keluar aplikasi saat proses</span>
              </div>
            </div>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
