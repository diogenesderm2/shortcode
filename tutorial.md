# Tutorial Completo: Inertia.js + Vue.js + Laravel + Jetstream

## 📋 Índice
1. [Introdução](#introdução)
2. [O que é Inertia.js?](#o-que-é-inertiajs)
3. [Configuração Inicial](#configuração-inicial)
4. [Estrutura do Projeto](#estrutura-do-projeto)
5. [Conceitos Fundamentais](#conceitos-fundamentais)
6. [Exemplo Prático: Blog com Posts e Categorias](#exemplo-prático)
7. [Melhores Práticas](#melhores-práticas)
8. [Debugging e Troubleshooting](#debugging)

---

## 🎯 Introdução

Este tutorial é destinado a desenvolvedores Laravel que querem aprender Vue.js e Inertia.js. Vamos criar um sistema de blog completo com posts e categorias para demonstrar todos os conceitos.

---

## 🤔 O que é Inertia.js?

**Inertia.js** é uma biblioteca que permite criar aplicações SPA (Single Page Application) usando controladores tradicionais do Laravel, mas com interfaces modernas em Vue.js, React ou Svelte.

### Vantagens:
- ✅ Não precisa de API REST
- ✅ Usa controladores Laravel tradicionais
- ✅ Transições suaves entre páginas
- ✅ SEO-friendly
- ✅ Menos complexidade que SPA puro

### Como funciona:
1. Usuário clica em um link
2. Inertia intercepta e faz requisição AJAX
3. Laravel retorna dados + componente Vue
4. Vue renderiza a nova página sem reload

---

## ⚙️ Configuração Inicial

### 1. Instalar Jetstream com Inertia + Vue
```bash
composer require laravel/jetstream
php artisan jetstream:install inertia --teams
npm install
npm run build
php artisan migrate
```

### 2. Estrutura de Arquivos Criada
```
resources/
├── js/
│   ├── Components/          # Componentes Vue reutilizáveis
│   ├── Layouts/            # Layouts da aplicação
│   ├── Pages/              # Páginas (rotas)
│   ├── app.js              # Configuração principal
│   └── bootstrap.js        # Inicialização
└── views/
    └── app.blade.php       # Template principal
```

---

## 📁 Estrutura do Projeto

### Organização de Arquivos:
```
resources/js/
├── Components/             # Componentes reutilizáveis
│   ├── UI/                # Componentes de interface
│   └── Forms/             # Componentes de formulário
├── Layouts/               # Layouts da aplicação
├── Pages/                 # Páginas (uma por rota)
│   ├── Dashboard/
│   ├── Posts/
│   └── Categories/
├── app.js                 # Configuração principal
└── bootstrap.js           # Inicialização
```

---

## 🧠 Conceitos Fundamentais

### 1. **Pages (Páginas)**
- Cada rota tem uma página Vue correspondente
- Localizadas em `resources/js/Pages/`
- Recebem props do controlador Laravel

### 2. **Layouts**
- Estrutura comum entre páginas
- Ex: header, sidebar, footer
- Páginas podem usar diferentes layouts

### 3. **Components**
- Componentes Vue reutilizáveis
- Podem ser globais ou locais
- Seguem convenções do Inertia

### 4. **Props**
- Dados passados do Laravel para Vue
- Tipagem com TypeScript (opcional)
- Validação automática

---

## 🚀 Exemplo Prático: Blog com Posts e Categorias

Vamos criar um sistema completo de blog para demonstrar todos os conceitos.

### 1. Criando as Migrations

```bash
php artisan make:migration create_categories_table
php artisan make:migration create_posts_table
```

### 2. Models e Relacionamentos

```php
// app/Models/Category.php
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

// app/Models/Post.php
class Post extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'category_id', 'user_id'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### 3. Controllers

```php
// app/Http/Controllers/PostController.php
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'user'])->latest()->paginate(10);
        
        return Inertia::render('Posts/Index', [
            'posts' => $posts
        ]);
    }
    
    public function create()
    {
        $categories = Category::all();
        
        return Inertia::render('Posts/Create', [
            'categories' => $categories
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        $post = auth()->user()->posts()->create($validated);
        
        return redirect()->route('posts.index')
            ->with('message', 'Post criado com sucesso!');
    }
}
```

### 4. Rotas

```php
// routes/web.php
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
});
```

### 5. Páginas Vue

#### Página de Listagem (Posts/Index.vue)
```vue
<template>
  <AppLayout title="Posts">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Posts
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Mensagem de Sucesso -->
        <div v-if="$page.props.flash.message" class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
          {{ $page.props.flash.message }}
        </div>

        <!-- Botão Novo Post -->
        <div class="mb-6">
          <Link
            :href="route('posts.create')"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
          >
            Novo Post
          </Link>
        </div>

        <!-- Lista de Posts -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <div v-if="posts.data.length === 0" class="text-center text-gray-500">
              Nenhum post encontrado.
            </div>
            
            <div v-else class="space-y-4">
              <div
                v-for="post in posts.data"
                :key="post.id"
                class="border rounded-lg p-4 hover:bg-gray-50"
              >
                <div class="flex justify-between items-start">
                  <div>
                    <h3 class="text-lg font-semibold">{{ post.title }}</h3>
                    <p class="text-gray-600 mt-1">{{ post.content.substring(0, 150) }}...</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                      <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                        {{ post.category.name }}
                      </span>
                      <span class="ml-4">Por: {{ post.user.name }}</span>
                      <span class="ml-4">{{ formatDate(post.created_at) }}</span>
                    </div>
                  </div>
                  <div class="flex space-x-2">
                    <Link
                      :href="route('posts.edit', post.id)"
                      class="text-blue-600 hover:text-blue-800"
                    >
                      Editar
                    </Link>
                    <button
                      @click="deletePost(post.id)"
                      class="text-red-600 hover:text-red-800"
                    >
                      Excluir
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Paginação -->
            <Pagination :links="posts.links" class="mt-6" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'

// Props recebidas do controlador
const props = defineProps({
  posts: Object
})

// Métodos
const deletePost = (id) => {
  if (confirm('Tem certeza que deseja excluir este post?')) {
    router.delete(route('posts.destroy', id))
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('pt-BR')
}
</script>
```

#### Página de Criação (Posts/Create.vue)
```vue
<template>
  <AppLayout title="Novo Post">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Novo Post
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6">
            <!-- Título -->
            <div class="mb-4">
              <label for="title" class="block text-sm font-medium text-gray-700">
                Título
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.title }"
              />
              <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                {{ form.errors.title }}
              </div>
            </div>

            <!-- Categoria -->
            <div class="mb-4">
              <label for="category" class="block text-sm font-medium text-gray-700">
                Categoria
              </label>
              <select
                id="category"
                v-model="form.category_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.category_id }"
              >
                <option value="">Selecione uma categoria</option>
                <option
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
              <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                {{ form.errors.category_id }}
              </div>
            </div>

            <!-- Conteúdo -->
            <div class="mb-6">
              <label for="content" class="block text-sm font-medium text-gray-700">
                Conteúdo
              </label>
              <textarea
                id="content"
                v-model="form.content"
                rows="6"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': form.errors.content }"
              ></textarea>
              <div v-if="form.errors.content" class="mt-1 text-sm text-red-600">
                {{ form.errors.content }}
              </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3">
              <Link
                :href="route('posts.index')"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50"
              >
                {{ form.processing ? 'Salvando...' : 'Salvar Post' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

// Props recebidas do controlador
const props = defineProps({
  categories: Array
})

// Formulário reativo
const form = useForm({
  title: '',
  content: '',
  category_id: ''
})

// Método de envio
const submit = () => {
  form.post(route('posts.store'))
}
</script>
```

### 6. Componentes Reutilizáveis

#### Componente de Paginação (Components/Pagination.vue)
```vue
<template>
  <div v-if="links.length > 3" class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
      <Link
        v-if="links[0].url"
        :href="links[0].url"
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Anterior
      </Link>
      <Link
        v-if="links[links.length - 1].url"
        :href="links[links.length - 1].url"
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Próximo
      </Link>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Mostrando
          <span class="font-medium">{{ meta.from }}</span>
          até
          <span class="font-medium">{{ meta.to }}</span>
          de
          <span class="font-medium">{{ meta.total }}</span>
          resultados
        </p>
      </div>
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
          <Link
            v-for="(link, key) in links"
            :key="key"
            :href="link.url"
            v-html="link.label"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
            :class="[
              link.url === null
                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                : link.active
                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
            ]"
          />
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: Array,
  meta: Object
})
</script>
```

---

## 🎯 Melhores Práticas

### 1. **Organização de Arquivos**
```
resources/js/Pages/
├── Dashboard/
│   └── Index.vue
├── Posts/
│   ├── Index.vue
│   ├── Create.vue
│   ├── Edit.vue
│   └── Show.vue
└── Categories/
    ├── Index.vue
    ├── Create.vue
    └── Edit.vue
```

### 2. **Nomenclatura**
- **Pages**: PascalCase (ex: `PostIndex.vue`)
- **Components**: PascalCase (ex: `PostCard.vue`)
- **Layouts**: PascalCase (ex: `AppLayout.vue`)

### 3. **Props e Validação**
```vue
<script setup>
// Com TypeScript
interface Props {
  posts: {
    data: Array<{
      id: number
      title: string
      content: string
      category: {
        id: number
        name: string
      }
    }>
    links: Array<any>
  }
}

const props = defineProps<Props>()
</script>
```

### 4. **Formulários**
- Use `useForm()` do Inertia
- Validação automática de erros
- Estado de loading automático

### 5. **Navegação**
```vue
<script setup>
import { Link, router } from '@inertiajs/vue3'

// Navegação programática
const goToPost = (id) => {
  router.visit(route('posts.show', id))
}

// Navegação com dados
const createPost = () => {
  router.post(route('posts.store'), {
    title: 'Novo Post',
    content: 'Conteúdo...'
  })
}
</script>
```

---

## 🐛 Debugging e Troubleshooting

### 1. **Erros Comuns**

#### Erro: "Page not found"
- Verifique se o arquivo Vue existe em `resources/js/Pages/`
- Confirme se o nome do arquivo corresponde à rota

#### Erro: "Component not registered"
- Verifique se o componente está importado
- Use `defineAsyncComponent` para componentes grandes

#### Erro: "Props undefined"
- Verifique se está passando as props no controlador
- Use `defineProps()` corretamente

### 2. **Ferramentas de Debug**

#### Vue DevTools
- Instale a extensão do navegador
- Inspecione componentes e props

#### Laravel Debugbar
```bash
composer require barryvdh/laravel-debugbar --dev
```

#### Inertia Debug
```javascript
// resources/js/app.js
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
  progress: {
    // Configurações da barra de progresso
    color: '#29d',
    showSpinner: true,
  },
})
```

### 3. **Logs Úteis**
```php
// No controlador
Log::info('Posts carregados', ['count' => $posts->count()]);

// No Vue
console.log('Props recebidas:', props);
```

---

## 📚 Recursos Adicionais

### Documentação Oficial:
- [Inertia.js](https://inertiajs.com/)
- [Vue.js](https://vuejs.org/)
- [Laravel Jetstream](https://jetstream.laravel.com/)

### Pacotes Úteis:
- `@inertiajs/progress` - Barra de progresso
- `@inertiajs/forms` - Formulários avançados
- `@headlessui/vue` - Componentes UI

### Exemplos Práticos:
- [Laravel Breeze + Inertia](https://github.com/laravel/breeze)
- [Filament Admin](https://filamentphp.com/)

---

## 🎉 Conclusão

Agora você tem um conhecimento sólido sobre Inertia.js e Vue.js no Laravel! O exemplo do blog demonstra:

- ✅ Estrutura de arquivos
- ✅ Controllers e rotas
- ✅ Páginas Vue
- ✅ Formulários reativos
- ✅ Componentes reutilizáveis
- ✅ Paginação
- ✅ Validação de dados

Continue praticando e explore recursos mais avançados como:
- TypeScript
- Componentes dinâmicos
- Middleware personalizado
- Testes automatizados

Boa sorte no seu desenvolvimento! 🚀 
