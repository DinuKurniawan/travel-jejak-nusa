<script setup>
defineProps({
  columns: { type: Array, default: () => [] },
  caption: { type: String, default: '' },
});
</script>

<template>
  <div class="bg-white rounded-xl border border-card-border overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left min-w-[640px]">
        <caption v-if="caption" class="sr-only">{{ caption }}</caption>
        <thead>
          <tr class="bg-surface-container-low border-b-[1.5px] border-card-border">
            <th
              v-for="col in columns"
              :key="col.key"
              scope="col"
              class="px-4 py-3 font-label-sm text-label-sm text-muted font-semibold uppercase tracking-wider whitespace-nowrap"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <slot />
        </tbody>
      </table>
    </div>
  </div>
</template>
