<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { api, formatRupiah } from './api.js';
import { useApiList } from './useApiList.js';
import TentangPage from './pages/TentangPage.vue';
import LayananPage from './pages/LayananPage.vue';
import PaketWisataPage from './pages/PaketWisataPage.vue';
import GaleriPage from './pages/GaleriPage.vue';
import DetailPaketWisataPage from './pages/DetailPaketWisataPage.vue';
import BlogPage from './pages/BlogPage.vue';
import DetailBlogPage from './pages/DetailBlogPage.vue';
import FaqPage from './pages/FaqPage.vue';
import KontakPage from './pages/KontakPage.vue';
import LoginPage from './pages/LoginPage.vue';
import SiteHeader from './components/SiteHeader.vue';
import SiteFooter from './components/SiteFooter.vue';
import WhatsAppFloat from './components/WhatsAppFloat.vue';
import AppButton from './components/AppButton.vue';
import SectionHeader from './components/SectionHeader.vue';
import InfoCard from './components/InfoCard.vue';
import PackageCard from './components/PackageCard.vue';
import DestinationCard from './components/DestinationCard.vue';
import TestimonialCard from './components/TestimonialCard.vue';
import CtaBanner from './components/CtaBanner.vue';
import AdminLayout from './layouts/AdminLayout.vue';
import AdminDashboardPage from './pages/admin/AdminDashboardPage.vue';
import AdminPackagesPage from './pages/admin/AdminPackagesPage.vue';
import AdminBookingsPage from './pages/admin/AdminBookingsPage.vue';
import AdminBookingDetailPage from './pages/admin/AdminBookingDetailPage.vue';
import AdminPaymentsPage from './pages/admin/AdminPaymentsPage.vue';
import AdminMessagesPage from './pages/admin/AdminMessagesPage.vue';
import AdminArticlesPage from './pages/admin/AdminArticlesPage.vue';
import AdminArticleEditorPage from './pages/admin/AdminArticleEditorPage.vue';
import AdminGalleryPage from './pages/admin/AdminGalleryPage.vue';
import AdminTestimonialsPage from './pages/admin/AdminTestimonialsPage.vue';
import AdminFaqsPage from './pages/admin/AdminFaqsPage.vue';
import AdminBannersPage from './pages/admin/AdminBannersPage.vue';
import AdminHomePage from './pages/admin/AdminHomePage.vue';
import AdminAboutPage from './pages/admin/AdminAboutPage.vue';
import AdminServicesPage from './pages/admin/AdminServicesPage.vue';
import AdminNavigationPage from './pages/admin/AdminNavigationPage.vue';
import AdminContactPage from './pages/admin/AdminContactPage.vue';
import AdminSeoPage from './pages/admin/AdminSeoPage.vue';
import AdminSettingsPage from './pages/admin/AdminSettingsPage.vue';
import AdminUsersPage from './pages/admin/AdminUsersPage.vue';
import AdminRolesPage from './pages/admin/AdminRolesPage.vue';
import AdminTeamPage from './pages/admin/AdminTeamPage.vue';
import AdminAuditPage from './pages/admin/AdminAuditPage.vue';
import AdminProfilePage from './pages/admin/AdminProfilePage.vue';
import UserLayout from './layouts/UserLayout.vue';
import UserRingkasanPage from './pages/user/UserRingkasanPage.vue';
import UserPesananPage from './pages/user/UserPesananPage.vue';
import UserDetailPesananPage from './pages/user/UserDetailPesananPage.vue';
import UserTiketPage from './pages/user/UserTiketPage.vue';
import UserPembayaranPage from './pages/user/UserPembayaranPage.vue';
import UserWishlistPage from './pages/user/UserWishlistPage.vue';
import UserVoucherPage from './pages/user/UserVoucherPage.vue';
import UserUlasanPage from './pages/user/UserUlasanPage.vue';
import UserNotifikasiPage from './pages/user/UserNotifikasiPage.vue';
import UserProfilPage from './pages/user/UserProfilPage.vue';
import UserTravelerPage from './pages/user/UserTravelerPage.vue';
import UserKeamananPage from './pages/user/UserKeamananPage.vue';
import UserBantuanPage from './pages/user/UserBantuanPage.vue';
import NotFoundPage from './pages/errors/NotFoundPage.vue';
import ServerErrorPage from './pages/errors/ServerErrorPage.vue';
import ForbiddenPage from './pages/errors/ForbiddenPage.vue';
import MaintenancePage from './pages/errors/MaintenancePage.vue';
import OfflinePage from './pages/errors/OfflinePage.vue';
import AuthRequiredPage from './pages/errors/AuthRequiredPage.vue';
import PackageUnavailablePage from './pages/errors/PackageUnavailablePage.vue';
import BookingExpiredPage from './pages/errors/BookingExpiredPage.vue';
import PaymentRejectedPage from './pages/errors/PaymentRejectedPage.vue';
import UploadFailedPage from './pages/errors/UploadFailedPage.vue';
import FormErrorPage from './pages/errors/FormErrorPage.vue';
import StatusErrorCatalogPage from './pages/errors/StatusErrorCatalogPage.vue';

