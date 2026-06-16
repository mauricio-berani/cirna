# Backend Guidelines (Laravel + Livewire)

These rules are mandatory. Treat them as requirements.

> **Módulo de referência (implementação viva): `Auth/User`.**
> Sempre que precisar de um exemplo concreto, abra os arquivos do módulo de usuários.
> Não existem módulos `Customer`/`Business`/`Financial` nesta base — não os cite.

## Core Rules (Must Follow)
- ALWAYS keep Livewire components thin. Business logic goes into **Services**.
- ALWAYS validate and authorize before any write.
- ALWAYS use **Service contracts** and bind them in `AppServiceProvider`.
- A camada de **Service é obrigatória** para toda lógica de escrita/coordenação (SOLID).
- NEVER write complex business rules in Blade or controllers.
- NEVER override Eloquent base properties with typed properties (ex.: `$table`, `$primaryKey`).

## Architecture Standard

```
Livewire Component (UI + estado + autorização)
    └── Service (regra de negócio, via contract granular)
            └── Model (lean: relações, casts, scopes)
                    └── Policy (autorização granular)
```

- Componentes Livewire orquestram estado de UI e chamam Services para mutações.
- Services encapsulam regra de negócio, abrem a própria transação e aceitam o payload já validado.
- Models são lean: relacionamentos, casts e accessors/mutators mínimos.
- Policies/Gates controlam acesso para toda mutação e leitura sensível.

## Service Layer Standard

### Localização e nomenclatura
- Implementações em `app/Services/{Dominio}/XptoService.php`.
- Contracts (interfaces) em `app/Contracts/{Dominio}/`.
- **Contracts granulares (1 capacidade por interface)** — Interface Segregation.
  Ex.: `CreatesUsers`, `UpdatesUsers`, `UpdatesProfiles`, `BuildsPermissionOptions`.
- Bind do contract → Service em `AppServiceProvider::register()`.

Exemplo real (`app/Contracts/Auth/CreatesUsers.php`):
```php
interface CreatesUsers
{
    public function handle(array $payload): User;
}
```

Exemplo real (`app/Services/Auth/CreateUserService.php`):
```php
class CreateUserService implements CreatesUsers
{
    public function handle(array $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $data = $payload['data'];
            $password = $data[User::FIELD_PASSWORD] ?? null;
            unset($data[User::FIELD_PASSWORD], $data['password_confirmation']);

            /** @var User $user */
            $user = new User($data);
            $user->forceFill([User::FIELD_PASSWORD => $password])->save();

            $user->syncRoles([$payload['role']]);
            $user->syncPermissions($payload['permissions']);

            return $user;
        });
    }
}
```

Binding (`AppServiceProvider::register()`):
```php
$this->app->bind(CreatesUsers::class, CreateUserService::class);
```

### Regras de Service
- A Service abre a própria transação (`DB::transaction(...)`) quando há mais de um write/efeito.
- Recebe o payload já validado pelo Form object (array), nunca o `Request` cru.
- Use constantes de campo do model (`User::FIELD_*`) — sem strings cruas.
- Não vire "utility service": uma capacidade por Service/contract.
- Quando precisar do usuário logado, passe-o explicitamente ou use `logged_user()`.

## DTO / Form Standard
- Use Livewire `Form` classes como DTO. Local: `app/Livewire/Forms/{Dominio}/`.
- Propriedades públicas tipadas com defaults.
- Métodos esperados: `rules()`, `setModel()`/`setUser()` (hidratar a partir do model) e
  um construtor de payload (`payload()` no `UserForm`).
- O payload usa constantes de campo do model — sem strings cruas.

Exemplo real (`app/Livewire/Forms/Auth/UserForm.php`):
```php
public function payload(): array
{
    $validated = $this->validate();

    $role = $validated[User::APPEND_FIELD_USER_ROLE];
    $permissions = Arr::wrap($validated[User::APPEND_FIELD_PERMISSIONS] ?? []);

    unset($validated[User::APPEND_FIELD_USER_ROLE], $validated[User::APPEND_FIELD_PERMISSIONS]);

    return [
        'data' => collect($validated)->filter(fn ($v) => ! is_null($v))->all(),
        'role' => $role,
        'permissions' => $permissions,
    ];
}
```

## Form Component Standard
- Estenda `FormBaseComponent`.
- Implemente `getModelClass()`, `getRoutePrefix()`, `getViewPath()`, `getUpdateRoute()`,
  `create()`, `update()`.
- `mount()` da base já resolve o item e chama `$this->authorize($action, ...)`.
- Em `create()`/`update()`: autorize, monte o payload via Form e chame a Service.
  Use os helpers de toast para sucesso/erro e registre o erro em log.

