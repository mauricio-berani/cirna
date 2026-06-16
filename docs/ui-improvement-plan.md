# Plano de Melhoria de UI/UX — Mobile-First & Design Moderno

## Status das Correções Imediatas (já aplicadas)

| Arquivo | Problema | Correção |
|---|---|---|
| `login.blade.php` | `w-80` fixo quebrava em mobile < 320px; sem max-width | `w-full` + `max-w-md` + padding responsivo |
| `two-factor-challenge.blade.php` | `text-gray-600` hardcoded quebrava dark theme | `text-base-content/60` |
| `two-factor-challenge.blade.php` | Sem max-width no container; padding fixo | `max-w-md` + padding responsivo |
| `modal-component.blade.php` | `class="row text-right"` — Bootstrap inválido no Tailwind | `flex justify-end gap-3 mt-6` |
| `update-component.blade.php` | `p-12` — inutilizável em mobile | `p-4 sm:p-8 lg:p-12` |
| `update-component.blade.php` | Classes repetidas desnecessariamente (md/lg/xl/2xl) | `w-full sm:w-auto` |
| `actions-component.blade.php` | Idem acima em 3 botões | `w-full sm:w-auto` |
| `navbar-component.blade.php` | Logo `hidden md:block` — sem marca no mobile | Visível em todos os tamanhos (`w-10 md:w-20`) |
| `user/form.blade.php` | Select de papel `hidden md:block` — inacessível no mobile | Removida a restrição |

---

## Backlog de Melhorias (Priorizado)

### ALTA — Experiência Core

#### U1 — Skeleton loaders nas tabelas
**Problema:** tabelas ficam em branco durante o carregamento Livewire, causando CLS (layout shift).

**Implementação:**
```blade
{{-- No x-table, adicionar slot de loading --}}
<div wire:loading.delay class="animate-pulse space-y-3 p-4">
    @foreach(range(1,5) as $i)
        <div class="h-10 bg-base-200 rounded-lg"></div>
    @endforeach
</div>
```

Adicionar em `IndexBaseComponent` como padrão reutilizável.

---

#### U2 — View Transitions API para navegação Livewire
**Problema:** navegação entre páginas é abrupta (sem transição).

**Implementação em `resources/css/app.css`:**
```css
@view-transition {
    navigation: auto;
}

::view-transition-old(root) {
    animation: 200ms ease-in fade-out;
}

::view-transition-new(root) {
    animation: 200ms ease-out fade-in;
}

@keyframes fade-out {
    from { opacity: 1; }
    to { opacity: 0; }
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@media (prefers-reduced-motion: reduce) {
    ::view-transition-old(root),
    ::view-transition-new(root) {
        animation: none;
    }
}
```

---

#### U3 — Dashboard enriquecido
**Problema:** dashboard atual mostra apenas 2 cards básicos — não orienta o usuário.

**Implementação mínima:**
- Card de boas-vindas com nome e avatar do usuário
- Stats cards: total de usuários, usuários ativos, atividade recente
- Lista das últimas atividades do log (últimos 5 registros)
- Ação rápida de acesso ao perfil

---

### MÉDIA — Polimento e Acessibilidade

#### U4 — `prefers-reduced-motion` global
**Problema:** animações não respeitam a preferência do sistema operacional.

**Implementação em `resources/css/app.css`:**
```css
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
```

---

#### U5 — `aria-label` no botão hamburguer
**Problema:** botão de menu mobile sem label acessível.

**Implementação em `navbar-component.blade.php`:**
```blade
<label for="main-drawer" aria-label="{{ __('interface.open_menu') }}" class="lg:hidden mr-3 flex items-center">
```

---

#### U6 — Feedback visual de upload de avatar
**Problema:** upload de imagem não dá feedback de progresso.

**Implementação:** adicionar `wire:loading` + spinner no componente de avatar:
```blade
<x-file wire:model="form.avatar" ...>
    <div class="relative">
        <img src="..." class="rounded-full h-60 w-60 shadow" />
        <div wire:loading wire:target="form.avatar"
             class="absolute inset-0 rounded-full bg-base-300/70 flex items-center justify-center">
            <x-loading class="loading-spinner loading-lg text-primary" />
        </div>
    </div>
</x-file>
```

---

#### U7 — Toast de feedback de ações
**Problema:** após salvar/deletar, não há confirmação visual (toast/alert).

**Implementação:** usar `$this->dispatch('mary.toast', ...)` ou `x-toast` do MaryUI após ações de escrita.

---

### BAIXA — Refinamentos

#### U8 — Favicon e meta tags mobile
Verificar e garantir:
- `<meta name="viewport" content="width=device-width, initial-scale=1">` no layout
- `apple-touch-icon` e `manifest.json` para PWA básico
- `theme-color` meta tag alinhado com `--color-primary`

---

## Ordem de Execução Sugerida

| Sprint | Items |
|---|---|
| 1 (já feito) | Todos os bugs de layout imediatos (tabela acima) |
| 2 | U4 (reduced-motion) + U5 (aria-label) + U2 (view transitions) |
| 3 | U1 (skeleton loaders) + U6 (avatar feedback) + U7 (toasts) |
| 4 | U3 (dashboard) + U8 (meta tags) |
