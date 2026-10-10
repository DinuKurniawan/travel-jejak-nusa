<script setup>
import { computed, onMounted, ref } from 'vue';
import { api } from '../../api.js';
import AppButton from '../../components/AppButton.vue';
import AdminModal from '../../components/admin/AdminModal.vue';

const loading = ref(true);
const error = ref('');
const reviews = ref([]);
const bookings = ref([]);

const showForm = ref(false);
const targetCode = ref('');
const rating = ref(5);
const text = ref('');
const notice = ref('');
const formError = ref('');
const saving = ref(false);

async function load() {
  loading.value = true;
  error.value = '';
  try {
    const [r, b] = await Promise.all([api.userReviews(), api.userBookings({ per_page: 50 })]);
    reviews.value = r;
    bookings.value = Array.isArray(b) ? b : (b.data || []);
  } catch (e) {
    error.value = e.message || 'Gagal memuat ulasan.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

const reviewedCodes = computed(() => new Set(reviews.value.map((r) => r.booking_code).filter(Boolean)));

const pending = computed(() => bookings.value
  .filter((b) => (b.status === 'paid' || b.payment_status === 'paid') && !reviewedCodes.value.has(b.code))
  .map((b) => ({ code: b.code, title: b.package?.title || 'Paket Wisata', image: b.package?.image || '/images/img-04.jpg' })));

function bookingTitle(code) {
  return bookings.value.find((b) => b.code === code)?.package?.title || code || 'Paket Wisata';
}

function openForm(code) {
  targetCode.value = code;
  rating.value = 5;
  text.value = '';
  formError.value = '';
  showForm.value = true;
}

async function submit() {
  saving.value = true;
  formError.value = '';
  try {
    await api.userSubmitReview({ booking_code: targetCode.value, rating: rating.value, review: text.value });
    notice.value = 'Makasih ulasannya! Ceritamu membantu traveler lain.';
    showForm.value = false;
    await load();
  } catch (e) {
    formError.value = e.message || 'Gagal mengirim ulasan.';
  } finally {
    saving.value = false;
  }
}

function stars(n) {
  return '★'.repeat(n) + '☆'.repeat(5 - n);
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <p v-if="notice" role="status" class="bg-info-tint border border-teal/30 text-ink rounded-xl px-4 py-3 font-body-md text-body-md">{{ notice }}</p>

    <div v-if="loading" class="flex flex-col gap-3" aria-label="Memuat ulasan">
      <div v-for="n in 2" :key="n" class="bg-white border border-card-border rounded-xl h-28 animate-pulse" />
    </div>

    <div v-else-if="error && reviews.length === 0" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat ulasan</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <template v-else>
      <section v-if="pending.length > 0" class="bg-white border border-card-border rounded-xl p-4 sm:p-5" aria-labelledby="ulasan-menunggu">
        <h2 id="ulasan-menunggu" class="font-jakarta font-bold text-[18px] text-ink">Menunggu ulasanmu</h2>
        <ul class="mt-3 flex flex-col gap-2.5 list-none m-0 p-0">
          <li v-for="p in pending" :key="p.code" class="flex flex-wrap items-center gap-3 border border-card-border rounded-lg p-3">
            <img :alt="p.title" :src="p.image" class="w-16 h-16 rounded-lg object-cover bg-page">
            <div class="flex-1 min-w-40">
              <p class="font-label-md text-label-md font-semibold text-ink">{{ p.title }}</p>
              <p class="font-body-sm text-body-sm text-muted tabular-nums">{{ p.code }}</p>
            </div>
            <AppButton size="sm" variant="primary" @click="openForm(p.code)">Tulis Ulasan</AppButton>
          </li>
        </ul>
      </section>

      <section aria-labelledby="ulasan-riwayat">
        <h2 id="ulasan-riwayat" class="font-jakarta font-bold text-[18px] text-ink mb-3">Ulasan yang kamu tulis</h2>
        <div v-if="reviews.length > 0" class="flex flex-col gap-3">
          <article v-for="r in reviews" :key="r.id" class="bg-white border border-card-border rounded-xl p-4 sm:p-5">
            <div class="flex flex-wrap items-center justify-between gap-2">
              <div>
                <h3 class="font-label-md text-label-md font-semibold text-ink">{{ bookingTitle(r.booking_code) }}</h3>
                <p class="font-body-sm text-body-sm text-muted tabular-nums">{{ r.booking_code }} • {{ r.created_at || '' }}</p>
              </div>
              <p class="text-yellow font-bold tracking-wider" :aria-label="`Rating ${r.rating} dari 5`">{{ stars(r.rating) }}</p>
            </div>
            <p class="font-body-md text-body-md text-ink mt-2">{{ r.review }}</p>
          </article>
        </div>
        <p v-else class="bg-white border border-card-border rounded-xl p-6 text-center font-body-md text-body-md text-muted">Belum ada ulasan. Selesaikan trip pertamamu lalu ceritakan pengalamanmu.</p>
      </section>
    </template>

    <AdminModal :open="showForm" title="Tulis ulasanmu" @close="showForm = false" @confirm="submit">
      <p v-if="formError" role="alert" class="mb-3 bg-danger-light border border-danger/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-danger">{{ formError }}</p>
      <fieldset>
        <legend class="font-label-sm text-label-sm text-ink font-medium mb-2">Rating</legend>
        <div class="flex gap-1.5" role="radiogroup" aria-label="Pilih rating">
          <button
            v-for="n in 5"
            :key="n"
            :aria-checked="rating === n"
            :aria-label="`${n} bintang`"
            :class="n <= rating ? 'text-yellow' : 'text-card-border'"
            class="text-[32px] leading-none cursor-pointer"
            role="radio"
            type="button"
            @click="rating = n"
          >★</button>
        </div>
      </fieldset>
      <label class="block font-label-sm text-label-sm text-ink font-medium mt-4 mb-2" for="ulasan-teks">Ceritamu</label>
      <textarea id="ulasan-teks" v-model="text" rows="4" placeholder="Gimana pengalaman trip-mu? Apa yang paling berkesan?" class="w-full px-3 py-2.5 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal" />
      <template #footer>
        <button class="h-10 px-4 rounded-lg border-[1.5px] border-card-border text-ink font-label-md text-label-md hover:bg-page transition-colors cursor-pointer" type="button" @click="showForm = false">Urungkan</button>
        <button :disabled="saving" class="h-10 px-4 rounded-lg bg-orange text-white font-label-md text-label-md hover:bg-[#DE5E26] transition-colors cursor-pointer disabled:opacity-50" type="button" @click="submit">Kirim Ulasan</button>
      </template>
    </AdminModal>
  </div>
</template>
