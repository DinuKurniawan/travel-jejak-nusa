// Minimal fetch client: no new deps. Public GET stateless; admin + login via web session cookies + CSRF.
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

async function request(path, options = {}) {
  const res = await fetch(`/api/v1${path}`, {
    ...options,
    headers: { Accept: 'application/json', 'Content-Type': 'application/json', ...(options.headers || {}) },
  });
  if (res.status === 204) {
    return null;
  }
  const data = await res.json().catch(() => ({}));
  if (!res.ok) {
    const err = new Error(data.message || `Request gagal (${res.status})`);
    err.status = res.status;
    err.errors = data.errors || {};
    throw err;
  }

  return data;
}

const get = (path) => request(path);

function write(path, method, body) {
  return request(path, {
    method,
    credentials: 'same-origin',
    headers: { 'X-CSRF-TOKEN': csrf() },
    body: JSON.stringify(body || {}),
  });
}

export const api = {
  packages: (params = {}) => {
    const q = new URLSearchParams(Object.fromEntries(Object.entries(params).filter(([, v]) => v !== '' && v !== undefined)));
    const s = q.toString();

    return get(`/packages${s ? `?${s}` : ''}`);
  },
  featuredPackages: () => get('/packages/featured'),
  packageDetail: (slug) => get(`/packages/${slug}`),
  articles: (params = {}) => {
    const q = new URLSearchParams(Object.fromEntries(Object.entries(params).filter(([, v]) => v !== '' && v !== undefined)));
    const s = q.toString();

    return get(`/articles${s ? `?${s}` : ''}`);
  },
  articleDetail: (slug) => get(`/articles/${slug}`),
  testimonials: () => get('/testimonials'),
  team: () => get('/team'),
  publicSettings: (group = '') => get(group ? `/settings?group=${group}` : '/settings'),
  faqs: (params = {}) => {
    const q = new URLSearchParams(Object.fromEntries(Object.entries(params).filter(([, v]) => v !== '' && v !== undefined)));
    const s = q.toString();

    return get(`/faqs${s ? `?${s}` : ''}`);
  },
  gallery: (params = {}) => {
    const q = new URLSearchParams(Object.fromEntries(Object.entries(params).filter(([, v]) => v !== '' && v !== undefined)));
    const s = q.toString();

    return get(`/gallery${s ? `?${s}` : ''}`);
  },
  createBooking: (body) => request('/bookings', { method: 'POST', body: JSON.stringify(body) }),
  sendContact: (body) => request('/contact', { method: 'POST', body: JSON.stringify(body) }),
  login: (body) => write('/auth/login', 'POST', body),
  logout: () => write('/auth/logout', 'POST'),
  me: () => request('/auth/me', { credentials: 'same-origin' }),
  adminList: (resource, params = {}) => {
    const q = new URLSearchParams(Object.fromEntries(Object.entries(params).filter(([, v]) => v !== '' && v !== undefined)));
    const s = q.toString();

    return request(`/admin/${resource}${s ? `?${s}` : ''}`, { credentials: 'same-origin' });
  },
  adminCreate: (resource, body) => write(`/admin/${resource}`, 'POST', body),
  adminUpdate: (resource, id, body) => write(`/admin/${resource}/${id}`, 'PUT', body),
  adminProfile: () => request('/admin/profile', { credentials: 'same-origin' }),
  adminUpdateProfile: (body) => write('/admin/profile', 'PUT', body),
  adminDelete: (resource, id) => write(`/admin/${resource}/${id}`, 'DELETE'),
  adminShow: (resource, id) => request(`/admin/${resource}/${id}`, { credentials: 'same-origin' }),
  userDashboard: () => request('/me/dashboard', { credentials: 'same-origin' }),
  userBookings: (params = {}) => {
    const q = new URLSearchParams(Object.fromEntries(Object.entries(params).filter(([, v]) => v !== '' && v !== undefined)));
    const s = q.toString();

    return request(`/me/bookings${s ? `?${s}` : ''}`, { credentials: 'same-origin' });
  },
  userBookingDetail: (code) => request(`/me/bookings/${code}`, { credentials: 'same-origin' }),
  userCancelBooking: (code, body) => write(`/me/bookings/${code}/cancel`, 'POST', body),
  userRescheduleBooking: (code, body) => write(`/me/bookings/${code}/reschedule`, 'POST', body),
  userPayments: () => request('/me/payments', { credentials: 'same-origin' }),
  userUploadProof: (code, body) => write(`/me/bookings/${code}/payment-proof`, 'POST', body),
  userWishlist: () => request('/me/wishlist', { credentials: 'same-origin' }),
  userToggleWishlist: (body) => write('/me/wishlist/toggle', 'POST', body),
  userVouchers: () => request('/me/vouchers', { credentials: 'same-origin' }),
  userClaimVoucher: (body) => write('/me/vouchers/claim', 'POST', body),
  userReviews: () => request('/me/reviews', { credentials: 'same-origin' }),
  userSubmitReview: (body) => write('/me/reviews', 'POST', body),
  userNotifications: () => request('/me/notifications', { credentials: 'same-origin' }),
  userReadNotification: (id) => write(`/me/notifications/${id}/read`, 'POST'),
  userProfile: () => request('/me/profile', { credentials: 'same-origin' }),
  userUpdateProfile: (body) => write('/me/profile', 'PUT', body),
  userTravelers: () => request('/me/travelers', { credentials: 'same-origin' }),
  userSaveTraveler: (body) => write('/me/travelers', 'POST', body),
  userDeleteTraveler: (id) => write(`/me/travelers/${id}`, 'DELETE'),
  userChangePassword: (body) => write('/me/password', 'PUT', body),
};

export function formatRupiah(n) {
  return 'Rp ' + Number(n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
