// src/config/axiosConfig.js
import axios from 'axios';

// Vite/Next: VITE_API_DOMAIN=http://localhost:8000
const API_BASE =
  (typeof import.meta !== 'undefined'
    ? import.meta.env.VITE_API_DOMAIN
    : process.env.VITE_API_DOMAIN) || 'http://localhost:8000';

const API_PREFIX = '/api';
export const baseURL = `${API_BASE.replace(/\/+$/, '')}${API_PREFIX}`;

export const api = axios.create({
  baseURL,
  withCredentials: true, // ESSENCIAL p/ cookies (Sanctum SPA)
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest',  // <<— ADICIONE ISTO
  },
  xsrfCookieName: 'XSRF-TOKEN', // Laravel padrão
  xsrfHeaderName: 'X-XSRF-TOKEN',
  timeout: 15000,
});

// --- Helpers --- //
export async function getCsrfCookie() {
  // Sempre use o endpoint oficial do Sanctum
  await axios.get(`${API_BASE.replace(/\/+$/, '')}/sanctum/csrf-cookie`, {
    withCredentials: true,
  });
}

// Lê cookie sem lib externa (escopo do domínio localhost vale p/ portas diferentes)
function getCookie(name) {
  const m = document.cookie.split('; ').find(c => c.startsWith(name + '='));
  return m ? m.split('=')[1] : '';
}

// --- Interceptor: força o header X-XSRF-TOKEN --- //
api.interceptors.request.use((config) => {
  const raw = getCookie('XSRF-TOKEN'); // NÃO é HttpOnly
  if (raw) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(raw);
  }
  return config;
});

// --- (Opcional) Auto-refresh em 419 --- //
api.interceptors.response.use(
  (res) => res,
  async (error) => {
    const { config, response } = error || {};
    if (!response) return Promise.reject(error);

    if (response.status === 419 && !config.__isRetry) {
      config.__isRetry = true;
      try {
        await getCsrfCookie();
        return api.request(config);
      } catch (e) { }
    }
    return Promise.reject(error);
  }
);

export { API_BASE, API_PREFIX };
