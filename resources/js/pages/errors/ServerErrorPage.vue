<script setup>
import { ref } from 'vue';
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);

const isRetrying = ref(false);
const showDebugInfo = ref(false);

const handleReload = () => {
  isRetrying.value = true;
  setTimeout(() => {
    window.location.reload();
  }, 500);
};
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="500"
        badge-text="Gelombang Server"
        badge-variant="danger"
        title="Kendala Teknis Mesin Kapal di Tengah Samudra"
        description="Maaf atas ketidaknyamanan ini! Sistem kami sedang menghadapi hambatan komunikasi tak terduga. Tim teknisi Jejak Nusa telah menerima laporan otomatis ini dan sedang memperbaikinya dengan seksama."
        illustration-src="/images/illustrations/illustration-500.svg"
        illustration-alt="Ilustrasi 500 Pinisi Diterpa Ombak Badai"
        primary-action-label="Muat Ulang Halaman"
        primary-action-icon="refresh"
        secondary-action-label="Kembali ke Beranda"
        secondary-action-icon="home"
        secondary-action-variant="outline"
        support-help-text="Kendala masih berlanjut? Laporkan ke CS Jejak Nusa:"
        extra-details="ERR_INTERNAL_SERVER_EXCEPTION: Connection timeout or unexpected payload at trip dispatcher."
        @primary-action="handleReload"
        @secondary-action="emit('navigate', 'beranda')"
      >
        <template #details>
          <div class="p-4 rounded-lg bg-[#FEF9E7] border border-[#F6C945] text-xs font-inter text-[#755B00] flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-[#F5A524] mt-0.5">lightbulb</span>
            <div>
              <p class="font-bold">Tips Cepat Menyelesaikan Masalah:</p>
              <ul class="list-disc list-inside mt-1 space-y-0.5 text-[#584400]">
                <li>Tunggu sekitar 10-30 detik lalu tekan tombol <strong>Muat Ulang Halaman</strong>.</li>
                <li>Bersihkan cache penjelajah atau coba gunakan mode penyamaran (*incognito*).</li>
                <li>Jika kamu sedang melakukan pembayaran, dana kamu aman! Hubungi kami untuk cek status manual.</li>
              </ul>
            </div>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
