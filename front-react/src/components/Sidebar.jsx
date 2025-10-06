// src/components/Sidebar.jsx
import { Link, useLocation } from 'react-router-dom'
import {
  Home, Users, Building2, Briefcase, UserCircle,
  Warehouse, FileText, ShoppingCart, Shapes,
  ChevronDown, ChevronRight, Egg
} from 'lucide-react'
import { useState, memo } from 'react'
import { AnimatePresence, motion } from 'framer-motion'
import { cn } from '@/lib/utils'

const menuItems = [
  { path: '/home', label: 'Dashboard', icon: Home },
  { path: '/user/profile', label: 'Perfil', icon: UserCircle },
  {
    label: 'Colaboradores',
    icon: Users,
    submenu: [
      { path: '/colaborators/all', label: 'Todos' },
      { path: '/rh-users', label: 'RH Usuários' },
      { path: '/rh-users/management', label: 'Gestão RH' },
    ],
  },
  { path: '/departments', label: 'Departamentos', icon: Building2 },
  { path: '/funcionarios', label: 'Funcionários', icon: Briefcase },
  {
    label: 'Produção',
    icon: Warehouse,
    submenu: [
      { path: '/nucleos', label: 'Núcleos' },
      { path: '/galpoes', label: 'Galpões' },
      { path: '/lotes', label: 'Lotes' },
      { path: '/coletas', label: 'Coletas' },
      { path: '/descartes', label: 'Descartes' },
      { path: '/mortes', label: 'Mortes' },
    ],
  },
  {
    label: 'Controles',
    icon: FileText,
    submenu: [
      { path: '/vacinas', label: 'Vacinas' },
      { path: '/pesos', label: 'Controle de Peso' },
    ],
  },
  {
    label: 'Comercial',
    icon: ShoppingCart,
    submenu: [
      { path: '/clientes', label: 'Clientes' },
      { path: '/vendas', label: 'Vendas' },
      { path: '/fornecedores', label: 'Fornecedores' },
    ],
  },
  {
    label: 'Configurações',
    icon: Shapes,
    submenu: [
      { path: '/formas_pgto', label: 'Formas de Pagamento' },
      { path: '/formatos', label: 'Formatos' },
    ],
  },
]

function MenuItem({ item, isCollapsed, onNavigate = () => { } }) {
  const location = useLocation()
  const [open, setOpen] = useState(false)
  const Icon = item.icon

  if (item.submenu) {
    return (
      <div className="mb-1">
        <button
          onClick={() => setOpen(!open)}
          className={cn(
            'w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all',
            'hover:bg-sidebar-accent text-sidebar-foreground'
          )}
        >
          <Icon className="w-5 h-5 flex-shrink-0" />
          {!isCollapsed && (
            <>
              <span className="flex-1 text-left text-sm font-medium">{item.label}</span>
              {open ? <ChevronDown className="w-4 h-4" /> : <ChevronRight className="w-4 h-4" />}
            </>
          )}
        </button>

        <AnimatePresence initial={false}>
          {open && !isCollapsed && (
            <motion.div
              initial={{ height: 0, opacity: 0 }}
              animate={{ height: 'auto', opacity: 1 }}
              exit={{ height: 0, opacity: 0 }}
              transition={{ duration: 0.18 }}
              className="ml-4 mt-1 overflow-hidden space-y-1"
            >
              {item.submenu.map((sub) => (
                <Link
                  key={sub.path}
                  to={sub.path}
                  onClick={onNavigate}
                  className={cn(
                    'flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all',
                    location.pathname === sub.path
                      ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
                      : 'hover:bg-sidebar-accent text-sidebar-foreground'
                  )}
                >
                  {sub.label}
                </Link>
              ))}
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    )
  }

  return (
    <Link
      to={item.path}
      onClick={onNavigate}
      className={cn(
        'flex items-center gap-3 px-4 py-2.5 rounded-lg mb-1 transition-all',
        location.pathname === item.path
          ? 'bg-sidebar-primary text-sidebar-primary-foreground font-medium'
          : 'hover:bg-sidebar-accent text-sidebar-foreground'
      )}
    >
      <Icon className="w-5 h-5 flex-shrink-0" />
      {!isCollapsed && <span className="text-sm font-medium">{item.label}</span>}
    </Link>
  )
}

export const Sidebar = memo(function Sidebar({ isOpen = false, isCollapsed = false, onClose = () => { } }) {
  // DESKTOP (lg+): largura animada
  const Desktop = (
    <motion.div
      className={cn('relative z-20 h-screen overflow-hidden hidden lg:block')} // overflow-hidden resolve o “véu”
      initial={false}
      animate={{ width: isCollapsed ? 80 : 256 }} // 80px (~w-20) / 256px (w-64)
      transition={{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }}
      style={{ willChange: 'width' }}
    >
      <aside className="glass-sidebar h-full sticky top-0 w-64">
        <div className="p-4">
          <div className="flex items-center gap-3 mb-8 px-2">
            <div className="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
              <Egg className="w-6 h-6 text-primary-foreground" />
            </div>
            {!isCollapsed && (
              <div>
                <h1 className="text-lg font-bold text-sidebar-foreground">Admin Panel</h1>
                <p className="text-xs text-muted-foreground">Sistema de Gestão</p>
              </div>
            )}
          </div>

          <nav className="space-y-1">
            {menuItems.map((item, i) => (
              <MenuItem key={i} item={item} isCollapsed={isCollapsed} />
            ))}
          </nav>
        </div>
      </aside>
    </motion.div>
  )

  // MOBILE (<lg): drawer com backdrop
  const Mobile = (
    <AnimatePresence>
      {isOpen && (
        <>
          <motion.div
            key="backdrop"
            className="fixed inset-0 bg-black/40 backdrop-blur-[1px] z-40 lg:hidden"
            onClick={onClose}
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
          />
          <motion.aside
            key="drawer"
            className="fixed left-0 top-0 bottom-0 z-50 w-64 glass-sidebar lg:hidden"
            initial={{ x: -280 }}
            animate={{ x: 0 }}
            exit={{ x: -280 }}
            transition={{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }}
          >
            <div className="p-4">
              <div className="flex items-center gap-3 mb-8 px-2">
                <div className="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                  <Egg className="w-6 h-6 text-primary-foreground" />
                </div>
                <div>
                  <h1 className="text-lg font-bold text-sidebar-foreground">Admin Panel</h1>
                  <p className="text-xs text-muted-foreground">Sistema de Gestão</p>
                </div>
              </div>

              <nav className="space-y-1">
                {menuItems.map((item, i) => (
                  <MenuItem key={i} item={item} onNavigate={onClose} />
                ))}
              </nav>
            </div>
          </motion.aside>
        </>
      )}
    </AnimatePresence>
  )

  return (
    <>
      {Desktop}
      {Mobile}
    </>
  )
})
