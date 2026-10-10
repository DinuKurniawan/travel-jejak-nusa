import { computed, onMounted, ref } from 'vue';
import { api } from './api.js';

// Server-backed admin list (live API only).
export function useAdminResource(resource, fallbackRows = []) {
  const rows = ref(fallbackRows);
  const loading = ref(true);
  const error = ref('');
  const live = ref(false);

  async function load(params = {}) {
    loading.value = true;
    error.value = '';
    try {
      const data = await api.adminList(resource, params);
      const list = Array.isArray(data) ? data : (data.data || []);
      if (list.length > 0 || live.value) {
        rows.value = list;
        live.value = true;
      }
    } catch (e) {
      error.value = e.message || 'Gagal memuat data.';
    } finally {
      loading.value = false;
    }
  }

  async function save(payload, editingId) {
    if (editingId) {
      const updated = await api.adminUpdate(resource, editingId, payload);
      const i = rows.value.findIndex((r) => r.id === editingId);
      if (i >= 0) {
        rows.value[i] = updated;
      }

      return updated;
    }
    const created = await api.adminCreate(resource, payload);
    rows.value.unshift(created);
    live.value = true;

    return created;
  }

  async function remove(id) {
    await api.adminDelete(resource, id);
    rows.value = rows.value.filter((r) => r.id !== id);
  }

  onMounted(() => load());

  const count = computed(() => rows.value.length);

  return { rows, loading, error, live, count, load, save, remove };
}
