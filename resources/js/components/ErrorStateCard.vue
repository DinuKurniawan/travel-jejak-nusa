<script setup>
import { computed } from 'vue';
import AppButton from './AppButton.vue';

const props = defineProps({
  code: { type: String, default: '' },
  badgeText: { type: String, default: '' },
  badgeVariant: { type: String, default: 'teal' }, // teal, orange, yellow, danger, ink
  title: { type: String, required: true },
  description: { type: String, required: true },
  illustrationSrc: { type: String, required: true },
  illustrationAlt: { type: String, default: 'Ilustrasi status Jejak Nusa' },
  primaryActionLabel: { type: String, default: 'Kembali ke Beranda' },
  primaryActionIcon: { type: String, default: 'home' },
  secondaryActionLabel: { type: String, default: '' },
  secondaryActionIcon: { type: String, default: '' },
  secondaryActionVariant: { type: String, default: 'outline' },
  showSupportHelp: { type: Boolean, default: true },
  supportHelpText: { type: String, default: 'Butuh bantuan segera dari travel consultant?' },
  extraDetails: { type: String, default: '' },
});

const emit = defineEmits(['primary-action', 'secondary-action']);

const badgeClasses = computed(() => {
  switch (props.badgeVariant) {
    case 'danger':
      return 'bg-[#FEECEE] border-[#E5484D] text-[#E5484D]';
    case 'warning':
    case 'yellow':
      return 'bg-[#FEF9E7] border-[#F6C945] text-[#755B00]';
    case 'orange':
      return 'bg-[#FFF0EA] border-[#F4743B] text-[#DE5E26]';
    case 'ink':
      return 'bg-[#F3F6F8] border-[#1F2937] text-[#1F2937]';
    case 'teal':
    default:
      return 'bg-[#E6F6F7] border-[#0E9AA7] text-[#0B7A85]';
  }
});
</script>

<template>
  <div class="w-full bg-[#FFFFFF] border border-[#E5E9ED] rounded-xl p-6 sm:p-8 md:p-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
      <!-- Sisi Kiri: Ilustrasi Vektor Flat 2.0 -->
      <div class="lg:col-span-5 flex justify-center items-center">
        <div class="w-full max-w-[280px] sm:max-w-[320px] md:max-w-[360px] aspect-[4/3] flex items-center justify-center p-2 rounded-xl bg-[#F3F6F8] border border-[#E5E9ED]">
          <img
            :src="illustrationSrc"
            :alt="illustrationAlt"
            class="w-full h-full object-contain select-none"
            loading="lazy"
          />
        </div>
      </div>

      <!-- Sisi Kanan: Konten Informasi & Aksi Solutif -->
      <div class="lg:col-span-7 flex flex-col items-start text-left">
        <!-- Badges Bar -->
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <span
            v-if="code"
            class="px-2.5 py-0.5 rounded-full border text-xs font-bold font-inter tracking-wide"
            :class="badgeClasses"
          >
            STATUS {{ code }}
          </span>
          <span
            v-if="badgeText"
            class="px-3 py-0.5 rounded-full border text-xs font-semibold font-inter"
            :class="badgeClasses"
          >
            {{ badgeText }}
          </span>
        </div>

        <!-- Judul Utama (Epilogue Bold) -->
        <h1 class="font-epilogue font-bold text-2xl sm:text-3xl md:text-display-mobile lg:text-headline-lg text-[#1F2937] leading-tight mb-4">
          {{ title }}
        </h1>

        <!-- Deskripsi Ramah Tamah Pemandu Nusantara -->
        <p class="font-inter text-base sm:text-lg text-[#4B5563] leading-relaxed mb-6 max-w-xl">
          {{ description }}
        </p>

        <!-- Slot Tambahan (Misal rincian checklist, panduan atau custom form) -->
        <div v-if="$slots.details" class="w-full mb-6">
          <slot name="details" />
        </div>

        <!-- Extra Technical Details Box (Jika Ada) -->
        <div
          v-if="extraDetails"
          class="w-full mb-6 p-4 rounded-lg bg-[#F3F6F8] border border-[#E5E9ED] font-inter text-xs text-[#6B7280]"
        >
          <div class="flex items-center gap-2 font-semibold text-[#1F2937] mb-1">
            <span class="material-symbols-outlined text-[16px] text-[#6B7280]">info</span>
            <span>Detail Catatan Sistem:</span>
          </div>
          <code class="block font-mono text-[#4B5563] break-all">{{ extraDetails }}</code>
        </div>

        <!-- Tombol Aksi Tanggap (Action Group) -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto mb-6">
          <AppButton
            v-if="primaryActionLabel"
            variant="primary"
            size="lg"
            class="w-full sm:w-auto"
            @click="emit('primary-action')"
          >
            <span v-if="primaryActionIcon" class="material-symbols-outlined text-[20px]">{{ primaryActionIcon }}</span>
            <span>{{ primaryActionLabel }}</span>
          </AppButton>

          <AppButton
            v-if="secondaryActionLabel"
            :variant="secondaryActionVariant"
            size="lg"
            class="w-full sm:w-auto"
            @click="emit('secondary-action')"
          >
            <span v-if="secondaryActionIcon" class="material-symbols-outlined text-[20px]">{{ secondaryActionIcon }}</span>
            <span>{{ secondaryActionLabel }}</span>
          </AppButton>
        </div>

        <!-- Fast Contact Assistance Strip (Flat AA) -->
        <div
          v-if="showSupportHelp"
          class="w-full pt-4 border-t border-[#E5E9ED] flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs font-inter text-[#6B7280]"
        >
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-[#0E9AA7]">support_agent</span>
            <span>{{ supportHelpText }}</span>
          </div>
          <a
            href="https://wa.me/6281234567890?text=Halo%20Jejak%20Nusa%2C%20saya%20butuh%20bantuan%20terkait%20halaman%20ini"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-1.5 font-semibold text-[#0E9AA7] hover:text-[#0B7A85] transition-colors"
          >
            <span>Hubungi WhatsApp Pemandu (24/7)</span>
            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
