<script setup>
import { onMounted, ref } from 'vue';
import { api } from '../../api.js';
import PackageCard from '../../components/PackageCard.vue';
import EmptyState from '../../components/EmptyState.vue';
import AppButton from '../../components/AppButton.vue';

const emit = defineEmits(['navigate']);

const loading = ref(true);
const error = ref('');
const items = ref([]);

function toCard(w) {
  const p = w.package || {};

  return {
    id: p.id,
    image: p.image || '/images/img-04.jpg',
    badge: p.badge || '',
    badgeVariant: 'promo',
    badgeShape: 'box',
    location: p.location || '',
    title: p.title || 'Paket Wisata',
    duration: p.duration || '',
    type: p.trip_type === 'private' ? 'Private Trip' : 'Open Trip',
    typeClass: p.trip_type === 'private' ? 'bg-yellow-light text-[#755b00]' : undefined,
    rating: String(p.rating ?? ''),
    reviews: String(p.reviews_count ?? ''),
    price: p.price ? `Rp ${Number(p.price).toLocaleString('id-ID')}` : '',
    wished: true,
  };
}

async function load() {
  loading.value = true;
  error.value = '';
  try {
    items.value = (await api.userWishlist()).map(toCard);
  } catch (e) {
    error.value = e.message || 'Gagal memuat wishlist.';
  } finally {
    loading.value = false;
  }
}

onMounted(load);

async function remove(id) {
  try {
    await api.userToggleWishlist({ package_id: id });
    await load();
  } catch (e) {
    error.value = e.message || 'Gagal menghapus wishlist.';
  }
}
</script>

<template>
  <div>
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" aria-label="Memuat wishlist">
      <div v-for="n in 3" :key="n" class="bg-white border border-card-border rounded-xl h-80 animate-pulse" />
    </div>

    <div v-else-if="error && items.length === 0" class="bg-white border border-card-border rounded-xl p-8 text-center">
      <span class="material-symbols-outlined text-[48px] text-muted" aria-hidden="true">cloud_off</span>
      <h2 class="font-jakarta font-bold text-[18px] text-ink mt-2">Gagal memuat wishlist</h2>
      <p class="font-body-md text-body-md text-muted mt-1">{{ error }}</p>
      <div class="mt-4"><AppButton variant="secondary" @click="load">Coba Lagi</AppButton></div>
    </div>

    <div v-else-if="items.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
      <PackageCard
        v-for="p in items"
        :key="p.id"
        :image="p.image"
        :title="p.title"
        :location="p.location"
        :badge="p.badge"
        :badge-variant="p.badgeVariant"
        :badge-shape="p.badgeShape"
        :duration="p.duration"
        :type="p.type"
        :type-class="p.typeClass"
        :rating="p.rating"
        :reviews="p.reviews"
        :price="p.price"
        :wished="p.wished"
        @wishlist="remove(p.id)"
        @detail="emit('navigate', 'paket-wisata')"
      />
    </div>
    <EmptyState
      v-else
      icon="favorite"
      title="Wishlist-mu masih kosong"
      description="Simpan destinasi impianmu dengan menekan ikon hati di tiap paket."
    >
      <AppButton variant="secondary" @click="emit('navigate', 'paket-wisata')">Cari Destinasi Impian</AppButton>
    </EmptyState>
  </div>
</template>
