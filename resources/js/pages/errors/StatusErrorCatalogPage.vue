<script setup>
const emit = defineEmits(['navigate']);

const errorStates = [
  {
    route: '404',
    code: '404',
    title: 'Halaman Tidak Ditemukan',
    desc: 'Tersesat di lautan informasi, tautan hilang atau rute salah.',
    badge: 'Tersesat di Samudra',
    badgeVariant: 'teal',
    icon: 'explore_off',
    illustration: '/images/illustrations/illustration-404.svg',
  },
  {
    route: '500',
    code: '500',
    title: 'Kesalahan Server Internal',
    desc: 'Mesin server menghadapi kendala teknis tak terduga.',
    badge: 'Gelombang Server',
    badgeVariant: 'danger',
    icon: 'warning',
    illustration: '/images/illustrations/illustration-500.svg',
  },
  {
    route: '403',
    code: '403',
    title: 'Akses Ditolak (Forbidden)',
    desc: 'Area dermaga khusus pengelola dengan hak akses terbatas.',
    badge: 'Akses Terkunci',
    badgeVariant: 'ink',
    icon: 'lock',
    illustration: '/images/illustrations/illustration-403.svg',
  },
  {
    route: 'maintenance',
    code: '503',
    title: 'Pemeliharaan Sistem (Dok)',
    desc: 'Kapal bersandar di dok perawatan rutin & peningkatan mesin.',
    badge: 'Dok Perawatan',
    badgeVariant: 'yellow',
    icon: 'build',
    illustration: '/images/illustrations/illustration-maintenance.svg',
  },
  {
    route: 'offline',
    code: 'OFFLINE',
    title: 'Kehilangan Sinyal / Internet',
    desc: 'Koneksi terputus di tengah samudra, panduan rekoneksi.',
    badge: 'Sinyal Hilang',
    badgeVariant: 'danger',
    icon: 'wifi_off',
    illustration: '/images/illustrations/illustration-offline.svg',
  },
  {
    route: 'perlu-login',
    code: 'AUTH',
    title: 'Perlu Login (Akses Akun)',
    desc: 'Koper akses terkunci, memerlukan login traveler.',
    badge: 'Buka Koper',
    badgeVariant: 'teal',
    icon: 'login',
    illustration: '/images/illustrations/illustration-auth-required.svg',
  },
  {
    route: 'paket-tidak-tersedia',
    code: 'HABIS',
    title: 'Paket / Kuota Tidak Tersedia',
    desc: 'Koper kosong di pantai, seluruh kuota trip telah penuh.',
    badge: 'Kuota Penuh',
    badgeVariant: 'yellow',
    icon: 'event_busy',
    illustration: '/images/illustrations/illustration-package-unavailable.svg',
  },
  {
    route: 'booking-kedaluwarsa',
    code: 'EXPIRED',
    title: 'Batas Waktu Bayar Habis',
    desc: 'Jam pasir waktu habis, invoice pembayaran kedaluwarsa.',
    badge: 'Batas Habis',
    badgeVariant: 'danger',
    icon: 'hourglass_disabled',
    illustration: '/images/illustrations/illustration-booking-expired.svg',
  },
  {
    route: 'bukti-bayar-ditolak',
    code: 'DITOLAK',
    title: 'Bukti Pembayaran Ditolak',
    desc: 'Struk transfer tidak valid, mutasi bank belum cocok.',
    badge: 'Verifikasi Ulang',
    badgeVariant: 'danger',
    icon: 'receipt_long',
    illustration: '/images/illustrations/illustration-payment-rejected.svg',
  },
  {
    route: 'upload-gagal',
    code: 'UPLOAD',
    title: 'Unggah Berkas Gagal',
    desc: 'Dokumen terputus dari awan, file melebihi 2MB atau timeout.',
    badge: 'Gagal Kirim',
    badgeVariant: 'danger',
    icon: 'upload_file',
    illustration: '/images/illustrations/illustration-upload-failed.svg',
  },
  {
    route: 'form-gagal',
    code: 'FORM',
    title: 'Formulir Gagal Terkirim',
    desc: 'Validasi form kontak/reservasi belum lengkap atau salah format.',
    badge: 'Cek Form',
    badgeVariant: 'danger',
    icon: 'edit_note',
    illustration: '/images/illustrations/illustration-form-error.svg',
  },
];
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin">
      <!-- Header Katalog Error -->
      <div class="bg-[#FFFFFF] border border-[#E5E9ED] rounded-xl p-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#E6F6F7] border border-[#0E9AA7] mb-3">
              <span class="material-symbols-outlined text-[16px] text-[#0E9AA7]">design_services</span>
              <span class="font-inter text-xs font-semibold text-[#0B7A85]">Google Stitch Design System • Flat 2.0</span>
            </div>
            <h1 class="font-epilogue font-bold text-2xl sm:text-3xl text-[#1F2937]">
              Katalog Status &amp; Layar Kendala Publik (Publik Error)
            </h1>
            <p class="font-inter text-sm sm:text-base text-[#6B7280] mt-2 max-w-2xl">
              Daftar seluruh 11 halaman penanganan kendala dan status sistem Jejak Nusa sesuai spesifikasi Google Stitch. Klik salah satu kartu untuk langsung menguji dan melihat tampilannya secara penuh.
            </p>
          </div>
          <button
            type="button"
            class="h-11 px-5 rounded-lg bg-[#0E9AA7] hover:bg-[#0B7A85] text-[#FFFFFF] font-inter font-semibold text-xs transition-colors cursor-pointer self-start md:self-center"
            @click="emit('navigate', 'beranda')"
          >
            ← Kembali ke Beranda
          </button>
        </div>
      </div>

      <!-- Grid 11 Halaman Error -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="err in errorStates"
          :key="err.route"
          class="bg-[#FFFFFF] border border-[#E5E9ED] rounded-xl p-5 flex flex-col justify-between hover:border-[#0E9AA7] transition-all cursor-pointer group"
          @click="emit('navigate', err.route)"
        >
          <div>
            <div class="w-full h-36 rounded-lg bg-[#F3F6F8] border border-[#E5E9ED] p-3 flex items-center justify-center mb-4 group-hover:bg-[#E6F6F7] transition-colors">
              <img
                :src="err.illustration"
                :alt="err.title"
                class="w-full h-full object-contain"
                loading="lazy"
              />
            </div>
            <div class="flex items-center justify-between gap-2 mb-2">
              <span class="px-2 py-0.5 rounded text-[11px] font-bold font-inter tracking-wide uppercase bg-[#F3F6F8] text-[#1F2937] border border-[#E5E9ED]">
                {{ err.code }}
              </span>
              <span class="text-xs font-semibold font-inter text-[#0E9AA7]">
                /{{ err.route }}
              </span>
            </div>
            <h3 class="font-epilogue font-bold text-base text-[#1F2937] group-hover:text-[#0E9AA7] transition-colors">
              {{ err.title }}
            </h3>
            <p class="font-inter text-xs text-[#6B7280] mt-1.5 leading-relaxed">
              {{ err.desc }}
            </p>
          </div>

          <div class="pt-4 mt-4 border-t border-[#E5E9ED] flex items-center justify-between text-xs font-inter font-semibold text-[#0E9AA7]">
            <span>Uji Layar Ini</span>
            <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
