<script setup>
import { ref, computed } from 'vue';
import PageHero from '../components/PageHero.vue';
import InfoCard from '../components/InfoCard.vue';
import FaqAccordion from '../components/FaqAccordion.vue';
import { api } from '../api.js';
import { useApiList } from '../useApiList.js';

const { items: apiSettings } = useApiList(() => api.publicSettings('contact'), []);
const setting = (key, fallback) => {
  const found = apiSettings.value.find((s) => s.key === key);

  return found?.value || fallback;
};

const emit = defineEmits(['navigate']);

const navigate = (page) => {
  emit('navigate', page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ── Form State ─────────────────────────────────────────────────────────────
const form = ref({
  name: '',
  email: 'rian.anggara@email.id',
  phone: '',
  subject: 'open-trip',
  message: '',
  privacy: true,
});

const isSubmitting = ref(false);
const submitSuccess = ref(false);
const submitError = ref('');
const alertRef = ref(null);

const isEmailValid = computed(() => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  return emailRegex.test(form.value.email);
});

const handleSubmit = async () => {
  submitError.value = '';
  if (!form.value.name || !form.value.email || !form.value.phone || !form.value.message) {
    submitError.value = 'Nama, email, telepon, dan pesan wajib diisi.';
    return;
  }
  if (!isEmailValid.value) {
    submitError.value = 'Format email tidak valid.';
    return;
  }
  isSubmitting.value = true;
  try {
    await api.sendContact({
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      subject: form.value.subject,
      message: form.value.message,
    });
    submitSuccess.value = true;
    form.value.name = '';
    form.value.phone = '';
    form.value.message = '';
    if (alertRef.value) {
      alertRef.value.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  } catch (err) {
    submitError.value = err.errors && Object.keys(err.errors).length > 0
      ? Object.values(err.errors).flat().join(' ')
      : (err.message || 'Pesan gagal terkirim. Coba lagi.');
  } finally {
    isSubmitting.value = false;
  }
};

// ── FAQ Accordion Data ─────────────────────────────────────────────────────
const contactFaqs = [
  {
    id: 1,
    question: 'Berapa lama waktu balasan pesan?',
    answer: 'Semua formulir yang masuk melalui web dan surel resmi ditindaklanjuti maksimal dalam 1×24 jam pada hari operasional. Untuk kebutuhan tanggap darurat perjalanan atau konfirmasi instan hari H, Anda dapat menghubungi staf siaga kami melalui WhatsApp resmi.',
  },
  {
    id: 2,
    question: 'Apakah bisa konsultasi jadwal secara tatap muka?',
    answer: 'Tentu bisa. Kami melayani janji temu langsung di kantor Sanur (Denpasar) maupun kantor Jakarta Selatan. Harap menjadwalkan kunjungan minimal satu hari sebelumnya melalui admin WhatsApp agar perencana perjalanan kami dapat menyiapkan berkas rute relevan.',
  },
  {
    id: 3,
    question: 'Apakah melayani kunjungan grup perusahaan?',
    answer: 'Ya, divisi Corporate Gathering & Private Charter kami mengkhususkan diri menangani rombongan korporat, outing kantor, hingga riset ilmiah pulau terpencil dengan fasilitas penunjang lengkap seperti sewa kapal eksklusif, tim medis, dan katering bersertifikasi.',
  },
];
</script>

<template>
  <div class="flex flex-col w-full">
    <!-- Page Header Band: Solid Teal -->
    <PageHero
      current="Kontak"
      title="Hubungi Kami"
      subtitle="Ada pertanyaan tentang rute impian, permintaan open trip rombongan, atau kolaborasi? Kami siap menyapa Anda."
      @home="navigate('beranda')"
    />

    <!-- 4 Info Cards -->
    <section class="w-full bg-surface-container-low py-space-xl px-space-lg">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-lg">
        <InfoCard icon="location_on" title="Alamat Kantor" icon-class="bg-surface-container text-primary" title-class="font-bold">
          {{ setting('address', 'Jl. Danau Tamblingan No. 88, Sanur, Denpasar, Bali & Wisma Bidakara Lt. 12, Jakarta Selatan.') }}
        </InfoCard>

        <InfoCard icon="call" title="Telepon & WhatsApp" icon-class="bg-surface-container text-primary" title-class="font-bold">
          <p class="font-body-md text-body-md text-on-surface font-semibold mt-1">{{ setting('contact_phone', '+62 812-3456-7890') }}</p>
          <span class="inline-block mt-1 font-label-sm text-label-sm text-primary font-semibold">Layanan Cepat Respon</span>
        </InfoCard>

        <InfoCard icon="mail" title="Email Resmi" icon-class="bg-surface-container text-primary" title-class="font-bold">
          {{ setting('contact_email', 'info@jejaknusa.id') }}
        </InfoCard>

        <InfoCard icon="schedule" title="Jam Operasional" icon-class="bg-surface-container text-primary" title-class="font-bold">
          Senin - Minggu<br>{{ setting('hours', '08.00 - 20.00 WITA / WIB') }}
        </InfoCard>
      </div>
    </section>

    <!-- Main Interaction Area: 2 Columns -->
    <section class="w-full bg-surface-container-lowest py-space-xl px-space-lg">
      <div class="max-w-7xl mx-auto flex flex-col gap-space-lg">
        <!-- Success Alert Toast / State -->
        <div
          v-if="submitSuccess"
          ref="alertRef"
          role="status"
          class="w-full bg-[#E6F7ED] p-space-md rounded-lg flex items-start gap-space-sm border border-[#A7F3D0] animate-fade-in"
        >
          <span class="material-symbols-outlined text-[#10B981] text-[22px] mt-0.5 shrink-0">check_circle</span>
          <div class="flex flex-col flex-1">
            <span class="font-label-md text-label-md text-[#065F46] font-bold">Pesan berhasil terkirim.</span>
            <p class="font-body-md text-body-md text-[#065F46]">
              Tim konsultan perjalanan kami akan membalas pesan Anda dalam 1×24 jam kerja.
            </p>
          </div>
          <button
            class="text-[#065F46] hover:text-[#047857] p-1 cursor-pointer"
            title="Tutup notifikasi"
            type="button"
            @click="submitSuccess = false"
          >
            <span class="material-symbols-outlined text-[18px]">close</span>
          </button>
        </div>
        <div
          v-if="submitError"
          role="alert"
          class="w-full bg-[#FEECEE] p-space-md rounded-lg flex items-start gap-space-sm border border-[#E5484D] animate-fade-in"
        >
          <span class="material-symbols-outlined text-[#E5484D] text-[22px] mt-0.5 shrink-0">error</span>
          <p class="font-body-md text-body-md text-[#93000a] flex-1">{{ submitError }}</p>
          <button
            class="text-[#93000a] p-1 cursor-pointer"
            title="Tutup notifikasi"
            type="button"
            @click="submitError = ''"
          >
            <span class="material-symbols-outlined text-[18px]">close</span>
          </button>
        </div>

        <!-- 2 Columns Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-start">
          <!-- Left: Form Card (7 Cols) -->
          <div class="lg:col-span-7 bg-surface-container-low p-space-lg md:p-space-xl rounded-xl">
            <div class="mb-space-lg">
              <h2 class="font-headline-sm text-headline-sm text-on-surface font-bold">Kirim Formulir Konsultasi</h2>
              <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                Isi data perjalanan Anda agar kami dapat menyiapkan solusi rencana wisata yang paling tepat.
              </p>
            </div>

            <form class="flex flex-col gap-space-md" @submit.prevent="handleSubmit">
              <!-- Nama Lengkap -->
              <div class="flex flex-col gap-1">
                <label class="font-label-md text-label-md text-on-surface font-semibold" for="contactName">
                  Nama Lengkap <span class="text-secondary">*</span>
                </label>
                <input
                  id="contactName"
                  v-model="form.name"
                  class="w-full h-11 px-space-md bg-surface-container-lowest rounded-lg font-body-md text-body-md text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition-colors"
                  placeholder="Contoh: Rian Anggara"
                  required
                  type="text"
                >
              </div>

              <!-- Email -->
              <div class="flex flex-col gap-1">
                <div class="flex items-center justify-between">
                  <label class="font-label-md text-label-md text-on-surface font-semibold" for="contactEmail">
                    Alamat Email <span class="text-secondary">*</span>
                  </label>
                  <span
                    v-if="isEmailValid"
                    class="font-label-sm text-label-sm text-[#065F46] flex items-center gap-1 font-semibold"
                  >
                    <span class="material-symbols-outlined text-[14px]">check</span> Format Terverifikasi
                  </span>
                </div>
                <div class="relative">
                  <input
                    id="contactEmail"
                    v-model="form.email"
                    class="w-full h-11 pl-space-md pr-10 bg-surface-container-lowest rounded-lg font-body-md text-body-md text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition-colors"
                    placeholder="nama@email.com"
                    required
                    type="email"
                  >
                  <span
                    v-if="isEmailValid"
                    class="material-symbols-outlined text-[#10B981] text-[20px] absolute right-3 top-2.5"
                  >
                    check_circle
                  </span>
                </div>
              </div>

              <!-- Nomor WhatsApp / Telepon -->
              <div class="flex flex-col gap-1">
                <label class="font-label-md text-label-md text-on-surface font-semibold" for="contactPhone">
                  Nomor WhatsApp / Telepon <span class="text-secondary">*</span>
                </label>
                <input
                  id="contactPhone"
                  v-model="form.phone"
                  class="w-full h-11 px-space-md bg-surface-container-lowest rounded-lg font-body-md text-body-md text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none transition-colors"
                  placeholder="0812XXXXXXXX atau +62812XXXXXXXX"
                  required
                  type="tel"
                >
              </div>

              <!-- Dropdown Subjek -->
              <div class="flex flex-col gap-1">
                <label class="font-label-md text-label-md text-on-surface font-semibold" for="contactSubject">
                  Topik / Subjek Perjalanan <span class="text-secondary">*</span>
                </label>
                <div class="relative">
                  <select
                    id="contactSubject"
                    v-model="form.subject"
                    class="w-full h-11 px-space-md bg-surface-container-lowest rounded-lg font-body-md text-body-md text-on-surface border border-surface-container-highest appearance-none focus:border-primary focus:outline-none cursor-pointer transition-colors"
                  >
                    <option value="open-trip">Konsultasi Open Trip</option>
                    <option value="corporate">Private / Corporate Outing</option>
                    <option value="custom">Kustom Itinerary</option>
                    <option value="payment">Pertanyaan Pembayaran / Refund</option>
                    <option value="other">Lainnya</option>
                  </select>
                  <span class="material-symbols-outlined text-on-surface-variant absolute right-3 top-2.5 pointer-events-none">
                    expand_more
                  </span>
                </div>
              </div>

              <!-- Textarea Pesan -->
              <div class="flex flex-col gap-1">
                <label class="font-label-md text-label-md text-on-surface font-semibold" for="contactMessage">
                  Detail Pesan &amp; Rencana Destinasi <span class="text-secondary">*</span>
                </label>
                <textarea
                  id="contactMessage"
                  v-model="form.message"
                  class="w-full p-space-md bg-surface-container-lowest rounded-lg font-body-md text-body-md text-on-surface border border-surface-container-highest focus:border-primary focus:outline-none resize-none transition-colors"
                  placeholder="Ceritakan rencana perjalanan Anda, perkiraan tanggal keberangkatan, atau destinasi spesifik yang diinginkan..."
                  required
                  rows="4"
                ></textarea>
              </div>

              <!-- Checkbox Kebijakan Privasi -->
              <div class="flex items-start gap-space-sm pt-space-xs">
                <input
                  id="privacyCheck"
                  v-model="form.privacy"
                  class="w-5 h-5 rounded mt-0.5 accent-primary cursor-pointer"
                  required
                  type="checkbox"
                >
                <label class="font-body-md text-body-md text-on-surface-variant cursor-pointer select-none" for="privacyCheck">
                  Saya menyetujui kebijakan privasi dan pengolahan data perjalanan Jejak Nusa.
                </label>
              </div>

              <!-- Tombol Kirim -->
              <div class="pt-space-xs">
                <button
                  :disabled="isSubmitting"
                  class="w-full md:w-auto px-space-xl h-11 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center justify-center gap-space-xs cursor-pointer font-semibold disabled:opacity-50"
                  type="submit"
                >
                  <span>{{ isSubmitting ? 'Mengirim...' : 'Kirim Pesan' }}</span>
                  <span class="material-symbols-outlined text-[18px]">send</span>
                </button>
              </div>
            </form>
          </div>

          <!-- Right: Map & Social Media (5 Cols) -->
          <div class="lg:col-span-5 flex flex-col gap-space-lg">
            <!-- Map Card -->
            <div class="bg-surface-container-low rounded-xl overflow-hidden flex flex-col border border-surface-container-highest">
              <!-- Map Vector / Visual -->
              <div
                class="w-full h-56 bg-surface-container-high flex flex-col items-center justify-center relative p-space-md overflow-hidden"
                data-location="Sanur, Bali, Indonesia"
              >
                <!-- Decorative Map Grid Background -->
                <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#00666f_1px,transparent_1px)] [background-size:16px_16px]"></div>
                <!-- Flat Pin Graphic Overlay -->
                <div class="flex flex-col items-center z-10">
                  <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-on-primary animate-bounce">
                    <span class="material-symbols-outlined text-[26px]">location_on</span>
                  </div>
                  <div class="mt-2 bg-on-surface px-space-sm py-1 rounded font-label-sm text-label-sm text-surface-bright font-semibold">
                    Kantor Operasional Denpasar &amp; Jakarta
                  </div>
                </div>
                <div class="absolute inset-0 bg-primary/5 pointer-events-none"></div>
              </div>

              <!-- Office Details Under Map -->
              <div class="p-space-lg flex flex-col gap-space-md bg-surface-container-lowest">
                <div class="flex flex-col gap-1">
                  <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider font-bold">Hub Bali</span>
                  <p class="font-label-md text-label-md text-on-surface font-semibold">Sanur Coastal Office</p>
                  <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Jl. Danau Tamblingan No. 88, Sanur, Denpasar Selatan, Kota Denpasar, Bali 80228
                  </p>
                </div>
                <div class="flex flex-col gap-1 pt-space-xs border-t border-surface-container-highest/60">
                  <span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider font-bold">Hub Jakarta</span>
                  <p class="font-label-md text-label-md text-on-surface font-semibold">Wisma Bidakara Headquarter</p>
                  <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Wisma Bidakara Lt. 12, Jl. Gatot Subroto Kav. 71-73, Pancoran, Jakarta Selatan 12870
                  </p>
                </div>
              </div>
            </div>

            <!-- Social Media Section -->
            <div class="bg-surface-container-low p-space-lg rounded-xl flex flex-col gap-space-md border border-surface-container-highest">
              <div>
                <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Ikuti Cerita Penjelajahan Kami</h3>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                  Dokumentasi langsung, rute terkini, dan panduan eksplorasi kepulauan setiap hari.
                </p>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-space-sm">
                <!-- Instagram -->
                <a
                  aria-label="Instagram"
                  class="h-12 bg-surface-container-lowest hover:bg-surface-container rounded-lg flex items-center justify-center gap-space-xs text-on-surface hover:text-primary transition-colors border border-surface-container-highest/50"
                  href="https://instagram.com"
                  rel="noopener noreferrer"
                  target="_blank"
                >
                  <span class="font-label-md text-label-md font-semibold">Instagram</span>
                </a>
                <!-- TikTok -->
                <a
                  aria-label="TikTok"
                  class="h-12 bg-surface-container-lowest hover:bg-surface-container rounded-lg flex items-center justify-center gap-space-xs text-on-surface hover:text-primary transition-colors border border-surface-container-highest/50"
                  href="https://tiktok.com"
                  rel="noopener noreferrer"
                  target="_blank"
                >
                  <span class="font-label-md text-label-md font-semibold">TikTok</span>
                </a>
                <!-- YouTube -->
                <a
                  aria-label="YouTube"
                  class="h-12 bg-surface-container-lowest hover:bg-surface-container rounded-lg flex items-center justify-center gap-space-xs text-on-surface hover:text-primary transition-colors border border-surface-container-highest/50"
                  href="https://youtube.com"
                  rel="noopener noreferrer"
                  target="_blank"
                >
                  <span class="font-label-md text-label-md font-semibold">YouTube</span>
                </a>
                <!-- Facebook -->
                <a
                  aria-label="Facebook"
                  class="h-12 bg-surface-container-lowest hover:bg-surface-container rounded-lg flex items-center justify-center gap-space-xs text-on-surface hover:text-primary transition-colors border border-surface-container-highest/50"
                  href="https://facebook.com"
                  rel="noopener noreferrer"
                  target="_blank"
                >
                  <span class="font-label-md text-label-md font-semibold">Facebook</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Teaser Section -->
    <section class="w-full bg-surface-container-low py-space-xl px-space-lg border-t border-surface-container-highest">
      <div class="max-w-4xl mx-auto flex flex-col gap-space-lg">
        <div class="text-center flex flex-col items-center gap-space-xs">
          <span class="font-label-sm text-label-sm text-primary uppercase tracking-wider font-semibold">Bantuan Cepat</span>
          <h2 class="font-headline-md text-headline-md text-on-surface font-bold">
            Pertanyaan yang Sering Muncul Sebelum Menghubungi
          </h2>
        </div>

        <!-- Flat Accordion List -->
        <FaqAccordion :items="contactFaqs" icon-style="chevron" />

        <!-- Link to full FAQ -->
        <div class="text-center pt-space-xs">
          <a
            class="inline-flex items-center gap-space-xs font-label-md text-label-md text-primary hover:text-on-primary-fixed-variant px-space-md py-2 rounded-lg bg-surface-container hover:bg-surface-variant transition-colors cursor-pointer font-semibold"
            @click.prevent="navigate('faq')"
          >
            <span>Lihat Semua FAQ</span>
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
          </a>
        </div>
      </div>
    </section>
  </div>
</template>
