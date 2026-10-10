<script setup>
import { ref } from 'vue';
import AppButton from '../components/AppButton.vue';
import { api } from '../api.js';

const emit = defineEmits(['navigate', 'success']);

const navigate = (page) => {
  emit('navigate', page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ── Login Form State ───────────────────────────────────────────────────────
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const rememberMe = ref(true);
const errorMessage = ref('');
const isSubmitting = ref(false);

const togglePassword = () => {
  showPassword.value = !showPassword.value;
};

const handleLogin = async (event) => {
  errorMessage.value = '';
  // Baca langsung dari DOM: browser autofill/password-manager mengisi input
  // tanpa memicu v-model, sehingga ref bisa tetap kosong saat submit.
  const formData = event?.target instanceof HTMLFormElement ? new FormData(event.target) : null;
  const submittedEmail = (formData?.get('email') ?? email.value ?? '').toString().trim();
  const submittedPassword = (formData?.get('password') ?? password.value ?? '').toString();
  email.value = submittedEmail;
  password.value = submittedPassword;
  if (!submittedEmail || !submittedPassword) {
    errorMessage.value = 'Isi email dan kata sandi dulu ya.';
    return;
  }
  isSubmitting.value = true;
  try {
    await api.login({ email: submittedEmail, password: submittedPassword, remember: rememberMe.value });
    emit('success');
  } catch (err) {
    errorMessage.value = err.status === 422 ? (err.message || 'Email atau kata sandi salah.') : 'Server tidak merespons. Coba lagi.';
  } finally {
    isSubmitting.value = false;
  }
};

// ── Countdown Timer (dekoratif brand panel) ────────────────────────────────
</script>

<template>
  <div class="min-h-screen w-full bg-[#F3F6F8] text-[#1F2937] flex flex-col lg:flex-row antialiased font-sans">
    <!-- ========================================== -->
    <!-- LEFT HALF: Solid Teal #0E9AA7 Brand Column -->
    <!-- ========================================== -->
    <div class="lg:w-1/2 w-full bg-[#0E9AA7] text-white flex flex-col justify-between p-8 sm:p-12 lg:p-16 relative overflow-hidden">
      <!-- Top Left: Button Back / Kembali ke Beranda -->
      <div class="z-20 mb-8">
        <a
          class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white/15 hover:bg-white/25 text-white text-xs font-semibold transition-all cursor-pointer border border-white/20 w-fit group select-none"
          title="Kembali ke Beranda"
          @click.prevent="navigate('beranda')"
        >
          <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Kembali ke Beranda</span>
        </a>
      </div>

      <!-- Top: Logo & Platform Identity -->
      <div class="z-10">
        <div class="flex items-center gap-3">
          <!-- Logo Emblem: Rounded square with pin, sun, and wave -->
          <div class="w-11 h-11 bg-white rounded-lg flex items-center justify-center border-2 border-[#0B7A85] shrink-0">
            <svg class="w-7 h-7" viewBox="0 0 32 32" fill="none">
              <!-- Location Pin (Teal) -->
              <path d="M16 4C12.134 4 9 7.134 9 11C9 16.25 16 23 16 23C16 23 23 16.25 23 11C23 7.134 19.866 4 16 4Z" fill="#0E9AA7"/>
              <!-- Sun Circle (Orange) -->
              <circle cx="21" cy="7" r="3" fill="#F4743B" />
              <!-- Geometric Wave lines (Yellow) -->
              <path d="M7 25C9 23.5 11 23.5 13 25C15 26.5 17 26.5 19 25C21 23.5 23 23.5 25 25" stroke="#F6C945" stroke-width="2.5" stroke-linecap="round"/>
              <circle cx="16" cy="11" r="2.5" fill="white" />
            </svg>
          </div>
          <div>
            <span class="font-bold text-2xl tracking-tight text-white block leading-none">Jejak Nusa</span>
            <span class="text-[11px] font-semibold text-[#F6C945] tracking-widest uppercase block mt-1">Sistem Manajemen CMS</span>
          </div>
        </div>
      </div>

      <!-- Center: Vector Flat Illustration & Headline -->
      <div class="my-10 lg:my-0 z-10 max-w-lg">
        <!-- Flat Vector Illustration: Indonesian Archipelago, Mount Bromo/Rinjani silhouettes, and Tropical Ocean Waves -->
        <div class="w-full bg-[#0B7A85] rounded-2xl p-6 mb-8 border-2 border-[#096670]">
          <svg class="w-full h-44 sm:h-52" viewBox="0 0 460 220" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Sky Background Pattern: Flat Sun & Birds -->
            <circle cx="360" cy="50" r="28" fill="#F6C945" />
            <path d="M70 45C75 41 80 43 85 45C90 41 95 43 100 45" stroke="white" stroke-width="2" stroke-linecap="round"/>
            <path d="M120 35C124 32 127 33 131 35C135 32 138 33 142 35" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
            
            <!-- Mountain Range -->
            <!-- Background Mountain -->
            <polygon points="170,140 240,55 310,140" fill="#0E9AA7" />
            <polygon points="240,55 220,80 240,86 260,80" fill="white" /> <!-- Crater rim snow/steam -->
            <!-- Secondary Mountain -->
            <polygon points="90,140 160,75 230,140" fill="#085B64" />
            <polygon points="280,140 350,85 420,140" fill="#085B64" />

            <!-- Island Hills & Archipelago Landmasses -->
            <path d="M20 155C50 142 80 148 110 152C140 156 160 145 200 150C240 155 260 144 300 148C340 152 380 142 440 155L440 170L20 170Z" fill="#F4743B" />
            
            <!-- Phinisi Traditional Boat Icon -->
            <g transform="translate(75, 120)">
              <!-- Hull -->
              <path d="M0 22C12 22 28 22 36 22C42 18 46 14 48 14L4 14C2 17 0 20 0 22Z" fill="#1F2937" />
              <!-- Main Masts -->
              <line x1="16" y1="2" x2="16" y2="14" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" />
              <line x1="30" y1="6" x2="30" y2="14" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" />
              <!-- Triangular Sails -->
              <polygon points="16,2 4,12 16,12" fill="#F6C945" />
              <polygon points="30,6 20,12 30,12" fill="white" />
            </g>

            <!-- Multi-layered Flat Waves -->
            <!-- Deep Water Layer -->
            <path d="M0 168 Q 30 160, 60 168 T 120 168 T 180 168 T 240 168 T 300 168 T 360 168 T 420 168 T 480 168 L 480 220 L 0 220 Z" fill="#085B64" />
            <!-- Mid Water Layer with Coral Accent Lines -->
            <path d="M0 180 Q 25 174, 50 180 T 100 180 T 150 180 T 200 180 T 250 180 T 300 180 T 350 180 T 400 180 T 460 180 L 460 220 L 0 220 Z" fill="#0B7A85" />
            <!-- Foam Crest Line -->
            <path d="M0 180 Q 25 174, 50 180 T 100 180 T 150 180 T 200 180 T 250 180 T 300 180 T 350 180 T 400 180 T 460 180" stroke="#FFFFFF" stroke-width="2" fill="none" opacity="0.6"/>
            <!-- Foreground Turquoise Ocean Wave -->
            <path d="M0 196 Q 35 190, 70 196 T 140 196 T 210 196 T 280 196 T 350 196 T 420 196 T 490 196 L 490 220 L 0 220 Z" fill="#0E9AA7" />
            <path d="M0 196 Q 35 190, 70 196 T 140 196 T 210 196 T 280 196 T 350 196 T 420 196 T 490 196" stroke="#F6C945" stroke-width="2.5" fill="none"/>
          </svg>
        </div>

        <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight mb-4">
          Kelola Jejak Nusa dengan Mudah
        </h1>
        <p class="text-[#E6F6F7] text-base leading-relaxed mb-6 font-normal">
          Pusat kendali operasional pemesanan, inventaris paket wisata Nusantara, publikasi cerita perjalanan, dan interaksi pelancong dalam satu dasbor terpadu.
        </p>

        <!-- Feature Points -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
          <div class="flex items-center gap-2 bg-[#0B7A85] px-3.5 py-2.5 rounded-lg border border-[#096670]">
            <svg class="w-4 h-4 text-[#F6C945] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span class="text-xs font-medium text-white">Monitoring Booking Realtime</span>
          </div>
          <div class="flex items-center gap-2 bg-[#0B7A85] px-3.5 py-2.5 rounded-lg border border-[#096670]">
            <svg class="w-4 h-4 text-[#F6C945] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span class="text-xs font-medium text-white">Kurasi Konten Ekspedisi</span>
          </div>
        </div>
      </div>

      <!-- Bottom: Security & System Badge -->
      <div class="pt-6 border-t border-[#0B7A85] flex flex-wrap items-center justify-between text-xs text-[#D0F0F3] gap-3 z-10">
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-[#F6C945]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
          <span>Sesi Terenkripsi SSL 256-bit</span>
        </div>
        <span>CMS v2.4.0 • Jejak Nusa Admin</span>
      </div>

      <!-- Decorative Geometric Circles in background -->
      <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full border-4 border-[#0B7A85] pointer-events-none opacity-40"></div>
      <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full border-4 border-[#0B7A85] pointer-events-none opacity-40"></div>
    </div>

    <!-- ========================================== -->
    <!-- RIGHT HALF: White Background + Form Cards  -->
    <!-- ========================================== -->
    <div class="lg:w-1/2 w-full bg-white flex flex-col justify-center items-center p-6 sm:p-12 lg:p-16">
      <div class="w-full max-w-md">

        <!-- Top Header for Right Half -->
        <div class="mb-6">
          <div class="flex items-center justify-between mb-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#E6F6F7] text-[#0E9AA7] border border-[#0E9AA7]">
              <span class="w-1.5 h-1.5 rounded-full bg-[#0E9AA7]"></span>
              Portal Khusus Administrator
            </span>
            <a
              class="text-xs font-medium text-[#6B7280] hover:text-[#0E9AA7] flex items-center gap-1 transition-colors cursor-pointer"
              @click.prevent="navigate('beranda')"
            >
              <span>Ke Halaman Publik</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
          </div>
          <h2 class="text-2xl sm:text-3xl font-bold text-[#1F2937]">Masuk ke Dashboard</h2>
          <p class="text-sm text-[#6B7280] mt-1">Masukkan kredensial akun terverifikasi Anda untuk mengelola sistem.</p>
        </div>

        <!-- ============================================== -->
        <!-- LOGIN FORM + ERROR STATE ALERT -->
        <!-- ============================================== -->
        <div class="space-y-5">
          <!-- ERROR STATE ALERT -->
          <div
            v-if="errorMessage"
            role="alert"
            class="bg-[#FEECEE] border-l-4 border-[#E5484D] rounded-r-lg p-3.5 flex items-start gap-3 animate-fade-in"
          >
            <svg class="w-5 h-5 text-[#E5484D] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/>
              <line x1="12" y1="8" x2="12" y2="12"/>
              <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div class="flex-1">
              <p class="text-xs font-bold text-[#E5484D]">Autentikasi Gagal</p>
              <p class="text-xs text-[#1F2937] mt-0.5">{{ errorMessage }}</p>
            </div>
            <button
              aria-label="Tutup pesan error"
              class="text-[#E5484D] hover:opacity-75 cursor-pointer text-xs font-bold"
              type="button"
              @click="errorMessage = ''"
            >
              ✕
            </button>
          </div>

          <!-- Login Form Card -->
          <form class="space-y-4" @submit.prevent="handleLogin">
            <!-- Field: Email -->
            <div>
              <label class="block text-xs font-semibold text-[#1F2937] uppercase tracking-wider mb-1.5" for="input-email">
                Alamat Email <span class="text-[#E5484D]">*</span>
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#6B7280]">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                </div>
                <input
                  id="input-email"
                  v-model="email"
                  name="email"
                  autocomplete="email"
                  :class="[
                    'w-full pl-9 pr-3 py-2.5 text-sm bg-white rounded-lg text-[#1F2937] focus:outline-none focus:ring-0 transition-colors',
                    errorMessage
                      ? 'border border-[#E5484D] focus:border-[#0E9AA7]'
                      : 'border border-[#E5E9ED] focus:border-[#0E9AA7]'
                  ]"
                  placeholder="nama@jejaknusa.com"
                  required
                  type="email"
                >
              </div>
            </div>

            <!-- Field: Password with Show/Hide icon toggle -->
            <div>
              <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-semibold text-[#1F2937] uppercase tracking-wider" for="input-password">
                  Kata Sandi <span class="text-[#E5484D]">*</span>
                </label>
                <a
                  class="text-xs font-semibold text-[#0E9AA7] hover:text-[#0B7A85] transition-colors cursor-pointer"
                  @click.prevent="alert('Silakan hubungi IT Support untuk reset password akun admin.')"
                >
                  Lupa password?
                </a>
              </div>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#6B7280]">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input
                  id="input-password"
                  v-model="password"
                  name="password"
                  autocomplete="current-password"
                  :class="[
                    'w-full pl-9 pr-10 py-2.5 text-sm bg-white rounded-lg text-[#1F2937] focus:outline-none focus:ring-0 transition-colors',
                    errorMessage
                      ? 'border border-[#E5484D] focus:border-[#0E9AA7]'
                      : 'border border-[#E5E9ED] focus:border-[#0E9AA7]'
                  ]"
                  placeholder="••••••••"
                  required
                  :type="showPassword ? 'text' : 'password'"
                >
                <!-- Show / Hide Password Icon Button -->
                <button
                  aria-label="Tampilkan atau sembunyikan kata sandi"
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B7280] hover:text-[#1F2937] focus:outline-none cursor-pointer"
                  type="button"
                  @click="togglePassword"
                >
                  <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Checkbox: Ingat saya -->
            <div class="flex items-center justify-between pt-1">
              <label class="flex items-center gap-2.5 cursor-pointer">
                <input
                  v-model="rememberMe"
                  class="w-4 h-4 text-[#0E9AA7] rounded border-[#E5E9ED] focus:ring-0 cursor-pointer accent-[#0E9AA7]"
                  type="checkbox"
                >
                <span class="text-xs font-medium text-[#1F2937] select-none">Ingat saya di perangkat ini (30 hari)</span>
              </label>
            </div>

            <!-- Full-width Orange Button "Masuk" -->
            <AppButton variant="primary" size="md" type="submit" block class="mt-2 !py-3">
              <span>Masuk ke Akun</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </AppButton>
          </form>

          <!-- Alternative / Quick Action: Hubungi IT Support -->
          <div class="pt-4 border-t border-[#E5E9ED] text-center">
            <p class="text-xs text-[#6B7280]">
              Mengalami kendala otorisasi atau akun terkunci? 
              <a class="font-semibold text-[#0E9AA7] hover:underline ml-1 cursor-pointer" @click.prevent="navigate('kontak')">
                Bantuan Teknis Admin
              </a>
            </p>
          </div>
        </div>

                <!-- Bottom Footer Meta -->
        <div class="mt-8 text-center text-xs text-[#6B7280] border-t border-[#E5E9ED] pt-4">
          <p>© 2026 Jejak Nusa Tour &amp; Travel. Hak Cipta Dilindungi.</p>
          <p class="mt-0.5 text-[11px] text-[#6B7280]">Sistem Internal Terbatas • Dilarang Membagikan Akses Kredensial</p>
        </div>

      </div>
    </div>
  </div>
</template>
