<script setup>
import { computed } from 'vue';
import AppBadge from './AppBadge.vue';
import WishlistButton from './WishlistButton.vue';

const props = defineProps({
  image: { type: String, required: true },
  title: { type: String, required: true },
  location: { type: String, default: '' },
  badge: { type: String, default: '' },
  badgeVariant: { type: String, default: 'promo' },
  badgeShape: { type: String, default: 'pill' },
  badgeClass: { type: String, default: '' },
  seatBadge: { type: String, default: '' },
  duration: { type: String, default: '' },
  durationClass: { type: String, default: 'bg-surface-container text-on-surface-variant' },
  type: { type: String, default: '' },
  typeClass: { type: String, default: 'bg-teal-light text-teal' },
  chips: { type: Array, default: () => [] },
  rating: { type: [Number, String], default: '' },
  reviews: { type: [Number, String], default: '' },
  ratingStyle: { type: String, default: 'star' },
  price: { type: String, default: '' },
  priceSuffix: { type: String, default: '/ pax' },
  titleVariant: { type: String, default: 'hero' },
  seatClass: { type: String, default: '' },
  ctaClass: { type: String, default: 'bg-teal hover:bg-teal-dark' },
  wishSize: { type: String, default: 'md' },
  wished: { type: Boolean, default: false },
  ctaLabel: { type: String, default: 'Lihat Detail Paket' },
});

const emit = defineEmits(['wishlist', 'detail']);

const showMeta = computed(() => props.duration || props.type);
const showRating = computed(() => props.rating !== '');
const titleCls = computed(() => props.titleVariant === 'list'
  ? 'font-headline-sm text-headline-sm text-on-surface line-clamp-1'
  : 'font-title-lg text-title-lg text-on-surface font-bold leading-snug line-clamp-2 min-h-[44px] mb-2.5');
const defaultSeatCls = 'absolute bottom-3 left-3 px-2.5 py-0.5 rounded-md bg-ink/80 text-white font-label-sm text-[11px] font-medium';
</script>

<template>
  <article class="bg-surface-container-lowest border border-card-border rounded-xl overflow-hidden hover:border-teal transition-colors flex flex-col group">
    <div class="relative w-full h-52 bg-surface-container overflow-hidden">
      <img :alt="title" :src="image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
      <AppBadge v-if="badge" :variant="badgeVariant" :shape="badgeShape" :class="['absolute top-3 left-3', badgeClass]">{{ badge }}</AppBadge>
      <span v-if="seatBadge" :class="seatClass || defaultSeatCls">{{ seatBadge }}</span>
      <WishlistButton :active="wished" :size="wishSize" :label="'Simpan ' + title" @toggle="emit('wishlist')" />
    </div>
    <div class="p-5 flex flex-col flex-1 justify-between gap-4">
      <div>
        <p v-if="location" class="flex items-center gap-1 text-muted font-label-sm text-label-sm mb-1.5">
          <span class="material-symbols-outlined text-[15px] text-teal">location_on</span>
          <span>{{ location }}</span>
        </p>
        <h3 :class="titleCls">{{ title }}</h3>
        <div v-if="showMeta || chips.length" class="flex items-center gap-2 flex-wrap mb-2.5">
          <span v-if="duration" :class="['px-2.5 py-1 rounded-md text-xs font-medium', durationClass]">{{ duration }}</span>
          <span v-if="type" :class="['px-2.5 py-1 rounded-md text-xs font-semibold', typeClass]">{{ type }}</span>
          <span
            v-for="(chip, i) in chips"
            :key="i"
            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded bg-surface-container border border-card-border text-xs text-[#3D494A]"
          >
            <span v-if="chip.icon" class="material-symbols-outlined text-[14px] text-muted">{{ chip.icon }}</span>
            {{ chip.label }}
          </span>
        </div>
        <p v-if="showRating && ratingStyle === 'star'" class="flex items-center gap-1.5 text-xs text-muted">
          <span class="text-yellow text-sm leading-none" aria-hidden="true">★</span>
          <span class="font-bold text-on-surface">{{ rating }}</span>
          <span>({{ reviews }} ulasan)</span>
        </p>
        <p v-else-if="showRating" class="flex items-center gap-1 text-on-surface font-label-md text-label-sm">
          <span class="material-symbols-outlined text-tertiary-container text-[16px]" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">star</span>
          <span class="font-title-lg">{{ rating }}</span>
          <span class="text-outline font-body-md">({{ reviews }} ulasan)</span>
        </p>
      </div>
      <div class="pt-3 border-t border-card-border">
        <div class="mb-3">
          <span class="block text-xs text-muted">Mulai dari</span>
          <p class="font-headline-sm text-lg text-teal font-bold leading-none">{{ price }} <span class="font-body-md text-xs font-normal text-muted">{{ priceSuffix }}</span></p>
        </div>
        <button
          :class="['w-full inline-flex items-center justify-center py-2.5 px-4 rounded-lg text-white font-label-md text-sm font-semibold transition-colors cursor-pointer', ctaClass]"
          type="button"
          @click="emit('detail')"
        >
          {{ ctaLabel }}
        </button>
      </div>
    </div>
  </article>
</template>
