<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: { type: String, default: 'draft' },
});

const map = {
  active: ['Aktif', 'success'],
  published: ['Terbit', 'success'],
  verified: ['Terverifikasi', 'success'],
  paid: ['Lunas', 'success'],
  draft: ['Draft', 'warning'],
  pending: ['Menunggu', 'warning'],
  reschedule: ['Jadwal Ulang', 'warning'],
  rejected: ['Ditolak', 'danger'],
  inactive: ['Nonaktif', 'danger'],
  cancelled: ['Batal', 'danger'],
  expired: ['Kedaluarsa', 'danger'],
  archived: ['Arsip', 'neutral'],
  closed: ['Tutup', 'neutral'],
};

const styles = {
  success: 'bg-[#EAF8F2] text-[#2EB67D]',
  warning: 'bg-[#FEF6E9] text-[#F5A524]',
  danger: 'bg-[#FCECED] text-[#E5484D]',
  neutral: 'bg-[#F1F3F5] text-muted',
};

const resolved = computed(() => map[props.status] || ['Draft', 'warning']);
</script>

<template>
  <span :class="['inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wide whitespace-nowrap', styles[resolved[1]]]">{{ resolved[0] }}</span>
</template>
