# AGENTS.md

Read this file before making any change in this repository. Treat every rule here as mandatory.

This project is a Laravel 13 + Livewire 4 + Mary UI application. The codebase must stay secure, predictable, performant, scalable, and strongly aligned with SOLID.

## Source of Truth
- This file is the root guide for any AI or coding agent.
- Also read:
  - `docs/guidelines-backend.md`
  - `docs/guidelines-frontend.md`
- If there is a conflict:
  - `AGENTS.md` wins.
  - Then `docs/guidelines-backend.md` and `docs/guidelines-frontend.md`.
  - Then local file conventions already established in the module.

## Non-Negotiable Safety Rules
- Never run tests, migrations, seeds, rollbacks, wipes, or destructive database commands against the system database.
- Never run:
  - `php artisan test`
  - `vendor/bin/phpunit`
  - `phpunit`
  - `php artisan migrate:fresh`
  - `php artisan db:wipe`
  - `php artisan migrate:refresh`
  - `php artisan migrate:reset`
  - `php artisan migrate:rollback`
- If tests must run, use only the isolated runner configured for this project.
- Assume any non-isolated database command is forbidden unless the user explicitly requests it and confirms risk.
- Never expose secrets in code, logs, commits, Blade, JS, or examples.
- Never add hardcoded credentials, tokens, default passwords, or API keys.
- Never weaken security headers, validation, authorization, or upload restrictions without explicit approval.

## Project Stack and Expected Standards
- PHP `8.3+`
- Laravel `13.x`
- Livewire `4.x`
- Mary UI for UI components
- Vite for assets
- MySQL 8 as the primary database, with UUID primary keys (`HasUuids`)
- Tests run **only** on isolated SQLite `:memory:` — never against the real database
- Redis for cache, rate limiting, queues, and Horizon
- Dockerized local environment; production deploy via Laravel Forge (`migrate --force`)

**Reference module (living implementation): `Auth/User`.** There are no `Customer`/`Business`/
`Financial` modules in this base — mirror `Auth/User` when adding a new module.

Use the most current stable patterns already adopted in this codebase for:
- Livewire `Form` objects
- typed properties
- contracts + concrete **services** bound in `AppServiceProvider`
- Eloquent attributes and casts aligned with Laravel 13
- route-level and component-level authorization
- secure uploads
- CSP-aware frontend behavior

## Architecture Rules
- Keep Livewire components thin.
- Components orchestrate UI state, authorization, and feedback only.
- Validation must live in Livewire `Form` objects or dedicated validators.
- Business logic must live in **Services** (`app/Services/{Domain}/`). The Service layer is
  mandatory for any write/coordination logic (SOLID).
- Complex mapping, grouping, or persistence logic must not stay inside Blade or bulky components.
- Depend on contracts, not concrete implementations, whenever the behavior represents a use case or domain capability.
- Bind all service/action contracts in `AppServiceProvider`.
- Prefer composition via concerns/services over large inheritance-heavy classes.

## SOLID Rules
- Single Responsibility:
  - one class should have one reason to change
  - move persistence, policy decisions, data mapping, upload handling, and menu building out of large components when they grow
- Open/Closed:
  - prefer extension by small hooks, concerns, contracts, or actions
  - avoid editing large generic base classes for module-specific behavior
- Liskov:
  - base classes must not force unsafe or surprising overrides
  - keep base class contracts minimal and explicit
- Interface Segregation:
  - prefer small, focused contracts
  - do not create “god interfaces”
- Dependency Inversion:
  - UI layer depends on contracts
  - provider binds contracts to implementations

## Livewire 4 Rules
- Prefer full-page components for pages and `#[Layout]`/`#[Title]` when appropriate.
- Use Livewire `Form` classes for form state and validation.
- Use typed public properties.
- Use `#[Locked]` for sensitive component state that must not be tampered with.
- Use `#[Url]` for persistent filter/query state when appropriate.
- Keep render methods simple.
- Avoid placing heavy business logic in `mount()`, `render()`, or Blade.
- Let validation exceptions reach Livewire so field errors render properly.
- Use Livewire events intentionally and name them consistently through enums/constants when the project already does so.

## Blade and Mary UI Rules
- Always prefer Mary UI components over custom HTML when the component exists.
- Reuse project-standard components:
  - headers
  - actions/filter bars
  - tables
  - modals
  - toasts
