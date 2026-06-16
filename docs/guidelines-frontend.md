# Frontend Guidelines

These rules are mandatory. Treat them as requirements.

---

## Design System

### Tokens de Cor — NUNCA use cores hardcoded Tailwind para texto/fundo/borda

| Propósito | Token correto | Exemplo |
|---|---|---|
| Texto principal | `text-base-content` | títulos, labels |
| Texto secundário | `text-base-content/60` | subtítulos, hints |
| Texto desativado | `text-base-content/40` | placeholders |
| Fundo de página | `bg-base-100` | body, containers |
| Fundo de card | `bg-base-200` ou `bg-base-300` | cards, panels |
| Primário | `text-primary` / `bg-primary` / `border-primary` | CTAs, destaques |
| Erro | `text-error` / `bg-error` | validação, delete |
| Sucesso | `text-success` / `bg-success` | confirmações |

**Nunca** use `text-gray-*`, `text-black`, `bg-white`, `bg-gray-*` — quebram o dark theme.

---

### Sistema de Espaçamento — Mobile-First

Sempre defina padding com progressive enhancement, nunca valores fixos grandes:

```blade
{{-- Correto --}}
class="p-4 sm:p-6 lg:p-8"
class="p-4 sm:p-8 lg:p-12"

{{-- Errado — inutilizável em mobile --}}
class="p-12"
class="p-20"
```

---

### Breakpoints

| Prefixo | Largura | Uso |
|---|---|---|
| (sem prefixo) | 0px+ | Mobile — padrão base |
| `sm:` | 640px+ | Tablet pequeno |
| `md:` | 768px+ | Tablet |
| `lg:` | 1024px+ | Desktop |
| `xl:` | 1280px+ | Desktop largo |

**Regra de ouro:** escreva o estilo mobile primeiro, sobrescreva para telas maiores. Nunca esconda funcionalidade com `hidden md:block` — isso torna a feature inacessível no mobile.

---

### Botões Responsivos

Padrão para botões em formulários e filtros:

```blade
{{-- Um botão responsivo --}}
class="w-full sm:w-auto"

{{-- Container de múltiplos botões --}}
class="flex flex-col sm:flex-row gap-3"

{{-- Nunca repita breakpoints desnecessários --}}
{{-- ERRADO: w-full sm:w-auto md:w-auto lg:w-auto xl:w-auto 2xl:w-auto --}}
{{-- CERTO:  w-full sm:w-auto --}}
```

---

### Containers com Max-Width

Formulários e páginas de auth devem ter max-width para não estirarem em telas largas:

```blade
class="w-full max-w-md"   {{-- formulários de auth --}}
class="w-full max-w-2xl"  {{-- formulários de edição --}}
class="w-full max-w-7xl"  {{-- containers de página --}}
```

---

### Acessibilidade

- Botões sem texto visível **devem** ter `aria-label` ou `tooltip`.
- Botão hamburguer do menu mobile: `aria-label="{{ __('interface.open_menu') }}"`.
- Inputs sempre com `label` associado (MaryUI faz isso automaticamente).
- Manter contraste mínimo WCAG AA — os tokens do design system já garantem isso.
- Nunca esconder funcionalidade crítica com `hidden md:block`.

---

### Respeitar `prefers-reduced-motion`

Adicionar em `resources/css/app.css` (já incluído):

```css
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
```

---

## Core Rules (Must Follow)
- ALWAYS use Livewire components and Mary UI components.
- ALWAYS keep UI data flow explicit: state -> render.
- ALWAYS reuse existing components (table, actions, modals, headers).
- NEVER introduce a new UI framework.
- NEVER add inline `style` without a strong reason.
- NEVER use hardcoded Tailwind color classes (`gray-*`, `black`, `white`) — use design tokens.
- NEVER use `hidden md:block` to hide interactive elements — find a responsive layout instead.

## Standard Index Page Pattern
Use the **User** index page (`resources/views/livewire/auth/user/index.blade.php`) as the
reference implementation. There is no `Customer` module in this base.

Required structure:
- `livewire:common.table.header-component` for title/breadcrumbs.
- `x-card` with `bg-base-300` and shadow.
- `livewire:common.table.actions-component` for search + create.
- `x-table` with `:headers`, `:rows`, `:sort-by`, `:per-page`, `:per-page-values`.
- `<x-slot:empty>` with `livewire:common.table.no-content-component`.
- `livewire:common.action.modal-component` for delete confirmation.

