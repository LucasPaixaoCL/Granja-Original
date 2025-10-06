// src/pages/Login.jsx
import { useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { motion } from 'framer-motion';
import { Mail, Lock, Eye, EyeOff, LogIn } from 'lucide-react';
import { toast } from 'react-hot-toast';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { useAuth } from '@/contexts/AuthContext';

export function Login() {
  const { login } = useAuth();              // login() já faz setUser no contexto
  const navigate = useNavigate();
  const location = useLocation();

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [rememberMe, setRememberMe] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const mapErr = (err) => {
    const s = err?.response?.status;
    if (s === 401) return 'Credenciais inválidas.';
    if (s === 419) return 'Sessão expirada. Tente novamente.';
    if (s === 422) {
      const first = Object.values(err.response.data?.errors || {}).flat()[0];
      return first || 'Dados inválidos.';
    }
    return err?.response?.data?.message || 'Erro ao autenticar.';
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    try {
      const me = await login({
        email,
        password,
        remember: !!rememberMe === true,   // garante boolean
      });

      const params = new URLSearchParams(location.search);
      const redirectTo = params.get('redirectTo') || '/home';

      toast.success(`Bem-vindo, ${me?.name || 'usuário'}!`);
      navigate(redirectTo, { replace: true });
    } catch (err) {
      toast.error(mapErr(err));
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center p-4 overflow-hidden relative bg-gradient-to-br from-[#1e3a8a] via-[#2563eb] to-[#3b82f6]">
      {/* Background com gradiente e grid */}
      <div className="absolute inset-0">
        <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSA2MCAwIEwgMCAwIDAgNjAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjA4KSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] opacity-40"></div>
      </div>

      {/* Círculos/partículas (igual ao seu) */}
      <motion.div className="absolute top-20 left-20 w-80 h-80 bg-blue-500/40 rounded-full blur-3xl"
        animate={{ scale: [1, 1.2, 1], x: [0, 60, 0], y: [0, 40, 0] }}
        transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }} />
      <motion.div className="absolute bottom-20 right-20 w-96 h-96 bg-indigo-500/40 rounded-full blur-3xl"
        animate={{ scale: [1, 1.3, 1], x: [0, -40, 0], y: [0, -60, 0] }}
        transition={{ duration: 10, repeat: Infinity, ease: 'easeInOut' }} />
      <motion.div className="absolute top-1/2 left-1/3 w-72 h-72 bg-sky-400/30 rounded-full blur-3xl"
        animate={{ scale: [1, 1.4, 1], x: [0, -50, 0], y: [0, 50, 0] }}
        transition={{ duration: 12, repeat: Infinity, ease: 'easeInOut' }} />
      {[...Array(20)].map((_, i) => (
        <motion.div key={i} className="absolute w-1 h-1 bg-white/30 rounded-full"
          style={{ left: `${Math.random() * 100}%`, top: `${Math.random() * 100}%` }}
          animate={{ y: [0, -30, 0], opacity: [0.2, 0.8, 0.2] }}
          transition={{ duration: 3 + Math.random() * 2, repeat: Infinity, delay: Math.random() * 2 }} />
      ))}

      {/* Card */}
      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.8 }} className="relative z-10 w-full max-w-md">
        <div className="bg-white rounded-3xl p-8 shadow-2xl border border-white/30">
          {/* Header */}
          <motion.div initial={{ opacity: 0, y: -20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.2, duration: 0.6 }} className="text-center mb-8">
            <motion.div
              className="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br text-white from-blue-400 via-blue-500 to-indigo-600 mb-4 shadow-lg shadow-blue-500/50"
              whileHover={{ scale: 1.1, rotate: 5 }} whileTap={{ scale: 0.95 }}
              animate={{ boxShadow: ["0 10px 40px rgba(59,130,246,.5)", "0 10px 60px rgba(99,102,241,.6)", "0 10px 40px rgba(59,130,246,.5)"] }}
              transition={{ duration: 3, repeat: Infinity, ease: 'easeInOut' }}>
              <LogIn className="w-10 h-10 " />
            </motion.div>
            <h1 className="text-4xl font-bold mb-2 tracking-tight">Bem-vindo</h1>
            <p className="text-blue-800 text-base">Entre na sua conta para continuar</p>
          </motion.div>

          {/* Form */}
          <motion.form initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.4, duration: 0.6 }} onSubmit={handleSubmit} className="space-y-5">
            {/* Email */}
            <div className="space-y-2">
              <Label htmlFor="email" className="text-sm font-medium">Email</Label>
              <div className="relative group">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-blue-800 z-10 transition-colors" />
                <Input
                  id="email"
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="seu@email.com"
                  className="glass-input w-full pl-11 pr-4 py-3 h-12 rounded-xl placeholder-blue-200/60 border-white/30 focus-visible:ring-blue-400 focus-visible:ring-2 focus-visible:border-blue-300/60 bg-white/10"
                  required
                />
              </div>
            </div>

            {/* Senha */}
            <div className="space-y-2">
              <Label htmlFor="password" className="text-sm font-medium">Senha</Label>
              <div className="relative group">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-blue-800 z-10 transition-colors" />
                <Input
                  id="password"
                  type={showPassword ? 'text' : 'password'}
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••"
                  className="glass-input w-full pl-11 pr-12 py-3 h-12 rounded-xl placeholder-blue-200/60 border-white/30 focus-visible:ring-blue-400 focus-visible:ring-2 focus-visible:border-blue-300/60 bg-white/10"
                  required
                />
                <motion.button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-blue-800 hover:text-black transition-colors z-10"
                  whileHover={{ scale: 1.1 }}
                  whileTap={{ scale: 0.9 }}
                >
                  {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                </motion.button>
              </div>
            </div>

            {/* Lembrar-me + Esqueceu a senha */}
            <div className="flex items-center justify-between text-sm">
              <div className="flex items-center gap-2">
                <Checkbox
                  id="remember"
                  checked={rememberMe}
                  onCheckedChange={(c) => setRememberMe(c === true)}
                  className="border-blue-200/60 data-[state=checked]:bg-blue-500 data-[state=checked]:border-blue-400"
                />
                <Label htmlFor="remember" className="cursor-pointer hover:text-blue-800 transition-colors">
                  Lembrar-me
                </Label>
              </div>

              <Link to="/esqueci-senha" className="text-blue-800 hover:text-black font-medium">
                Esqueceu a senha?
              </Link>
            </div>

            {/* Botão */}
            <motion.div whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}>
              <Button
                type="submit"
                disabled={isLoading}
                className="w-full py-6 rounded-xl bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600 hover:from-blue-600 hover:via-blue-700 hover:to-indigo-700 text-white font-semibold shadow-lg shadow-blue-500/50 hover:shadow-blue-600/60 transition-all disabled:opacity-50 relative overflow-hidden group border-0"
              >
                <span className="absolute inset-0 bg-gradient-to-r from-indigo-600 via-blue-600 to-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                <span className="relative flex items-center justify-center gap-2">
                  {isLoading ? (
                    <>
                      <motion.div
                        className="w-5 h-5 border-2 border-white/30 border-t-white rounded-full"
                        animate={{ rotate: 360 }}
                        transition={{ duration: 1, repeat: Infinity, ease: 'linear' }}
                      />
                      Entrando...
                    </>
                  ) : (
                    <>
                      <LogIn className="w-5 h-5" />
                      Entrar
                    </>
                  )}
                </span>
              </Button>
            </motion.div>
          </motion.form>
          <motion.form onSubmit={handleSubmit} className="space-y-5">
            {/* campos */}
            <div className="flex items-center justify-between text-sm">
              <div className="flex items-center gap-2">
                <Checkbox
                  id="remember"
                  checked={rememberMe}
                  onCheckedChange={setRememberMe}
                  className="border-blue-200/60 data-[state=checked]:bg-blue-500 data-[state=checked]:border-blue-400"
                />
                <Label htmlFor="remember" className="cursor-pointer hover:text-blue-800 transition-colors">
                  Lembrar-me
                </Label>
              </div>

              <Link
                to="/esqueci-senha"
                className="text-blue-800 hover:text-black font-medium"
              >
                Esqueceu a senha?
              </Link>
            </div>
            {/* botão etc... */}
          </motion.form>
          {/* Footer */}
          <motion.p initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.8, duration: 0.6 }} className="text-center text-sm mt-6">
            Não tem uma conta?{' '}
            <motion.a href="#" className="text-blue-800 hover:text-black font-semibold transition-colors inline-block" whileHover={{ scale: 1.05 }} whileTap={{ scale: 0.95 }}>
              Cadastre-se
            </motion.a>
          </motion.p>
        </div>

        {/* Partículas extras */}
        <motion.div className="absolute -top-6 -right-6 w-24 h-24 bg-blue-400/30 rounded-full blur-2xl"
          animate={{ scale: [1, 1.3, 1], opacity: [0.3, 0.6, 0.3] }}
          transition={{ duration: 3, repeat: Infinity, ease: 'easeInOut' }} />
        <motion.div className="absolute -bottom-6 -left-6 w-20 h-20 bg-indigo-400/30 rounded-full blur-2xl"
          animate={{ scale: [1, 1.4, 1], opacity: [0.3, 0.5, 0.3] }}
          transition={{ duration: 4, repeat: Infinity, ease: 'easeInOut' }} />
        <motion.div className="absolute top-1/2 -right-8 w-16 h-16 bg-sky-400/20 rounded-full blur-xl"
          animate={{ scale: [1, 1.2, 1], opacity: [0.2, 0.4, 0.2] }}
          transition={{ duration: 5, repeat: Infinity, ease: 'easeInOut' }} />
      </motion.div>
    </div>
  );
}