- Forms must be mobile first.
- Validation messages belong below the relevant field.
- A top summary of errors may exist, but never replace field-level errors.
- Avoid inline styles.
- Avoid inline scripts.
- Keep Blade dumb:
  - simple conditional rendering is fine
  - business rules, filtering, and heavy transformations are not

## Backend Rules
- Validate before write operations.
- Authorize before write operations and sensitive reads.
- Wrap write use cases in transactions when more than one write or side effect occurs.
- Log failures with useful context, but never leak secrets.
- Never swallow domain failures silently.
- Models must stay lean:
  - relationships
  - scopes
  - casts
  - minimal accessors/mutators
- Avoid putting application logic in models when an action/service is a better fit.

## Model Rules
- Use constants for all database field names.
- Use constants for important appended or synthetic field names when used repeatedly.
- Prefer Laravel 13 style:
  - `casts(): array`
  - `'hashed'` cast for passwords when applicable
  - Eloquent attributes where already adopted in the project
- Keep fillable/hidden/table metadata aligned with the chosen project pattern.
- No raw field-name strings in business logic when a model constant exists.

## Migration and Database Rules
- Every migration must be reversible.
- Every foreign key must be explicit where applicable.
- Add indexes deliberately:
  - foreign keys
  - unique business identifiers
  - frequent filters
  - frequent joins
  - columns used in sorting/searching when justified
- Avoid over-indexing blindly.
- Choose column nullability carefully.
- Use database constraints for integrity, not only application validation.
- Prefer UUID-aware design where the project already uses UUIDs.
- Review query patterns before schema changes.

## Performance and Scalability Rules
- Prefer query scopes or dedicated query builders for complex list screens.
- Avoid N+1 queries.
- Eager load relationships intentionally.
- Do not over-fetch columns if only a subset is required.
- Use Redis for:
  - cache
  - queues
  - rate limiting
  - Horizon monitoring
- Long-running or heavy non-UI work should go to queues when appropriate.
- Keep paginated index screens efficient and predictable.
- Search/filter logic must be explicit and safe.

## Security Rules
- All write flows must be policy-protected.
- Every module must have its policies and permissions defined.
- Permissions and roles must be seeded consistently.
- Do not add a module without:
  - policy coverage
  - permission enum entries
  - seeder registration
  - UI authorization checks
- Maintain strong CSP and secure headers.
- Keep cookies, session settings, proxy handling, and HTTPS assumptions production-safe.
- Uploads must be validated by:
  - file type
  - MIME type
  - extension
  - size
  - managed path restrictions
- Never trust user-controlled file paths.
- Escape Blade output by default.
- Do not render raw HTML unless there is a hard reason and the input is trusted and sanitized.
- Avoid introducing XSS vectors in modals, tables, toasts, or rich text.

## Auth, Roles, and Permissions Rules
- Roles and permissions must be enum-driven where the project already follows that pattern.
- Policies are required for CRUD modules.
- Permissions must be reflected in:
  - enum definitions
  - policy checks
  - seeder assignments
  - menus/buttons/actions visibility
- Self-edit and self-delete flows must be considered explicitly.

## Service Layer Rules
- Implementations live in `app/Services/{Domain}/XptoService.php`.
- Contracts are **granular (one capability per interface)** in `app/Contracts/{Domain}/`
  (Interface Segregation), bound to the Service in `AppServiceProvider`.
- Components depend on the contract (injected in `boot()`), never on the concrete class.
- Use a Service when:
  - a use case writes data
  - multiple writes happen
  - an operation coordinates validation-adjacent transformation
  - logic is reused
  - external services/files/cache/queue are involved
- The Service opens its own transaction (`DB::transaction(...)`) when more than one write/side effect occurs.
- Keep Services focused. Avoid “utility service” dumping grounds. Prefer one capability per Service/contract.

## Forms and Filters Rules
- Livewire `Form` objects must expose:
  - typed properties
  - `rules()`
  - methods like `setModel()`/`setUser()` when needed
  - a payload builder method
- Filters should be explicit in components and query logic, not hidden in Blade.
- Searchable fields and fixed filters must be intentionally defined.
- Reset pagination on filter/search changes.

