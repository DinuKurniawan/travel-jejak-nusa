<script setup>
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="EXPIRED"
        badge-text="Waktu Pembayaran Habis"
        badge-variant="danger"
        title="Batas Waktu Pembayaran Booking Telah Habis"
        description="Batas waktu transfer untuk kode pesanan ini telah melewati jendela pembayaran (24 jam). Sistem reservasi otomatis mengembalikan kursi ke publik agar adil bagi penjelajah lainnya."
        illustration-src="/images/illustrations/illustration-booking-expired.svg"
        illustration-alt="Ilustrasi Jam Pasir Booking Kedaluwarsa"
        primary-action-label="Buat Pemesanan Tiket Baru"
        primary-action-icon="restart_alt"
        secondary-action-label="Eksplor Paket Lainnya"
        secondary-action-icon="explore"
        secondary-action-variant="outline"
        support-help-text="Sudah terlanjur transfer namun booking terlanjur kedaluwarsa?"
        @primary-action="emit('navigate', 'paket-wisata')"
        @secondary-action="emit('navigate', 'paket-wisata')"
      >
        <template #details>
          <div class="p-4 rounded-lg bg-[#FEF9E7] border border-[#F6C945] text-xs font-inter text-[#755B00] flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-[#F5A524] mt-0.5">priority_high</span>
            <div>
              <p class="font-bold">Sudah Melakukan Pembayaran Sebelum Waktu Habis?</p>
              <p class="mt-1 text-[#584400]">
                Jika kamu sudah menyelesaikan transfer bank atau QRIS tetapi status pesanan berubah menjadi kedaluwarsa, jangan panik! Dana kamu 100% aman. Kirimkan bukti mutasi ke tim keuangan kami untuk pengaktifan kursi manual atau pengembalian dana utuh.
              </p>
            </div>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
