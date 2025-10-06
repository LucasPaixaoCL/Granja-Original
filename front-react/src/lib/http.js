// src/lib/http.js
import axios from 'axios';
import { api, API_BASE } from '@/config/axios.config';

// Helper: caminho padrão do CSRF do Sanctum (na RAIZ do domínio)
const csrfPath = '/sanctum/csrf-cookie';

// CRUD base, sempre retornando .data
export const http = {
  get: (url, params) => api.get(url, { params }).then(r => r.data),
  post: (url, data) => api.post(url, data).then(r => r.data),
  put: (url, data) => api.put(url, data).then(r => r.data),
  patch: (url, data) => api.patch(url, data).then(r => r.data),
  delete: (url) => api.delete(url).then(r => r.data),
};

// Fluxo de autenticação (Sanctum SPA, cookie-based)
export const authApi = {
  /**
   * IMPORTANTE: sem /api — esta rota é na raiz do domínio Laravel.
   * Ela quem seta o cookie XSRF-TOKEN usado na proteção CSRF.
   */
  csrf: () => axios.get(`${API_BASE}${csrfPath}`, { withCredentials: true }),

  // Login com “lembrar-me”: passe { remember: true } do seu formulário
  login: (payload) => api.post('/auth/login', payload).then(r => r.data),

  logout: () => api.post('/auth/logout').then(r => r.data),
  me: () => api.get('/auth/me').then(r => r.data),

  // --- Esqueci a senha / Reset de senha ---
  // Envia e-mail com link de redefinição
  forgotPassword: async (email) => {
    await authApi.csrf(); // garante o XSRF-TOKEN
    return api.post('/auth/forgot-password', { email }).then(r => r.data);
  },

  // Efetiva a troca de senha com token recebido por e-mail
  resetPassword: async ({ token, email, password, password_confirmation }) => {
    await authApi.csrf();
    return api
      .post('/auth/reset-password', { token, email, password, password_confirmation })
      .then(r => r.data);
  },
};

// Builder para recursos REST (lista, cria, edita, remove…)
export const resource = (name) => ({
  list: (params) => http.get(`/${name}`, params),
  show: (id) => http.get(`/${name}/${id}`),
  create: (payload) => http.post(`/${name}`, payload),
  update: (id, payload) => http.put(`/${name}/${id}`, payload),
  destroy: (id) => http.delete(`/${name}/${id}`),
});

// Mapeie seus módulos rapidamente
export const Departments = resource('departments');
export const RhUsers = resource('rh-users');
export const Nucleos = resource('nucleos');
export const Galpoes = resource('galpoes');
export const Lotes = resource('lotes');
export const Coletas = resource('coletas');
export const Descartes = resource('descartes');
export const Mortes = Object.assign(resource('mortes'), {
  chart: (params) => http.get('/grafico-mortes', params),
});
export const Vacinas = resource('vacinas');
export const Pesos = resource('pesos');
export const Clientes = resource('clientes');
export const Funcionarios = resource('funcionarios');
export const Fornecedores = resource('fornecedores');
export const FormasPgto = resource('formas-pgto');
export const Formatos = resource('formatos');
export const Vendas = resource('vendas');
export const Despesas = resource('despesas');

// Parâmetros
export const ParamProgramaLuz = resource('param/programa-luz');
export const ParamProgramaVacinacao = resource('param/programa-vacinacao');
export const ParamDetalheProgramaVac = resource('param/detalhe-programa-vacinacao');
export const ParamLinhagens = resource('param/linhagens');
export const ParamControlePeso = resource('param/controle-peso');
export const ParamConsumoAgua = resource('param/consumo-agua');
export const ParamConsumoRacao = resource('param/consumo-racao');
export const ParamMortalidade = resource('param/mortalidade');
export const ParamFasesAve = resource('param/fases-ave');
export const ParamTipoDespesa = resource('param/tipo-despesa');
export const ParamNaturezaDespesa = resource('param/natureza-despesa');
export const ParamCategoriaDespesa = resource('param/categoria-despesa');
