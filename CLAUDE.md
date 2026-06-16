# CLAUDE.md — Guia de Trabalho para Claude Code

Este arquivo é lido automaticamente pelo Claude Code em toda conversa neste repositório.
**Leia este arquivo inteiro antes de qualquer alteração de código.**

---

## Hierarquia de Autoridade

1. **`CLAUDE.md`** (este arquivo) — regras de operação do Claude Code
2. **`AGENTS.md`** — arquitetura, SOLID, segurança, módulos, checklist pré-entrega
3. **`docs/guidelines-backend.md`** — padrões de backend: Services, Contracts, Forms, Scopes
4. **`docs/guidelines-frontend.md`** — padrões de frontend: MaryUI, Livewire, Blade, tabelas

Em caso de conflito, a ordem acima define precedência. Leia todos antes de codar.

---

## Stack Obrigatória

| Camada        | Tecnologia                                         |
|---------------|----------------------------------------------------|
| Backend       | PHP 8.3 · Laravel 13 · Livewire 4                 |
| Frontend      | MaryUI · Tailwind CSS 4 · DaisyUI · Vite           |
| Banco         | MySQL 8 com UUIDs como PK                          |
| Cache/Queue   | Redis · Laravel Horizon                            |
| Auth          | Spatie Permission · Google 2FA                     |
| Deploy        | Laravel Forge (ver seção Forge abaixo)             |
| Tests         | PHPUnit 12 em SQLite :memory: isolado              |

---

## Regras Críticas de Segurança (Não Negociáveis)

### Testes — NUNCA na base real
```
NUNCA execute:
  php artisan test
  vendor/bin/phpunit
  phpunit

NUNCA execute migrations destrutivas:
  php artisan migrate:fresh
  php artisan migrate:refresh
  php artisan migrate:reset
  php artisan migrate:rollback
  php artisan db:wipe
```

**O único comando permitido para rodar testes é:**
```bash
./bin/test-isolated
```
Ou via composer:
```bash
composer test:isolated
```

Esse script valida que `DB_CONNECTION=sqlite` e `DB_DATABASE=:memory:` estão em `.env.testing`
e roda os testes dentro do container Docker isolado (`app-test`). Nunca use a base real.

### Segredos
- Nunca exponha credenciais em código, Blade, JS, logs ou commits.
- Nunca adicione senhas, tokens ou API keys hardcoded.
- O `.env` nunca deve ser commitado (já está no `.gitignore`).
- Use variáveis de ambiente para toda configuração sensível.

### Autorização
- Nunca pule policies "temporariamente".
- Todo módulo precisa de policy, permission enum e seeder.
- Toda escrita precisa de `$this->authorize()` antes de executar.

---

## Deploy via Laravel Forge

O deploy é feito via **Laravel Forge**, não via Docker em produção.

Ao implementar qualquer feature com impacto em deploy:
- Migrations devem ser **seguras e reversíveis** — nunca alteram dados destrutivamente.
- Comandos de deploy no Forge executam `php artisan migrate --force` — garanta que a migration
  seja idempotente (sem `migrate:fresh`, `migrate:reset`, etc.).
- Variáveis de ambiente sensíveis são gerenciadas pelo painel do Forge, não pelo `.env` do repo.
- Otimizações de cache (`config:cache`, `route:cache`, `view:cache`) são executadas pelo Forge.
- Se um job ou comando de fila for adicionado, mencione ao usuário para configurar o supervisor
  no Forge.
- **Nunca** execute comandos de deploy ou push para produção sem aprovação explícita do usuário.

---

## Design & Usabilidade — Regras Mobile-First

### Tokens de cor — nunca hardcode
- **Nunca** use `text-gray-*`, `bg-white`, `text-black`, `bg-gray-*` — quebram o dark theme.
- Use sempre os tokens DaisyUI: `text-base-content`, `text-base-content/60`, `bg-base-100`,
  `bg-base-200`, `bg-base-300`, `text-primary`, `text-error`, `text-success`.

