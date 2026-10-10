<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);

const countdownSeconds = ref(180);
let timer = null;

onMounted(() => {
  timer = setInterval(() => {
    if (countdownSeconds.value > 0) {
      countdownSeconds.value -= 1;
    } else {
      window.location.reload();
    }
  }, 1000);
});

onUnmounted(() => {
  if (timer) clearInterval(timer);
});
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="503"
        badge-text="Dok Perawatan Sistem"
        badge-variant="yellow"
        title="Kapal Jejak Nusa Sedang Bersandar di Dok Perawatan"
        description="Kami sedang melakukan pemeliharaan berkala dan peningkatan performa sistem mesin reservasi agar petualanganmu berikutnya semakin cepat, aman, dan nyaman tanpa hambatan."
        illustration-src="/images/illustrations/illustration-maintenance.svg"
        illustration-alt="Ilustrasi Maintenance Kapal di Dok"
        primary-action-label="Cek Sambungan & Segarkan"
        primary-action-icon="refresh"
        secondary-action-label="Tanya Status via WhatsApp"
        secondary-action-icon="chat"
        secondary-action-variant="secondary"
        support-help-text="Butuh konsultasi pemesanan darurat saat dok berlangsung?"
        @primary-action="() => window.location.reload()"
        @secondary-action="() => window.open('https://wa.me/6281234567890?text=Halo%20Jejak%20Nusa%2C%20saya%20butuh%20info%20booking%20karena%20sistem%20sedang%20maintenance')"
      >
        <template #details>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Box 1: Estimasi Waktu Selesai -->
            <div class="p-4 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED]">
              <span class="block text-xs font-semibold uppercase tracking-wider text-[#0E9AA7]">Estimasi Siap Berlayar</span>
              <p class="font-epilogue font-bold text-lg text-[#1F2937] mt-1">Hari ini, ± 15-30 Menit</p>
              <p class="text-xs text-[#6B7280] mt-0.5">Auto-refresh dalam: <span class="font-bold text-[#F4743B]">{{ countdownSeconds }} detik</span></p>
            </div>

            <!-- Box 2: Jaminan Keamanan Data -->
            <div class="p-4 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED]">
              <span class="block text-xs font-semibold uppercase tracking-wider text-[#2EB67D]">Status Data Reservasi</span>
              <p class="font-inter font-bold text-sm text-[#1F2937] mt-1">100% Aman & Terenkripsi</p>
              <p class="text-xs text-[#6B7280] mt-0.5">Seluruh tiket & bukti pembayaran tidak terpengaruh.</p>
            </div>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
