# Plano de Correção — Projeto Base

Gerado em: 2026-05-07
Base da análise: revisão de segurança, boas práticas Laravel 13 e qualidade sênior.

---

## Legenda de Prioridade

| Prioridade | Descrição                                              |
|------------|--------------------------------------------------------|
| CRÍTICO    | Bloqueia produção segura — resolver antes de qualquer deploy público |
| ALTA       | Impacta segurança ou confiabilidade — resolver na primeira sprint    |
| MÉDIA      | Melhora qualidade e auditoria — planejar para breve                  |
| BAIXA      | Otimização e conveniência — backlog de melhorias                     |

---

## CRÍTICO

### C1 — Rotação de todas as credenciais do `.env`

**Problema:** O arquivo `.env` contém credenciais reais (OpenAI, Pagar.me, e-mail Zoho,
senha de admin). Mesmo que não esteja commitado, essas credenciais existem localmente e
qualquer vazamento do ambiente (log, screenshot, acesso ao servidor) as expõe.

**Ação:**
- Gerar novo `APP_KEY` com `php artisan key:generate`.
- Revogar e regenerar a chave da OpenAI no painel deles.
- Revogar e regenerar as chaves do Pagar.me (secret + encryption key).
- Trocar a senha do e-mail Zoho configurada como remetente.
- Trocar a senha do admin inicial hardcoded no seeder (ou remover do `.env`).
- No Forge: configurar todas as variáveis de ambiente pelo painel, nunca pelo `.env` do repo.
- Adicionar ao `.env.example` apenas placeholders sem valores reais.

**Arquivo do seeder:** `database/seeders/AdministratorUserSeeder.php`
**Responsável:** Manual — requer acesso aos painéis externos.

---

### C2 — Implementar `.env.testing` para testes isolados

**Problema:** O script `bin/test-isolated` já exige `DB_CONNECTION=sqlite` e
`DB_DATABASE=:memory:` em `.env.testing`, mas esse arquivo não existe no repositório.
Sem ele, o comando de teste recusa executar.

**Ação:** Criar `.env.testing` com:

```env
APP_ENV=testing
APP_KEY=base64:CHAVE_GERADA_PARA_TESTES
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=:memory:
DB_FOREIGN_KEYS=true

CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array

BCRYPT_ROUNDS=4
```

Adicionar `.env.testing` ao `.gitignore` (já está — validar).
Adicionar `.env.testing.example` ao repo com os placeholders acima.

---

## ALTA

### A1 — Implementar testes automatizados (Unit + Feature)

**Problema:** O projeto só tem os placeholders do Laravel. Para um projeto base que vai
originar múltiplos sistemas, testes são essenciais para garantir que novas features não
quebrem o que já existe.

**Regra:** Todos os testes usam `RefreshDatabase` + SQLite `:memory:` via `phpunit.xml`.
Nunca usar `RefreshDatabase` apontando para a base real.

**Escopo mínimo:**

```
tests/
├── Unit/
│   └── Services/
│       ├── AuthenticateUserServiceTest.php
│       ├── CreateUserServiceTest.php
│       └── UpdateUserServiceTest.php
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php              ← login OK, credenciais erradas, rate limiting
│   │   ├── TwoFactorTest.php          ← middleware 2FA, desafio, setup
│   │   └── SessionTimeoutTest.php     ← inatividade desloga
│   ├── Users/
│   │   ├── UserIndexTest.php          ← listagem, autorização
│   │   ├── UserCreateTest.php         ← criação, validação, policy
│   │   ├── UserUpdateTest.php         ← atualização, auto-edição, policy
│   │   └── UserDeleteTest.php         ← deleção, auto-delete proibido
│   └── Files/
│       └── FileServeTest.php          ← signed URL, auth, path traversal
```

**Cobertura mínima por teste:**
- Happy path com dados válidos
- Tentativa sem autenticação → 401/redirect
- Tentativa sem permissão → 403
- Dados inválidos → erros de validação corretos
- Rate limiting (onde aplicável)

**Como rodar:**
```bash
./bin/test-isolated
# ou: composer test:isolated
```

---

### A2 — Configurar CI/CD (GitHub Actions)

**Problema:** Sem pipeline, não há garantia de que código quebrado chegue ao Forge.

**Ação:** Criar `.github/workflows/ci.yml`:

