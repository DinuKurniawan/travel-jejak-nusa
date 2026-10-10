<script setup>
import { ref } from 'vue';
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);

const selectedFile = ref(null);
const uploadStatus = ref(null);

const onFileSelected = (e) => {
  const files = e.target.files;
  if (files && files[0]) {
    selectedFile.value = files[0];
  }
};

const handleUploadNew = () => {
  if (!selectedFile.value) return;
  uploadStatus.value = 'uploading';
  setTimeout(() => {
    uploadStatus.value = 'success';
  }, 1000);
};
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="DITOLAK"
        badge-text="Verifikasi Pembayaran"
        badge-variant="danger"
        title="Bukti Pembayaran Belum Dapat Kami Verifikasi"
        description="Tim keuangan Jejak Nusa belum dapat memvalidasi struk/bukti transfer yang kamu unggah. Hal ini biasanya dikarenakan foto buram/terpotong, nominal transfer tidak sesuai kode unik, atau nama pemilik rekening berbeda."
        illustration-src="/images/illustrations/illustration-payment-rejected.svg"
        illustration-alt="Ilustrasi Bukti Bayar Ditolak"
        primary-action-label="Unggah Ulang Bukti Transfer"
        primary-action-icon="upload_file"
        secondary-action-label="Hubungi Bagian Keuangan"
        secondary-action-icon="chat"
        secondary-action-variant="secondary"
        support-help-text="Ada kendala transaksi mutasi bank?"
        @primary-action="() => {}"
        @secondary-action="() => window.open('https://wa.me/6281234567890?text=Halo%20Keuangan%20Jejak%20Nusa%2C%20saya%20ingin%20konfirmasi%20bukti%20transfer%20yang%20ditolak')"
      >
        <template #details>
          <div class="p-4 sm:p-5 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED] flex flex-col gap-4">
            <!-- Alasan Penolakan Umum -->
            <div class="p-3 rounded-lg bg-[#FEECEE] border border-[#E5484D] text-xs font-inter text-[#E5484D]">
              <span class="font-bold block mb-1">Catatan Validator:</span>
              <p>Struk mutasi tidak menampilkan tanggal transaksi atau nominal tidak cocok dengan invoice. Harap unggah tangkapan layar m-Banking asli atau struk ATM utuh.</p>
            </div>

            <!-- Upload Box Pengganti -->
            <div>
              <label class="block text-xs font-bold text-[#1F2937] mb-2">Unggah Berkas Struk Baru:</label>
              <div v-if="uploadStatus === 'success'" class="p-3 rounded-lg bg-[#E6F6F7] border border-[#2EB67D] text-xs font-semibold text-[#0B7A85] flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-[#2EB67D]">check_circle</span>
                <span>Bukti transfer baru berhasil dikirim! Tim finance akan memeriksa ulang dalam 15 menit.</span>
              </div>
              <div v-else class="flex flex-col sm:flex-row gap-3">
                <input
                  type="file"
                  accept="image/png,image/jpeg,application/pdf"
                  class="flex-1 text-xs text-[#4B5563] file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#E6F6F7] file:text-[#0E9AA7] hover:file:bg-[#D0F0F3]"
                  @change="onFileSelected"
                />
                <button
                  type="button"
                  :disabled="!selectedFile"
                  class="h-10 px-4 rounded-lg bg-[#F4743B] hover:bg-[#DE5E26] disabled:opacity-50 text-white font-inter text-xs font-semibold transition-colors cursor-pointer"
                  @click="handleUploadNew"
                >
                  Kirim Ulang
                </button>
              </div>
            </div>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
