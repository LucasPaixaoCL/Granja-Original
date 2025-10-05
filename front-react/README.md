# Admin Panel - Sistema de Gestão

Template moderno de painel de administração desenvolvido com **React + Vite**, apresentando design **Glassy** com suporte a temas **Dark/Light** e paleta de cores azul diferenciada.

## 🎨 Características

### Design Moderno
- **Efeito Glassy (Glassmorphism)** com backdrop blur
- **Tema Dark/Light** com transição suave
- **Paleta azul personalizada** diferenciada para cada tema
- **Interface responsiva** e adaptável
- **Animações e transições** suaves

### Tecnologias Utilizadas
- ⚛️ **React 19** - Biblioteca JavaScript para interfaces
- ⚡ **Vite 6** - Build tool moderna e rápida
- 🎨 **Tailwind CSS 4** - Framework CSS utilitário
- 🧩 **Radix UI** - Componentes acessíveis e sem estilo
- 🎯 **Lucide React** - Ícones modernos
- 📊 **Recharts** - Biblioteca de gráficos
- 🛣️ **React Router DOM** - Roteamento
- 🎭 **Framer Motion** - Animações

## 📁 Estrutura do Projeto

```
admin-dashboard/
├── src/
│   ├── components/
│   │   ├── ui/              # Componentes UI (shadcn/ui)
│   │   ├── ThemeProvider.jsx # Gerenciador de tema
│   │   ├── Sidebar.jsx      # Barra lateral de navegação
│   │   └── Header.jsx       # Cabeçalho com busca e perfil
│   ├── pages/
│   │   ├── Dashboard.jsx    # Página principal com gráficos
│   │   ├── Funcionarios.jsx # Listagem de funcionários
│   │   ├── FuncionarioCreate.jsx # Formulário de cadastro
│   │   ├── Profile.jsx      # Página de perfil
│   │   ├── Login.jsx        # Página de login
│   │   └── GenericList.jsx  # Componente genérico de listagem
│   ├── App.jsx              # Componente principal com rotas
│   ├── App.css              # Estilos globais e tema
│   └── main.jsx             # Ponto de entrada
├── package.json
└── vite.config.js
```

## 🚀 Instalação e Execução

### Pré-requisitos
- Node.js 18+ instalado
- pnpm (ou npm/yarn)

### Passos

1. **Instalar dependências**
```bash
cd admin-dashboard
pnpm install
```

2. **Iniciar servidor de desenvolvimento**
```bash
pnpm run dev
```

3. **Acessar no navegador**
```
http://localhost:5173
```

4. **Build para produção**
```bash
pnpm run build
```

## 📄 Páginas Implementadas

### ✅ Páginas Completas
- **Dashboard** (`/home`) - Visão geral com cards estatísticos e gráficos
- **Perfil** (`/user/profile`) - Gerenciamento de dados pessoais e senha
- **Funcionários** (`/funcionarios`) - Listagem com busca e ações
- **Novo Funcionário** (`/funcionarios/create`) - Formulário completo de cadastro
- **Login** (`/login`) - Página de autenticação

### 📋 Páginas com Estrutura Genérica
Todas as rotas abaixo estão implementadas com o componente `GenericList` e podem ser personalizadas:

**Colaboradores**
- `/colaborators/all` - Todos os colaboradores
- `/rh-users` - RH Usuários
- `/rh-users/management` - Gestão RH

**Gestão**
- `/departments` - Departamentos

**Produção**
- `/nucleos` - Núcleos
- `/galpoes` - Galpões
- `/lotes` - Lotes
- `/coletas` - Coletas de ovos
- `/descartes` - Descartes
- `/mortes` - Registro de mortes

**Controles**
- `/vacinas` - Controle de vacinas
- `/pesos` - Controle de peso

**Comercial**
- `/clientes` - Clientes
- `/vendas` - Vendas
- `/fornecedores` - Fornecedores

**Configurações**
- `/formas_pgto` - Formas de pagamento
- `/formatos` - Formatos

## 🎨 Temas e Cores

### Tema Light (Claro)
- Background: `oklch(0.98 0.005 240)` - Azul muito claro
- Primary: `oklch(0.55 0.18 240)` - Azul médio
- Cards: Efeito glassy com transparência

