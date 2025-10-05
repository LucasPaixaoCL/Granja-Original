import { useState } from 'react'
import { Plus, Search, MoreVertical, Edit, Trash2, Eye } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Badge } from '@/components/ui/badge'
import { Link } from 'react-router-dom'

const funcionarios = [
  { id: 1, nome: 'João Silva', cargo: 'Operador', departamento: 'Produção', status: 'Ativo', email: 'joao@example.com' },
  { id: 2, nome: 'Maria Santos', cargo: 'Supervisora', departamento: 'Qualidade', status: 'Ativo', email: 'maria@example.com' },
  { id: 3, nome: 'Pedro Costa', cargo: 'Técnico', departamento: 'Manutenção', status: 'Ativo', email: 'pedro@example.com' },
  { id: 4, nome: 'Ana Oliveira', cargo: 'Analista', departamento: 'RH', status: 'Ativo', email: 'ana@example.com' },
  { id: 5, nome: 'Carlos Souza', cargo: 'Veterinário', departamento: 'Saúde Animal', status: 'Férias', email: 'carlos@example.com' },
]

export function Funcionarios() {
  const [searchTerm, setSearchTerm] = useState('')

  const filteredFuncionarios = funcionarios.filter(func =>
    func.nome.toLowerCase().includes(searchTerm.toLowerCase()) ||
    func.cargo.toLowerCase().includes(searchTerm.toLowerCase()) ||
    func.departamento.toLowerCase().includes(searchTerm.toLowerCase())
  )

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Funcionários</h1>
          <p className="text-muted-foreground">Gerencie os funcionários da empresa</p>
        </div>
        <Link to="/funcionarios/create">
          <Button className="gap-2">
            <Plus className="w-4 h-4" />
            Novo Funcionário
          </Button>
        </Link>
      </div>

      <Card className="glass">
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Lista de Funcionários</CardTitle>
              <CardDescription>Total de {funcionarios.length} funcionários cadastrados</CardDescription>
            </div>
            <div className="relative w-64">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <Input
                type="search"
                placeholder="Buscar funcionário..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="pl-10"
              />
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Nome</TableHead>
                <TableHead>Cargo</TableHead>
                <TableHead>Departamento</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Ações</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {filteredFuncionarios.map((funcionario) => (
                <TableRow key={funcionario.id} className="hover:bg-accent/50">
                  <TableCell className="font-medium">{funcionario.nome}</TableCell>
                  <TableCell>{funcionario.cargo}</TableCell>
                  <TableCell>{funcionario.departamento}</TableCell>
                  <TableCell className="text-muted-foreground">{funcionario.email}</TableCell>
                  <TableCell>
                    <Badge 
                      variant={funcionario.status === 'Ativo' ? 'default' : 'secondary'}
                      className={funcionario.status === 'Ativo' ? 'bg-primary' : ''}
                    >
                      {funcionario.status}
                    </Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon">
                          <MoreVertical className="w-4 h-4" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end" className="glass-strong">
                        <DropdownMenuItem>
                          <Eye className="w-4 h-4 mr-2" />
                          Visualizar
                        </DropdownMenuItem>
                        <DropdownMenuItem>
                          <Edit className="w-4 h-4 mr-2" />
                          Editar
                        </DropdownMenuItem>
                        <DropdownMenuItem className="text-destructive">
                          <Trash2 className="w-4 h-4 mr-2" />
                          Excluir
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  )
}
