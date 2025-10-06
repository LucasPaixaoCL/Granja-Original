import * as z from 'zod'

// Schemas de validação
export const departamentoSchema = z.object({
  nome: z.string().min(2, 'Nome deve ter pelo menos 2 caracteres'),
  responsavel: z.string().min(2, 'Responsável deve ter pelo menos 2 caracteres'),
  funcionarios: z.number().min(0, 'Número de funcionários deve ser positivo'),
})

export const nucleoSchema = z.object({
  nome: z.string().min(2, 'Nome deve ter pelo menos 2 caracteres'),
  galpoes: z.number().min(1, 'Deve ter pelo menos 1 galpão'),
  capacidade: z.number().min(1, 'Capacidade deve ser positiva'),
  status: z.string().min(1, 'Status é obrigatório'),
})

export const clienteSchema = z.object({
  nome: z.string().min(2, 'Nome deve ter pelo menos 2 caracteres'),
  cidade: z.string().min(2, 'Cidade deve ter pelo menos 2 caracteres'),
  telefone: z.string().min(10, 'Telefone deve ter pelo menos 10 dígitos'),
})

export const galpaoSchema = z.object({
  nome: z.string().min(2, 'Nome deve ter pelo menos 2 caracteres'),
  nucleo: z.string().min(1, 'Núcleo é obrigatório'),
  capacidade: z.number().min(1, 'Capacidade deve ser positiva'),
  status: z.string().min(1, 'Status é obrigatório'),
})

export const loteSchema = z.object({
  numero: z.string().min(1, 'Número do lote é obrigatório'),
  galpao: z.string().min(1, 'Galpão é obrigatório'),
  quantidade: z.number().min(1, 'Quantidade deve ser positiva'),
  dataEntrada: z.string().min(1, 'Data de entrada é obrigatória'),
})

export const coletaSchema = z.object({
  lote: z.string().min(1, 'Lote é obrigatório'),
  quantidade: z.number().min(1, 'Quantidade deve ser positiva'),
  data: z.string().min(1, 'Data é obrigatória'),
  responsavel: z.string().min(2, 'Responsável é obrigatório'),
})

export const fornecedorSchema = z.object({
  nome: z.string().min(2, 'Nome deve ter pelo menos 2 caracteres'),
  cnpj: z.string().min(14, 'CNPJ deve ter 14 dígitos'),
  telefone: z.string().min(10, 'Telefone deve ter pelo menos 10 dígitos'),
  email: z.string().email('Email inválido'),
})

export const vendaSchema = z.object({
  cliente: z.string().min(1, 'Cliente é obrigatório'),
  quantidade: z.number().min(1, 'Quantidade deve ser positiva'),
  valor: z.number().min(0.01, 'Valor deve ser positivo'),
  data: z.string().min(1, 'Data é obrigatória'),
})