### Espaçamento progressivo (mobile-first)
```blade
{{-- Correto: cresce progressivamente --}}
class="p-4 sm:p-8 lg:p-12"

{{-- Errado: valor fixo grande, quebra no mobile --}}
class="p-12"
```

### Botões responsivos
```blade
{{-- Um botão --}}
class="w-full sm:w-auto"

{{-- Container de botões --}}
class="flex flex-col sm:flex-row gap-3"

{{-- NUNCA repita breakpoints: md:w-auto lg:w-auto xl:w-auto 2xl:w-auto são redundantes --}}
```

### Containers com max-width
- Formulários de auth: `w-full max-w-md`
- Formulários de edição: `w-full max-w-2xl`
- Nunca deixe um formulário esticar até a borda em telas largas.

### Esconder elementos — nunca para funcionalidades
- `hidden md:block` pode ser usado para elementos decorativos (imagem de fundo).
- **Nunca** use `hidden md:block` para campos de formulário, selects, botões ou controles interativos.
  Isso torna a funcionalidade inacessível no mobile.

### Acessibilidade mínima
- Botões sem texto visível precisam de `aria-label` ou `tooltip`.
- Manter `alt` em todas as `<img>`.
- Respeitar `prefers-reduced-motion` (já configurado no `app.css`).

Detalhes completos: `docs/guidelines-frontend.md` → seção Design System.

---

## Componentes de UI — MaryUI é Obrigatório

Este projeto usa **MaryUI** (`robsontenorio/mary`) como biblioteca de componentes.

### Regras:
- **Sempre** use componentes MaryUI quando existir equivalente: `x-input`, `x-button`,
  `x-select`, `x-table`, `x-card`, `x-modal`, etc.
- **Nunca** crie HTML customizado para o que o MaryUI já resolve.
- Consulte `docs/guidelines-frontend.md` para os padrões visuais de índice e formulário.
- Botões de ação: `btn-circle btn-sm` + `bg-*` + `tooltip` + `spinner`.
- Tabelas: use `x-table` com `:headers`, `:rows`, `:sort-by`, `:per-page`, `with-pagination`.
- Cards: `x-card` com `class="shadow bg-base-300"`.
- Formulários: `x-form` com `wire:submit.prevent`, campos em grid de 12 colunas.

---

## Arquitetura — Resumo Rápido

```
Livewire Component (UI + estado + autorização)
    └── Service (lógica de negócio, via contract granular)
            └── Model (lean: relacionamentos, casts, scopes)
                    └── Policy (autorização granular)
```

- **Componentes thin**: orquestram, não implementam.
- **Services**: toda escrita, coordenação e regra de domínio (camada obrigatória).
- **Form objects**: `rules()`, `setModel()`/`setUser()`, `payload()`, propriedades tipadas.
- **Contracts**: todo Service usado por componentes tem interface granular em
  `app/Contracts/{Dominio}/`, vinculada no `AppServiceProvider`.
- **Enums**: use `App\Enums\Auth\Roles` e `App\Enums\Auth\Permissions` — nunca strings cruas.
- **Constantes de campo**: use `Model::FIELD_*` e `Model::TABLE` — nunca strings raw.

---

## Padrão de Testes

Os testes usam SQLite em memória (`:memory:`). `RefreshDatabase` é permitido **somente**
dentro do ambiente de teste isolado, nunca na base de desenvolvimento ou produção.

```php
// TestCase base já configurado — apenas herde
class MeuTest extends TestCase
{
    use RefreshDatabase; // seguro: roda em SQLite :memory: via phpunit.xml
}
```

Onde escrever testes:
```
tests/
├── Unit/
│   └── Services/      ← lógica pura, sem HTTP
├── Feature/
│   ├── Auth/          ← login, 2FA, rate limiting
│   ├── Users/         ← CRUD, policies, autorização
│   └── Files/         ← upload, serve, assinatura
```

