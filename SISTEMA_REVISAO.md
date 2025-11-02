# Sistema de Revisão de Resultados Genéticos

## Visão Geral

O sistema de revisão permite que usuários autorizados revisem, aprovem ou rejeitem resultados genéticos antes de serem liberados. Este sistema garante a qualidade e precisão dos dados genéticos.

## Funcionalidades Implementadas

### 1. Estados de Revisão
- **Pendente** (`pending`): Resultado recém-criado, aguardando revisão
- **Em Revisão** (`under_review`): Resultado sendo analisado por um revisor
- **Aprovado** (`approved`): Resultado aprovado e liberado
- **Rejeitado** (`rejected`): Resultado rejeitado, necessita correção

### 2. Permissões
- `review results`: Visualizar e acessar o sistema de revisão
- `approve results`: Aprovar resultados genéticos
- `reject results`: Rejeitar resultados genéticos

### 3. Campos Adicionados à Tabela `genetic_results`
- `status`: Estado atual do resultado (enum)
- `reviewed_by`: ID do usuário que fez a revisão
- `reviewed_at`: Data e hora da revisão
- `review_notes`: Observações do revisor
- `quality_metrics`: Métricas de qualidade (JSON)

## Páginas Criadas

### 1. Lista de Revisão (`/admin/review`)
- **Funcionalidades:**
  - Visualização de estatísticas (pendentes, em revisão, aprovados, rejeitados)
  - Filtros por status, proprietário, animal e marcador
  - Seleção múltipla para ações em lote
  - Ações rápidas (revisar, aprovar, rejeitar)
  - Paginação

### 2. Detalhes da Revisão (`/admin/review/{id}`)
- **Funcionalidades:**
  - Visualização completa dos dados genéticos
  - Formulário de revisão com observações
  - Métricas de qualidade
  - Histórico de revisões
  - Resultados relacionados da mesma amostra

## Rotas Implementadas

```php
// Visualizar lista de revisão
GET /admin/review

// Visualizar detalhes de um resultado
GET /admin/review/{result}

// Atualizar status de um resultado
POST /admin/review/{result}/status

// Atualização em lote
POST /admin/review/bulk-update

// Obter resultados por amostra
GET /admin/review/sample/{sample}
```

## Roles com Permissões de Revisão

### Admin
- Todas as permissões (incluindo revisão)

### Liberação
- `review results`
- `approve results` 
- `reject results`
- Visualização de dados relacionados

## Como Usar

### 1. Acessar o Sistema
- Faça login com um usuário que tenha permissões de revisão
- Acesse o menu "Revisão de Resultados" na barra lateral

### 2. Revisar Resultados
- Na lista, visualize os resultados pendentes
- Use os filtros para encontrar resultados específicos
- Clique em "Ver" para análise detalhada
- Use ações rápidas para aprovação/rejeição imediata

### 3. Ações em Lote
- Selecione múltiplos resultados usando as checkboxes
- Escolha a ação desejada (Colocar em Revisão, Aprovar, Rejeitar)
- Adicione observações se necessário
- Confirme a ação

### 4. Revisão Detalhada
- Acesse a página de detalhes do resultado
- Analise os dados genéticos e métricas
- Adicione observações sobre a qualidade
- Defina métricas de qualidade (opcional)
- Selecione a ação apropriada

## Fluxo de Trabalho Recomendado

1. **Resultados Novos**: Automaticamente criados como "Pendente"
2. **Triagem**: Revisor coloca resultados "Em Revisão"
3. **Análise**: Revisor analisa dados e qualidade
4. **Decisão**: Resultado é "Aprovado" ou "Rejeitado"
5. **Liberação**: Resultados aprovados ficam disponíveis

## Configuração

### 1. Executar Migrações
```bash
php artisan migrate
```

### 2. Executar Seeder
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### 3. Verificar Permissões
```bash
php artisan permissions:manage list-roles
php artisan permissions:manage list-users
```

### 4. Atribuir Permissões
```bash
php artisan permissions:manage assign-role admin@admin.com Admin
php artisan permissions:manage assign-role liberacao@admin.com Liberação
```

## Usuários Padrão

- **Admin**: admin@admin.com / password
- **Liberação**: liberacao@admin.com / password
- **Gerente**: gerente@admin.com / password
- **Área Técnica**: tecnica@admin.com / password
- **Cadastro**: cadastro@admin.com / password

## Melhorias Futuras

1. **Notificações**: Alertas quando resultados precisam de revisão
2. **Relatórios**: Estatísticas de produtividade dos revisores
3. **Workflow**: Fluxos automáticos baseados em critérios
4. **Auditoria**: Log detalhado de todas as ações
5. **API**: Endpoints para integração externa
6. **Dashboard**: Métricas em tempo real para supervisores

## Troubleshooting

### Erro 403 - Permissões
- Verifique se o usuário tem as permissões necessárias
- Execute: `php artisan permissions:manage show-user-permissions email@usuario.com`

### Menu não aparece
- Confirme que o usuário tem a permissão `review results`
- Limpe o cache: `php artisan cache:clear`

### Resultados não aparecem
- Verifique se existem resultados genéticos no banco
- Confirme os filtros aplicados na interface