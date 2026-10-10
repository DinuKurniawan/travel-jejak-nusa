<script setup>
import { computed } from 'vue';
import TeamCard from '../components/TeamCard.vue';
import CtaBanner from '../components/CtaBanner.vue';
import EmptyState from '../components/EmptyState.vue';
import { api } from '../api.js';
import { useApiList } from '../useApiList.js';

const { items: apiTeam } = useApiList(() => api.team(), []);
const team = computed(() => {
  const accents = ['text-teal', 'text-orange', 'text-primary', 'text-tertiary'];

  return apiTeam.value.filter((m) => m.status !== 'inactive').slice(0, 4).map((m, i) => ({
    photo: m.photo || '/images/img-63.jpg',
    name: m.name,
    role: m.role || '',
    bio: m.bio || '',
    accent: accents[i % accents.length],
  }));
});
const emit = defineEmits(['navigate']);

const navigate = (page) => {
  emit('navigate', page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
  <div class="flex flex-col w-full">
    <!-- 1. PAGE HEADER -->
    <section class="w-full bg-[#0E9AA7] text-white relative">
      <div class="max-w-[1200px] mx-auto px-margin pt-14 pb-20">
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-white/80 font-label-sm text-label-sm mb-4">
          <a class="hover:text-white transition-colors cursor-pointer" @click.prevent="navigate('beranda')">Beranda</a>
          <span class="text-white/50">/</span>
          <span class="text-white font-medium">Tentang Kami</span>
        </nav>
        <!-- Main Titles -->
        <div class="max-w-3xl">
          <h1 class="font-display-lg text-display-lg tracking-tight text-white mb-4">
            Tentang Jejak Nusa
          </h1>
          <p class="font-body-lg text-body-lg text-white/90 leading-relaxed">
            Mengenal lebih dekat visi kami dalam merajut perjalanan autentik dan memberdayakan komunitas lokal di seluruh pelosok Indonesia.
          </p>
        </div>
      </div>
      <!-- Strict Flat Decorative Bottom Edge Accent (Pure Flat Vector Chevron/Pattern) -->
      <div class="w-full h-4 bg-[#0B7A85] flex">
        <div class="w-1/4 h-full bg-[#0E9AA7]"></div>
        <div class="w-1/4 h-full bg-[#00818c]"></div>
        <div class="w-1/4 h-full bg-[#F6C945]"></div>
        <div class="w-1/4 h-full bg-[#F4743B]"></div>
      </div>
    </section>

    <!-- 2. CERITA KAMI (Story Section) -->
    <section class="w-full bg-[#FFFFFF] py-20">
      <div class="max-w-[1200px] mx-auto px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <!-- Left: Flat Vector Stylized Illustration of Archipelago -->
          <div class="lg:col-span-6 bg-[#F3F6F8] rounded-[12px] border border-[#E5E9ED] p-8 flex flex-col justify-between">
            <div class="flex items-center justify-between pb-6 border-b border-[#E5E9ED]">
              <span class="font-label-sm text-label-sm uppercase tracking-wider text-[#0E9AA7] font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">explore</span>
                Peta Penjelajahan Autentik
              </span>
              <span class="font-label-sm text-label-sm text-[#6d797b]">Kepulauan Nusantara</span>
            </div>
            <!-- Stylized Flat Map Vector -->
            <div class="py-6 flex items-center justify-center">
              <svg class="w-full h-auto max-h-[300px]" fill="none" viewBox="0 0 540 280" xmlns="http://www.w3.org/2000/svg">
                <!-- Sea Background Area -->
                <rect fill="#E6F5F7" height="280" rx="8" width="540"></rect>
                <!-- Lat/Long Subtle Flat Grid lines -->
                <line stroke="#CCEBF0" stroke-dasharray="4 4" stroke-width="1" x1="20" x2="520" y1="90" y2="90"></line>
                <line stroke="#CCEBF0" stroke-dasharray="4 4" stroke-width="1" x1="20" x2="520" y1="180" y2="180"></line>
                <line stroke="#CCEBF0" stroke-dasharray="4 4" stroke-width="1" x1="140" x2="140" y1="20" y2="260"></line>
                <line stroke="#CCEBF0" stroke-dasharray="4 4" stroke-width="1" x1="280" x2="280" y1="20" y2="260"></line>
                <line stroke="#CCEBF0" stroke-dasharray="4 4" stroke-width="1" x1="420" x2="420" y1="20" y2="260"></line>
                <!-- Flat Sumatera Island -->
                <path d="M45 80 L75 55 L90 85 L125 150 L110 175 L80 155 L50 115 Z" fill="#0E9AA7"></path>
                <!-- Flat Jawa -->
                <rect fill="#0E9AA7" height="18" rx="6" width="105" x="115" y="185"></rect>
                <!-- Flat Kalimantan -->
                <path d="M165 75 L235 60 L245 125 L190 145 L155 120 Z" fill="#0E9AA7"></path>
                <!-- Flat Sulawesi -->
                <path d="M270 95 L295 90 L305 130 L325 105 L310 165 L285 170 L280 135 L260 120 Z" fill="#0E9AA7"></path>
                <!-- Flat Bali & Nusa Tenggara -->
                <rect fill="#F4743B" height="8" rx="2" width="16" x="225" y="190"></rect>
                <rect fill="#F4743B" height="8" rx="2" width="20" x="246" y="191"></rect>
                <rect fill="#0E9AA7" height="9" rx="3" width="45" x="272" y="192"></rect>
                <!-- Flat Maluku -->
                <circle cx="355" cy="120" fill="#F6C945" r="9"></circle>
                <circle cx="365" cy="150" fill="#0E9AA7" r="11"></circle>
                <circle cx="375" cy="180" fill="#F4743B" r="8"></circle>
                <!-- Flat Papua -->
                <path d="M395 110 L445 105 L490 135 L485 185 L440 180 L425 150 L395 135 Z" fill="#0E9AA7"></path>
                <!-- Flat Location Pins -->
                <!-- Pin: Raja Ampat -->
                <g transform="translate(405, 95)">
                  <circle cx="8" cy="8" fill="#F4743B" r="8"></circle>
                  <circle cx="8" cy="8" fill="#FFFFFF" r="3.5"></circle>
                </g>
                <!-- Pin: Labuan Bajo -->
                <g transform="translate(280, 185)">
                  <circle cx="8" cy="8" fill="#F4743B" r="8"></circle>
                  <circle cx="8" cy="8" fill="#FFFFFF" r="3.5"></circle>
                </g>
                <!-- Pin: Derawan / Berau -->
                <g transform="translate(225, 80)">
                  <circle cx="8" cy="8" fill="#F6C945" r="7"></circle>
                  <circle cx="8" cy="8" fill="#1F2937" r="3"></circle>
                </g>
                <!-- Compass Rose Flat Accent -->
                <g transform="translate(470, 30)">
                  <circle cx="20" cy="20" fill="#FFFFFF" r="18" stroke="#0E9AA7" stroke-width="1.5"></circle>
                  <path d="M20 7 L23 20 L20 18 L17 20 Z" fill="#F4743B"></path>
                  <path d="M20 33 L23 20 L20 22 L17 20 Z" fill="#1F2937"></path>
                  <path d="M33 20 L20 23 L22 20 L20 17 Z" fill="#0E9AA7"></path>
                  <path d="M7 20 L20 23 L18 20 L20 17 Z" fill="#0E9AA7"></path>
                  <text fill="#F4743B" font-size="7" font-weight="bold" text-anchor="middle" x="20" y="5">U</text>
                </g>
              </svg>
            </div>
            <!-- Bottom Micro Details -->
            <div class="grid grid-cols-3 gap-2 pt-4 border-t border-[#E5E9ED] text-center">
              <div class="p-2 bg-white rounded-[8px] border border-[#E5E9ED]">
                <span class="block font-headline-sm text-headline-sm text-[#0E9AA7]">17.508</span>
                <span class="font-label-sm text-label-sm text-[#6d797b]">Pulau Tropis</span>
              </div>
              <div class="p-2 bg-white rounded-[8px] border border-[#E5E9ED]">
                <span class="block font-headline-sm text-headline-sm text-[#F4743B]">100%</span>
                <span class="font-label-sm text-label-sm text-[#6d797b]">Pandu Lokal</span>
              </div>
              <div class="p-2 bg-white rounded-[8px] border border-[#E5E9ED]">
                <span class="block font-headline-sm text-headline-sm text-[#1F2937]">0</span>
                <span class="font-label-sm text-label-sm text-[#6d797b]">Biaya Gaib</span>
              </div>
            </div>
          </div>
          <!-- Right: Story Copy -->
          <div class="lg:col-span-6 flex flex-col items-start gap-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#E6F5F7] text-[#0E9AA7] font-label-sm text-label-sm font-semibold tracking-wide border border-[#BDE3E8]">
              CERITA KAMI
            </span>
            <h2 class="font-headline-lg text-headline-lg text-[#121c2a] leading-tight">
              Dari Satu Jejak Menjadi Seribu Cerita
            </h2>
            <p class="font-body-lg text-body-lg text-[#3d494a] leading-relaxed">
              Jejak Nusa berawal pada tahun 2018 dari sebuah inisiatif kolektif para pemandu lokal berpengalaman di Flores dan gugusan kepulauan Nusa Tenggara. Kami resah melihat industri pariwisata massal yang seringkali memperlakukan keindahan alam Nusantara sekadar latar berfoto kilat tanpa menghargai kearifan masyarakat lokal. Kami bertekad membuka jalur perjalanan alternatif yang mengantarkan pelancong menyelami denyut kehidupan pulau-pulau terpencil secara intim, damai, dan aman.
            </p>
            <p class="font-body-lg text-body-lg text-[#3d494a] leading-relaxed">
              Kini, semangat tersebut menjelma menjadi gerakan pariwisata bertanggung jawab berbasis pemberdayaan warga lokal (<em>community-based tourism</em>). Bagi kami, setiap ekspedisi bahari maupun pendakian bukan sekadar transaksi tiket, melainkan jembatan kebaikan ekonomi bagi nelayan, pengrajin adat, dan pemilik <em>homestay</em> setempat, seraya menjaga kelestarian laut serta hutan hujan Nusantara tetap asri untuk generasi mendatang.
            </p>
            <div class="flex items-center gap-4 pt-2">
              <div class="w-12 h-12 rounded-[8px] bg-[#FEF9E7] border border-[#F6C945] flex items-center justify-center">
                <span class="material-symbols-outlined text-[#F4743B] text-[24px]">verified</span>
              </div>
              <div>
                <p class="font-title-lg text-title-lg text-[#121c2a]">Tur Terkurasi &amp; Berizin Resmi</p>
                <p class="font-body-md text-body-md text-[#6d797b]">Anggota ASITA &amp; Himpunan Pramuwisata Indonesia (HPI)</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. VISI & MISI -->
    <section class="w-full bg-[#F3F6F8] py-20">
      <div class="max-w-[1200px] mx-auto px-margin">
        <div class="text-center max-w-2xl mx-auto mb-14">
          <span class="inline-flex items-center px-3 py-1 rounded-full bg-white text-[#0E9AA7] font-label-sm text-label-sm font-semibold tracking-wide border border-[#E5E9ED] mb-3">
            KOMITMEN STRATEGIS
          </span>
          <h2 class="font-headline-lg text-headline-lg text-[#121c2a]">
            Arah Langkah dan Pengabdian Kami
          </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Visi Card -->
          <div class="bg-white rounded-[12px] border border-[#E5E9ED] p-8 lg:p-10 flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-[8px] bg-[#0E9AA7] flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[22px]">visibility</span>
                  </div>
                  <h3 class="font-headline-sm text-headline-sm text-[#121c2a]">Visi Kami</h3>
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-[#E6F5F7] text-[#0E9AA7] font-label-sm text-label-sm border border-[#0E9AA7]">
                  Visi Nusantara 2030
                </span>
              </div>
              <p class="font-headline-sm text-headline-sm text-[#00666f] font-semibold leading-relaxed mb-6">
                "Menjadi platform kurasi perjalanan terdepan di Indonesia yang menghubungkan penjelajah dunia dengan kearifan lokal Nusantara secara berkelanjutan, aman, dan penuh makna."
              </p>
              <p class="font-body-md text-body-md text-[#3d494a] leading-relaxed">
                Mewujudkan ekosistem tur mandiri di mana setiap pulau memiliki daya saing pariwisata lestari tanpa kehilangan identitas kultural dan keaslian ekologinya.
              </p>
            </div>
            <div class="pt-8 mt-8 border-t border-[#E5E9ED] flex items-center justify-between">
              <span class="font-label-md text-label-md text-[#6d797b]">Fokus Jangka Panjang</span>
              <span class="font-label-md text-label-md text-[#0E9AA7] font-semibold">Keberlanjutan &amp; Manfaat Sosial</span>
            </div>
          </div>
          <!-- Misi Card -->
          <div class="bg-white rounded-[12px] border border-[#E5E9ED] p-8 lg:p-10 flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-[8px] bg-[#F4743B] flex items-center justify-center text-white">
                  <span class="material-symbols-outlined text-[22px]">rocket_launch</span>
                </div>
                <h3 class="font-headline-sm text-headline-sm text-[#121c2a]">Misi Kami</h3>
              </div>
              <!-- 4 Checklist Items -->
              <ul class="space-y-4">
                <li class="flex items-start gap-3">
                  <span class="material-symbols-outlined text-[#0E9AA7] text-[22px] flex-shrink-0 mt-0.5">check_circle</span>
                  <span class="font-body-md text-body-md text-[#121c2a] leading-relaxed">
                    Memberdayakan pemandu lokal bersertifikat dan UMKM di setiap titik destinasi kepulauan.
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <span class="material-symbols-outlined text-[#0E9AA7] text-[22px] flex-shrink-0 mt-0.5">check_circle</span>
                  <span class="font-body-md text-body-md text-[#121c2a] leading-relaxed">
                    Menghadirkan kurasi itinerary yang seimbang antara petualangan, edukasi budaya, dan relaksasi.
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <span class="material-symbols-outlined text-[#0E9AA7] text-[22px] flex-shrink-0 mt-0.5">check_circle</span>
                  <span class="font-body-md text-body-md text-[#121c2a] leading-relaxed">
                    Menerapkan standar keselamatan tinggi dan transparansi harga tanpa biaya tak terduga.
                  </span>
                </li>
                <li class="flex items-start gap-3">
                  <span class="material-symbols-outlined text-[#0E9AA7] text-[22px] flex-shrink-0 mt-0.5">check_circle</span>
                  <span class="font-body-md text-body-md text-[#121c2a] leading-relaxed">
                    Menjaga kelestarian ekosistem bahari dan cagar budaya melalui etika wisata tanpa jejak sampah (<em>Leave No Trace</em>).
                  </span>
                </li>
              </ul>
            </div>
            <div class="pt-8 mt-8 border-t border-[#E5E9ED] flex items-center justify-between">
              <span class="font-label-md text-label-md text-[#6d797b]">Pelaksanaan Operasional</span>
              <span class="font-label-md text-label-md text-[#F4743B] font-semibold">Tersertifikasi &amp; Terukur</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. NILAI PERUSAHAAN (Company Values) -->
    <section class="w-full bg-[#FFFFFF] py-20">
      <div class="max-w-[1200px] mx-auto px-margin">
        <div class="text-center max-w-2xl mx-auto mb-14">
          <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FEF9E7] text-[#92400E] font-label-sm text-label-sm font-semibold tracking-wide border border-[#FDE68A] mb-3">
            NILAI INTI KAMI
          </span>
          <h2 class="font-headline-lg text-headline-lg text-[#121c2a] mb-2">
            Fondasi di Setiap Langkah Perjalanan
          </h2>
          <p class="font-body-lg text-body-lg text-[#3d494a]">
            Prinsip dasar yang kami pegang teguh untuk memastikan pengalaman berlibur Anda senantiasa tenteram, aman, dan berkesan.
          </p>
        </div>
        <div v-if="team.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <TeamCard
            v-for="m in team"
            :key="m.name"
            :photo="m.photo"
            :name="m.name"
            :role="m.role"
            :bio="m.bio"
            :accent="m.accent"
            :hover-border="m.hoverBorder || 'hover:border-teal'"
          />
        </div>
        <EmptyState
          v-else
          icon="group"
          title="Tim kami segera hadir"
          description="Profil tim sedang disiapkan dan akan tampil di sini."
        />
      </div>
    </section>

    <!-- 8. CTA BANNER -->
    <CtaBanner
      variant="orange"
      badge="Mulai Cerita Anda"
      title="Siap Menjelajah Bersama Kami?"
      description="Konsultasikan rencana perjalanan impian Anda atau pilih paket wisata yang siap berangkat minggu ini."
      primary-label="Lihat Paket Wisata"
      secondary-label="Hubungi Kami"
      @primary="navigate('paket-wisata')"
      @secondary="navigate('kontak')"
    />
  </div>
</template>