Cobertura mínima esperada por módulo:
- Happy path de create/update
- Tentativa sem autorização (403)
- Validação com dados inválidos (422)
- Rate limiting quando aplicável

---

## Quando Adicionar um Novo Módulo

Checklist obrigatório (ver `AGENTS.md` para detalhes):

- [ ] Model com `FIELD_*` e `TABLE` constants
- [ ] Migration reversível com índices e FKs explícitas
- [ ] Policy com todos os métodos CRUD
- [ ] Entry no enum `Permissions`
- [ ] Entry no enum `Roles` se aplicável
- [ ] Seeder em `PermissionRoleSeeder`
- [ ] Livewire `IndexComponent` estendendo `IndexBaseComponent`
- [ ] Livewire `FormComponent` estendendo `FormBaseComponent`
- [ ] Livewire `Form` object com `rules()`, `setModel()`/`setUser()`, `payload()`
- [ ] Service + Contract granular para a lógica de escrita (camada obrigatória)
- [ ] Binding do contract → Service no `AppServiceProvider`
- [ ] Blade usando MaryUI (padrões de `guidelines-frontend.md`)
- [ ] Traduções em `lang/pt_BR/` e `lang/en/`
- [ ] Testes de autorização e validação

---

## Checklist Pré-Entrega

Antes de reportar qualquer tarefa como concluída, verifique:

- [ ] Imports limpos (sem unused)
- [ ] Sem N+1 óbvio (eager loading aplicado)
- [ ] Policies aplicadas em toda escrita
- [ ] Permissions seedadas
- [ ] Erros de validação renderizam por campo
- [ ] Migrations seguras e indexadas
- [ ] Nenhum segredo exposto
- [ ] Uploads ainda validados por tipo + MIME + extensão + tamanho
- [ ] Headers CSP/Security ainda coerentes
- [ ] Nenhum comando destrutivo de DB foi executado
- [ ] Código formatado com `vendor/bin/pint --dirty`
- [ ] MaryUI usado em todos os componentes de UI
- [ ] Nenhuma cor hardcoded (`gray-*`, `white`, `black`) — apenas tokens DaisyUI
- [ ] Padding com progressive enhancement (`p-4 sm:p-8`, nunca `p-12` direto)
- [ ] Botões com `w-full sm:w-auto`, nunca `md:w-auto lg:w-auto xl:w-auto 2xl:w-auto`
- [ ] Nenhum campo/controle escondido com `hidden md:block`

---

## Formatação de Código

Após qualquer alteração em arquivo PHP:
```bash
vendor/bin/pint --dirty
```

Nunca entregue código não formatado pelo Pint.

---

## Referências Rápidas

| O que preciso?             | Onde encontrar?                          |
|----------------------------|------------------------------------------|
| Padrão de Service/Contract | `docs/guidelines-backend.md`            |
| Padrão de Index/Form UI    | `docs/guidelines-frontend.md`           |
| Regras de segurança full   | `AGENTS.md` → Security Rules            |
| Rodar testes com segurança | `./bin/test-isolated`                   |
| Enums de permissão         | `app/Enums/Auth/Permissions.php`        |
| Enums de roles             | `app/Enums/Auth/Roles.php`              |
| Middleware registrados     | `bootstrap/app.php`                     |
| Rotas                      | `routes/web.php`                        |
| Plano de correção ativo    | `docs/correction-plan.md`              |
| Plano de melhoria de UI/UX | `docs/ui-improvement-plan.md`          |
| Guidelines de design       | `docs/guidelines-frontend.md` → Design System |

---

# Mapa Técnico da Base (Realidade do Código)

> Esta seção descreve o que **de fato existe** neste repositório base. Eventuais nomes
> "Customer / Business / Financial / Lead" que apareçam nos guidelines são **ilustrativos de
> módulos futuros** e **não existem** no código. Ao criar um módulo novo, **espelhe o módulo
> `Auth/User`**, que é a referência viva.

