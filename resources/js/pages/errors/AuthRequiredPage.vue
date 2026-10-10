<script setup>
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="LOGIN"
        badge-text="Koper Terkunci"
        badge-variant="teal"
        title="Buka Koper Akses Petualanganmu"
        description="Fitur ini memerlukan identitas akun penjelajah Jejak Nusa. Masuk untuk mengelola tiket pemesanan, melihat mutasi pembayaran, serta menyimpan paket wisata impian ke dalam wishlist pribadimu."
        illustration-src="/images/illustrations/illustration-auth-required.svg"
        illustration-alt="Ilustrasi Perlu Login Koper dan Kunci"
        primary-action-label="Masuk ke Akun Sekarang"
        primary-action-icon="login"
        secondary-action-label="Kembali ke Beranda"
        secondary-action-icon="home"
        secondary-action-variant="outline"
        support-help-text="Belum punya akun atau lupa kata sandi?"
        @primary-action="emit('navigate', 'login')"
        @secondary-action="emit('navigate', 'beranda')"
      >
        <template #details>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="p-3.5 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED] flex flex-col items-start gap-1">
              <span class="material-symbols-outlined text-[20px] text-[#0E9AA7]">confirmation_number</span>
              <p class="font-bold text-xs text-[#1F2937]">E-Tiket & Voucher</p>
              <p class="text-[11px] text-[#6B7280]">Akses mudah bukti booking digital tanpa repot cetak.</p>
            </div>
            <div class="p-3.5 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED] flex flex-col items-start gap-1">
              <span class="material-symbols-outlined text-[20px] text-[#F4743B]">favorite</span>
              <p class="font-bold text-xs text-[#1F2937]">Katalog Wishlist</p>
              <p class="text-[11px] text-[#6B7280]">Simpan destinasi favorit dan pantau promo keberangkatan.</p>
            </div>
            <div class="p-3.5 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED] flex flex-col items-start gap-1">
              <span class="material-symbols-outlined text-[20px] text-[#2EB67D]">verified_user</span>
              <p class="font-bold text-xs text-[#1F2937]">Garansi & Asuransi</p>
              <p class="text-[11px] text-[#6B7280]">Klaim asuransi perjalanan langsung terhubung identitas resmi.</p>
            </div>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