```yaml
name: CI

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: sqlite3, pdo_sqlite
          coverage: none

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist --optimize-autoloader

      - name: Copy .env.testing
        run: cp .env.testing.example .env.testing

      - name: Security audit
        run: composer audit

      - name: Code style (Pint)
        run: vendor/bin/pint --test

      - name: Run tests (isolated)
        run: php artisan test --env=testing
        env:
          APP_ENV: testing
          DB_CONNECTION: sqlite
          DB_DATABASE: ':memory:'
          DB_FOREIGN_KEYS: true
          CACHE_STORE: array
          SESSION_DRIVER: array
          QUEUE_CONNECTION: sync
```

---

### A3 — Rate limiting global nas rotas web

**Problema:** Apenas o login tem rate limiting. As demais rotas podem ser abusadas.

**Ação:** Em `bootstrap/app.php`, adicionar dentro de `withMiddleware`:

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('web', function (Request $request) {
    return Limit::perMinute(120)->by(
        $request->user()?->id ?: $request->ip()
    );
});
```

E aplicar ao grupo `web`:
```php
$middleware->throttle('web');
```

**Arquivo:** `bootstrap/app.php`

---

### A4 — Horizon: trocar gate de e-mail por Spatie Permission

**Problema:** O acesso ao Horizon Dashboard é controlado por lista de e-mails hardcoded
via variável de ambiente. Isso não integra com o sistema de permissions já existente.

**Ação:** Em `app/Providers/HorizonServiceProvider.php`, substituir:

```php
// Antes (frágil — depende de lista de e-mails no .env)
Horizon::auth(function ($request) {
    return in_array($request->user()?->email, explode(',', env('HORIZON_ALLOWED_EMAILS', '')));
});
```

Por:

```php
// Depois (usa o sistema de permissions do projeto)
Gate::define('viewHorizon', function ($user) {
    return $user->hasPermissionTo(\App\Enums\Auth\Permissions::ViewHorizon);
});
```

Adicionar `ViewHorizon` ao enum `app/Enums/Auth/Permissions.php` e ao seeder
`database/seeders/PermissionRoleSeeder.php` (apenas para o role Superadmin).

**Arquivos:** `app/Providers/HorizonServiceProvider.php`, `app/Enums/Auth/Permissions.php`,
`database/seeders/PermissionRoleSeeder.php`

---

### A5 — Revalidar MIME type ao servir arquivos no FileController

**Problema:** A validação de MIME type é feita no upload (`ManagesFilesTrait`), mas não
ao servir o arquivo. Um arquivo com extensão válida poderia ter conteúdo diferente se
manipulado diretamente no storage.

**Ação:** Em `app/Http/Controllers/FileController.php`, adicionar no método `serve`:

```php
private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

public function serve(Request $request, string $path): Response
{
    $normalizedPath = trim(str_replace(['..', '\\'], '', $path), '/');

    if (
        $normalizedPath !== $path
        || ! $this->canServePath($request, $normalizedPath)
        || ! Storage::disk('private')->exists($normalizedPath)
    ) {
        abort(404);
    }

    // Revalidar MIME no momento de servir
    $mime = Storage::disk('private')->mimeType($normalizedPath);
    abort_unless(in_array($mime, self::ALLOWED_MIME_TYPES), 403);

    return Storage::disk('private')->response($normalizedPath);
}
```

**Arquivo:** `app/Http/Controllers/FileController.php`

---

## MÉDIA

### M1 — Soft deletes no BaseModel

**Problema:** Sem soft deletes, dados deletados são perdidos permanentemente, dificultando
auditoria e recuperação. Para um projeto base que vai originar vários sistemas, essa
segurança deve estar na base.

**Ação:** Adicionar `SoftDeletes` ao `BaseModel`:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseModel extends Model
{
    use SoftDeletes;
    // ...
}
```

Criar migration para adicionar `deleted_at` às tabelas existentes:

```php
Schema::table('users', function (Blueprint $table) {
    $table->softDeletes();
});
```

**Nota:** Verificar impacto nas queries existentes — `withTrashed()` pode ser necessário
em alguns casos administrativos (como a listagem de usuários pelo admin).

**Arquivos:** `app/Models/BaseModel.php`, nova migration.

---

### M2 — Converter `activity_log.id` para UUID

**Problema:** A migration recente converteu os morphs (`subject_id`, `causer_id`) para
UUID, mas o `id` da tabela `activity_log` ainda é `bigIncrements`. Isso cria inconsistência
no modelo de dados.

