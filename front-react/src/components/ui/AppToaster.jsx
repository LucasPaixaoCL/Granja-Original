// src/components/ui/AppToaster.jsx
import { Toaster } from 'react-hot-toast';

export default function AppToaster() {
  return (
    <Toaster
      position="top-right"
      gutter={12}
      toastOptions={{
        duration: 3500,
        className:
          'bg-slate-900/95 text-slate-100 border border-emerald-400/30 rounded-xl shadow-xl shadow-emerald-500/10 backdrop-blur px-3 py-2',
        style: { maxWidth: 520 },
        success: {
          className:
            'bg-emerald-900/80 border-emerald-400/40 text-emerald-50 shadow-emerald-400/20',
        },
        error: {
          className:
            'bg-rose-950/90 border-rose-400/40 text-rose-50 shadow-rose-400/20',
        },
      }}
    />
  );
}