## Módulos Existentes

- **Auth**: `Login`, `TwoFactorChallenge`, `Profile/UpdateComponent`, `Profile/TwoFactorSetup`,
  `User/IndexComponent`, `User/FormComponent`.
- **Common**: `Dashboard`, `Navigation/{Navbar,Sidebar}Component`, `Table/{Header,Actions,NoContent}Component`,
  `Action/ModalComponent`.
- **Logging**: `Models/Logging/Activity` (spatie/laravel-activitylog).

## Camada de Lógica: Services + Contracts (obrigatória)

Toda lógica de escrita/coordenação vive em **Services** que implementam um **Contract granular**
(uma capacidade por interface — Interface Segregation), vinculados no `AppServiceProvider::register()`.

```
app/Services/{Dominio}/XptoService.php     implements App\Contracts\{Dominio}\DoesXpto
app/Contracts/{Dominio}/DoesXpto.php       interface granular com handle(...): Model
```

- Assinatura típica: `public function handle(array $payload): Model` (ver `CreateUserService`).
- O Service abre a própria transação: `DB::transaction(fn () => ...)`.
- Contracts granulares já existentes: `CreatesUsers`, `UpdatesUsers`, `UpdatesProfiles`,
  `AuthenticatesUsers`, `BuildsPermissionOptions`, `BuildsSidebarMenus`.
- Binding obrigatório em `AppServiceProvider`:
  ```php
  $this->app->bind(CreatesUsers::class, CreateUserService::class);
  ```
- Componentes recebem o Service por **injeção do contract** no `boot()`
  (ex.: `boot(CreatesUsers $createUserService)`), nunca instanciam a classe concreta.
- A camada de Service é **obrigatória** — não coloque regra de negócio em componente/Blade.

## Hierarquia de Componentes Livewire

Todo componente herda da cadeia: `BaseComponent` → `IndexBaseComponent` **ou** `FormBaseComponent`.

`BaseComponent` (abstrata) traz as concerns e exige implementar:
- `getModelClass(): string`
- `getRoutePrefix(): string`
- `getViewPath(): string`

### Concerns disponíveis (reutilize, não reescreva)
| Concern | Papel |
|---|---|
| `HasBreadcrumbs` | `setBreadcrumbs($page)` |
| `HasComponentFeedback` | `toastSuccess($msg, $redirect)`, `toastError($msg)` (MaryUI Toast) |
| `HasComponentTranslations` | `mountTranslationPath()` |
| `HasViewData` | `setBaseViewData()`, `setCustomViewData()` |
| `HandlesIndexListingState` | busca, paginação, sort, `getModelWhere()` |
| `HandlesIndexDeletion` | fluxo de confirmação + delete |
| `HandlesFormItemState` | `setItem()`, `$item`, `$action` (create/update) |

### IndexComponent — implementar
- `itemsQuery(): Builder` e `deleteItem(Model $item): void`.
- No `mount()` (após `parent::mount()`): `setSortByColumn()`, `setTableHeaders()`,
  `setSearchableFields()`, e `setItemModalText()` quando houver delete.
- Filtros fixos: sobrescrever `getModelWhere(): ?array` retornando linhas `[coluna, operador, valor]`
  (operador `IN` aceito). Filtros dinâmicos: propriedade pública + `updatedX()` chamando `resetPage()`.
- Estado de URL já incluso: `#[Url(as: 'q')] $search`, `#[Url(as: 'pp')] $perPage` (10/25/50).
- `mount()` autoriza `UserActions::MOUNT`; a computed `items()` autoriza `UserActions::READ`.

