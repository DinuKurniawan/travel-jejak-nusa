<script setup>
const roles = [
  { id: 'superadmin', label: 'Superadmin', desc: 'Akses penuh: semua modul, pengguna, dan pengaturan.' },
  { id: 'admin', label: 'Admin', desc: 'Kelola konten, pemesanan, dan pesan. Tanpa ubah peran pengguna.' },
  { id: 'editor', label: 'Editor', desc: 'Tulis dan ubah artikel, FAQ, galeri, testimoni. Tanpa hapus.' },
];
const matrix = [
  ['Paket & Booking', true, true, false],
  ['Artikel & Media', true, true, true],
  ['Pengaturan Situs', true, false, false],
  ['Pengguna & Peran', true, false, false],
];
</script>

<template>
  <div class="flex flex-col gap-4 max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div v-for="r in roles" :key="r.id" class="bg-white rounded-xl border border-card-border p-5">
        <h2 class="font-title-lg text-title-lg text-ink font-semibold">{{ r.label }}</h2>
        <p class="font-body-md text-body-md text-muted mt-1">{{ r.desc }}</p>
      </div>
    </div>
    <div class="bg-white rounded-xl border border-card-border overflow-hidden">
      <table class="w-full text-left">
        <thead>
          <tr class="bg-surface-container-low border-b-[1.5px] border-card-border">
            <th scope="col" class="px-4 py-3 font-label-sm text-label-sm text-muted font-semibold uppercase">Modul</th>
            <th scope="col" class="px-4 py-3 font-label-sm text-label-sm text-muted font-semibold uppercase">Superadmin</th>
            <th scope="col" class="px-4 py-3 font-label-sm text-label-sm text-muted font-semibold uppercase">Admin</th>
            <th scope="col" class="px-4 py-3 font-label-sm text-label-sm text-muted font-semibold uppercase">Editor</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in matrix" :key="row[0]" class="border-b border-card-border last:border-0">
            <td class="px-4 py-3 font-label-md text-label-md text-ink">{{ row[0] }}</td>
            <td v-for="(v, i) in row.slice(1)" :key="i" class="px-4 py-3 text-lg" :class="v ? 'text-[#2EB67D]' : 'text-muted'">
              {{ v ? '✓' : '—' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p class="text-xs text-muted">Ubah peran tiap akun di modul Pengguna. Model single-role disengaja (YAGNI) — tambah tabel permissions saat butuh granular.</p>
  </div>
</template>