## Horizon, Queue, and Redis Rules
- Horizon must remain configured for queue visibility and operations.
- Queueable work must be idempotent when possible.
- Cache keys must be explicit and namespaced.
- Do not introduce cache behavior without invalidation strategy.

## Frontend Asset Rules
- Use the Livewire 4-compatible asset bootstrap pattern already adopted in this project.
- Do not reintroduce conflicting Alpine/Livewire bootstraps.
- Prefer local bundled dependencies over random CDNs.
- If CSP is impacted by a frontend library choice, document and justify it.

## Testing Rules
- Never assume the default Laravel testing command is safe here.
- If the user explicitly asks to run tests, use only the isolated project runner configured for this repository.
- Prefer adding or updating tests for:
  - authorization
  - validation
  - action/service behavior
  - Livewire component behavior
- Do not run tests silently if there is any risk to the active database.

## Coding Rules
- Remove unused imports.
- Keep methods short and intention-revealing.
- Prefer explicit names over clever abstractions.
- Do not add dead code.
- Do not keep legacy compatibility code unless the project still needs it.
- Favor ASCII unless the file already uses Unicode naturally.

## When Adding a New Module
- Required minimum:
  - model
  - migration with proper indexes and FKs
  - policy
  - permission enum entries
  - seeder registration for roles/permissions
  - Livewire index component (extends `IndexBaseComponent`)
  - Livewire form component (extends `FormBaseComponent`)
  - Livewire form object (`rules()`, `setModel()`/`setUser()`, `payload()`)
  - a Service + granular contract for write logic, bound in `AppServiceProvider`
  - Blade pages using Mary UI
  - translations in `lang/pt_BR/` and `lang/en/`
- Optional but preferred:
  - scopes
  - queued jobs
  - caching strategy
  - tests (in the isolated runner only)

## Pre-Delivery Checklist
- imports cleaned
- no obvious N+1
- policies applied
- permissions seeded
- validation errors render correctly
- field-level errors appear under inputs
- migrations safe and indexed
- no secret exposure
- uploads remain safe
- CSP/security headers still coherent
- no direct DB-destructive command was run

## Forbidden Shortcuts
- No business logic in Blade.
- No hardcoded secrets.
- No skipping policies “temporarily”.
- No raw unreviewed HTML rendering.
- No tests against the system database.
- No broad refactors without keeping the module consistent with existing project patterns.

===

<laravel-boost-guidelines>
=== foundation rules ===

> **PROJECT OVERRIDE — TESTING:** The Laravel Boost block below suggests running
> `php artisan test`, `vendor/bin/phpunit`, etc. **These are forbidden in this project.**
> The only allowed way to run tests is the isolated runner `./bin/test-isolated`
> (SQLite `:memory:` in the `app-test` container). The Non-Negotiable Safety Rules at the
> top of this file always win over the Boost block.

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3
- laravel/framework (LARAVEL) - v13
- laravel/horizon (HORIZON) - v5
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v4
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `laravel-best-practices` — Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns.
- `configuring-horizon` — Use this skill whenever the user mentions Horizon by name in a Laravel context. Covers the full Horizon lifecycle: installing Horizon (horizon:install, Sail setup), configuring config/horizon.php (supervisor blocks, queue assignments, balancing strategies, minProcesses/maxProcesses), fixing the dashboard (authorization via Gate::define viewHorizon, blank metrics, horizon:snapshot scheduling), and troubleshooting production issues (worker crashes, timeout chain ordering, LongWaitDetected notifications, waits config). Also covers job tagging and silencing. Do not use for generic Laravel queues without Horizon, SQS or database drivers, standalone Redis setup, Linux supervisord, Telescope, or job batching.
- `livewire-development` — Use for any task or question involving Livewire. Activate if user mentions Livewire, wire: directives, or Livewire-specific concepts like wire:model, wire:click, wire:sort, or islands, invoke this skill. Covers building new components, debugging reactivity issues, real-time form validation, drag-and-drop, loading states, migrating from Livewire 3 to 4, converting component formats (SFC/MFC/class-based), and performance optimization. Do not use for non-Livewire reactive UI (React, Vue, Alpine-only, Inertia.js) or standard Laravel forms without Livewire.
- `tailwindcss-development` — Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS.
- `laravel-permission-development` — Build and work with Spatie Laravel Permission features, including roles, permissions, middleware, policies, teams, and Blade directives.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

</laravel-boost-guidelines>
