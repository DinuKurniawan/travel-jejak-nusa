<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: { type: String, default: '' },
});

// Flat pill badges per Stitch spec: tint bg + colored text, no shadow.
const map = {
  menunggu_pembayaran: 'bg-warning-tint text-warning',
  menunggu_verifikasi: 'bg-info-tint text-info',
  terkonfirmasi: 'bg-success-tint text-success',
  selesai: 'bg-success-tint text-success',
  dibatalkan: 'bg-danger-light text-danger',
  kedaluwarsa: 'bg-danger-light text-danger',
  refund_diproses: 'bg-info-tint text-info',
};

const labels = {
  menunggu_pembayaran: 'Menunggu Pembayaran',
  menunggu_verifikasi: 'Menunggu Verifikasi',
  terkonfirmasi: 'Terkonfirmasi',
  selesai: 'Selesai',
  dibatalkan: 'Dibatalkan',
  kedaluwarsa: 'Kedaluwarsa',
  refund_diproses: 'Refund Diproses',
};

const cls = computed(() => [
  'inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold whitespace-nowrap',
  map[props.status] || 'bg-page text-muted border border-card-border',
].join(' '));
</script>

<template>
  <span :class="cls">{{ labels[status] || status }}</span>
</template>
