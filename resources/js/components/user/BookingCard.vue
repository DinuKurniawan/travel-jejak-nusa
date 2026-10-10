<script setup>
import AppButton from '../AppButton.vue';
import StatusBadge from './StatusBadge.vue';
import { formatRupiah } from '../../api.js';

defineProps({
  code: { type: String, default: '' },
  title: { type: String, default: '' },
  image: { type: String, default: '/images/img-04.jpg' },
  date: { type: String, default: '' },
  meta: { type: String, default: '' },
  status: { type: String, default: '' },
  total: { type: [Number, String], default: 0 },
  actionLabel: { type: String, default: 'Lihat Detail' },
});

const emit = defineEmits(['detail', 'primary']);
</script>

<template>
  <article class="bg-white border border-card-border rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row gap-4">
    <img :alt="title" :src="image" class="w-full sm:w-28 h-40 sm:h-24 object-cover rounded-lg bg-page shrink-0" loading="lazy">
    <div class="flex-1 min-w-0">
      <div class="flex flex-wrap items-center gap-2">
        <StatusBadge :status="status" />
        <span class="font-body-sm text-body-sm text-muted tabular-nums">{{ code }}</span>
      </div>
      <h3 class="font-title-lg text-title-lg text-ink font-semibold mt-1.5 line-clamp-1">{{ title }}</h3>
      <p class="font-body-sm text-body-sm text-muted mt-0.5">{{ date }}<span v-if="meta"> • {{ meta }}</span></p>
      <div class="flex flex-wrap items-center justify-between gap-3 mt-3">
        <p class="font-label-md text-label-md text-ink font-bold tabular-nums">{{ typeof total === 'number' ? formatRupiah(total) : total }}</p>
        <div class="flex items-center gap-2">
          <AppButton size="sm" variant="outline" @click="emit('detail')">{{ actionLabel }}</AppButton>
          <slot name="actions" />
        </div>
      </div>
    </div>
  </article>
</template>
