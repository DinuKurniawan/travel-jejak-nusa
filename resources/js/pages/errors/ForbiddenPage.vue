<script setup>
import ErrorStateCard from '../../components/ErrorStateCard.vue';

const emit = defineEmits(['navigate']);
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <ErrorStateCard
        code="403"
        badge-text="Akses Terkunci"
        badge-variant="ink"
        title="Gerbang Dermaga Tertutup Khusus Pengelola"
        description="Kamu mencoba mengakses rute atau berkas yang membutuhkan hak izin istimewa (Role Administrator). Jika kamu adalah tim operasional Jejak Nusa, silakan masuk menggunakan akun resmi pengelola."
        illustration-src="/images/illustrations/illustration-403.svg"
        illustration-alt="Ilustrasi 403 Gerbang Adat Terkunci"
        primary-action-label="Masuk Akun Pengelola"
        primary-action-icon="admin_panel_settings"
        secondary-action-label="Kembali ke Beranda"
        secondary-action-icon="home"
        secondary-action-variant="outline"
        support-help-text="Merasa ini kekeliruan hak akses akunmu?"
        @primary-action="emit('navigate', 'login')"
        @secondary-action="emit('navigate', 'beranda')"
      >
        <template #details>
          <div class="p-4 rounded-lg bg-[#FFFFFF] border border-[#E5E9ED] text-xs font-inter text-[#4B5563] flex flex-col gap-2">
            <span class="font-bold text-[#1F2937]">Informasi Keamanan:</span>
            <p>
              Halaman operasional reservasi, validasi tiket keuangan, dan modul dashboard internal dilindungi enkripsi multi-faktor.
            </p>
          </div>
        </template>
      </ErrorStateCard>
    </div>
  </div>
</template>
