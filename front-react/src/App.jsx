// src\App.jsx
import { useState } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider } from './components/ThemeProvider';
import { Sidebar } from './components/Sidebar';
import { Header } from './components/Header';
import { Dashboard } from './pages/Dashboard';
import { Funcionarios } from './pages/Funcionarios';
import { FuncionarioCreate } from './pages/FuncionarioCreate';
import { Profile } from './pages/Profile';
import { Login } from './pages/Login';
import { GenericList } from './pages/GenericList';
import { modalConfigs } from './config/modalConfigs';
import './App.css';
import { Toaster } from 'react-hot-toast';
import { AuthProvider, RequireAuth, PublicOnly } from '@/contexts/AuthContext';
import { ForgotPassword } from '@/pages/ForgotPassword';
import { ResetPassword } from '@/pages/ResetPassword';




function Layout({ children }) {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);      // drawer (mobile)
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false); // colapso (desktop)

  const handleBurger = () => {
    if (window.matchMedia('(min-width: 1024px)').matches) {
      setIsSidebarCollapsed(v => !v);
    } else {
      setIsSidebarOpen(true);
    }
  };
  // Decide: em telas >= 1024px (lg) colapsa/expande; senão abre/fecha o drawer
  const handleToggleSidebar = () => {
    const isDesktop = window.matchMedia('(min-width: 1024px)').matches;
    if (isDesktop) {
      setIsSidebarCollapsed((c) => !c);
    } else {
      setIsSidebarOpen((o) => !o);
    }
  };

  return (
    <div className="flex min-h-screen">
      <Sidebar
        isOpen={isSidebarOpen}
        isCollapsed={isSidebarCollapsed}
        onClose={() => setIsSidebarOpen(false)}
      />
      <div className="flex-1 min-w-0 flex flex-col">{/* <-- min-w-0 evita “sumir” conteúdo */}
        <Header onToggleSidebar={handleBurger} />
        <main className="flex-1 p-6 overflow-auto">{children}</main>
      </div>
    </div>
  );
}




function ProtectedLayout({ children }) {
  return (
    <RequireAuth fallback={<div className="p-6">Carregando…</div>}>
      <Layout>{children}</Layout>
    </RequireAuth>
  );
}

// Dados de exemplo
const initialDepartamentosData = [
  { id: 1, nome: 'Produção', responsavel: 'João Silva', funcionarios: 45 },
  { id: 2, nome: 'Qualidade', responsavel: 'Maria Santos', funcionarios: 12 },
  { id: 3, nome: 'Manutenção', responsavel: 'Pedro Costa', funcionarios: 8 },
];
const initialNucleosData = [
  { id: 1, nome: 'Núcleo A', galpoes: 5, capacidade: 10000, status: 'Ativo' },
  { id: 2, nome: 'Núcleo B', galpoes: 4, capacidade: 8000, status: 'Ativo' },
  { id: 3, nome: 'Núcleo C', galpoes: 3, capacidade: 6000, status: 'Manutenção' },
];
const initialClientesData = [
  { id: 1, nome: 'Supermercado ABC', cidade: 'São Paulo', telefone: '(11) 98888-8888' },
  { id: 2, nome: 'Distribuidora XYZ', cidade: 'Rio de Janeiro', telefone: '(21) 97777-7777' },
  { id: 3, nome: 'Mercado Central', cidade: 'Belo Horizonte', telefone: '(31) 96666-6666' },
];