// Configurações dos modais
export const modalConfigs = {
  departamentos: {
    title: "Adicionar Departamento",
    description: "Preencha os dados do novo departamento",
    buttonText: "Novo Departamento",
    schema: departamentoSchema,
    fields: [
      { name: 'nome', label: 'Nome', type: 'text', placeholder: 'Digite o nome do departamento' },
      { name: 'responsavel', label: 'Responsável', type: 'text', placeholder: 'Digite o nome do responsável' },
      { name: 'funcionarios', label: 'Número de Funcionários', type: 'number', placeholder: '0' },
    ],
    defaultValues: { nome: '', responsavel: '', funcionarios: 0 }
  },

  nucleos: {
    title: "Adicionar Núcleo",
    description: "Preencha os dados do novo núcleo de produção",
    buttonText: "Novo Núcleo",
    schema: nucleoSchema,
    fields: [
      { name: 'nome', label: 'Nome', type: 'text', placeholder: 'Digite o nome do núcleo' },
      { name: 'galpoes', label: 'Número de Galpões', type: 'number', placeholder: '1' },
      { name: 'capacidade', label: 'Capacidade', type: 'number', placeholder: '1000' },
      {
        name: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Selecione o status',
        options: [
          { value: 'Ativo', label: 'Ativo' },
          { value: 'Manutenção', label: 'Manutenção' },
          { value: 'Inativo', label: 'Inativo' }
        ]
      },
    ],
    defaultValues: { nome: '', galpoes: 1, capacidade: 1000, status: 'Ativo' }
  },

  clientes: {
    title: "Adicionar Cliente",
    description: "Preencha os dados do novo cliente",
    buttonText: "Novo Cliente",
    schema: clienteSchema,
    fields: [
      { name: 'nome', label: 'Nome', type: 'text', placeholder: 'Digite o nome do cliente' },
      { name: 'cidade', label: 'Cidade', type: 'text', placeholder: 'Digite a cidade' },
      { name: 'telefone', label: 'Telefone', type: 'text', placeholder: '(11) 99999-9999' },
    ],
    defaultValues: { nome: '', cidade: '', telefone: '' }
  },

  galpoes: {
    title: "Adicionar Galpão",
    description: "Preencha os dados do novo galpão",
    buttonText: "Novo Galpão",
    schema: galpaoSchema,
    fields: [
      { name: 'nome', label: 'Nome', type: 'text', placeholder: 'Digite o nome do galpão' },
      {
        name: 'nucleo',
        label: 'Núcleo',
        type: 'select',
        placeholder: 'Selecione o núcleo',
        options: [
          { value: 'Núcleo A', label: 'Núcleo A' },
          { value: 'Núcleo B', label: 'Núcleo B' },
          { value: 'Núcleo C', label: 'Núcleo C' }
        ]
      },
      { name: 'capacidade', label: 'Capacidade', type: 'number', placeholder: '1000' },
      {
        name: 'status',
        label: 'Status',
        type: 'select',
        placeholder: 'Selecione o status',
        options: [
          { value: 'Ativo', label: 'Ativo' },
          { value: 'Manutenção', label: 'Manutenção' },
          { value: 'Inativo', label: 'Inativo' }
        ]
      },
    ],
    defaultValues: { nome: '', nucleo: '', capacidade: 1000, status: 'Ativo' }
  },

  lotes: {
    title: "Adicionar Lote",
    description: "Preencha os dados do novo lote",
    buttonText: "Novo Lote",
    schema: loteSchema,
    fields: [
      { name: 'numero', label: 'Número do Lote', type: 'text', placeholder: 'Digite o número do lote' },
      {
        name: 'galpao',
        label: 'Galpão',
        type: 'select',
        placeholder: 'Selecione o galpão',
        options: [
          { value: 'Galpão A1', label: 'Galpão A1' },
          { value: 'Galpão A2', label: 'Galpão A2' },
          { value: 'Galpão B1', label: 'Galpão B1' }
        ]
      },
      { name: 'quantidade', label: 'Quantidade', type: 'number', placeholder: '1000' },
      { name: 'dataEntrada', label: 'Data de Entrada', type: 'date', placeholder: '' },
    ],
    defaultValues: { numero: '', galpao: '', quantidade: 1000, dataEntrada: '' }
  },

  coletas: {
    title: "Registrar Coleta",
    description: "Preencha os dados da coleta",
    buttonText: "Nova Coleta",
    schema: coletaSchema,
    fields: [
      {
        name: 'lote',
        label: 'Lote',
        type: 'select',
        placeholder: 'Selecione o lote',
        options: [
          { value: 'Lote 001', label: 'Lote 001' },
          { value: 'Lote 002', label: 'Lote 002' },
          { value: 'Lote 003', label: 'Lote 003' }
        ]
      },
      { name: 'quantidade', label: 'Quantidade', type: 'number', placeholder: '100' },
      { name: 'data', label: 'Data', type: 'date', placeholder: '' },
      { name: 'responsavel', label: 'Responsável', type: 'text', placeholder: 'Nome do responsável' },
    ],
    defaultValues: { lote: '', quantidade: 0, data: '', responsavel: '' }
  },

  fornecedores: {
    title: "Adicionar Fornecedor",
    description: "Preencha os dados do novo fornecedor",
    buttonText: "Novo Fornecedor",
    schema: fornecedorSchema,
    fields: [
      { name: 'nome', label: 'Nome', type: 'text', placeholder: 'Digite o nome do fornecedor' },
      { name: 'cnpj', label: 'CNPJ', type: 'text', placeholder: '00.000.000/0000-00' },
      { name: 'telefone', label: 'Telefone', type: 'text', placeholder: '(11) 99999-9999' },
      { name: 'email', label: 'Email', type: 'email', placeholder: 'email@exemplo.com' },
    ],
    defaultValues: { nome: '', cnpj: '', telefone: '', email: '' }
  },

  vendas: {
    title: "Registrar Venda",
    description: "Preencha os dados da venda",
    buttonText: "Nova Venda",
    schema: vendaSchema,
    fields: [
      {
        name: 'cliente',
        label: 'Cliente',
        type: 'select',
        placeholder: 'Selecione o cliente',
        options: [
          { value: 'Supermercado ABC', label: 'Supermercado ABC' },
          { value: 'Distribuidora XYZ', label: 'Distribuidora XYZ' },
          { value: 'Mercado Central', label: 'Mercado Central' }
        ]
      },
      { name: 'quantidade', label: 'Quantidade', type: 'number', placeholder: '100' },
      { name: 'valor', label: 'Valor (R$)', type: 'number', placeholder: '0.00' },
      { name: 'data', label: 'Data', type: 'date', placeholder: '' },
    ],
    defaultValues: { cliente: '', quantidade: 0, valor: 0, data: '' }
  }
}