**Ação:** Criar migration:

```php
Schema::table('activity_log', function (Blueprint $table) {
    $table->uuid('id')->change()->primary();
});
```

**Nota:** Avaliar impacto em queries que referenciam `activity_log.id` por tipo inteiro.
Confirmar com o usuário antes de executar em produção.

**Arquivo:** Nova migration.

---

### M3 — Configurar Dependabot para atualizações de segurança

**Problema:** Sem Dependabot, vulnerabilidades em dependências só são descobertas pelo
`composer audit` manual.

**Ação:** Criar `.github/dependabot.yml`:

```yaml
version: 2
updates:
  - package-ecosystem: "composer"
    directory: "/"
    schedule:
      interval: "weekly"
    open-pull-requests-limit: 5
    labels:
      - "dependencies"
      - "security"

  - package-ecosystem: "npm"
    directory: "/"
    schedule:
      interval: "weekly"
    open-pull-requests-limit: 5
    labels:
      - "dependencies"
      - "frontend"
```

---

### M4 — Validar tamanho máximo no upload de arquivos

**Problema:** `ManagesFilesTrait` valida tipo e MIME, mas não tem limite explícito de
tamanho no nível da trait (apenas no PHP/nginx config).

**Ação:** Em `app/Traits/ManagesFilesTrait.php`, adicionar validação de tamanho:

```php
private const MAX_FILE_SIZE_BYTES = 5 * 1024 * 1024; // 5MB

// Dentro do método de upload:
if ($file->getSize() > self::MAX_FILE_SIZE_BYTES) {
    throw new \InvalidArgumentException('File size exceeds maximum allowed size.');
}
```

**Arquivo:** `app/Traits/ManagesFilesTrait.php`

---

## BAIXA

### B1 — Substituir `predis/predis` por extensão `phpredis`

**Problema:** `predis` é implementação PHP puro, 3-5x mais lento que a extensão C `phpredis`.

**Ação:**
- Instalar `phpredis` no Dockerfile: `apt-get install -y php8.3-redis`
- Remover `predis/predis` do `composer.json`
- Atualizar `REDIS_CLIENT=phpredis` no `.env` e `.env.example`

**Impacto:** Melhoria de performance em operações Redis (cache, sessions, queues).
**Arquivos:** `docker/Dockerfile`, `composer.json`, `.env.example`

---

### B2 — Documentação de onboarding por módulo

**Problema:** Novos desenvolvedores (e o Claude em novas conversas) não têm visão clara
dos módulos existentes e seus fluxos.

**Ação:** Criar `docs/modules.md` com:
- Mapa de módulos existentes
- Fluxo de autenticação + 2FA
- Fluxo de permissionamento (Role → Permission → Policy → UI)
- Como adicionar um novo módulo (passo a passo com exemplos)

---

## Ordem de Execução Recomendada

```
Sprint 0 (Imediato — antes de qualquer deploy público):
  C1 → Rotação de credenciais
  C2 → .env.testing criado

Sprint 1 (Primeira semana):
  A1 → Testes (Auth + Users)
  A2 → CI/CD GitHub Actions
  A5 → Revalidação MIME no FileController

Sprint 2 (Segunda semana):
  A3 → Rate limiting global
  A4 → Horizon com Spatie Permission
  M3 → Dependabot

Sprint 3 (Quando planejar):
  M1 → Soft deletes
  M2 → Activity log UUID
  M4 → Tamanho máximo de upload

Backlog:
  B1 → phpredis
  B2 → Docs de módulos
```

---

## Status de Acompanhamento

| Item | Prioridade | Status     | Responsável |
|------|------------|------------|-------------|
| C1   | CRÍTICO    | Pendente   | Manual      |
| C2   | CRÍTICO    | Pendente   | Dev         |
| A1   | ALTA       | Pendente   | Dev         |
| A2   | ALTA       | Pendente   | Dev         |
| A3   | ALTA       | Pendente   | Dev         |
| A4   | ALTA       | Pendente   | Dev         |
| A5   | ALTA       | Pendente   | Dev         |
| M1   | MÉDIA      | Pendente   | Dev         |
| M2   | MÉDIA      | Pendente   | Dev         |
| M3   | MÉDIA      | Pendente   | Dev         |
| M4   | MÉDIA      | Pendente   | Dev         |
| B1   | BAIXA      | Backlog    | Dev         |
| B2   | BAIXA      | Backlog    | Dev         |
