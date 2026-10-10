<script setup>
defineProps({
  tabs: { type: Array, default: () => [] },
  active: { type: String, default: '' },
});

const emit = defineEmits(['select']);
</script>

<template>
  <div class="flex items-center gap-6 border-b border-card-border overflow-x-auto" role="tablist">
    <button
      v-for="tab in tabs"
      :key="tab.id"
      :aria-selected="active === tab.id"
      :class="active === tab.id ? 'text-teal border-b-[2.5px] border-teal font-semibold' : 'text-muted hover:text-ink border-b-[2.5px] border-transparent'"
      class="pb-2.5 -mb-px font-label-md text-label-md whitespace-nowrap transition-colors cursor-pointer"
      role="tab"
      type="button"
      @click="emit('select', tab.id)"
    >
      {{ tab.label }}
    </button>
  </div>
</template>
