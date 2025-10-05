// src/services/axiosConfig.js
import axios from 'axios';

// Use as variáveis de ambiente do seu front (Vite/Next) para apontar ao backend
// Ex.: VITE_API_DOMAIN=http://localhost:8000   VITE_API_PREFIX=/api
const API_BASE = (typeof import.meta !== 'undefined' ? import.meta.env.VITE_API_DOMAIN : process.env.VITE_API_DOMAIN) || 'http://localhost:8000/api';

const baseURL = `${API_BASE.replace(/\/+$/, '')}`;

export const api = axios.create({
  baseURL,
  withCredentials: true,                 // <— ESSENCIAL para cookies (Sanctum SPA)
  headers: { Accept: 'application/json' },
  xsrfCookieName: 'XSRF-TOKEN',         // Laravel padrão
  xsrfHeaderName: 'X-XSRF-TOKEN',
  timeout: 15000,
});

// Auto-refresh de CSRF se der 419 (token mismatch)
api.interceptors.response.use(
  (res) => res,
  async (error) => {
    const { config, response } = error;
    if (!response) return Promise.reject(error);

    // 419 — Token CSRF inválido/expirado
    if (response.status === 419 && !config.__isRetry) {
      config.__isRetry = true;
      try {
        await axios.get(`${API_BASE}/csrf-cookie`, { withCredentials: true });
        return api.request(config);
      } catch (e) {
        /* segue para o reject */
      }
    }

    // 401 — sessão expirou; deixe o caller decidir (exibir login/redirect)
    return Promise.reject(error);
  }
);

export { API_BASE, baseURL };