### Tema Dark (Escuro)
- Background: `oklch(0.12 0.02 240)` - Azul escuro profundo
- Primary: `oklch(0.65 0.20 240)` - Azul claro vibrante
- Cards: Efeito glassy com backdrop blur

### Toggle de Tema
O botão de alternância de tema está localizado no header (ícone de lua/sol).

## 🧩 Componentes Principais

### Sidebar
Navegação lateral com:
- Logo e título do sistema
- Menu hierárquico com submenus
- Ícones do Lucide React
- Estado colapsável
- Indicação de rota ativa

### Header
Barra superior com:
- Botão de toggle da sidebar
- Campo de busca global
- Toggle de tema dark/light
- Notificações
- Menu de perfil do usuário

### Dashboard
Página inicial com:
- 4 cards estatísticos
- Gráfico de barras (Produção)
- Gráfico de linha (Vendas)
- Lista de atividades recentes
- Gráfico de pizza (Distribuição)

### GenericList
Componente reutilizável para listagens com:
- Busca integrada
- Tabela responsiva
- Menu de ações (visualizar, editar, excluir)
- Suporte a renderização customizada

## 🔧 Personalização

### Adicionar Nova Página

1. Criar componente em `src/pages/`:
```jsx
export function MinhaPage() {
  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold">Minha Página</h1>
      {/* Conteúdo */}
    </div>
  )
}
```

2. Adicionar rota em `App.jsx`:
```jsx
<Route path="/minha-rota" element={<Layout><MinhaPage /></Layout>} />
```

3. Adicionar item no menu em `Sidebar.jsx`:
```jsx
{ path: '/minha-rota', label: 'Minha Página', icon: IconName }
```

### Modificar Cores do Tema

Editar variáveis CSS em `src/App.css`:
```css
:root {
  --primary: oklch(0.55 0.18 240); /* Sua cor */
}
```

### Usar Componente GenericList

```jsx
<GenericList
  title="Meus Dados"
  description="Descrição"
  data={meusDados}
  columns={[
    { key: 'nome', label: 'Nome' },
    { key: 'valor', label: 'Valor' }
  ]}
  onAdd={() => {}}
  onEdit={(item) => {}}
  onDelete={(item) => {}}
/>
```

## 🔌 Integração com Backend Laravel

O template está pronto para integração com API Laravel. Exemplo:

```jsx
import { useEffect, useState } from 'react'

function Funcionarios() {
  const [funcionarios, setFuncionarios] = useState([])

  useEffect(() => {
    fetch('http://sua-api.com/api/funcionarios')
      .then(res => res.json())
      .then(data => setFuncionarios(data))
  }, [])

  // Resto do componente
}
```

## 📦 Componentes UI Disponíveis

Todos os componentes do **shadcn/ui** estão pré-instalados:

- Button, Input, Label, Textarea
- Card, Badge, Avatar
- Dialog, Dropdown Menu, Popover
- Table, Tabs, Accordion
- Select, Checkbox, Switch
- Alert, Toast (Sonner)
- E muitos outros...

## 🎯 Próximos Passos

1. **Conectar com API Laravel** - Substituir dados mockados por chamadas reais
2. **Implementar autenticação** - Adicionar lógica de login/logout
3. **Adicionar validação de formulários** - Usar React Hook Form + Zod
4. **Implementar paginação** - Adicionar controles de paginação nas listagens
5. **Adicionar notificações** - Usar Sonner para feedback ao usuário
6. **Personalizar páginas genéricas** - Adicionar campos específicos para cada entidade

## 📝 Licença

Este projeto é um template de código aberto para uso em projetos pessoais e comerciais.

## 🤝 Suporte

Para dúvidas ou sugestões sobre o template, consulte a documentação das tecnologias utilizadas:

- [React](https://react.dev)
- [Vite](https://vitejs.dev)
- [Tailwind CSS](https://tailwindcss.com)
- [Radix UI](https://www.radix-ui.com)
- [shadcn/ui](https://ui.shadcn.com)

---

**Desenvolvido com ❤️ usando React + Vite**
