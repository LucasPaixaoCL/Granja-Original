import { useState } from 'react'
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { ThemeProvider } from './components/ThemeProvider'
import { Sidebar } from './components/Sidebar'
import { Header } from './components/Header'
import { Dashboard } from './pages/Dashboard'
import { Funcionarios } from './pages/Funcionarios'
import { FuncionarioCreate } from './pages/FuncionarioCreate'
import { Profile } from './pages/Profile'
import { Login } from './pages/Login'
import { GenericList } from './pages/GenericList'
import './App.css'
import { Toaster } from 'react-hot-toast';
import { AuthProvider } from '@/contexts/AuthContext';

function Layout({ children }) {
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false)

  return (
    <div className="flex min-h-screen">
      <Sidebar isCollapsed={isSidebarCollapsed} />
      <div className="flex-1 flex flex-col">
        <Header onToggleSidebar={() => setIsSidebarCollapsed(!isSidebarCollapsed)} />
        <main className="flex-1 p-6 overflow-auto">
          {children}
        </main>
      </div>
    </div>
  )
}

// Dados de exemplo para páginas genéricas
const departamentosData = [
  { id: 1, nome: 'Produção', responsavel: 'João Silva', funcionarios: 45 },
  { id: 2, nome: 'Qualidade', responsavel: 'Maria Santos', funcionarios: 12 },
  { id: 3, nome: 'Manutenção', responsavel: 'Pedro Costa', funcionarios: 8 },
]

const nucleosData = [
  { id: 1, nome: 'Núcleo A', galpoes: 5, capacidade: 10000, status: 'Ativo' },
  { id: 2, nome: 'Núcleo B', galpoes: 4, capacidade: 8000, status: 'Ativo' },
  { id: 3, nome: 'Núcleo C', galpoes: 3, capacidade: 6000, status: 'Manutenção' },
]

const clientesData = [
  { id: 1, nome: 'Supermercado ABC', cidade: 'São Paulo', telefone: '(11) 98888-8888' },
  { id: 2, nome: 'Distribuidora XYZ', cidade: 'Rio de Janeiro', telefone: '(21) 97777-7777' },
  { id: 3, nome: 'Mercado Central', cidade: 'Belo Horizonte', telefone: '(31) 96666-6666' },
]

function App() {
  return (
    <ThemeProvider>
      <BrowserRouter>
        <AuthProvider>
          <Routes>
            <Route path="/login" element={<Login />} />

            <Route path="/" element={<Navigate to="/home" replace />} />

            <Route path="/home" element={<Layout><Dashboard /></Layout>} />
            <Route path="/user/profile" element={<Layout><Profile /></Layout>} />

            {/* Funcionários */}
            <Route path="/funcionarios" element={<Layout><Funcionarios /></Layout>} />
            <Route path="/funcionarios/create" element={<Layout><FuncionarioCreate /></Layout>} />

            {/* Departamentos */}
            <Route path="/departments" element={
              <Layout>


                <Toaster
                  position="top-right"
                  toastOptions={{
                    duration: 3500,
                    className:
                      'bg-slate-900/95 text-slate-100 border border-emerald-400/30 rounded-xl shadow-xl shadow-emerald-500/10 backdrop-blur px-3 py-2',
                    success: { className: 'bg-emerald-900/80 border-emerald-400/40' },
                    error: { className: 'bg-rose-950/90 border-rose-400/40' },
                  }}
                />
                <GenericList
                  title="Departamentos"
                  description="Gerencie os departamentos da empresa"
                  data={departamentosData}
                  columns={[
                    { key: 'nome', label: 'Nome' },
                    { key: 'responsavel', label: 'Responsável' },
                    { key: 'funcionarios', label: 'Funcionários' },
                  ]}
                />

              </Layout>
            } />

            {/* Núcleos */}
            <Route path="/nucleos" element={
              <Layout>
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
                />
              </Layout>
            } />

            {/* Clientes */}
            <Route path="/clientes" element={
              <Layout>
                <GenericList
                  title="Clientes"
                  description="Gerencie os clientes da empresa"
                  data={clientesData}
                  columns={[
                    { key: 'nome', label: 'Nome' },
                    { key: 'cidade', label: 'Cidade' },
                    { key: 'telefone', label: 'Telefone' },
                  ]}
                />
              </Layout>
            } />

            {/* Rotas genéricas para outras páginas */}
            <Route path="/colaborators/all" element={<Layout><GenericList title="Colaboradores" description="Lista de todos os colaboradores" data={[]} columns={[]} /></Layout>} />
            <Route path="/rh-users" element={<Layout><GenericList title="RH Usuários" description="Usuários do RH" data={[]} columns={[]} /></Layout>} />
            <Route path="/rh-users/management" element={<Layout><GenericList title="Gestão RH" description="Gestão de recursos humanos" data={[]} columns={[]} /></Layout>} />
            <Route path="/galpoes" element={<Layout><GenericList title="Galpões" description="Gerencie os galpões" data={[]} columns={[]} /></Layout>} />
            <Route path="/lotes" element={<Layout><GenericList title="Lotes" description="Gerencie os lotes" data={[]} columns={[]} /></Layout>} />
            <Route path="/coletas" element={<Layout><GenericList title="Coletas" description="Registro de coletas" data={[]} columns={[]} /></Layout>} />
            <Route path="/descartes" element={<Layout><GenericList title="Descartes" description="Registro de descartes" data={[]} columns={[]} /></Layout>} />
            <Route path="/mortes" element={<Layout><GenericList title="Mortes" description="Registro de mortes" data={[]} columns={[]} /></Layout>} />
            <Route path="/vacinas" element={<Layout><GenericList title="Vacinas" description="Controle de vacinas" data={[]} columns={[]} /></Layout>} />
            <Route path="/pesos" element={<Layout><GenericList title="Controle de Peso" description="Controle de peso dos animais" data={[]} columns={[]} /></Layout>} />
            <Route path="/vendas" element={<Layout><GenericList title="Vendas" description="Registro de vendas" data={[]} columns={[]} /></Layout>} />
            <Route path="/fornecedores" element={<Layout><GenericList title="Fornecedores" description="Gerencie os fornecedores" data={[]} columns={[]} /></Layout>} />
            <Route path="/formas_pgto" element={<Layout><GenericList title="Formas de Pagamento" description="Gerencie as formas de pagamento" data={[]} columns={[]} /></Layout>} />
            <Route path="/formatos" element={<Layout><GenericList title="Formatos" description="Gerencie os formatos" data={[]} columns={[]} /></Layout>} />
          </Routes>
        </AuthProvider>
      </BrowserRouter>
    </ThemeProvider>
  )
}

export default App
