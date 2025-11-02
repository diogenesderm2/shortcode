# Sistema de Permissões - Shortcode

## Estrutura de Roles e Permissões

### 🔐 Roles Criados

1. **ADMIN** - Acesso total ao sistema
2. **GERENTE** - Gestão geral, sem configurações críticas do sistema
3. **ÁREA TÉCNICA** - Execução de exames e análises laboratoriais
4. **LIBERAÇÃO** - Aprovação e liberação de resultados
5. **CADASTRO** - Entrada de dados e cadastros básicos

### 📋 Permissões por Categoria

#### Sistema
- `view dashboard` - Visualizar painel principal
- `access admin panel` - Acessar painel administrativo

#### Usuários
- `view users` - Visualizar usuários
- `create users` - Criar usuários
- `edit users` - Editar usuários
- `delete users` - Excluir usuários
- `manage user permissions` - Gerenciar permissões de usuários

#### Proprietários
- `view owners` - Visualizar proprietários
- `create owners` - Criar proprietários
- `edit owners` - Editar proprietários
- `delete owners` - Excluir proprietários
- `export owners` - Exportar dados de proprietários

#### Animais
- `view animals` - Visualizar animais
- `create animals` - Criar animais
- `edit animals` - Editar animais
- `delete animals` - Excluir animais
- `export animals` - Exportar dados de animais

#### Amostras
- `view samples` - Visualizar amostras
- `create samples` - Criar amostras
- `edit samples` - Editar amostras
- `delete samples` - Excluir amostras
- `collect samples` - Coletar amostras
- `receive samples` - Receber amostras
- `process samples` - Processar amostras
- `export samples` - Exportar dados de amostras

#### Exames/Testes
- `view tests` - Visualizar testes
- `create tests` - Criar testes
- `edit tests` - Editar testes
- `delete tests` - Excluir testes
- `schedule tests` - Agendar testes
- `execute tests` - Executar testes
- `validate tests` - Validar testes

#### Resultados Genéticos
- `view genetic results` - Visualizar resultados genéticos
- `create genetic results` - Criar resultados genéticos
- `edit genetic results` - Editar resultados genéticos
- `delete genetic results` - Excluir resultados genéticos
- `validate genetic results` - Validar resultados genéticos
- `release genetic results` - Liberar resultados genéticos
- `export genetic results` - Exportar resultados genéticos

#### Relatórios
- `view reports` - Visualizar relatórios
- `create reports` - Criar relatórios
- `edit reports` - Editar relatórios
- `delete reports` - Excluir relatórios
- `export reports` - Exportar relatórios
- `view financial reports` - Visualizar relatórios financeiros

#### Laboratório
- `access laboratory` - Acessar área do laboratório
- `manage equipment` - Gerenciar equipamentos
- `quality control` - Controle de qualidade
- `batch processing` - Processamento em lote

#### Liberação
- `review results` - Revisar resultados
- `approve results` - Aprovar resultados
- `reject results` - Rejeitar resultados
- `release reports` - Liberar relatórios
- `digital signature` - Assinatura digital

#### Configurações
- `manage settings` - Gerenciar configurações
- `manage system config` - Gerenciar configurações do sistema
- `view logs` - Visualizar logs
- `backup data` - Backup de dados

## 👥 Usuários Padrão Criados

| Email | Senha | Role | Nome |
|-------|-------|------|------|
| admin@admin.com | password | admin | Administrador do Sistema |
| gerente@shortcode.com | password | gerente | Gerente Geral |
| tecnico@shortcode.com | password | area_tecnica | Técnico de Laboratório |
| liberacao@shortcode.com | password | liberacao | Responsável pela Liberação |
| cadastro@shortcode.com | password | cadastro | Operador de Cadastro |

## 🚀 Comandos para Configuração

### 1. Executar Migrações e Seeders
```bash
# Limpar cache
php artisan config:clear
php artisan cache:clear

# Executar migrações
php artisan migrate

# Executar seeder de permissões
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### 2. Comandos de Gerenciamento de Permissões

```bash
# Listar todos os roles
php artisan permissions:manage list-roles

# Listar todas as permissões
php artisan permissions:manage list-permissions

# Listar todos os usuários
php artisan permissions:manage list-users

# Ver permissões de um usuário específico
php artisan permissions:manage user-permissions --email=admin@admin.com

# Atribuir role a um usuário
php artisan permissions:manage assign-role --email=usuario@email.com --role=gerente
```

## 🔧 Estrutura de Permissões por Role

### ADMIN
- ✅ Todas as permissões do sistema

### GERENTE
- ✅ Gestão de usuários (visualizar, criar, editar)
- ✅ Gestão completa de proprietários e animais
- ✅ Gestão de amostras (exceto processamento técnico)
- ✅ Visualização e criação de testes
- ✅ Visualização de resultados genéticos
- ✅ Gestão de relatórios e relatórios financeiros
- ✅ Aprovação e liberação de resultados

### ÁREA TÉCNICA
- ✅ Visualização de proprietários e animais
- ✅ Gestão completa de amostras
- ✅ Execução e validação de testes
- ✅ Criação e validação de resultados genéticos
- ✅ Acesso ao laboratório e controle de qualidade
- ✅ Visualização de relatórios

### LIBERAÇÃO
- ✅ Visualização de dados básicos
- ✅ Validação de resultados genéticos
- ✅ Aprovação, rejeição e liberação de resultados
- ✅ Assinatura digital
- ✅ Gestão de relatórios

### CADASTRO
- ✅ Criação e edição de proprietários
- ✅ Criação e edição de animais
- ✅ Criação de amostras e coleta
- ✅ Criação e agendamento de testes
- ✅ Visualização de resultados e relatórios

## 🛡️ Middleware de Segurança

O sistema utiliza os seguintes middlewares:
- `auth:sanctum` - Autenticação
- `verified` - Email verificado
- `permission:nome_da_permissao` - Verificação de permissão específica
- `role:nome_do_role` - Verificação de role específico

## 📝 Próximos Passos

1. Execute os comandos de configuração
2. Teste o login com os usuários padrão
3. Verifique se as permissões estão funcionando corretamente
4. Customize as permissões conforme necessário
5. Crie usuários adicionais conforme a necessidade

## 🔍 Troubleshooting

Se encontrar problemas:

1. **Erro "Target class [permission] does not exist"**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Permissões não funcionando**:
   ```bash
   php artisan permissions:manage user-permissions --email=seu@email.com
   ```

3. **Usuário sem permissões**:
   ```bash
   php artisan permissions:manage assign-role --email=seu@email.com --role=admin
   ```