<script setup>
import { ref } from 'vue';
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);

const isTesting = ref(false);
const testResult = ref(null);

const testConnection = async () => {
  isTesting.value = true;
  testResult.value = null;
  try {
    const res = await fetch('/api/public/site-settings', { method: 'HEAD', cache: 'no-store' });
    if (res.ok || res.status < 500) {
      testResult.value = 'online';
      setTimeout(() => {
        emit('navigate', 'beranda');
      }, 800);
    } else {
      testResult.value = 'offline';
    }
  } catch {
    testResult.value = 'offline';
  } finally {
    isTesting.value = false;
  }
};
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="OFFLINE"
        badge-text="Sinyal Hilang di Samudra"
        badge-variant="danger"
        title="Koneksi Internetmu Terputus di Tengah Samudra"
        description="Perangkat kamu tidak terhubung ke jaringan internet saat ini. Pastikan Wi-Fi atau paket data selulermu menyala agar kita bisa kembali menjelajahi keindahan kepulauan Indonesia."
        illustration-src="/images/illustrations/illustration-offline.svg"
        illustration-alt="Ilustrasi Offline WiFi Ombak Terputus"
        primary-action-label="Tes Sambungan Kembali"
        primary-action-icon="wifi_find"
        secondary-action-label="Kembali ke Beranda (Cache)"
        secondary-action-icon="home"
        secondary-action-variant="outline"
        support-help-text="Butuh panduan destinasi saat sinyal minim?"
        @primary-action="testConnection"
        @secondary-action="emit('navigate', 'beranda')"
      >
        <template #details>
          <div class="flex flex-col gap-3">
            <!-- Test feedback banner -->
            <div
              v-if="testResult === 'online'"
              class="p-3 rounded-lg bg-[#E6F6F7] border border-[#2EB67D] text-xs font-semibold text-[#0B7A85] flex items-center gap-2"
            >
              <span class="material-symbols-outlined text-[18px] text-[#2EB67D]">check_circle</span>
              <span>Koneksi berhasil terhubung kembali! Mengalihkan ke beranda...</span>
            </div>
            <div
              v-else-if="testResult === 'offline'"
              class="p-3 rounded-lg bg-[#FEECEE] border border-[#E5484D] text-xs font-semibold text-[#E5484D] flex items-center gap-2"
            >
              <span class="material-symbols-outlined text-[18px]">wifi_off</span>
              <span>Perangkat masih dalam status offline. Silakan periksa kembali jaringanmu.</span>
            </div>

            <!-- Petunjuk Langkah Praktis -->
            <div class="p-4 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED] text-xs font-inter text-[#4B5563]">
              <span class="font-bold text-[#1F2937] block mb-1">Panduan Mengatasi Sinyal Lemah:</span>
              <ul class="list-disc list-inside space-y-1 text-[#6B7280]">
                <li>Nyalakan dan matikan <strong>Mode Pesawat (Airplane Mode)</strong> selama 5 detik.</li>
                <li>Pastikan kuota internet lokal aktif atau beralih ke jaringan Wi-Fi sekitar.</li>
                <li>Bila berada di atas kapal atau pulau terpencil, simpan tangkapan layar (screenshot) kode reservasi kamu.</li>
              </ul>
            </div>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