function App() {
  const [departamentosData, setDepartamentosData] = useState(initialDepartamentosData);
  const [nucleosData, setNucleosData] = useState(initialNucleosData);
  const [clientesData, setClientesData] = useState(initialClientesData);

  const addDepartamento = (data) => {
    const newItem = { id: departamentosData.length + 1, ...data };
    setDepartamentosData([...departamentosData, newItem]);
  };
  const addNucleo = (data) => {
    const newItem = { id: nucleosData.length + 1, ...data };
    setNucleosData([...nucleosData, newItem]);
  };
  const addCliente = (data) => {
    const newItem = { id: clientesData.length + 1, ...data };
    setClientesData([...clientesData, newItem]);
  };

  return (
    <ThemeProvider>
      <BrowserRouter>
        <AuthProvider>
          <Routes>
            {/* PÚBLICA: Login (se logado, redireciona) */}
            <Route
              path="/login"
              element={
                <PublicOnly fallback={<div className="p-6">Carregando…</div>}>
                  <Login />
                </PublicOnly>
              }
            />

            {/* PÚBLICA: Esqueci a senha */}
            <Route
              path="/esqueci-senha"
              element={
                <PublicOnly fallback={<div className="p-6">Carregando…</div>}>
                  <ForgotPassword />
                </PublicOnly>
              }
            />

            {/* PÚBLICA: Reset de senha (via link do e-mail) */}
            <Route
              path="/reset-password"
              element={
                <PublicOnly fallback={<div className="p-6">Carregando…</div>}>
                  <ResetPassword />
                </PublicOnly>
              }
            />

            {/* Atalho raiz */}
            <Route path="/" element={<Navigate to="/home" replace />} />

            {/* PROTEGIDAS */}
            <Route
              path="/home"
              element={
                <RequireAuth fallback={<>Carregando…</>}>
                  <Layout><Dashboard /></Layout>
                </RequireAuth>
              }
            />
            <Route path="/user/profile" element={<ProtectedLayout><Profile /></ProtectedLayout>} />

            <Route path="/funcionarios" element={<ProtectedLayout><Funcionarios /></ProtectedLayout>} />
            <Route path="/funcionarios/create" element={<ProtectedLayout><FuncionarioCreate /></ProtectedLayout>} />

            <Route
              path="/departments"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Departamentos"
                    description="Gerencie os departamentos da empresa"
                    data={departamentosData}
                    columns={[
                      { key: 'nome', label: 'Nome' },
                      { key: 'responsavel', label: 'Responsável' },
                      { key: 'funcionarios', label: 'Funcionários' },
                    ]}
                    modalConfig={{ ...modalConfigs.departamentos, onSubmit: addDepartamento }}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/nucleos"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Núcleos"
                    description="Gerencie os núcleos de produção"
                    data={nucleosData}
                    columns={[
                      { key: 'nome', label: 'Nome' },
                      { key: 'galpoes', label: 'Galpões' },
                      { key: 'capacidade', label: 'Capacidade' },
                      { key: 'status', label: 'Status' },
                    ]}
                    modalConfig={{ ...modalConfigs.nucleos, onSubmit: addNucleo }}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/clientes"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Clientes"
                    description="Gerencie os clientes da empresa"
                    data={clientesData}
                    columns={[
                      { key: 'nome', label: 'Nome' },
                      { key: 'cidade', label: 'Cidade' },
                      { key: 'telefone', label: 'Telefone' },
                    ]}
                    modalConfig={{ ...modalConfigs.clientes, onSubmit: addCliente }}
                  />
                </ProtectedLayout>
              }
            />

            {/* Outras protegidas */}
            <Route path="/colaborators/all" element={<ProtectedLayout><GenericList title="Colaboradores" description="Lista de todos os colaboradores" data={[]} columns={[]} /></ProtectedLayout>} />
            <Route path="/rh-users" element={<ProtectedLayout><GenericList title="RH Usuários" description="Usuários do RH" data={[]} columns={[]} /></ProtectedLayout>} />
            <Route path="/rh-users/management" element={<ProtectedLayout><GenericList title="Gestão RH" description="Gestão de recursos humanos" data={[]} columns={[]} /></ProtectedLayout>} />

            <Route
              path="/galpoes"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Galpões"
                    description="Gerencie os galpões"
                    data={[]}
                    columns={[
                      { key: 'nome', label: 'Nome' },
                      { key: 'nucleo', label: 'Núcleo' },
                      { key: 'capacidade', label: 'Capacidade' },
                      { key: 'status', label: 'Status' },
                    ]}
                    modalConfig={modalConfigs.galpoes}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/lotes"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Lotes"
                    description="Gerencie os lotes"
                    data={[]}
                    columns={[
                      { key: 'numero', label: 'Número' },
                      { key: 'galpao', label: 'Galpão' },
                      { key: 'quantidade', label: 'Quantidade' },
                      { key: 'dataEntrada', label: 'Data de Entrada' },
                    ]}
                    modalConfig={modalConfigs.lotes}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/coletas"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Coletas"
                    description="Registro de coletas"
                    data={[]}
                    columns={[
                      { key: 'lote', label: 'Lote' },
                      { key: 'quantidade', label: 'Quantidade' },
                      { key: 'data', label: 'Data' },
                      { key: 'responsavel', label: 'Responsável' },
                    ]}
                    modalConfig={modalConfigs.coletas}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/vendas"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Vendas"
                    description="Registro de vendas"
                    data={[]}
                    columns={[
                      { key: 'cliente', label: 'Cliente' },
                      { key: 'quantidade', label: 'Quantidade' },
                      { key: 'valor', label: 'Valor' },
                      { key: 'data', label: 'Data' },
                    ]}
                    modalConfig={modalConfigs.vendas}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/fornecedores"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Fornecedores"
                    description="Gerencie os fornecedores"
                    data={[]}
                    columns={[
                      { key: 'nome', label: 'Nome' },
                      { key: 'cnpj', label: 'CNPJ' },
                      { key: 'telefone', label: 'Telefone' },
                      { key: 'email', label: 'Email' },
                    ]}
                    modalConfig={modalConfigs.fornecedores}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/descartes"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Descartes"
                    description="Registro de descartes"
                    data={[]}
                    columns={[
                      { key: 'lote', label: 'Lote' },
                      { key: 'quantidade', label: 'Quantidade' },
                      { key: 'motivo', label: 'Motivo' },
                      { key: 'data', label: 'Data' },
                      { key: 'responsavel', label: 'Responsável' },
                    ]}
                    modalConfig={modalConfigs.descartes}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/mortes"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Mortes"
                    description="Registro de mortes"
                    data={[]}
                    columns={[
                      { key: 'lote', label: 'Lote' },
                      { key: 'animal', label: 'Animal' },
                      { key: 'causa', label: 'Causa' },
                      { key: 'data', label: 'Data' },
                      { key: 'idade', label: 'Idade (semanas)' },
                      { key: 'responsavel', label: 'Responsável' },
                    ]}
                    modalConfig={modalConfigs.mortes}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/vacinas"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Vacinas"
                    description="Controle de vacinas"
                    data={[]}
                    columns={[
                      { key: 'lote', label: 'Lote' },
                      { key: 'tipoVacina', label: 'Tipo de Vacina' },
                      { key: 'quantidade', label: 'Quantidade' },
                      { key: 'dataAplicacao', label: 'Data de Aplicação' },
                      { key: 'veterinario', label: 'Veterinário' },
                    ]}
                    modalConfig={modalConfigs.vacinas}
                  />
                </ProtectedLayout>
              }
            />

            <Route
              path="/pesos"
              element={
                <ProtectedLayout>
                  <GenericList
                    title="Controle de Peso"
                    description="Controle de peso dos animais"
                    data={[]}
                    columns={[
                      { key: 'lote', label: 'Lote' },
                      { key: 'animal', label: 'Animal' },
                      { key: 'peso', label: 'Peso (kg)' },
                      { key: 'data', label: 'Data' },
                      { key: 'responsavel', label: 'Responsável' },
                    ]}
                    modalConfig={modalConfigs.pesos}
                  />
                </ProtectedLayout>
              }
            />
            <Route path="/formas_pgto" element={<ProtectedLayout><GenericList title="Formas de Pagamento" description="Gerencie as formas de pagamento" data={[]} columns={[]} /></ProtectedLayout>} />
            <Route path="/formatos" element={<ProtectedLayout><GenericList title="Formatos" description="Gerencie os formatos" data={[]} columns={[]} /></ProtectedLayout>} />

            {/* Opcional: rota de Configurações usada no menu */}
            <Route path="/configuracoes" element={<ProtectedLayout><div>Configurações</div></ProtectedLayout>} />
          </Routes>
        </AuthProvider>

        <Toaster
          position="bottom-right"
          reverseOrder={false}
          toastOptions={{
            duration: 3500,
            className:
              'bg-slate-900/95 text-slate-100 border border-emerald-400/30 rounded-xl shadow-xl shadow-emerald-500/10 backdrop-blur px-3 py-2',
            success: { className: 'bg-emerald-900/80 border-emerald-400/40' },
            error: { className: 'bg-rose-950/90 border-rose-400/40' },
          }}
          containerStyle={{ bottom: 16, right: 16 }}
        />
      </BrowserRouter>
    </ThemeProvider>
  );
}

export default App;
