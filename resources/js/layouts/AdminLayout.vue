<script setup>
import { ref } from 'vue';
import AdminSidebar from '../components/admin/AdminSidebar.vue';
import AdminTopbar from '../components/admin/AdminTopbar.vue';

defineProps({
  active: { type: String, default: 'dashboard' },
  title: { type: String, default: 'Dashboard' },
  subtitle: { type: String, default: '' },
});

const emit = defineEmits(['navigate', 'logout', 'profile']);

const sidebarOpen = ref(false);
</script>

<template>
  <div class="min-h-screen bg-surface-container-low text-ink flex">
    <AdminSidebar
      :active="active"
      :open="sidebarOpen"
      @navigate="(id) => { sidebarOpen = false; emit('navigate', id); }"
      @close="sidebarOpen = false"
      @logout="emit('logout')"
    />
    <div class="flex-1 min-w-0 flex flex-col">
      <AdminTopbar
        :title="title"
        :subtitle="subtitle"
        @menu="sidebarOpen = true"
        @profile="emit('profile')"
      />
      <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>
