<script setup>
import ErrorStateCard from '../../components/ErrorStateCard.vue';
import PackageCard from '../../components/PackageCard.vue';

const emit = defineEmits(['navigate']);

const popularPicks = [
  {
    title: 'Pesona Raja Ampat: Wayag & Misool Bahari',
    location: 'Raja Ampat, Papua Barat Daya',
    badge: 'Pasti Berangkat',
    badgeVariant: 'teal',
    badgeShape: 'pill',
    duration: '4 Hari 3 Malam',
    type: 'Open Trip',
    typeClass: 'bg-teal-light text-teal font-semibold',
    rating: 5,
    reviews: 128,
    price: 'Rp 6.850.000',
    image: '/images/img-04.jpg',
  },
  {
    title: 'Ekspedisi Komodo & Labuan Bajo Sunset Cruise',
    location: 'Labuan Bajo, Flores, NTT',
    badge: 'Favorit Musim Ini',
    badgeVariant: 'yellow',
    badgeShape: 'pill',
    duration: '3 Hari 2 Malam',
    type: 'Semi-Private',
    typeClass: 'bg-[#FFF0EA] text-[#DE5E26] font-semibold',
    rating: 5,
    reviews: 214,
    price: 'Rp 3.450.000',
    image: '/images/img-05.jpg',
  },
  {
    title: 'Sunrise Bromo & Kawah Ijen Blue Fire',
    location: 'Malang & Banyuwangi, Jawa Timur',
    badge: 'Best Seller',
    badgeVariant: 'teal',
    badgeShape: 'pill',
    duration: '2 Hari 1 Malam',
    type: 'Open Trip',
    typeClass: 'bg-teal-light text-teal font-semibold',
    rating: 5,
    reviews: 310,
    price: 'Rp 1.250.000',
    image: '/images/img-06.jpg',
  },
];
</script>

<template>
  <div class="w-full bg-[#F3F6F8] py-12 md:py-16">
    <div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 md:px-margin flex flex-col gap-12">
      <!-- Error Card Utama -->
      <ErrorStateCard
        code="404"
        badge-text="Tersesat di Samudra"
        badge-variant="teal"
        title="Halaman yang Kamu Cari Sedang Berlayar Jauh"
        description="Sepertinya tautan yang kamu tuju telah berpindah dermaga atau belum terpetakan dalam rute pelayaran kami. Jangan khawatir, mari kembali ke dermaga utama atau pilih rute petualangan lain di bawah."
        illustration-src="/images/illustrations/illustration-404.svg"
        illustration-alt="Ilustrasi 404 Pinisi Tersesat di Lautan"
        primary-action-label="Kembali ke Beranda"
        primary-action-icon="home"
        secondary-action-label="Eksplor Semua Paket Wisata"
        secondary-action-icon="explore"
        secondary-action-variant="outline"
        @primary-action="emit('navigate', 'beranda')"
        @secondary-action="emit('navigate', 'paket-wisata')"
      />

      <!-- Rekomendasi Destinasi Alternatif Agar Traveler Tidak Bingung -->
      <section class="w-full bg-[#FFFFFF] border border-[#E5E9ED] rounded-xl p-6 sm:p-8 md:p-10">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 pb-6 border-b border-[#E5E9ED]">
          <div>
            <span class="inline-block px-3 py-1 rounded-full bg-[#E6F6F7] border border-[#0E9AA7] font-inter text-xs text-[#0B7A85] font-semibold mb-2">
              Rekomendasi Terkurasi
            </span>
            <h2 class="font-epilogue text-2xl sm:text-3xl font-bold text-[#1F2937]">
              Jelajahi Jalur Favorit Penjelajah Lainnya
            </h2>
          </div>
          <button
            class="text-sm font-inter font-semibold text-[#0E9AA7] hover:text-[#0B7A85] inline-flex items-center gap-1 transition-colors cursor-pointer"
            type="button"
            @click="emit('navigate', 'paket-wisata')"
          >
            <span>Lihat Semua Katalog</span>
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
          </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <PackageCard
            v-for="pkg in popularPicks"
            :key="pkg.title"
            :title="pkg.title"
            :location="pkg.location"
            :badge="pkg.badge"
            :badge-variant="pkg.badgeVariant"
            :badge-shape="pkg.badgeShape"
            :duration="pkg.duration"
            :type="pkg.type"
            :type-class="pkg.typeClass"
            :rating="pkg.rating"
            :reviews="pkg.reviews"
            :price="pkg.price"
            :image="pkg.image"
            @detail="emit('navigate', 'detail-paket', {})"
          />
        </div>
      </section>
    </div>
  </div>
</template>
