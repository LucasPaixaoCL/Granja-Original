import { Link, useLocation } from 'react-router-dom'
import { 
  Home, Users, Building2, Briefcase, UserCircle, 
  Warehouse, Package, Egg, Trash2, Skull, 
  Syringe, Scale, ShoppingCart, DollarSign,
  Box, CreditCard, Shapes, TrendingUp, FileText,
  ChevronDown, ChevronRight
} from 'lucide-react'
import { useState } from 'react'
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
    ]
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
    ]
  },
  {
    label: 'Controles',
    icon: FileText,
    submenu: [
      { path: '/vacinas', label: 'Vacinas' },
      { path: '/pesos', label: 'Controle de Peso' },
    ]
  },
  {
    label: 'Comercial',
    icon: ShoppingCart,
    submenu: [
      { path: '/clientes', label: 'Clientes' },
      { path: '/vendas', label: 'Vendas' },
      { path: '/fornecedores', label: 'Fornecedores' },
    ]
  },
  {
    label: 'Configurações',
    icon: Shapes,
    submenu: [
      { path: '/formas_pgto', label: 'Formas de Pagamento' },
      { path: '/formatos', label: 'Formatos' },
    ]
  },
]

function MenuItem({ item, isCollapsed }) {
  const location = useLocation()
  const [isOpen, setIsOpen] = useState(false)
  const Icon = item.icon

  if (item.submenu) {
    return (
      <div className="mb-1">
        <button
          onClick={() => setIsOpen(!isOpen)}
          className={cn(
            "w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all",
            "hover:bg-sidebar-accent text-sidebar-foreground",
            "group"
          )}
        >
          <Icon className="w-5 h-5 flex-shrink-0" />
          {!isCollapsed && (
            <>
              <span className="flex-1 text-left text-sm font-medium">{item.label}</span>
              {isOpen ? <ChevronDown className="w-4 h-4" /> : <ChevronRight className="w-4 h-4" />}
            </>
          )}
        </button>
        {isOpen && !isCollapsed && (
          <div className="ml-4 mt-1 space-y-1">
            {item.submenu.map((subItem) => (
              <Link
                key={subItem.path}
                to={subItem.path}
                className={cn(
                  "flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition-all",
                  location.pathname === subItem.path
                    ? "bg-sidebar-primary text-sidebar-primary-foreground font-medium"
                    : "hover:bg-sidebar-accent text-sidebar-foreground"
                )}
              >
                {subItem.label}
              </Link>
            ))}
          </div>
        )}
      </div>
    )
  }

  return (
    <Link
      to={item.path}
      className={cn(
        "flex items-center gap-3 px-4 py-2.5 rounded-lg mb-1 transition-all",
        location.pathname === item.path
          ? "bg-sidebar-primary text-sidebar-primary-foreground font-medium"
          : "hover:bg-sidebar-accent text-sidebar-foreground"
      )}
    >
      <Icon className="w-5 h-5 flex-shrink-0" />
      {!isCollapsed && <span className="text-sm font-medium">{item.label}</span>}
    </Link>
  )
}

export function Sidebar({ isCollapsed }) {
  return (
    <aside
      className={cn(
        "glass-sidebar h-screen sticky top-0 transition-all duration-300 overflow-y-auto",
        isCollapsed ? "w-20" : "w-64"
      )}
    >
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
          {menuItems.map((item, index) => (
            <MenuItem key={index} item={item} isCollapsed={isCollapsed} />
          ))}
        </nav>
      </div>
    </aside>
  )
}
