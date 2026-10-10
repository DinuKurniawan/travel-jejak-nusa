import { onMounted, ref } from 'vue';

// Fetch-once with static fallback: pages stay pixel-identical offline,
// live data wins when API responds.
export function useApiList(fetcher, fallback = []) {
  const items = ref(fallback);
  const loading = ref(true);
  const error = ref('');
  const live = ref(false);

  async function load(...args) {
    loading.value = true;
    error.value = '';
    try {
      const data = await fetcher(...args);
      const list = Array.isArray(data) ? data : (data.data || []);
      if (list.length > 0) {
        items.value = list;
        live.value = true;
      }
    } catch (e) {
      error.value = e.message || 'Gagal memuat data.';
    } finally {
      loading.value = false;
    }
  }

  onMounted(() => load());

  return { items, loading, error, live, reload: load };
}
