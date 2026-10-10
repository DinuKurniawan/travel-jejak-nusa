<script setup>
import { computed } from 'vue';
import FaqAccordion from '../components/FaqAccordion.vue';
import CtaBanner from '../components/CtaBanner.vue';

const emit = defineEmits(['navigate']);

const navigate = (page) => {
  emit('navigate', page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const faqs = [
  {
    id: 0,
    question: 'Apakah uang muka (DP) dapat dikembalikan jika terjadi kendala tak terduga?',
    answer:
      'Kami memiliki kebijakan pembatalan ramah pelanggan. Pembatalan hingga H-14 sebelum jadwal keberangkatan dapat di-reschedule tanpa biaya administrasi tambahan, atau pengembalian dana hingga 80% menyesuaikan ketentuan mitra maskapai penerbangan dan hotel terkait.',
  },
  {
    id: 1,
    question: 'Bagaimana jika kuota minimal Open Trip belum terpenuhi mendekati hari H?',
    answer:
      'Semua trip berlabel "Garansi Pasti Berangkat" akan tetap berjalan berapapun jumlah pesertanya tanpa ada pungutan biaya tambahan sepihak, atau Anda bebas memilih opsi alih jadwal maupun destinasi lain secara cuma-cuma.',
  },
  {
    id: 2,
    question: 'Apakah saya bisa memesan menu makanan khusus (vegetarian/halal/alergi) selama tur?',
    answer:
      'Tentu saja. Seluruh katering mitra Jejak Nusa menyajikan menu halal, bersih, dan higienis. Anda cukup menyampaikan preferensi pantangan makanan seperti alergi seafood, pola makan vegan, atau vegetarian saat tahap pengisian formulir data rombongan.',
  },
];
</script>

<template>
  <div class="flex flex-col w-full">
    <!-- 1. PAGE HEADER (Solid Primary Teal Band with Flat Geometric Accent) -->
    <section class="w-full bg-primary text-on-primary relative overflow-hidden py-16 lg:py-20">
      <div class="max-w-[1200px] mx-auto px-margin flex flex-col items-start gap-space-md">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex items-center gap-space-xs text-on-primary/80 font-body-md text-body-md">
          <a
            class="hover:text-on-primary transition-colors underline-offset-4 hover:underline cursor-pointer"
            @click.prevent="navigate('beranda')"
          >Beranda</a>
          <span class="text-on-primary/50">/</span>
          <span aria-current="page" class="text-on-primary font-medium">Layanan</span>
        </nav>
        <!-- Main Title -->
        <h1 class="font-display-lg text-display-lg-mobile lg:text-display-lg text-on-primary tracking-tight max-w-3xl">
          Layanan Kami
        </h1>
        <!-- Subtitle -->
        <p class="font-body-lg text-body-lg text-on-primary/90 max-w-3xl leading-relaxed">
          Pilihan fleksibel penjelajahan Nusantara — mulai dari open trip berbiaya efisien, private trip eksklusif, hingga rancangan kustom sesuai ritme dan impian perjalanan Anda.
        </p>
      </div>
      <!-- Flat Geometric Bottom Divider -->
      <div class="w-full h-3 bg-tertiary-container mt-12 flex">
        <div class="w-1/3 h-full bg-secondary"></div>
        <div class="w-1/3 h-full bg-surface-container-highest"></div>
        <div class="w-1/3 h-full bg-secondary-container"></div>
      </div>
    </section>

    <!-- 2. SERVICE SECTIONS (Alternating Backgrounds & Layouts) -->
    <!-- SERVICE 1: OPEN TRIP -->
    <section class="w-full bg-surface-container-lowest py-16 lg:py-20 border-b border-[#E5E9ED]">
      <div class="max-w-[1200px] mx-auto px-margin grid grid-cols-1 lg:grid-cols-12 gap-space-xl lg:gap-gutter items-center">
        <!-- Image Left -->
        <div class="lg:col-span-6 w-full">
          <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-surface-container border border-[#E5E9ED]">
            <img
              alt="Open Trip Peserta di Kapal Phinisi"
              class="w-full h-full object-cover rounded-xl"
              src="/images/img-50.jpg"
            >
            <div class="absolute top-4 left-4 bg-primary text-on-primary px-3 py-1.5 rounded-lg font-label-sm text-label-sm uppercase tracking-wider">
              Jadwal Mingguan
            </div>
          </div>
        </div>
        <!-- Text Right -->
        <div class="lg:col-span-6 flex flex-col items-start gap-space-md">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-primary-fixed text-primary font-label-md text-label-md border border-primary/20">
            Paling Hemat &amp; Seru
          </div>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface tracking-tight">
            Open Trip Nusantara
          </h2>
          <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
            Bergabung bersama sesama penjelajah dengan jadwal keberangkatan pasti setiap akhir pekan dan musim liburan. Solusi sempurna untuk solo traveler atau kelompok kecil yang ingin menjelajah destinasi impian tanpa khawatir biaya sewa transportasi mandiri yang tinggi.
          </p>
          <ul class="flex flex-col gap-space-sm w-full pt-space-xs">
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Pasti Berangkat tanpa kuota minimal tersembunyi</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Didampingi pemandu lokal berlisensi resmi HPI</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Akomodasi standar bersih &amp; armada transportasi nyaman</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Dokumentasi foto &amp; video perjalanan sudah termasuk</span>
            </li>
          </ul>
          <div class="pt-space-sm">
            <a
              class="inline-flex items-center justify-center h-12 px-6 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors cursor-pointer"
              @click.prevent="navigate('paket-wisata')"
            >
              Lihat Jadwal Open Trip
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICE 2: PRIVATE TRIP -->
    <section class="w-full bg-surface-container-low py-16 lg:py-20 border-b border-[#E5E9ED]">
      <div class="max-w-[1200px] mx-auto px-margin grid grid-cols-1 lg:grid-cols-12 gap-space-xl lg:gap-gutter items-center">
        <!-- Text Left -->
        <div class="lg:col-span-6 flex flex-col items-start gap-space-md order-2 lg:order-1">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-md text-label-md border border-secondary/20">
            Eksklusif &amp; Personal
          </div>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface tracking-tight">
            Private Trip Eksklusif
          </h2>
          <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
            Nikmati kebebasan berlibur privat hanya bersama pasangan, keluarga inti, atau sahabat terdekat. Anda bebas memilih tanggal keberangkatan dan durasi wisata dengan ritme santai tanpa interupsi kelompok lain.
          </p>
          <ul class="flex flex-col gap-space-sm w-full pt-space-xs">
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Tanggal dan durasi keberangkatan bebas Anda tentukan</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Kendaraan pribadi eksklusif (tidak digabung peserta lain)</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Pilihan resort tepi pantai bintang 4 atau kabin privat kapal Phinisi</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Pemandu wisata dan pengemudi pribadi bersertifikat resmi</span>
            </li>
          </ul>
          <div class="pt-space-sm">
            <a
              class="inline-flex items-center justify-center h-12 px-6 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors cursor-pointer"
              @click.prevent="navigate('kontak')"
            >
              Rencanakan Private Trip
            </a>
          </div>
        </div>
        <!-- Image Right -->
        <div class="lg:col-span-6 w-full order-1 lg:order-2">
          <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-surface-container border border-[#E5E9ED]">
            <img
              alt="Private Trip Eksklusif Raja Ampat"
              class="w-full h-full object-cover rounded-xl"
              src="/images/img-51.jpg"
            >
            <div class="absolute top-4 right-4 bg-surface-container-lowest text-on-surface px-3 py-1.5 rounded-lg font-label-sm text-label-sm border border-[#E5E9ED]">
              Privasi 100% Terjaga
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICE 3: PAKET KELUARGA & KOMUNITAS -->
    <section class="w-full bg-surface-container-lowest py-16 lg:py-20 border-b border-[#E5E9ED]">
      <div class="max-w-[1200px] mx-auto px-margin grid grid-cols-1 lg:grid-cols-12 gap-space-xl lg:gap-gutter items-center">
        <!-- Image Left -->
        <div class="lg:col-span-6 w-full">
          <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-surface-container border border-[#E5E9ED]">
            <img
              alt="Paket Keluarga dan Komunitas Wisata Nusantara"
              class="w-full h-full object-cover rounded-xl"
              src="/images/img-52.jpg"
            >
            <div class="absolute top-4 left-4 bg-tertiary-fixed text-on-tertiary-fixed px-3 py-1.5 rounded-lg font-label-sm text-label-sm border border-tertiary/20">
              Akses Kursi Roda &amp; Stroller Siaga
            </div>
          </div>
        </div>
        <!-- Text Right -->
        <div class="lg:col-span-6 flex flex-col items-start gap-space-md">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-md text-label-md border border-tertiary/20">
            Ramah Segala Usia
          </div>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface tracking-tight">
            Paket Keluarga &amp; Komunitas
          </h2>
          <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
            Program wisata tematik yang dirancang khusus untuk kenyamanan anak-anak, lansia, maupun rombongan reuni keluarga dan perkumpulan komunitas. Jalur aman, fasilitas teruji, dan tempo aktivitas yang seimbang antara rekreasi dan istirahat santai.
          </p>
          <ul class="flex flex-col gap-space-sm w-full pt-space-xs">
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Itinerary bersahabat bagi lansia dan anak-anak (konsep slow travel)</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Pilihan menu makanan disesuaikan dengan kebutuhan diet &amp; selera anak</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Kamar hotel berdampingan (connecting rooms) terjamin konfirmasi awal</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Fasilitas medis darurat &amp; pendamping operasional siaga 24 jam</span>
            </li>
          </ul>
          <div class="pt-space-sm">
            <a
              class="inline-flex items-center justify-center h-12 px-6 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors cursor-pointer"
              @click.prevent="navigate('kontak')"
            >
              Konsultasi Trip Keluarga
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICE 4: CUSTOM & OUTING -->
    <section class="w-full bg-surface-container-low py-16 lg:py-20 border-b border-[#E5E9ED]">
      <div class="max-w-[1200px] mx-auto px-margin grid grid-cols-1 lg:grid-cols-12 gap-space-xl lg:gap-gutter items-center">
        <!-- Text Left -->
        <div class="lg:col-span-6 flex flex-col items-start gap-space-md order-2 lg:order-1">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-surface-container-highest text-on-surface font-label-md text-label-md border border-[#E5E9ED]">
            100% Fleksibel
          </div>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface tracking-tight">
            Custom &amp; Corporate Outing
          </h2>
          <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
            Rancang penjelajahan impian dari nol sesuai preferensi destinasi, aktivitas riset, team building perusahaan, fotografi lanskap, hingga diving ekspedisi terpencil. Tim perencana kami menerjemahkan setiap visi ke dalam proposal transparan.
          </p>
          <ul class="flex flex-col gap-space-sm w-full pt-space-xs">
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Rute dan aktivitas bebas kustom hingga pulau terpencil nusantara</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Fasilitator team building berlisensi &amp; modul kegiatan outbound teruji</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Faktur pajak resmi dan administrasi pertanggungjawaban korporasi lengkap</span>
            </li>
            <li class="flex items-start gap-space-sm text-on-surface font-body-md text-body-md">
              <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 0, 'wght' 600;">check_circle</span>
              <span>Dedikasi 1 designated Tour Manager selama perjalanan di lapangan</span>
            </li>
          </ul>
          <div class="pt-space-sm">
            <a
              class="inline-flex items-center justify-center h-12 px-6 rounded-lg bg-secondary text-on-secondary font-label-md text-label-md hover:bg-on-secondary-fixed-variant transition-colors cursor-pointer"
              @click.prevent="navigate('kontak')"
            >
              Rancang Trip Kustom
            </a>
          </div>
        </div>
        <!-- Image Right -->
        <div class="lg:col-span-6 w-full order-1 lg:order-2">
          <div class="relative w-full aspect-[4/3] rounded-xl overflow-hidden bg-surface-container border border-[#E5E9ED]">
            <img
              alt="Custom Expedition dan Corporate Outing"
              class="w-full h-full object-cover rounded-xl"
              src="/images/img-53.jpg"
            >
            <div class="absolute bottom-4 left-4 bg-on-surface text-surface-container-lowest px-3 py-1.5 rounded-lg font-label-sm text-label-sm">
              Kapasitas 1 hingga 250+ Peserta
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. PERBANDINGAN LAYANAN (Flat Comparison Table) -->
    <section class="w-full bg-surface-container-lowest py-16 lg:py-24 border-b border-[#E5E9ED]">
      <div class="max-w-[1200px] mx-auto px-margin">
        <!-- Section Header -->
        <div class="flex flex-col items-center text-center max-w-2xl mx-auto gap-space-xs mb-12">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-surface-container text-primary font-label-md text-label-md border border-[#E5E9ED]">
            Komparasi Cepat
          </div>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface tracking-tight">
            Perbandingan Detail Layanan
          </h2>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Pilih skema penjelajahan yang paling selaras dengan gaya bepergian, jadwal libur, dan anggaran Anda.
          </p>
        </div>
        <!-- Table -->
        <div class="w-full overflow-x-auto rounded-xl border border-[#E5E9ED]">
          <table class="w-full min-w-[760px] text-left border-collapse bg-surface-container-lowest">
            <thead>
              <tr class="border-b border-[#E5E9ED] bg-surface-container-low text-on-surface">
                <th class="p-4 lg:p-5 font-title-lg text-title-lg text-on-surface w-[22%]" scope="col">Kriteria / Fitur</th>
                <th class="p-4 lg:p-5 font-title-lg text-title-lg text-on-surface w-[19%]" scope="col">Open Trip</th>
                <th class="p-4 lg:p-5 bg-primary text-on-primary w-[23%] relative" scope="col">
                  <div class="flex items-center justify-between gap-2">
                    <span class="font-title-lg text-title-lg text-on-primary">Private Trip</span>
                    <span class="bg-secondary text-on-secondary text-[11px] font-semibold px-2 py-0.5 rounded uppercase tracking-wider">Paling Diminati</span>
                  </div>
                </th>
                <th class="p-4 lg:p-5 font-title-lg text-title-lg text-on-surface w-[18%]" scope="col">Paket Keluarga</th>
                <th class="p-4 lg:p-5 font-title-lg text-title-lg text-on-surface w-[18%]" scope="col">Custom &amp; Outing</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E9ED] font-body-md text-body-md text-on-surface-variant">
              <!-- Row 1 -->
              <tr>
                <td class="p-4 lg:p-5 font-medium text-on-surface bg-surface-container-low/50">Jumlah Peserta</td>
                <td class="p-4 lg:p-5">1 orang pun bisa<br><span class="text-xs text-on-surface-variant">(gabung grup 8–15 pax)</span></td>
                <td class="p-4 lg:p-5 bg-primary/5 font-medium text-on-surface border-x border-primary/20">
                  Mulai dari 2 orang<br><span class="text-xs text-primary font-normal">(hanya grup Anda sendiri)</span>
                </td>
                <td class="p-4 lg:p-5">Min. 6 – 30 orang rombongan</td>
                <td class="p-4 lg:p-5">Fleksibel penuh<br><span class="text-xs text-on-surface-variant">(1 hingga 250+ pax)</span></td>
              </tr>
              <!-- Row 2 -->
              <tr>
                <td class="p-4 lg:p-5 font-medium text-on-surface bg-surface-container-low/50">Jadwal Keberangkatan</td>
                <td class="p-4 lg:p-5">Jadwal tetap<br><span class="text-xs text-on-surface-variant">(Weekend &amp; Libur Nasional)</span></td>
                <td class="p-4 lg:p-5 bg-primary/5 font-medium text-on-surface border-x border-primary/20">
                  Bebas kapan saja<br><span class="text-xs text-primary font-normal">(Tersedia setiap hari)</span>
                </td>
                <td class="p-4 lg:p-5">Bebas disepakati keluarga</td>
                <td class="p-4 lg:p-5">Bebas &amp; disinkronkan kalender kantor</td>
              </tr>
              <!-- Row 3 -->
              <tr>
                <td class="p-4 lg:p-5 font-medium text-on-surface bg-surface-container-low/50">Estimasi Biaya</td>
                <td class="p-4 lg:p-5">
                  <span class="text-primary font-semibold">Mulai Rp 950.000</span><br>
                  <span class="text-xs text-on-surface-variant">/ orang (Paling hemat)</span>
                </td>
                <td class="p-4 lg:p-5 bg-primary/5 border-x border-primary/20">
                  <span class="text-primary font-semibold">Mulai Rp 2.450.000</span><br>
                  <span class="text-xs text-on-surface-variant">/ orang (Fasilitas VIP)</span>
                </td>
                <td class="p-4 lg:p-5">
                  <span class="text-on-surface font-semibold">Mulai Rp 1.850.000</span><br>
                  <span class="text-xs text-on-surface-variant">/ orang (Skema diskon grup)</span>
                </td>
                <td class="p-4 lg:p-5">Sesuai pagu anggaran &amp; fasilitas yang dipilih</td>
              </tr>
              <!-- Row 4 -->
              <tr>
                <td class="p-4 lg:p-5 font-medium text-on-surface bg-surface-container-low/50">Fleksibilitas Rute</td>
                <td class="p-4 lg:p-5">Tetap sesuai susunan terkurasi</td>
                <td class="p-4 lg:p-5 bg-primary/5 font-medium text-on-surface border-x border-primary/20">
                  Fleksibel santai<br><span class="text-xs text-primary font-normal">(Bisa ubah durasi di spot)</span>
                </td>
                <td class="p-4 lg:p-5">Disesuaikan stamina lansia &amp; anak</td>
                <td class="p-4 lg:p-5">100% kustom dari titik nol</td>
              </tr>
              <!-- Row 5 -->
              <tr>
                <td class="p-4 lg:p-5 font-medium text-on-surface bg-surface-container-low/50">Paling Cocok Untuk</td>
                <td class="p-4 lg:p-5">Solo traveler, pasangan muda, pecinta teman baru</td>
                <td class="p-4 lg:p-5 bg-primary/5 font-medium text-on-surface border-x border-primary/20">
                  Honeymoon romantis, keluarga inti, relaksasi tenang
                </td>
                <td class="p-4 lg:p-5">Keluarga besar 3 generasi, reuni, arisan keluarga</td>
                <td class="p-4 lg:p-5">Perusahaan, instansi riset, komunitas hobi khusus</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- 4. PROSES KERJA (Horizontal 4-Step Stepper) -->
    <section class="w-full bg-surface-container-low py-16 lg:py-24 border-b border-[#E5E9ED]">
      <div class="max-w-[1200px] mx-auto px-margin">
        <!-- Header -->
        <div class="flex flex-col items-center text-center max-w-2xl mx-auto gap-space-xs mb-14">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-surface-container text-primary font-label-md text-label-md border border-[#E5E9ED]">
            Langkah Mudah
          </div>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface tracking-tight">
            4 Langkah Memulai Petualangan Anda
          </h2>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Dari obrolan ide sederhana hingga kepulangan membawa koper penuh memori indah nusantara.
          </p>
        </div>
        <!-- Stepper Grid -->
        <div class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-lg">
          <!-- Step 1 -->
          <div class="bg-surface-container-lowest p-6 rounded-xl border border-[#E5E9ED] flex flex-col items-start gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary text-on-primary flex items-center justify-center font-bold text-[20px]">01</div>
            <h3 class="font-title-lg text-title-lg text-on-surface font-semibold pt-1">Konsultasi Bebas</h3>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              Sampaikan destinasi impian, perkiraan tanggal libur, dan kebutuhan khusus rombongan Anda kepada trip planner kami secara gratis.
            </p>
          </div>
          <!-- Step 2 -->
          <div class="bg-surface-container-lowest p-6 rounded-xl border border-[#E5E9ED] flex flex-col items-start gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary text-on-primary flex items-center justify-center font-bold text-[20px]">02</div>
            <h3 class="font-title-lg text-title-lg text-on-surface font-semibold pt-1">Proposal Penawaran</h3>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              Kami kirimkan lembar proposal rute harian detail, opsi hotel/armada terverifikasi, dan transparansi kalkulasi biaya tanpa markup rahasia.
            </p>
          </div>
          <!-- Step 3 -->
          <div class="bg-surface-container-lowest p-6 rounded-xl border border-[#E5E9ED] flex flex-col items-start gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary text-on-primary flex items-center justify-center font-bold text-[20px]">03</div>
            <h3 class="font-title-lg text-title-lg text-on-surface font-semibold pt-1">Konfirmasi &amp; DP</h3>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              Kunci jadwal perjalanan dengan uang muka (DP) 30% via rekening korporat resmi kami, disusul pelunasan santai hingga H-7 keberangkatan.
            </p>
          </div>
          <!-- Step 4 -->
          <div class="bg-surface-container-lowest p-6 rounded-xl border border-[#E5E9ED] flex flex-col items-start gap-3">
            <div class="w-12 h-12 rounded-lg bg-secondary text-on-secondary flex items-center justify-center font-bold text-[20px]">04</div>
            <h3 class="font-title-lg text-title-lg text-on-surface font-semibold pt-1">Berangkat Nyaman</h3>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              Terima panduan travel kit digital, briefing pra-keberangkatan, dan nikmati penyambutan ramah langsung oleh pemandu lokal di bandara tujuan.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. FAQ RINGKAS (Clean Flat Accordion) -->
    <section class="w-full bg-surface-container-lowest py-16 lg:py-24 border-b border-[#E5E9ED]">
      <div class="max-w-[840px] mx-auto px-margin">
        <!-- Section Header -->
        <div class="flex flex-col items-center text-center max-w-xl mx-auto gap-space-xs mb-12">
          <div class="inline-flex items-center px-3 py-1 rounded-full bg-surface-container text-primary font-label-md text-label-md border border-[#E5E9ED]">
            Tanya Jawab
          </div>
          <h2 class="font-headline-lg text-headline-lg-mobile lg:text-headline-lg text-on-surface tracking-tight">
            Pertanyaan yang Sering Diajukan
          </h2>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Ketahui jaminan transparansi operasional sebelum memutuskan melangkah bersama kami.
          </p>
        </div>
        <!-- Accordions -->
        <FaqAccordion :items="faqs" icon-style="chevron" />
      </div>
    </section>

    <!-- 6. SOLID ORANGE CTA BANNER -->
    <CtaBanner
      variant="orange"
      badge="Layanan Konsultasi Bebas Biaya"
      title="Bingung Memilih Layanan yang Tepat?"
      description="Diskusikan rencana perjalanan Anda bersama konsultan wisata kami. Kami siap memberikan rekomendasi rute terbaik dan estimasi anggaran yang paling pas dengan waktu Anda."
      primary-label="Konsultasi via WhatsApp"
      primary-href="https://wa.me/6281234567890"
      secondary-label="Unduh Brosur Paket"
      @secondary="navigate('paket-wisata')"
    />
  </div>
</template>
