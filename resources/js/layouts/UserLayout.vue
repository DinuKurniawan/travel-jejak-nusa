<script setup>
import { onMounted, ref } from 'vue';
import UserSidebar from '../components/user/UserSidebar.vue';
import UserTopbar from '../components/user/UserTopbar.vue';
import UserBottomNav from '../components/user/UserBottomNav.vue';
import { api } from '../api.js';
import { initialsOf } from '../userStatus.js';

defineProps({
  active: { type: String, default: 'ringkasan' },
  title: { type: String, default: 'Ringkasan' },
  subtitle: { type: String, default: '' },
});

const emit = defineEmits(['navigate', 'logout']);

const sidebarOpen = ref(false);
const profileName = ref('');
const profileInitial = ref('');

onMounted(async () => {
  try {
    const me = await api.userProfile();
    profileName.value = me.name || '';
    profileInitial.value = initialsOf(me.name);
  } catch {
    // biarkan placeholder "Memuat…"; halaman profil menampilkan error-nya sendiri
  }
});

const go = (id) => {
  sidebarOpen.value = false;
  emit('navigate', id);
};
</script>

<template>
  <div class="min-h-screen bg-page text-ink flex font-body-md text-body-md">
    <UserSidebar :active="active" :open="sidebarOpen" @navigate="go" @close="sidebarOpen = false" />
    <div class="flex-1 min-w-0 flex flex-col">
      <UserTopbar
        :title="title"
        :subtitle="subtitle"
        :user-name="profileName"
        :user-initial="profileInitial"
        @menu="sidebarOpen = true"
        @notifications="go('notifikasi')"
        @profile="go('profil')"
      />
      <main class="flex-1 w-full max-w-[1280px] mx-auto p-4 sm:p-6 pb-24 lg:pb-8">
        <slot />
      </main>
    </div>
    <UserBottomNav :active="active" @navigate="go" />
  </div>
</template>