const adminPages = {
  dashboard: { component: 'dashboard', title: 'Dashboard', subtitle: 'Ringkasan operasional hari ini' },
  bookings: { title: 'Pemesanan', subtitle: 'Seluruh pemesanan masuk' },
  'booking-detail': { title: 'Detail Pemesanan', subtitle: 'JN-2026-1184' },
  payments: { title: 'Verifikasi Pembayaran', subtitle: 'Bukti transfer menunggu' },
  messages: { title: 'Pesan Masuk', subtitle: 'Formulir kontak publik' },
  packages: { title: 'Paket Wisata', subtitle: 'Katalog paket terkurasi' },
  articles: { title: 'Artikel Blog', subtitle: 'Konten panduan & cerita' },
  'article-editor': { title: 'Editor Artikel', subtitle: 'Draf baru' },
  gallery: { title: 'Media & Galeri', subtitle: 'Arsip dokumentasi' },
  testimonials: { title: 'Testimoni', subtitle: 'Ulasan tamu terkurasi' },
  faqs: { title: 'Kelola FAQ', subtitle: 'Pertanyaan umum' },
  banners: { title: 'Kelola Banner', subtitle: 'Promo & CTA' },
  home: { title: 'Kelola Beranda', subtitle: 'Section halaman utama' },
  about: { title: 'Kelola Tentang', subtitle: 'Cerita & tim' },
  services: { title: 'Kelola Layanan', subtitle: 'Katalog layanan' },
  navigation: { title: 'Navigasi & Footer', subtitle: 'Menu & kontak footer' },
  contact: { title: 'Kelola Kontak', subtitle: 'Info kontak publik' },
  seo: { title: 'SEO', subtitle: 'Meta tiap halaman' },
  settings: { title: 'Pengaturan Situs', subtitle: 'Preferensi umum' },
  users: { title: 'Pengguna', subtitle: 'Akun admin' },
  roles: { title: 'Hak Akses', subtitle: 'Matriks izin peran' },
  team: { title: 'Tim', subtitle: 'Profil tim publik' },
  audit: { title: 'Audit Log', subtitle: 'Jejak aktivitas' },
  profile: { title: 'Profil Saya', subtitle: 'Akun Anda' },
};

const isAdminPage = (page) => Object.hasOwn(adminPages, page);

const userPages = {
  ringkasan: { title: 'Ringkasan', subtitle: 'Halo, petualanganmu dimulai di sini' },
  'pesanan-saya': { title: 'Booking Saya', subtitle: 'Semua perjalananmu dalam satu tempat' },
  'detail-pesanan': { title: 'Detail Booking', subtitle: 'Rincian perjalananmu' },
  'tiket-invoice': { title: 'Tiket & Invoice', subtitle: 'E-tiket dan invoice perjalananmu' },
  pembayaran: { title: 'Pembayaran', subtitle: 'Selesaikan pembayaranmu tepat waktu' },
  wishlist: { title: 'Wishlist', subtitle: 'Destinasi impian yang kamu simpan' },
  voucher: { title: 'Voucher Saya', subtitle: 'Klaim dan gunakan voucher hematmu' },
  ulasan: { title: 'Ulasan Saya', subtitle: 'Bagikan ceritamu ke traveler lain' },
  notifikasi: { title: 'Notifikasi', subtitle: 'Kabar terbaru perjalananmu' },
  profil: { title: 'Profil', subtitle: 'Kelola data dirimu' },
  'data-traveler': { title: 'Data Traveler', subtitle: 'Daftar teman seperjalananmu' },
  keamanan: { title: 'Keamanan', subtitle: 'Jaga akunmu tetap aman' },
  bantuan: { title: 'Bantuan', subtitle: 'Kami siap membantumu kapan pun' },
};

const isUserPage = (page) => Object.hasOwn(userPages, page);

// Session guard nyata (cookie + CSRF, tanpa dep baru): tanpa session = login.
const isAdminLoggedIn = ref(false);
const authChecked = ref(false);

async function refreshAuth() {
  try {
    await api.me();
    isAdminLoggedIn.value = true;
  } catch {
    isAdminLoggedIn.value = false;
  }
  authChecked.value = true;
}

const errorPages = [
  '404',
  '500',
  '403',
  'maintenance',
  'offline',
  'perlu-login',
  'paket-tidak-tersedia',
  'booking-kedaluwarsa',
  'bukti-bayar-ditolak',
  'upload-gagal',
  'form-gagal',
  'status-error',
];

