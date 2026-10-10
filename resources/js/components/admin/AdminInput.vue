<script setup>
defineProps({
  label: { type: String, default: '' },
  id: { type: String, default: '' },
  modelValue: { type: [String, Number], default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  error: { type: String, default: '' },
  required: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <div class="flex flex-col gap-1.5">
    <label v-if="label" :for="id" class="font-label-md text-label-md text-ink font-semibold">
      {{ label }} <span v-if="required" class="text-error">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :aria-invalid="!!error"
      :class="error ? 'border-error' : 'border-card-border focus:border-teal'"
      class="h-10 px-3 bg-white border-[1.5px] rounded-lg font-body-md text-body-md text-ink placeholder:text-muted focus:outline-none transition-colors"
      @input="emit('update:modelValue', $event.target.value)"
    >
    <p v-if="error" class="text-xs text-error">{{ error }}</p>
  </div>
</template>
