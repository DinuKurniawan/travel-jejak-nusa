<script setup>
import { ref } from 'vue';

const props = defineProps({
  items: { type: Array, default: () => [] },
  multiple: { type: Boolean, default: false },
  iconStyle: { type: String, default: 'plus' },
});

const openIds = ref(new Set());

function isOpen(id) {
  return openIds.value.has(id);
}

function toggle(id) {
  if (isOpen(id)) {
    openIds.value.delete(id);
  } else {
    if (!props.multiple) {
      openIds.value.clear();
    }
    openIds.value.add(id);
  }
}
</script>

<template>
  <div class="flex flex-col gap-3">
    <div
      v-for="item in items"
      :key="item.id ?? item.question"
      class="bg-surface-container-lowest border border-card-border rounded-xl overflow-hidden"
    >
      <h3>
        <button
          :aria-expanded="isOpen(item.id ?? item.question)"
          class="w-full px-6 py-5 flex items-center justify-between text-left gap-4 cursor-pointer select-none"
          type="button"
          @click="toggle(item.id ?? item.question)"
        >
          <span class="font-title-lg text-title-lg text-on-surface font-semibold">{{ item.question }}</span>
          <span
            v-if="iconStyle === 'plus'"
            :class="isOpen(item.id ?? item.question) ? 'bg-teal text-white' : 'bg-surface-container text-teal'"
            class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-colors"
          >
            <span class="material-symbols-outlined text-[18px]">{{ isOpen(item.id ?? item.question) ? 'remove' : 'add' }}</span>
          </span>
          <span
            v-else
            class="material-symbols-outlined text-teal text-[24px] shrink-0 transition-transform"
            :style="isOpen(item.id ?? item.question) ? 'transform: rotate(180deg)' : 'transform: rotate(0deg)'"
          >
            expand_more
          </span>
        </button>
      </h3>
      <div
        v-show="isOpen(item.id ?? item.question)"
        class="px-6 pb-6 pt-1 text-on-surface-variant font-body-md text-body-md border-t border-card-border"
      >
        <slot :item="item">{{ item.answer }}</slot>
      </div>
    </div>
  </div>
</template>
