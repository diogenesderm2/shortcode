# Testes PHPUnit para Posts

Este documento explica como executar os testes PHPUnit criados para o sistema de cadastro de posts.

## Estrutura dos Testes

### 1. Factories (Factories)
- **`database/factories/PostFactory.php`**: Factory para criar posts de teste
- **`database/factories/CategoryFactory.php`**: Factory para criar categorias de teste

### 2. Testes de Feature (Feature Tests)
- **`tests/Feature/PostTest.php`**: Testa as funcionalidades do controller de posts

### 3. Testes Unitários (Unit Tests)
- **`tests/Unit/PostTest.php`**: Testa o modelo Post e seus relacionamentos

## Como Executar os Testes

### Executar todos os testes
```bash
php artisan test
```

### Executar apenas os testes de posts
```bash
php artisan test --filter=PostTest
```

### Executar testes de feature específicos
```bash
php artisan test tests/Feature/PostTest.php
```

### Executar testes unitários específicos
```bash
php artisan test tests/Unit/PostTest.php
```

### Executar um teste específico
```bash
php artisan test --filter=pode_criar_um_novo_post
```

## Testes Disponíveis

### Testes de Feature (PostTest.php)

#### Funcionalidades CRUD
- `pode_listar_posts()`: Testa se a listagem de posts funciona
- `pode_acessar_pagina_de_criar_post()`: Testa acesso à página de criação
- `pode_criar_um_novo_post()`: Testa criação de posts
- `pode_acessar_pagina_de_editar_post()`: Testa acesso à página de edição
- `pode_atualizar_um_post()`: Testa atualização de posts
- `pode_excluir_um_post()`: Testa exclusão de posts

#### Validações
- `validacao_requer_titulo_para_criar_post()`: Testa validação de título obrigatório
- `validacao_requer_conteudo_para_criar_post()`: Testa validação de conteúdo obrigatório
- `validacao_requer_categoria_para_criar_post()`: Testa validação de categoria obrigatória
- `validacao_requer_titulo_para_atualizar_post()`: Testa validação de título na atualização
- `validacao_requer_conteudo_para_atualizar_post()`: Testa validação de conteúdo na atualização
- `validacao_requer_categoria_para_atualizar_post()`: Testa validação de categoria na atualização
- `validacao_categoria_deve_existir()`: Testa validação de categoria existente
- `validacao_titulo_nao_pode_ser_muito_longo()`: Testa validação de tamanho do título

### Testes Unitários (PostTest.php)

#### Factory e Criação
- `pode_criar_post_com_factory()`: Testa criação via factory
- `pode_criar_post_com_dados_especificos()`: Testa criação com dados específicos

#### Relacionamentos
- `post_pertence_a_uma_categoria()`: Testa relacionamento com categoria
- `post_pertence_a_um_usuario()`: Testa relacionamento com usuário
- `categoria_pode_ter_muitos_posts()`: Testa relacionamento inverso categoria-posts
- `usuario_pode_ter_muitos_posts()`: Testa relacionamento inverso usuário-posts

#### Estados Especiais
- `pode_criar_post_sem_imagem()`: Testa criação sem imagem
- `pode_criar_post_inativo()`: Testa criação de post inativo

#### Operações CRUD
- `post_pode_ser_atualizado()`: Testa atualização do modelo
- `post_pode_ser_excluido()`: Testa exclusão do modelo

#### Funcionalidades
- `slug_e_gerado_automaticamente()`: Testa geração automática de slug
- `campos_fillable_sao_preenchiveis()`: Testa campos preenchíveis

## Configuração do Ambiente de Teste

### 1. Banco de Dados de Teste
Os testes usam `RefreshDatabase` para garantir um banco limpo a cada teste.

### 2. Autenticação
Os testes de feature simulam um usuário autenticado usando `actingAs()`.

### 3. Factories
As factories criam dados de teste realistas usando o Faker.

## Exemplo de Execução

```bash
# Executar todos os testes
php artisan test

# Saída esperada:
# PASS  Tests\Feature\PostTest
# ✓ pode listar posts
# ✓ pode acessar pagina de criar post
# ✓ pode criar um novo post
# ✓ pode acessar pagina de editar post
# ✓ pode atualizar um post
# ✓ pode excluir um post
# ✓ validacao requer titulo para criar post
# ✓ validacao requer conteudo para criar post
# ✓ validacao requer categoria para criar post
# ✓ validacao requer titulo para atualizar post
# ✓ validacao requer conteudo para atualizar post
# ✓ validacao requer categoria para atualizar post
# ✓ validacao categoria deve existir
# ✓ validacao titulo nao pode ser muito longo

# PASS  Tests\Unit\PostTest
# ✓ pode criar post com factory
# ✓ post pertence a uma categoria
# ✓ post pertence a um usuario
# ✓ categoria pode ter muitos posts
# ✓ usuario pode ter muitos posts
# ✓ pode criar post sem imagem
# ✓ pode criar post inativo
# ✓ post pode ser atualizado
# ✓ post pode ser excluido
# ✓ slug e gerado automaticamente
# ✓ campos fillable sao preenchiveis
# ✓ pode criar post com dados especificos

# Tests:  26 passed
# Time:   2.34s
```

## Dicas para Desenvolvimento

1. **Execute os testes frequentemente** durante o desenvolvimento
2. **Use `--filter`** para executar testes específicos durante o debug
3. **Mantenha os testes atualizados** quando modificar funcionalidades
4. **Adicione novos testes** para novas funcionalidades
5. **Use factories** para criar dados de teste consistentes

## Troubleshooting

### Erro: "Class 'PostFactory' not found"
- Verifique se o modelo Post tem `use HasFactory;`
- Execute `composer dump-autoload`

### Erro: "Table 'posts' doesn't exist"
- Execute as migrações: `php artisan migrate`
- Para testes: `php artisan migrate --env=testing`

### Erro: "Route not found"
- Verifique se as rotas estão definidas em `routes/admin/admin.php`
- Execute `php artisan route:list` para verificar as rotas 
