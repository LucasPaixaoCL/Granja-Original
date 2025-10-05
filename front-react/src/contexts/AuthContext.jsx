import { createContext, useContext, useEffect, useMemo, useState } from 'react';
import { authApi } from '@/lib/http';
import { toast } from 'react-hot-toast';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [initialized, setInitialized] = useState(false);

  const refresh = async () => {
    try {
      const me = await authApi.me();
      setUser(me);
      return me;
    } catch (_) {
      setUser(null);
      throw _;
    } finally {
      setInitialized(true);
    }
  };

  useEffect(() => {
    refresh().catch(() => { });
  }, []);

  const logout = async () => {
    try {
      await authApi.logout();
      toast.success('Sessão encerrada.');
    } catch (_) {
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

/** Guard opcional para rotas/trechos protegidos */
export function RequireAuth({ children, fallback = null }) {
  const { initialized, isAuthenticated } = useAuth();
  if (!initialized) return fallback;           // exibir skeleton/spinner
  return isAuthenticated ? children : fallback;
}
