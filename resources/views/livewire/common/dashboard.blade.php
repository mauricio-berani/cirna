<div>
    <div class="mb-8 flex flex-col gap-1">
        <span class="text-xs uppercase tracking-[0.3em] text-base-content/60 font-bold">
            Bem-vindo
        </span>
        <h2 class="text-3xl font-bold text-base-content tracking-tight">
            {{ $user?->name ? "Olá, {$user->name}" : 'Dashboard' }}
        </h2>
        <p class="text-base text-base-content/70">
            Este ambiente está configurado com os módulos administrativos ativos no momento.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card class="bg-base-100 shadow border border-base-200/60">
            <div class="space-y-4">
                <div>
                    <h3 class="text-base font-semibold text-base-content">Resumo da conta</h3>
                    <p class="text-sm text-base-content/70">Informações básicas do usuário autenticado.</p>
                </div>

                <div class="grid gap-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-base-content/70">Nome</span>
                        <span class="font-medium text-base-content">{{ $user->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-base-content/70">E-mail</span>
                        <span class="font-medium text-base-content">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-base-content/70">Hoje</span>
                        <span class="font-medium text-base-content">{{ $today }}</span>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card class="bg-base-100 shadow border border-base-200/60">
            <div class="space-y-4">
                <div>
                    <h3 class="text-base font-semibold text-base-content">Próximos passos</h3>
                    <p class="text-sm text-base-content/70">Use o menu lateral para administrar os usuários do sistema.</p>
                </div>

                <div class="flex flex-col gap-3">
                    <x-button label="Gerenciar usuários" link="{{ route('users.index') }}" wire:navigate class="btn-primary text-white" />
                </div>
            </div>
        </x-card>
    </div>
</div>