Exemplo real (`app/Livewire/Auth/User/FormComponent.php`):
```php
public function create(): void
{
    $this->authorize('create', $this->getModelClass());
    $payload = $this->form->payload();

    try {
        $this->item = $this->createUserService->handle($payload);
        $this->toastSuccess(__('feedback.create_success'), $this->getUpdateRoute());
    } catch (Throwable $error) {
        logger()->error($error->getMessage());
        $this->toastError(__('feedback.create_error'));
    }
}
```

> A Service do componente é injetada por contract no `boot()`:
> `public function boot(CreatesUsers $createUserService): void`.

## Index Component Standard
- Estenda `IndexBaseComponent`.
- Implemente `itemsQuery(): Builder`, `deleteItem(Model $item): void`,
  `getModelClass()`, `getRoutePrefix()`, `getViewPath()`.
- No `mount()` (após `parent::mount()`): `setSortByColumn()`, `setTableHeaders()`,
  `setSearchableFields()` e `setItemModalText()`.

Exemplo real (`app/Livewire/Auth/User/IndexComponent.php`):
```php
public function mount(): void
{
    parent::mount();
    $this->setSortByColumn(User::FIELD_NAME);
    $this->setTableHeaders([
        User::FIELD_NAME  => __('fields.name'),
        User::FIELD_EMAIL => __('fields.email'),
    ]);
    $this->setItemModalText(__('interface.identification.users.modal_text'));
    $this->setSearchableFields([
        User::FIELD_NAME,
        User::FIELD_EMAIL,
    ]);
}
```

## Validation
- Valide no Livewire `Form` (`rules()`).
- Evite sobrescrever colunas não-nulas com `null` (filtre nulos no payload).
- Use regras customizadas para lógica de domínio.

## Authorization
- Policies para models, Gates para páginas de infraestrutura.
- `BasePolicy::can()` traduz `Permissions` → `$user->can(...)`.
- As ações são padronizadas pelo enum `App\Enums\Common\UserActions`
  (`mount`, `read`, `create`, `update`, `delete`).
- `IndexBaseComponent::mount()` autoriza `mount`; a computed `items()` autoriza `read`;
  o delete autoriza `delete`. Nunca pule autorização em escrita.

## Database & Migrations
- Base principal: **MySQL 8** com **UUID** como PK (`HasUuids`). Testes em SQLite `:memory:` isolado.
- Migrations reversíveis e seguras (deploy via Forge roda `migrate --force`).
- FKs e índices explícitos (FKs, identificadores únicos, filtros e ordenações frequentes).
- Use constantes de campo do model no código.

## Error Handling
- Envolva mutações em transações (dentro da Service).
- Logue erros com contexto (ids, user id), sem vazar segredos.
- Não engula exceções silenciosamente.

## Models
- Models de domínio herdam `App\Models\BaseModel` (`HasUuids`, `SoftDeletes`, `HasFactory`,
  `$guarded = ['*']`, `getOptions()`, identificação de frontend via
  `App\Models\Concerns\ProvidesFrontendIdentification`).
- Metadados via atributos PHP 8 (`#[Table]`, `#[Fillable]`, `#[Hidden]`) como em `User`.
- Constantes `TABLE`, `FIELD_*`, `APPEND_FIELD_*` obrigatórias.
- Casts via `casts(): array`. Senha `'hashed'`; segredos `'encrypted'`.

## Tests
- Cubra Service, ações Livewire e policies.
- Casos: happy path, autorização (403) e validação (422).
- Testes só no runner isolado: `./bin/test-isolated` (SQLite `:memory:`). Nunca na base real.
- Testes de Service vivem em `tests/Unit/Services/`.

## Code Style
- Métodos curtos e de propósito único.
- Intenção explícita acima de esperteza.
- Rode `vendor/bin/pint --dirty` após alterar PHP.

## Scopes (Query Standard)
- Prefira scopes dedicados para joins e restrições por papel.
- Coloque scopes em `app/Scopes/...` e mantenha-os pequenos.

## Enums (Status & Roles)
- Use enums para status de domínio e papéis (`App\Enums\Auth\Roles`,
  `App\Enums\Auth\Permissions`, `App\Enums\Common\*`).
- Nunca use string crua de status/role em Services ou componentes.

## Filters (IndexBaseComponent)
- Filtros fixos: `getModelWhere()` retornando linhas `[coluna, operador, valor]` (operador `IN` aceito).
- Busca: `setSearchableFields()`.
- Filtros dinâmicos: propriedade pública + `updatedX()` chamando `$this->resetPage()`.
- Mantenha filtros no componente, nunca no Blade.

Exemplo:
```php
protected function getModelWhere(): ?array
{
    $filters = [];

    if ($this->status !== '') {
        $filters[] = [Lead::FIELD_STATUS, '=', $this->status];
    }

    return $filters ?: null;
}
```
