import { TrendingUp, Users, Egg, DollarSign, ArrowUpRight, ArrowDownRight } from 'lucide-react'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { BarChart, Bar, LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts'

const statsCards = [
  {
    title: 'Total de Ovos',
    value: '45.231',
    change: '+12.5%',
    trend: 'up',
    icon: Egg,
    description: 'Últimos 30 dias'
  },
  {
    title: 'Funcionários Ativos',
    value: '127',
    change: '+3',
    trend: 'up',
    icon: Users,
    description: 'Total de colaboradores'
  },
  {
    title: 'Receita Mensal',
    value: 'R$ 89.450',
    change: '+8.2%',
    trend: 'up',
    icon: DollarSign,
    description: 'Comparado ao mês anterior'
  },
  {
    title: 'Taxa de Descarte',
    value: '2.4%',
    change: '-0.5%',
    trend: 'down',
    icon: TrendingUp,
    description: 'Redução este mês'
  },
]

const productionData = [
  { month: 'Jan', ovos: 4000, descartes: 240 },
  { month: 'Fev', ovos: 3800, descartes: 220 },
  { month: 'Mar', ovos: 4200, descartes: 280 },
  { month: 'Abr', ovos: 4500, descartes: 250 },
  { month: 'Mai', ovos: 4800, descartes: 230 },
  { month: 'Jun', ovos: 5200, descartes: 260 },
]

const salesData = [
  { month: 'Jan', vendas: 65000 },
  { month: 'Fev', vendas: 72000 },
  { month: 'Mar', vendas: 68000 },
  { month: 'Abr', vendas: 78000 },
  { month: 'Mai', vendas: 82000 },
  { month: 'Jun', vendas: 89000 },
]

const distributionData = [
  { name: 'Núcleo A', value: 35 },
  { name: 'Núcleo B', value: 28 },
  { name: 'Núcleo C', value: 22 },
  { name: 'Núcleo D', value: 15 },
]

const COLORS = ['oklch(0.55 0.18 240)', 'oklch(0.65 0.15 220)', 'oklch(0.45 0.20 260)', 'oklch(0.70 0.12 200)']

export function Dashboard() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Dashboard</h1>
        <p className="text-muted-foreground">Visão geral do sistema de gestão</p>
      </div>

      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        {statsCards.map((stat, index) => {
          const Icon = stat.icon
          return (
            <Card key={index} className="glass hover:shadow-lg transition-shadow">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">{stat.title}</CardTitle>
                <Icon className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{stat.value}</div>
                <div className="flex items-center gap-1 text-xs text-muted-foreground mt-1">
                  {stat.trend === 'up' ? (
                    <ArrowUpRight className="w-3 h-3 text-green-500" />
                  ) : (
                    <ArrowDownRight className="w-3 h-3 text-red-500" />
                  )}
                  <span className={stat.trend === 'up' ? 'text-green-500' : 'text-red-500'}>
                    {stat.change}
                  </span>
                  <span>{stat.description}</span>
                </div>
              </CardContent>
            </Card>
          )
        })}
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        <Card className="glass">
          <CardHeader>
            <CardTitle>Produção de Ovos</CardTitle>
            <CardDescription>Produção e descartes mensais</CardDescription>
          </CardHeader>
          <CardContent>
            <ResponsiveContainer width="100%" height={300}>
              <BarChart data={productionData}>
                <CartesianGrid strokeDasharray="3 3" opacity={0.1} />
                <XAxis dataKey="month" />
                <YAxis />
                <Tooltip 
                  contentStyle={{ 
                    backgroundColor: 'var(--popover)', 
                    border: '1px solid var(--border)',
                    borderRadius: '8px'
                  }} 
                />
                <Bar dataKey="ovos" fill="oklch(0.55 0.18 240)" radius={[8, 8, 0, 0]} />
                <Bar dataKey="descartes" fill="oklch(0.577 0.245 27.325)" radius={[8, 8, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        <Card className="glass">
          <CardHeader>
            <CardTitle>Receita de Vendas</CardTitle>
            <CardDescription>Evolução mensal de vendas</CardDescription>
          </CardHeader>
          <CardContent>
            <ResponsiveContainer width="100%" height={300}>
              <LineChart data={salesData}>
                <CartesianGrid strokeDasharray="3 3" opacity={0.1} />
                <XAxis dataKey="month" />
                <YAxis />
                <Tooltip 
                  contentStyle={{ 
                    backgroundColor: 'var(--popover)', 
                    border: '1px solid var(--border)',
                    borderRadius: '8px'
                  }} 
                />
                <Line 
                  type="monotone" 
                  dataKey="vendas" 
                  stroke="oklch(0.55 0.18 240)" 
                  strokeWidth={3}
                  dot={{ fill: 'oklch(0.55 0.18 240)', r: 4 }}
                />
              </LineChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
      </div>

      <div className="grid gap-4 md:grid-cols-3">
        <Card className="glass md:col-span-2">
          <CardHeader>
            <CardTitle>Atividades Recentes</CardTitle>
            <CardDescription>Últimas ações no sistema</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {[
                { action: 'Nova coleta registrada', time: 'Há 5 minutos', user: 'João Silva' },
                { action: 'Lote #1234 atualizado', time: 'Há 15 minutos', user: 'Maria Santos' },
                { action: 'Venda #5678 finalizada', time: 'Há 1 hora', user: 'Pedro Costa' },
                { action: 'Novo funcionário cadastrado', time: 'Há 2 horas', user: 'Ana Oliveira' },
                { action: 'Vacina aplicada - Lote #1235', time: 'Há 3 horas', user: 'Carlos Souza' },
              ].map((activity, index) => (
                <div key={index} className="flex items-center gap-4 p-3 rounded-lg hover:bg-accent/50 transition-colors">
                  <div className="w-2 h-2 rounded-full bg-primary"></div>
                  <div className="flex-1">
                    <p className="text-sm font-medium">{activity.action}</p>
                    <p className="text-xs text-muted-foreground">{activity.user}</p>
                  </div>
                  <span className="text-xs text-muted-foreground">{activity.time}</span>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        <Card className="glass">
          <CardHeader>
            <CardTitle>Distribuição por Núcleo</CardTitle>
            <CardDescription>Produção percentual</CardDescription>
          </CardHeader>
          <CardContent>
            <ResponsiveContainer width="100%" height={250}>
              <PieChart>
                <Pie
                  data={distributionData}
                  cx="50%"
                  cy="50%"
                  labelLine={false}
                  label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                  outerRadius={80}
                  fill="#8884d8"
                  dataKey="value"
                >
                  {distributionData.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                  ))}
                </Pie>
                <Tooltip />
              </PieChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