### FormComponent — implementar
- `create(): void`, `update(): void`, `getUpdateRoute(): ?string`.
- Service injetado por contract granular no `boot()` (ex.: `boot(CreatesUsers $createUserService)`).
- Fluxo obrigatório em create/update:
  ```php
  $this->authorize('create', $this->getModelClass());
  $payload = $this->form->payload(); // valida e monta o payload
  try {
      $this->item = $this->createUserService->handle($payload);
      $this->toastSuccess(__('feedback.create_success'), $this->getUpdateRoute());
  } catch (Throwable $e) {
      logger()->error($e->getMessage());
      $this->toastError(__('feedback.create_error'));
  }
  ```
- `mount(Model|string|null $itemId)` já resolve item + autoriza a `$action` automaticamente.

### Form Objects (`app/Livewire/Forms/{Dominio}/XptoForm.php`)
- Livewire `Form` com propriedades tipadas, `rules()`, `setModel()`/`setUser()` (hidratar do model)
  e um construtor de payload (`payload()` no `UserForm`).
- O payload usa **constantes de campo do model** — nunca strings cruas.

## Models

- Herde `App\Models\BaseModel` para models de domínio (traz `HasUuids`, `SoftDeletes`,
  `HasFactory`, `$guarded = ['*']`, `getOptions()`, `getFrontendTitle/Subtitle()`).
- `User` é `Authenticatable` (não estende `BaseModel`) e adiciona `HasRoles`, `LogsActivity`, `Notifiable`.
- **Metadados via atributos PHP 8** (padrão do `User`): `#[Table(...)]`, `#[Fillable([...])]`, `#[Hidden([...])]`.
- **Constantes obrigatórias**: `FIELD_*` para colunas, `APPEND_FIELD_*` para campos sintéticos,
  `TABLE` para o nome da tabela. Nunca use string crua de coluna na lógica.
- Casts via `casts(): array` (Laravel 13). Senha sempre `'hashed'`; segredos 2FA `'encrypted'`.
- `getOptions($fieldId, $fieldDescription, $filter)` gera arrays `{value,label}` para `x-select`,
  já com a opção vazia "Selecione".

## Autorização

- `BasePolicy::can(User $user, Permissions|string $permission)` → `$user->can(...)`.
- Policies em `app/Policies/{Dominio}/XptoPolicy.php`, registradas no `AuthServiceProvider`.
- **Ações** são padronizadas pelo enum `App\Enums\Common\UserActions`:
  `mount`, `read`, `create`, `update`, `delete` — usadas direto em `$this->authorize(UserActions::X->value, ...)`.
- **Permissions** (`App\Enums\Auth\Permissions`) seguem o padrão `{action}_{model}`
  (`create_user`, `read_user`, `mount_dashboard`, `view_horizon`, ...).
- **Roles** (`App\Enums\Auth\Roles`): `user`, `manager`, `administrator` (com `label()` e `options()`).
- Todo módulo novo: adicionar cases em `Permissions`, semear em `PermissionRoleSeeder`, criar Policy.

## Enums

- `Common\ComponentEvents`: nomes de eventos Livewire (`action-required`, `delete-item`,
  `profile-updated`, `search-updated`, `status-filter-updated`). Use o enum, nunca string crua em `#[On]`/`dispatch`.
- `Common\UserActions`, `Common\Boolean`, `Common\Gender`, `Auth\Roles`, `Auth\Permissions`.

## Traduções (pt_BR e en — sempre as duas)

Estrutura de chaves usada pela base:
- `interface.identification.{tabela}.title|subtitle` e `...{tabela}.{page}.title|subtitle`
  (resolvido por `BaseModel::getFrontendTitle/Subtitle`).
- `interface.identification.{tabela}.modal_text` (texto do modal de exclusão).
- `fields.*` (labels de campos), `feedback.*` (`create_success`, `create_error`, ...).
- `interface.*` (`update_button`, `delete_button`, `back_button`, `create_button`, `open_menu`, `select.option`).
- `enums.auth.roles.*`.

## Arquivos & Uploads (`App\Traits\ManagesFilesTrait`)

