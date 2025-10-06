import { createContext, useContext, useEffect, useMemo, useState } from 'react';
import { authApi } from '@/lib/http';
import { toast } from 'react-hot-toast';
import { Navigate, useLocation } from 'react-router-dom';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [initialized, setInitialized] = useState(false);

  const refresh = async () => {
    try {
      const me = await authApi.me();
      setUser(me);
      return me;
    } catch (err) {
      setUser(null);
      throw err;
    } finally {
      setInitialized(true);
    }
  };

  const login = async ({ email, password, remember }) => {
    await authApi.csrf();
    await authApi.login({ email, password, remember });
    const me = await authApi.me();
    setUser(me);
    return me;
  };

  useEffect(() => {
    refresh().catch(() => { });
  }, []);

  const logout = async () => {
    try {
      await authApi.logout();
      toast.success('Sessão encerrada.', { id: 'logout' }); // evita duplicar toast
    } catch (_) {
      // noop
    } finally {
      setUser(null);
    }
  };

  const value = useMemo(
    () => ({
      user,
      setUser,
      isAuthenticated: !!user,
      initialized,
      refresh,
      login,
      logout,
    }),
    [user, initialized]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth deve ser usado dentro de <AuthProvider>.');
  return ctx;
}

/** Guard PROTEGIDO: se não logado, redireciona para /login?redirectTo=rota-atual */
export function RequireAuth({ children, fallback = null }) {
  const { initialized, isAuthenticated } = useAuth();
  const location = useLocation();

  if (!initialized) return fallback;

  if (!isAuthenticated) {
    const redirectTo = encodeURIComponent(location.pathname + location.search);
    return <Navigate to={`/login?redirectTo=${redirectTo}`} replace />;
  }

  return children;
}

/** Guard PÚBLICO: se já logado, manda para /home (ou ?redirectTo=) */
export function PublicOnly({ children, fallback = null }) {
  const { initialized, isAuthenticated } = useAuth();
  const location = useLocation();

  if (!initialized) return fallback;

  if (isAuthenticated) {
    const params = new URLSearchParams(location.search);
    const target = params.get('redirectTo') || '/home';
    return <Navigate to={target} replace />;
  }

  return children;
}