Example (User index):
```blade
<livewire:common.table.header-component :$title :$subtitle :$breadcrumbs />

<x-card class="shadow bg-base-300">
    <livewire:common.table.actions-component :$searching wire:model="search" :$createRoute />
    <x-table
        :headers="$headers"
        :rows="$this->items"
        :sort-by="$sortBy"
        :per-page="$perPage"
        :per-page-values="$perPageValues"
        link="{{ url('/users/update/{id}') }}"
        :no-headers="$noContent"
        class="[&_td]:py-3 [&_th]:py-3"
        with-pagination
    >
        @scope('actions', $item)
            <x-button icon="s-pencil" class="bg-primary btn-circle btn-sm" tooltip="{{ __('interface.update_button') }}" link="{{ route('users.update', ['itemId' => $item->id]) }}" spinner />
            <x-button icon="s-trash" class="bg-error btn-circle btn-sm" tooltip="{{ __('interface.delete_button') }}" wire:click="confirmAction('{{ $item['name'] }}', '{{ $item['id'] }}')" spinner />
        @endscope
        <x-slot:empty>
            <div class="w-full py-6">
                <livewire:common.table.no-content-component />
            </div>
        </x-slot:empty>
    </x-table>
</x-card>

<livewire:common.action.modal-component />
```

## Filters & Search
- Use `IndexBaseComponent` for search, per-page, and sorting.
- Bind search with `wire:model="search"` on the actions component.
- If no results, show the empty state instead of errors.

## Standard Form Page Pattern
Use the **User** form page (`resources/views/livewire/auth/user/form.blade.php`) as the
reference implementation.

Required structure:
- `x-form` with `wire:submit.prevent`.
- Inputs use Mary UI components: `x-input`, `x-select`, `x-textarea`.
- Mensagens de erro com `error-class="text-error"` (token do design system, nunca `text-red-*`).
- Buttons in `x-slot:actions`.

Example (User form):
```blade
<x-form wire:submit.prevent="{{ $item ? 'update' : 'create' }}">
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 md:col-span-4">
            <x-input label="{{ __('fields.name') }}" wire:model="form.name" error-class="text-error" />
        </div>
    </div>

    <x-slot:actions>
        <x-button label="{{ __('interface.back_button') }}" link="{{ route('users.index') }}" />
        <x-button label="{{ $item ? __('interface.update_button') : __('interface.create_button') }}" class="btn-primary text-white" type="submit" spinner="{{ $item ? 'update' : 'create' }}" />
    </x-slot:actions>
</x-form>
```

## Tables
- Use `x-table` consistently.
- Keep column spacing with `class="[&_td]:py-3 [&_th]:py-3"`.
- Provide action buttons in the `actions` scope.

## Buttons
- Use `x-button` only.
- Icon actions use `btn-circle btn-sm` and `bg-*` classes.
- Include `tooltip` and `spinner` attributes.

## Modals
- Use `livewire:common.action.modal-component` for confirmation.
- Do not create custom modal flows unless required.

## Badges & Status
- Badges must have background and white text.
- Use `badge` + `badge-*` + `text-white`.

## CSS
- Add styles only in `resources/css/app.css`.
- Keep CSS minimal and scoped by class names.

## Accessibility
- Ensure buttons, inputs, and actions have labels or tooltips.
- Keep color contrast readable.

## Cards (Standard)
- Use `x-card` with `bg-base-300` and `shadow`.
- Prefer consistent padding and spacing.

Example:
```blade
<x-card class="shadow bg-base-300">
    <div class="p-4">...</div>
</x-card>
```

## Dashboard Pattern
- Use `resources/views/livewire/common/dashboard.blade.php` as the reference layout.
- Keep sections: header, stats cards, chart panel, summary panel.

## Filters UI Pattern
- Filters must live in the actions component or a dedicated filter row.
- Use Mary UI `x-input`, `x-select` with `wire:model`.
- Always reset pagination on filter changes.

Example:
```blade
<x-select wire:model="status" :options="$statusOptions" />
```

## Status Badges
- Always map status to a fixed class set.
- Example mapping:
- `open` -> `badge-success text-white`
- `pending` -> `badge-warning text-white`
- `canceled` -> `badge-error text-white`

## Filters UI (Multiple Filters)
Use a dedicated filter row with Mary UI components.

Example:
```blade
<div class="grid grid-cols-12 gap-4">
    <div class="col-span-12 md:col-span-4">
        <x-select wire:model="status" :options="$statusOptions" />
    </div>
    <div class="col-span-12 md:col-span-4">
        <x-select wire:model="agentId" :options="$agentOptions" />
    </div>
</div>
```

## Status Badges (Enum Mapping)
Ao adicionar um enum de status num módulo novo, mapeie cada caso para uma classe fixa de badge
(sempre com `text-white`). Exemplo ilustrativo (`StatusXpto` é um enum hipotético do seu módulo):

```php
$badgeMap = [
    StatusXpto::NEW->value       => 'badge-info text-white',
    StatusXpto::IN_PROGRESS->value => 'badge-primary text-white',
    StatusXpto::PENDING->value   => 'badge-warning text-white',
    StatusXpto::DONE->value      => 'badge-success text-white',
    StatusXpto::CANCELED->value  => 'badge-error text-white',
];
```
