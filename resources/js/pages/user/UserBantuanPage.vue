<script setup>
import { onMounted, ref } from 'vue';
import FaqAccordion from '../../components/FaqAccordion.vue';
import AppButton from '../../components/AppButton.vue';
import { api } from '../../api.js';

const emit = defineEmits(['navigate']);

const loadingFaq = ref(true);
const faqError = ref('');
const faqs = ref([]);

async function loadFaq() {
  loadingFaq.value = true;
  faqError.value = '';
  try {
    faqs.value = await api.faqs();
  } catch (e) {
    faqError.value = e.message || 'Gagal memuat FAQ.';
  } finally {
    loadingFaq.value = false;
  }
}

onMounted(loadFaq);

const message = ref('');
const profile = ref({ name: '', email: '' });
const sendNotice = ref('');
const sendError = ref('');
const sending = ref(false);

onMounted(async () => {
  try {
    const me = await api.userProfile();
    profile.value = { name: me.name || '', email: me.email || '' };
  } catch { /* form tetap bisa dikirim tanpa prefill */ }
});

async function send() {
  if (message.value.trim().length < 10) {
    sendError.value = 'Tulis pesan minimal 10 karakter ya.';
    return;
  }
  sending.value = true;
  sendNotice.value = '';
  sendError.value = '';
  try {
    await api.sendContact({
      name: profile.value.name || 'Traveler Jejak Nusa',
      email: profile.value.email || 'traveler@jejaknusa.id',
      subject: 'lainnya',
      message: message.value.trim(),
    });
    sendNotice.value = 'Pesan terkirim. Kami balas maksimal 1x24 jam.';
    message.value = '';
  } catch (e) {
    sendError.value = e.message || 'Gagal mengirim pesan.';
  } finally {
    sending.value = false;
  }
}
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 max-w-6xl">
    <section class="lg:col-span-2 bg-white border border-card-border rounded-xl p-4 sm:p-6" aria-labelledby="bantuan-faq">
      <h2 id="bantuan-faq" class="font-jakarta font-bold text-[18px] text-ink">Pertanyaan populer</h2>
      <div v-if="loadingFaq" class="mt-3 flex flex-col gap-3" aria-label="Memuat FAQ">
        <div v-for="n in 3" :key="n" class="border border-card-border rounded-xl h-16 animate-pulse" />
      </div>
      <div v-else-if="faqError && faqs.length === 0" class="mt-3 text-center py-6">
        <p class="font-body-md text-body-md text-muted">{{ faqError }}</p>
        <div class="mt-3"><AppButton variant="secondary" size="sm" @click="loadFaq">Coba Lagi</AppButton></div>
      </div>
      <FaqAccordion v-else :items="faqs" class="mt-3" />
    </section>

    <div class="flex flex-col gap-4">
      <section class="bg-teal rounded-xl p-5 text-white" aria-labelledby="bantuan-cs">
        <span class="material-symbols-outlined text-[32px]" aria-hidden="true">support_agent</span>
        <h2 id="bantuan-cs" class="font-jakarta font-bold text-[18px] mt-2">Butuh bantuan cepat?</h2>
        <p class="text-sm opacity-90 mt-1">Tim CS kami online setiap hari 08.00–21.00 WIB.</p>
        <div class="flex flex-col gap-2 mt-4">
          <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="h-11 inline-flex items-center justify-center gap-2 rounded-lg bg-white text-teal font-label-md text-label-md font-semibold hover:bg-info-tint transition-colors">Chat WhatsApp</a>
          <AppButton variant="outlineWhite" @click="emit('navigate', 'kontak')">Formulir Kontak</AppButton>
        </div>
      </section>

      <form class="bg-white border border-card-border rounded-xl p-4 sm:p-5" aria-labelledby="bantuan-pesan" @submit.prevent="send">
        <h2 id="bantuan-pesan" class="font-jakarta font-bold text-[18px] text-ink">Kirim pesan</h2>
        <p v-if="sendNotice" role="status" class="mt-2 bg-info-tint border border-teal/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-ink">{{ sendNotice }}</p>
        <p v-if="sendError" role="alert" class="mt-2 bg-danger-light border border-danger/30 rounded-lg px-3.5 py-2.5 font-body-md text-body-md text-danger">{{ sendError }}</p>
        <label class="block font-label-sm text-label-sm text-ink font-medium mt-3 mb-1.5" for="bantuan-teks">Apa yang bisa kami bantu?</label>
        <textarea id="bantuan-teks" v-model="message" rows="4" placeholder="Tulis kendalamu di sini… (min. 10 karakter)" class="w-full px-3 py-2.5 bg-white border-[1.5px] border-card-border rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none focus:border-teal" />
        <AppButton variant="secondary" type="submit" block class="mt-3" :disabled="sending">{{ sending ? 'Mengirim…' : 'Kirim Pesan' }}</AppButton>
      </form>
    </div>
  </div>
</template>