- Use o trait; defina a propriedade `$filePath` (diretório lógico) no consumidor.
- Armazenamento no disco `private`; servido por `route('files.serve')` via
  **URL assinada temporária** (`getFileUrl()`, 30 min) atrás de middleware `signed` + `auth`.
- Validação embutida: MIME allowlist (jpeg/png/gif/webp), tamanho máx. 5 MB, sanitização de path
  (`isManagedFilePath`). Nunca confie em path vindo do usuário.

## Segurança da Plataforma

- `SecurityHeaders` (global): CSP com **nonce** (`Vite::useCspNonce()`), `X-Frame-Options: DENY`,
  `nosniff`, `Referrer-Policy`, `Permissions-Policy`, HSTS em produção HTTPS.
  → Nunca adicione scripts/estilos inline; use o nonce ou bundle via Vite.
- `SessionIdleTimeout` (grupo web) e `EnsureTwoFactorVerified` (alias `two-factor`).
- `bootstrap/app.php`: `throttleWithRedis()`, `authenticateSessions()`, `trustProxies(...)`.
- Rate limiter `global-web`: 120/min por usuário ou IP (`AppServiceProvider`).
- 2FA: `pragmarx/google2fa-laravel` + `bacon/bacon-qr-code`; segredo `encrypted` no model.
- Activity log automático no `User` via `LogsActivity` (`logOnlyDirty`, sem logs vazios).

## Rotas

- Páginas Livewire com `Route::livewire(...)->name(...)`.
- Auth-only: `/two-factor-challenge`. Auth + `two-factor`: `/`, `/profile`, `/profile/two-factor`, `/users/*`.
- `files.serve` com `where('path', '.*')` + `signed` + `auth`.
- Sempre use `route('nome', [...])` para links — nunca URL hardcoded.

## Helpers Globais (autoload em `composer.json`)

- `logged_user(): User`, `logged_user_id(): ?string` (`user_helpers.php`).
- `currency_helpers.php`, `string_helpers.php`.

## Testes & Ambiente

- Runner permitido: `./bin/test-isolated` (valida `DB_CONNECTION=sqlite` + `DB_DATABASE=:memory:`
  em `.env.testing`, usa lock e roda no container `app-test` via `docker compose`).
- `./bin/test-safe` / `composer test:safe` e `composer test:isolated` como atalhos.
- `RefreshDatabase` só dentro desse ambiente isolado. **Nunca** `php artisan test` na base local.
- Local dockerizado (`docker-compose.yml`); deploy em produção via **Forge** (sem Docker).

## Checklist de Novo Módulo (espelhar `Auth/User`)

1. Model herdando `BaseModel` com `TABLE`, `FIELD_*`, `APPEND_FIELD_*`, atributos `#[Table/Fillable/Hidden]`, `casts()`.
2. Migration reversível com FKs e índices explícitos (UUID PK).
3. `Contract` granular em `app/Contracts/{Dominio}/` + `Service` em `app/Services/{Dominio}/` (`handle(array): Model`, transação).
4. Binding no `AppServiceProvider::register()`.
5. Policy em `app/Policies/{Dominio}/` registrada no `AuthServiceProvider`.
6. Cases em `Permissions` (`{action}_{model}`) + seed em `PermissionRoleSeeder`.
7. `Forms/{Dominio}/XptoForm.php` (`rules()`, `setModel()`/`setUser()`, `payload()` com `FIELD_*`).
8. `IndexComponent` (extends `IndexBaseComponent`) + `FormComponent` (extends `FormBaseComponent`).
9. Blade MaryUI seguindo os padrões de índice/form de `docs/guidelines-frontend.md`.
10. Traduções em `lang/pt_BR/` **e** `lang/en/` em todas as chaves acima.
11. Rotas com `Route::livewire(...)` no grupo de middleware correto.
12. Testes de autorização (403), validação (422) e happy path no runner isolado.
