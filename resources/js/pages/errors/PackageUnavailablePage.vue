<script setup>
import { ref } from 'vue';
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);

const emailOrWa = ref('');
const isSubscribed = ref(false);

const handleWaitlist = () => {
  if (!emailOrWa.value.trim()) return;
  isSubscribed.value = true;
};
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="PENUH"
        badge-text="Kuota Terpenuhi"
        badge-variant="yellow"
        title="Kuota Petualangan Paket Ini Sedang Habis Terpesan"
        description="Antusiasme penjelajah Nusantara sangat tinggi! Seluruh kursi untuk tanggal keberangkatan yang kamu pilih telah terisi penuh, atau jadwal trip ditutup sementara demi konservasi habitat kepulauan."
        illustration-src="/images/illustrations/illustration-package-unavailable.svg"
        illustration-alt="Ilustrasi Paket Tidak Tersedia Koper Kosong"
        primary-action-label="Lihat Jadwal Tanggal Lain"
        primary-action-icon="calendar_month"
        secondary-action-label="Konsultasi Private Tour"
        secondary-action-icon="diversity_3"
        secondary-action-variant="outline"
        support-help-text="Ingin request kuota rombongan keluarga atau kantor?"
        @primary-action="emit('navigate', 'paket-wisata')"
        @secondary-action="emit('navigate', 'kontak')"
      >
        <template #details>
          <div class="p-4 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED]">
            <span class="block text-xs font-bold text-[#1F2937] mb-1">
              Daftar Antrean Tiket (Waitlist Alert)
            </span>
            <p class="text-xs text-[#6B7280] mb-3">
              Masukkan nomor WhatsApp atau emailmu. Kami kabari seketika jika ada traveler yang membatalkan slot kursi atau saat kuota tambahan dibuka!
            </p>

            <div v-if="isSubscribed" class="p-3 rounded-lg bg-[#E6F6F7] border border-[#2EB67D] text-xs font-semibold text-[#0B7A85] flex items-center gap-2">
              <span class="material-symbols-outlined text-[18px] text-[#2EB67D]">check_circle</span>
              <span>Kamu telah terdaftar di antrean prioritas! Notifikasi akan dikirimkan otomatis.</span>
            </div>

            <form v-else class="flex flex-col sm:flex-row gap-2" @submit.prevent="handleWaitlist">
              <input
                v-model="emailOrWa"
                type="text"
                placeholder="Nomor WhatsApp (misal: 0812xxxx) atau Email"
                class="flex-1 h-11 px-3.5 bg-[#FFFFFF] border border-[#E5E9ED] rounded-lg text-sm text-[#1F2937] focus:outline-none focus:border-[#0E9AA7]"
                required
              />
              <button
                type="submit"
                class="h-11 px-5 rounded-lg bg-[#0E9AA7] hover:bg-[#0B7A85] text-[#FFFFFF] font-inter font-semibold text-xs transition-colors cursor-pointer"
              >
                Ingatkan Saya
              </button>
            </form>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
