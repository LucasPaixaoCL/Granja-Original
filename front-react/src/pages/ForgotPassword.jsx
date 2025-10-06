import { useState } from 'react';
import { authApi } from '@/lib/http';
import { toast } from 'react-hot-toast';
import { useNavigate } from 'react-router-dom';

export function ForgotPassword() {
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const submit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await authApi.csrf();
      await authApi.forgotPassword(email);
      toast.success('Se o e-mail existir, enviamos um link para redefinição.');
      navigate('/login', { replace: true });
    } catch (err) {
      toast.error(err?.response?.data?.message || 'Não foi possível enviar o link.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="p-6 max-w-md mx-auto">
      <h1 className="text-xl font-semibold mb-4">Esqueci minha senha</h1>
      <form onSubmit={submit} className="space-y-3">
        <input
          className="w-full border rounded px-3 py-2"
          placeholder="Seu e-mail"
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <button disabled={loading} className="btn btn-primary">
          {loading ? 'Enviando...' : 'Enviar link'}
        </button>
      </form>
    </div>
  );
}
