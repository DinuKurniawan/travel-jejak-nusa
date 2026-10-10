<script setup>
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="VALIDATION"
        badge-text="Koreksi Isian Formulir"
        badge-variant="danger"
        title="Formulir Belum Dapat Kami Proses"
        description="Masih terdapat beberapa data isian yang belum lengkap atau format kontak yang tidak sesuai standar (misalnya format nomor telepon atau alamat email). Silakan lengkapi kolom yang ditandai merah lalu kirimkan kembali."
        illustration-src="/images/illustrations/illustration-form-error.svg"
        illustration-alt="Ilustrasi Form Error Amplop Tertahan"
        primary-action-label="Periksa & Koreksi Formulir"
        primary-action-icon="edit_note"
        secondary-action-label="Kirim Pesan Cepat via WhatsApp"
        secondary-action-icon="chat"
        secondary-action-variant="secondary"
        support-help-text="Kesulitan mengisi form di perangkat seluler?"
        @primary-action="emit('navigate', 'kontak')"
        @secondary-action="() => window.open('https://wa.me/6281234567890?text=Halo%20Jejak%20Nusa%2C%20saya%20kesulitan%20mengirim%20form%20di%20web%2C%20bisa%20bantu%20langsung%20lewat%20chat%3F')"
      >
        <template #details>
          <div class="p-4 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED]">
            <span class="block text-xs font-bold text-[#1F2937] mb-2">Hal-hal yang Perlu Diperiksa:</span>
            <ul class="space-y-1.5 text-xs font-inter text-[#4B5563]">
              <li class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-[#E5484D]">cancel</span>
                <span>Pastikan <strong>nomor WhatsApp</strong> aktif dan diawali angka 08 atau +62.</span>
              </li>
              <li class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-[#E5484D]">cancel</span>
                <span>Pastikan <strong>alamat email</strong> memiliki format valid (contoh: nama@domain.com).</span>
              </li>
              <li class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-[#E5484D]">cancel</span>
                <span>Jumlah peserta minimal 1 orang dan tanggal keberangkatan tidak boleh tanggal lampau.</span>
              </li>
            </ul>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
