// Derivasi real dari record booking backend ke status badge Stitch.
// Backend: status pending|verified|paid|cancelled, payment_status unpaid|dp|paid.
export function bookingStatus(b) {
  if (!b) {
    return '';
  }
  if (b.status === 'cancelled') {
    return 'dibatalkan';
  }
  if (b.status === 'verified') {
    return 'menunggu_verifikasi';
  }
  if (b.status === 'paid' || b.payment_status === 'paid') {
    if (b.departure_date && new Date(b.departure_date) < new Date(new Date().toDateString())) {
      return 'selesai';
    }

    return 'terkonfirmasi';
  }

  return 'menunggu_pembayaran';
}

export function normalizeBooking(b) {
  const pkg = b.package || {};

  return {
    code: b.code || '',
    title: pkg.title || 'Paket Wisata',
    image: pkg.image || '/images/img-04.jpg',
    date: b.departure_date || '',
    meta: `${b.adults || 1} Dewasa${b.children ? ` + ${b.children} Anak` : ''}`,
    status: bookingStatus(b),
    total: b.total || 0,
  };
}

export function initialsOf(name) {
  return String(name || '?').split(/\s+/).filter(Boolean).slice(0, 2).map((w) => w[0]).join('').toUpperCase();
}
