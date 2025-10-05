import { User, Mail, Lock, Save } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Separator } from '@/components/ui/separator'

export function Profile() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold tracking-tight">Meu Perfil</h1>
        <p className="text-muted-foreground">Gerencie suas informações pessoais e senha</p>
      </div>

      <div className="grid gap-6 md:grid-cols-3">
        <Card className="glass md:col-span-2">
          <CardHeader>
            <CardTitle>Informações Pessoais</CardTitle>
            <CardDescription>Atualize seus dados pessoais</CardDescription>
          </CardHeader>
          <CardContent className="space-y-6">
            <div className="flex items-center gap-6">
              <Avatar className="w-24 h-24">
                <AvatarImage src="" alt="User" />
                <AvatarFallback className="bg-primary text-primary-foreground text-2xl">
                  <User className="w-12 h-12" />
                </AvatarFallback>
              </Avatar>
              <div className="space-y-2">
                <Button variant="outline">Alterar Foto</Button>
                <p className="text-xs text-muted-foreground">JPG, PNG ou GIF. Máximo 2MB.</p>
              </div>
            </div>

            <Separator />

            <div className="grid gap-4 md:grid-cols-2">
              <div className="space-y-2">
                <Label htmlFor="nome">Nome Completo</Label>
                <Input id="nome" defaultValue="Usuário Admin" />
              </div>
              <div className="space-y-2">
                <Label htmlFor="email">Email</Label>
                <Input id="email" type="email" defaultValue="admin@example.com" />
              </div>
            </div>

            <div className="grid gap-4 md:grid-cols-2">
              <div className="space-y-2">
                <Label htmlFor="telefone">Telefone</Label>
                <Input id="telefone" placeholder="(00) 00000-0000" />
              </div>
              <div className="space-y-2">
                <Label htmlFor="cargo">Cargo</Label>
                <Input id="cargo" defaultValue="Administrador" disabled />
              </div>
            </div>

            <Button className="gap-2">
              <Save className="w-4 h-4" />
              Salvar Alterações
            </Button>
          </CardContent>
        </Card>

        <div className="space-y-6">
          <Card className="glass">
            <CardHeader>
              <CardTitle>Alterar Senha</CardTitle>
              <CardDescription>Mantenha sua conta segura</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="current_password">Senha Atual</Label>
                <Input id="current_password" type="password" />
              </div>

              <div className="space-y-2">
                <Label htmlFor="new_password">Nova Senha</Label>
                <Input id="new_password" type="password" />
              </div>

              <div className="space-y-2">
                <Label htmlFor="confirm_password">Confirmar Nova Senha</Label>
                <Input id="confirm_password" type="password" />
              </div>

              <Button className="w-full gap-2">
                <Lock className="w-4 h-4" />
                Atualizar Senha
              </Button>
            </CardContent>
          </Card>

          <Card className="glass">
            <CardHeader>
              <CardTitle>Informações da Conta</CardTitle>
            </CardHeader>
            <CardContent className="space-y-3 text-sm">
              <div className="flex justify-between">
                <span className="text-muted-foreground">Membro desde</span>
                <span className="font-medium">Janeiro 2024</span>
              </div>
              <div className="flex justify-between">
                <span className="text-muted-foreground">Último acesso</span>
                <span className="font-medium">Hoje às 14:30</span>
              </div>
              <div className="flex justify-between">
                <span className="text-muted-foreground">Nível de acesso</span>
                <span className="font-medium text-primary">Administrador</span>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  )
}