const validPages = [
  'beranda',
  'tentang',
  'layanan',
  'paket-wisata',
  'galeri',
  'detail-paket',
  'blog',
  'detail-blog',
  'faq',
  'kontak',
  'login',
  ...errorPages,
  ...Object.keys(adminPages),
  ...Object.keys(userPages),
];

const adminLogin = () => {
  isAdminLoggedIn.value = true;
  navigate('dashboard');
};

const adminLogout = async () => {
  try {
    await api.logout();
  } catch {
    // session already gone; still leave admin area
  }
  isAdminLoggedIn.value = false;
  navigate('beranda');
};

const currentPage = ref('beranda');
const pageParams = ref({});
const isMobileMenuOpen = ref(false);
const isOnline = ref(typeof navigator !== 'undefined' ? navigator.onLine : true);

const getPageFromUrl = () => {
  // Check hash first (e.g. #/tentang or #tentang)
  const hash = window.location.hash.replace(/^#\/?/, '').trim();
  if (hash) {
    if (validPages.includes(hash)) {
      return hash;
    }
    return '404';
  }
  // Check pathname (e.g. /paket-wisata)
  const path = window.location.pathname.replace(/^\/+|\/+$/g, '').trim();
  if (path) {
    if (validPages.includes(path)) {
      return path;
    }
    return '404';
  }
  return 'beranda';
};

const navigate = (page, params = {}) => {
  if (!validPages.includes(page)) {
    page = '404';
  }
  if (isAdminPage(page) && !isAdminLoggedIn.value) {
    page = 'login';
  }
  if (page === 'login' && isAdminLoggedIn.value && !params.fromLogin) {
    page = 'dashboard';
  }
  currentPage.value = page;
  pageParams.value = params || {};
  isMobileMenuOpen.value = false;

  const targetPath = page === 'beranda' ? '/' : `/${page}`;
  if (window.location.pathname !== targetPath) {
    window.history.pushState({ page, params }, '', targetPath);
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const handlePopState = (event) => {
  if (event.state && event.state.page && validPages.includes(event.state.page)) {
    currentPage.value = event.state.page;
    pageParams.value = event.state.params || {};
  } else {
    currentPage.value = getPageFromUrl();
    pageParams.value = {};
  }
  isMobileMenuOpen.value = false;
};

const handleOnlineState = () => {
  isOnline.value = navigator.onLine;
};

onMounted(async () => {
  currentPage.value = getPageFromUrl();
  window.addEventListener('popstate', handlePopState);
  window.addEventListener('online', handleOnlineState);
  window.addEventListener('offline', handleOnlineState);
  await refreshAuth();
  if (isAdminPage(currentPage.value) && !isAdminLoggedIn.value) {
    currentPage.value = 'login';
  }
});

onUnmounted(() => {
  window.removeEventListener('popstate', handlePopState);
  window.removeEventListener('online', handleOnlineState);
  window.removeEventListener('offline', handleOnlineState);
});

const heroSearch = ref({
  destinasi: '',
  kategori: '',
  bulan: '',
  budget: '',
});

const handleHeroSearch = () => {
  navigate('paket-wisata', {
    destinasi: heroSearch.value.destinasi,
    kategori: heroSearch.value.kategori,
  });
};

const activeTab = ref('Semua Destinasi');
const tabs = ['Semua Destinasi', 'Labuan Bajo', 'Raja Ampat', 'Jawa Timur', 'Bali & Lombok', 'Sumatera & Kalimantan'];

const selectTab = (tab) => {
  activeTab.value = tab;
};

const destinationBase = [
  { image: '/images/img-07.jpg', name: 'Labuan Bajo', match: 'labuan bajo', params: { destinasi: 'ntt', search: 'Labuan Bajo' } },
  { image: '/images/img-08.jpg', name: 'Pulau Sumba', match: 'sumba', params: { destinasi: 'ntt', search: 'Sumba' } },
  { image: '/images/img-09.jpg', name: 'Dieng Plateau', match: 'dieng', params: { destinasi: 'jatim', search: 'Dieng' } },
  { image: '/images/img-10.jpg', name: 'Wakatobi Marine Park', match: 'wakatobi', params: { destinasi: 'sulawesi', search: 'Wakatobi' } },
  { image: '/images/img-11.jpg', name: 'Pulau Morotai', match: 'morotai', params: { destinasi: 'maluku', search: 'Morotai' } },
  { image: '/images/img-12.jpg', name: 'Banda Neira', match: 'banda', params: { destinasi: 'maluku', search: 'Banda Neira' } },
];

const highlights = [
  { icon: 'badge', title: 'Pemandu Lokal Berlisensi', text: 'Didampingi pemandu asli putra daerah yang terakreditasi HPI, ramah, dan paham etika kearifan lokal.', footer: 'Standar Keamanan Tinggi' },
  { icon: 'verified', title: 'Garansi Pasti Berangkat', text: 'Bebas waswas pembatalan mendadak karena kuota open trip kurang. Jadwal terjamin berangkat.', footer: 'Transparansi Jadwal 100%' },
  { icon: 'all_inclusive', title: 'Semua Termasuk (All-In)', text: 'Akomodasi, kuliner khas lezat, tiket konservasi taman nasional, asuransi, hingga dokumentasi beres.', footer: 'Bebas Biaya Tak Terduga' },
  { icon: 'payments', title: 'Pembayaran Fleksibel', text: 'Cukup bayar DP 30% untuk mengamankan kursi trip Anda. Pelunasan fleksibel H-7 keberangkatan.', footer: 'Metode Resmi & Aman' },
];

const wishedPackages = ref(new Set());
const toggleWished = (idx) => {
  if (wishedPackages.value.has(idx)) {
    wishedPackages.value.delete(idx);
  } else {
    wishedPackages.value.add(idx);
  }
};

// Live home data (API only, no mock fallback).
const { items: apiFeatured } = useApiList(() => api.featuredPackages(), []);
const { items: apiTestimonials } = useApiList(() => api.testimonials(), []);
const { items: apiAllPackages } = useApiList(() => api.packages({ per_page: 100 }), []);

function initialsOf(name) {
  return String(name || '?').split(/\s+/).filter(Boolean).slice(0, 2).map((w) => w[0]).join('').toUpperCase();
}

const featuredPackages = computed(() => apiFeatured.value.slice(0, 3).map((p) => ({
  slug: p.slug,
  image: p.image || '/images/img-04.jpg',
  badge: p.badge || '',
  badgeVariant: 'promo',
  badgeShape: 'box',
  location: p.location,
  title: p.title,
  duration: p.duration || '',
  type: p.trip_type === 'private' ? 'Private Trip' : 'Open Trip',
  typeClass: p.trip_type === 'private' ? 'bg-yellow-light text-[#755b00]' : undefined,
  rating: String(p.rating ?? ''),
  reviews: String(p.reviews_count ?? ''),
  price: formatRupiah(p.price),
})));

const featuredDestinations = computed(() => destinationBase.map((d) => {
  const count = apiAllPackages.value.filter((p) => `${p.title || ''} ${p.location || ''}`.toLowerCase().includes(d.match)).length;

  return { ...d, tripCount: `${count} Paket Trip Aktif` };
}));

const featuredTestimonials = computed(() => {
  const avatars = ['bg-teal-light text-teal', 'bg-yellow-light text-[#755b00]', 'bg-[#EFF6FF] text-[#1D4ED8]'];

  return apiTestimonials.value.slice(0, 3).map((t, i) => ({
    quote: t.quote,
    initials: initialsOf(t.name),
    name: t.name,
    meta: t.meta || '',
    avatarClass: avatars[i % avatars.length],
  }));
});
</script>

<template>
  <!-- Dedicated Fullscreen Login View -->
  <LoginPage v-if="currentPage === 'login'" @navigate="navigate" @success="adminLogin" />

  <!-- Admin CMS Layout -->
  <AdminLayout
    v-else-if="isAdminPage(currentPage)"
    :active="currentPage"
    :title="adminPages[currentPage].title"
    :subtitle="adminPages[currentPage].subtitle"
    @navigate="navigate"
    @logout="adminLogout"
    @profile="navigate('profile')"
  >
    <AdminDashboardPage v-if="currentPage === 'dashboard'" @navigate="navigate" />
    <AdminPackagesPage v-else-if="currentPage === 'packages'" />
    <AdminBookingsPage v-else-if="currentPage === 'bookings'" />
    <AdminBookingDetailPage v-else-if="currentPage === 'booking-detail'" :booking-id="pageParams.id" @navigate="navigate" />
    <AdminPaymentsPage v-else-if="currentPage === 'payments'" />
    <AdminMessagesPage v-else-if="currentPage === 'messages'" />
    <AdminArticlesPage v-else-if="currentPage === 'articles'" @navigate="navigate" />
    <AdminArticleEditorPage v-else-if="currentPage === 'article-editor'" :article-id="pageParams.id" @navigate="navigate" />
    <AdminGalleryPage v-else-if="currentPage === 'gallery'" />
    <AdminTestimonialsPage v-else-if="currentPage === 'testimonials'" />
    <AdminFaqsPage v-else-if="currentPage === 'faqs'" />
    <AdminBannersPage v-else-if="currentPage === 'banners'" @navigate="navigate" />
    <AdminHomePage v-else-if="currentPage === 'home'" @navigate="navigate" />
    <AdminAboutPage v-else-if="currentPage === 'about'" @navigate="navigate" />
    <AdminServicesPage v-else-if="currentPage === 'services'" @navigate="navigate" />
    <AdminNavigationPage v-else-if="currentPage === 'navigation'" @navigate="navigate" />
    <AdminContactPage v-else-if="currentPage === 'contact'" @navigate="navigate" />
    <AdminSeoPage v-else-if="currentPage === 'seo'" @navigate="navigate" />
    <AdminSettingsPage v-else-if="currentPage === 'settings'" />
    <AdminUsersPage v-else-if="currentPage === 'users'" />
    <AdminRolesPage v-else-if="currentPage === 'roles'" />
    <AdminTeamPage v-else-if="currentPage === 'team'" />
    <AdminAuditPage v-else-if="currentPage === 'audit'" />
    <AdminProfilePage v-else-if="currentPage === 'profile'" />
  </AdminLayout>

  <!-- User Dashboard Layout (Jejak Nusa Customer Portal) -->
  <UserLayout
    v-else-if="isUserPage(currentPage)"
    :active="currentPage"
    :title="userPages[currentPage].title"
    :subtitle="userPages[currentPage].subtitle"
    @navigate="navigate"
    @logout="adminLogout"
  >
    <UserRingkasanPage v-if="currentPage === 'ringkasan'" :params="pageParams" @navigate="navigate" />
    <UserPesananPage v-else-if="currentPage === 'pesanan-saya'" :params="pageParams" @navigate="navigate" />
    <UserDetailPesananPage v-else-if="currentPage === 'detail-pesanan'" :params="pageParams" @navigate="navigate" />
    <UserTiketPage v-else-if="currentPage === 'tiket-invoice'" :params="pageParams" @navigate="navigate" />
    <UserPembayaranPage v-else-if="currentPage === 'pembayaran'" @navigate="navigate" />
    <UserWishlistPage v-else-if="currentPage === 'wishlist'" @navigate="navigate" />
    <UserVoucherPage v-else-if="currentPage === 'voucher'" @navigate="navigate" />
    <UserUlasanPage v-else-if="currentPage === 'ulasan'" @navigate="navigate" />
    <UserNotifikasiPage v-else-if="currentPage === 'notifikasi'" @navigate="navigate" />
    <UserProfilPage v-else-if="currentPage === 'profil'" @navigate="navigate" />
    <UserTravelerPage v-else-if="currentPage === 'data-traveler'" @navigate="navigate" />
    <UserKeamananPage v-else-if="currentPage === 'keamanan'" @navigate="navigate" />
    <UserBantuanPage v-else-if="currentPage === 'bantuan'" @navigate="navigate" />
  </UserLayout>

  <!-- Standard Public Website Layout -->
  <div v-else class="bg-surface font-body-md text-body-md text-on-surface antialiased min-h-screen">
    <SiteHeader :current-page="currentPage" @navigate="navigate" />

    <!-- Offline Alert Banner (Flat Design 2.0 Solid Banner) -->
    <div
      v-if="!isOnline"
      class="fixed top-[72px] left-0 w-full bg-[#E5484D] text-[#FFFFFF] py-2 px-4 z-40 flex items-center justify-center gap-2 text-xs font-inter font-semibold"
    >
      <span class="material-symbols-outlined text-[16px]">wifi_off</span>
      <span>Koneksi internet terputus. Mode offline diaktifkan.</span>
      <button
        class="ml-2 underline text-[#FFFFFF] hover:text-[#FFF0EA] cursor-pointer"
        type="button"
        @click="navigate('offline')"
      >
        Buka Halaman Offline
      </button>
    </div>

    <main class="w-full pt-[72px] bg-surface">
      <!-- Tentang Page -->
      <TentangPage v-if="currentPage === 'tentang'" @navigate="navigate" />

      <!-- Layanan Page -->
      <LayananPage v-else-if="currentPage === 'layanan'" @navigate="navigate" />

      <!-- Paket Wisata Page -->
      <PaketWisataPage v-else-if="currentPage === 'paket-wisata'" :initial-params="pageParams" @navigate="navigate" />

      <!-- Galeri Page -->
      <GaleriPage v-else-if="currentPage === 'galeri'" @navigate="navigate" />

      <!-- Detail Paket Wisata Page -->
      <DetailPaketWisataPage v-else-if="currentPage === 'detail-paket'" :initial-params="pageParams" @navigate="navigate" />

      <!-- Detail Blog Page -->
      <DetailBlogPage v-else-if="currentPage === 'detail-blog'" :initial-params="pageParams" @navigate="navigate" />

      <!-- Blog Page -->
      <BlogPage v-else-if="currentPage === 'blog'" @navigate="navigate" />

      <!-- FAQ Page -->
      <FaqPage v-else-if="currentPage === 'faq'" @navigate="navigate" />

      <!-- Kontak Page -->
      <KontakPage v-else-if="currentPage === 'kontak'" @navigate="navigate" />

      <!-- Public Error & Status Pages (Stitch Design System) -->
      <NotFoundPage v-else-if="currentPage === '404'" @navigate="navigate" />
      <ServerErrorPage v-else-if="currentPage === '500'" @navigate="navigate" />
      <ForbiddenPage v-else-if="currentPage === '403'" @navigate="navigate" />
      <MaintenancePage v-else-if="currentPage === 'maintenance'" @navigate="navigate" />
      <OfflinePage v-else-if="currentPage === 'offline'" @navigate="navigate" />
      <AuthRequiredPage v-else-if="currentPage === 'perlu-login'" @navigate="navigate" />
      <PackageUnavailablePage v-else-if="currentPage === 'paket-tidak-tersedia'" @navigate="navigate" />
      <BookingExpiredPage v-else-if="currentPage === 'booking-kedaluwarsa'" @navigate="navigate" />
      <PaymentRejectedPage v-else-if="currentPage === 'bukti-bayar-ditolak'" @navigate="navigate" />
      <UploadFailedPage v-else-if="currentPage === 'upload-gagal'" @navigate="navigate" />
      <FormErrorPage v-else-if="currentPage === 'form-gagal'" @navigate="navigate" />
      <StatusErrorCatalogPage v-else-if="currentPage === 'status-error'" @navigate="navigate" />

      <!-- Beranda (Home) Page -->
      <div v-else class="flex flex-col w-full">
        <!-- 1. HERO SECTION -->
        <section class="w-full bg-[#FFFFFF] py-12 md:py-20 border-b border-[#E5E9ED]">
          <div class="w-full px-4 sm:px-6 md:px-margin">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
              <div class="lg:col-span-7 flex flex-col items-start gap-6">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#EFF6FF] border border-[#DBEAFE]">
                  <span class="text-[#F59E0B] text-sm">★</span>
                  <span class="font-label-sm text-label-sm text-[#1D4ED8] font-semibold tracking-wide">
                    12.000+ Traveler Puas • 98% Rating Bintang 5
                  </span>
                </div>
                <h1 class="font-display-lg text-display-lg text-[#121c2a] leading-tight">
                  Jelajahi Keindahan Nusantara, Ukir Kenangan Abadi
                </h1>
                <p class="font-body-lg text-body-lg text-[#3d494a] max-w-xl">
                  Satu jejak, seribu cerita Nusantara. Temukan paket open trip, private tour eksklusif, dan petualangan terbaik dari Sabang sampai Merauke bersama pemandu lokal berlisensi resmi.
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-2 w-full sm:w-auto">
                  <AppButton variant="primary" size="lg" class="w-full sm:w-auto" @click="navigate('paket-wisata')">
                    Eksplor Paket Wisata
                  </AppButton>
                  <AppButton variant="outline" size="lg" class="w-full sm:w-auto" @click="navigate('kontak')">
                    Konsultasi Gratis
                  </AppButton>
                </div>
                <div class="grid grid-cols-3 gap-6 pt-6 border-t border-[#E5E9ED] w-full">
                  <div>
                    <p class="font-headline-md text-headline-md text-[#0E9AA7] font-bold">45+</p>
                    <p class="font-label-sm text-label-sm text-[#6d797b]">Destinasi Bahari &amp; Darat</p>
                  </div>
                  <div>
                    <p class="font-headline-md text-headline-md text-[#0E9AA7] font-bold">100%</p>
                    <p class="font-label-sm text-label-sm text-[#6d797b]">Pasti Berangkat</p>
                  </div>
                  <div>
                    <p class="font-headline-md text-headline-md text-[#0E9AA7] font-bold">4.9/5</p>
                    <p class="font-label-sm text-label-sm text-[#6d797b]">Indeks Kepuasan Tamu</p>
                  </div>
                </div>
              </div>
              <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-4">
                  <div class="rounded-xl overflow-hidden border border-[#E5E9ED] bg-[#F3F6F8] aspect-[4/5]">
                    <img class="w-full h-full object-cover" data-alt="Dramatic aerial landscape view" src="/images/img-02.jpg">
                  </div>
                  <div class="p-4 rounded-xl border border-[#E5E9ED] bg-[#F8F9FF] flex flex-col justify-center">
                    <span class="font-label-sm text-label-sm text-[#0E9AA7] font-bold uppercase tracking-wider">Ekspedisi Lokal</span>
                    <span class="font-title-lg text-title-lg text-[#121c2a] mt-1 font-semibold">Berakar Tradisi, Aman Terkelola</span>
                  </div>
                </div>
                <div class="flex flex-col gap-4 pt-8">
                  <div class="p-4 rounded-xl border border-[#E5E9ED] bg-[#FEF9E7]">
                    <span class="font-label-sm text-label-sm text-[#755b00] font-semibold">Tersertifikasi HPI</span>
                    <p class="font-body-md text-body-md text-[#121c2a] mt-1">Dipandu pemandu ahli konservasi alam kepulauan.</p>
                  </div>
                  <div class="rounded-xl overflow-hidden border border-[#E5E9ED] bg-[#F3F6F8] aspect-[4/5]">
                    <img class="w-full h-full object-cover" data-alt="Majestic sunrise view" src="/images/img-03.jpg">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- 2. SEARCH & FILTER WIDGET -->
        <section class="w-full bg-[#FFFFFF] -mt-8 relative z-20 px-4 sm:px-6 md:px-margin">
          <div class="w-full bg-[#FFFFFF] border border-[#E5E9ED] rounded-xl p-6">
            <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end" id="trip-search-form" @submit.prevent="handleHeroSearch">
              <div>
                <label class="block font-label-sm text-label-sm text-[#121c2a] font-medium mb-2" for="search-destinasi">Destinasi</label>
                <div class="relative">
                  <select v-model="heroSearch.destinasi" class="w-full h-11 px-3 bg-[#FFFFFF] border border-[#E5E9ED] rounded-lg font-body-md text-body-md text-[#121c2a] focus:outline-none focus:border-[#0E9AA7]" id="search-destinasi">
                    <option value="">Semua Destinasi</option>
                    <option value="labuan-bajo">Labuan Bajo (Komodo)</option>
                    <option value="raja-ampat">Raja Ampat</option>
                    <option value="bromo-semeru">Bromo &amp; Kawah Ijen</option>
                    <option value="bali-nusa">Bali &amp; Nusa Penida</option>
                    <option value="derawan">Kepulauan Derawan</option>
                    <option value="danau-toba">Danau Toba &amp; Samosir</option>
                  </select>
                </div>
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-[#121c2a] font-medium mb-2" for="search-kategori">Kategori Tur</label>
                <select v-model="heroSearch.kategori" class="w-full h-11 px-3 bg-[#FFFFFF] border border-[#E5E9ED] rounded-lg font-body-md text-body-md text-[#121c2a] focus:outline-none focus:border-[#0E9AA7]" id="search-kategori">
                  <option value="">Semua Kategori</option>
                  <option value="open-trip">Open Trip Gabungan</option>
                  <option value="private-tour">Private Tour Eksklusif</option>
                  <option value="honeymoon">Honeymoon Romantis</option>
                  <option value="outing-kantor">Corporate Outing</option>
                </select>
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-[#121c2a] font-medium mb-2" for="search-bulan">Bulan Keberangkatan</label>
                <select v-model="heroSearch.bulan" class="w-full h-11 px-3 bg-[#FFFFFF] border border-[#E5E9ED] rounded-lg font-body-md text-body-md text-[#121c2a] focus:outline-none focus:border-[#0E9AA7]" id="search-bulan">
                  <option value="">Bulan Apapun</option>
                  <option value="mei-2026">Mei 2026</option>
                  <option value="juni-2026">Juni 2026</option>
                  <option value="juli-2026">Juli 2026</option>
                  <option value="agustus-2026">Agustus 2026</option>
                </select>
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-[#121c2a] font-medium mb-2" for="search-budget">Rentang Budget</label>
                <select v-model="heroSearch.budget" class="w-full h-11 px-3 bg-[#FFFFFF] border border-[#E5E9ED] rounded-lg font-body-md text-body-md text-[#121c2a] focus:outline-none focus:border-[#0E9AA7]" id="search-budget">
                  <option value="">Semua Budget</option>
                  <option value="under-15m">&lt; Rp 1,5 Juta</option>
                  <option value="15m-35m">Rp 1,5 - 3,5 Juta</option>
                  <option value="35m-7m">Rp 3,5 - 7 Juta</option>
                  <option value="above-7m">&gt; Rp 7 Juta</option>
                </select>
              </div>
              <div>
                <button class="w-full h-11 inline-flex items-center justify-center gap-2 rounded-lg bg-[#F4743B] hover:bg-[#DE5E26] text-[#FFFFFF] font-label-md text-label-md transition-colors cursor-pointer" type="submit">
                  <span class="material-symbols-outlined text-[18px]">search</span>
                  <span>Cari Jadwal Trip</span>
                </button>
              </div>
            </form>
          </div>
        </section>

        <!-- 3. KEY HIGHLIGHTS -->
        <section class="w-full bg-[#F3F6F8] py-16 md:py-20 border-b border-[#E5E9ED]">
          <div class="w-full px-4 sm:px-6 md:px-margin">
            <SectionHeader
              eyebrow="Keunggulan Layanan"
              title="Kenapa Berkelana Bersama Jejak Nusa?"
              link-text="Pelajari Lebih Lanjut Tentang Kami"
              class="mb-12"
              @link="navigate('tentang')"
            />
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <InfoCard
                v-for="h in highlights"
                :key="h.title"
                :icon="h.icon"
                :title="h.title"
                :footer="h.footer"
              >
                {{ h.text }}
              </InfoCard>
            </div>
          </div>
        </section>

        <!-- 4. PAKET WISATA UNGGULAN -->
        <section v-if="featuredPackages.length > 0" class="w-full bg-[#FFFFFF] py-16 md:py-20 border-b border-[#E5E9ED]">
          <div class="w-full px-4 sm:px-6 md:px-margin">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
              <div>
                <span class="inline-block px-3 py-1 rounded-full bg-[#FEF9E7] border border-[#F6C945] font-label-sm text-label-sm text-[#755b00] font-semibold mb-2">
                  Paling Diminati Musim Ini
                </span>
                <h2 class="font-headline-lg text-headline-lg text-[#121c2a]">Paket Wisata Populer Nusantara</h2>
              </div>
              <p class="font-body-md text-body-md text-[#3d494a] max-w-md">
                Pilihan perjalanan favorit yang dirancang dengan rasio waktu istirahat dan petualangan paling seimbang.
              </p>
            </div>
            
            <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 border-b border-[#E5E9ED]" id="package-filter-tabs">
              <button 
                v-for="tab in tabs" 
                :key="tab"
                @click="selectTab(tab)"
                :class="activeTab === tab ? 'bg-[#0E9AA7] text-[#FFFFFF]' : 'bg-[#FFFFFF] border border-[#E5E9ED] text-[#3d494a] hover:border-[#0E9AA7]'"
                class="px-4 py-2 rounded-lg font-label-md text-label-md whitespace-nowrap transition-colors" 
                type="button"
              >
                {{ tab }}
              </button>
            </div>

            <!-- 3 Detail Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <PackageCard
                v-for="(pkg, idx) in featuredPackages"
                :key="pkg.title"
                :image="pkg.image"
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
                :wished="wishedPackages.has(idx)"
              @wishlist="toggleWished(idx)"
              @detail="navigate('detail-paket', { slug: pkg.slug || '' })"
            />
            </div>

            <div class="mt-12 text-center">
              <AppButton variant="outline" size="lg" @click="navigate('paket-wisata')">Lihat Semua Paket Wisata Lengkap →</AppButton>
            </div>
          </div>
        </section>

        <!-- 5. DESTINASI IMPIAN -->
        <section class="w-full bg-[#F3F6F8] py-16 md:py-20 border-b border-[#E5E9ED]">
          <div class="w-full px-4 sm:px-6 md:px-margin">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
              <div>
                <span class="font-label-sm text-label-sm text-[#0E9AA7] font-bold uppercase tracking-wider">Katalog Kepulauan</span>
                <h2 class="font-headline-lg text-headline-lg text-[#121c2a] mt-2">Destinasi Impian di Seluruh Penjuru</h2>
              </div>
              <p class="font-body-md text-body-md text-[#3d494a] max-w-md">
                Dari gugusan karang timur hingga dataran tinggi mistis, temukan surga tersembunyi yang siap Anda jejaki.
              </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <DestinationCard
                v-for="d in featuredDestinations"
                :key="d.name"
                :image="d.image"
                :name="d.name"
                :trip-count="d.tripCount"
                @explore="navigate('paket-wisata', d.params)"
              />
            </div>
          </div>
        </section>

        <!-- 6. TESTIMONI TRAVELER ASLI -->
        <section v-if="featuredTestimonials.length > 0" class="w-full bg-[#FFFFFF] py-16 md:py-20 border-b border-[#E5E9ED]">
          <div class="w-full px-4 sm:px-6 md:px-margin">
            <div class="text-center max-w-2xl mx-auto mb-12">
              <span class="font-label-sm text-label-sm text-[#0E9AA7] font-bold uppercase tracking-wider">Cerita Perjalanan</span>
              <h2 class="font-headline-lg text-headline-lg text-[#121c2a] mt-2">Suara Asli Para Penjelajah</h2>
              <p class="font-body-md text-body-md text-[#3d494a] mt-3">
                Pengalaman nyata dari mereka yang telah mempercayakan perjalanan berharganya bersama pemandu Jejak Nusa.
              </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <TestimonialCard
                v-for="t in featuredTestimonials"
                :key="t.name"
                :quote="t.quote"
                :initials="t.initials"
                :name="t.name"
                :meta="t.meta"
                :avatar-class="t.avatarClass"
              />
            </div>
          </div>
        </section>

        <!-- 7. CTA BANNER BAWAH -->
        <CtaBanner
          badge="Kustom Perjalanan Bebas Hambatan"
          title="Punya Rencana Liburan Kustom Sendiri?"
          description="Diskusikan itinerary impian kelompok, keluarga, atau rombongan perusahaan Anda bersama travel consultant Jejak Nusa. Dapatkan proposal transparan dalam 1x24 jam."
          primary-label="Hubungi Tim Trip Planner"
          secondary-label="Lihat Ragam Layanan"
          @primary="navigate('kontak')"
          @secondary="navigate('layanan')"
        />
      </div>
    </main>

    <WhatsAppFloat />

    <SiteFooter @navigate="navigate" />
  </div>
</template>