<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\FileController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profile\TwoFactorSetup;
use App\Livewire\Auth\Profile\UpdateComponent as ProfileUpdateComponent;
use App\Livewire\Auth\TwoFactorChallenge;
use App\Livewire\Auth\User\FormComponent as UserFormComponent;
use App\Livewire\Auth\User\IndexComponent as UserIndexComponent;
use App\Livewire\Common\Client\FormComponent as ClientFormComponent;
use App\Livewire\Common\Client\IndexComponent as ClientIndexComponent;
use App\Livewire\Common\Dashboard;
use App\Livewire\Common\Settings\UpdateComponent as SettingsUpdateComponent;
use App\Livewire\Recruitment\Application\IndexComponent as ApplicationIndexComponent;
use App\Livewire\Recruitment\Application\ShowComponent as ApplicationShowComponent;
use App\Livewire\Site\ClientesComponent;
use App\Livewire\Site\ContatoComponent;
use App\Livewire\Site\EmpresaComponent;
use App\Livewire\Site\HistoricoComponent;
use App\Livewire\Site\HomeComponent;
use App\Livewire\Site\QualidadeComponent;
use App\Livewire\Site\ServicosComponent;
use App\Livewire\Site\TrabalheConoscoComponent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site institucional público (Cirna)
|--------------------------------------------------------------------------
*/
Route::livewire('/', HomeComponent::class)->name('site.home');
Route::livewire('/empresa', EmpresaComponent::class)->name('site.empresa');
Route::livewire('/historico', HistoricoComponent::class)->name('site.historico');
Route::livewire('/qualidade', QualidadeComponent::class)->name('site.qualidade');
Route::livewire('/servicos', ServicosComponent::class)->name('site.servicos');
Route::livewire('/clientes', ClientesComponent::class)->name('site.clientes');
Route::livewire('/contato', ContatoComponent::class)->name('site.contato');
Route::livewire('/trabalhe-conosco', TrabalheConoscoComponent::class)->name('site.trabalhe-conosco');
Route::get('/certificado-iso', [CertificateController::class, 'download'])->name('site.certificate');

/*
|--------------------------------------------------------------------------
| Painel administrativo (acesso restrito)
|--------------------------------------------------------------------------
*/
Route::livewire('/login', Login::class)->name('login');

Route::get('/files/{path}', [FileController::class, 'serve'])
    ->where('path', '.*')
    ->name('files.serve')
    ->middleware(['signed', 'auth']);

Route::middleware(['auth'])->group(function () {
    Route::livewire('/two-factor-challenge', TwoFactorChallenge::class)->name('two-factor.challenge');
});

Route::middleware(['auth', 'two-factor'])->group(function () {
    Route::livewire('/dashboard', Dashboard::class)->name('dashboard');
    Route::livewire('/profile', ProfileUpdateComponent::class)->name('profile');
    Route::livewire('/profile/two-factor', TwoFactorSetup::class)->name('profile.two-factor');
    Route::livewire('/settings', SettingsUpdateComponent::class)->name('settings');

    Route::prefix('users')->group(function () {
        Route::livewire('/', UserIndexComponent::class)->name('users.index');
        Route::livewire('/create', UserFormComponent::class)->name('users.create');
        Route::livewire('/update/{itemId}', UserFormComponent::class)->name('users.update');
    });

    Route::prefix('candidaturas')->group(function () {
        Route::livewire('/', ApplicationIndexComponent::class)->name('candidaturas.index');
        Route::livewire('/{itemId}', ApplicationShowComponent::class)->name('candidaturas.show');
    });

    Route::prefix('gestao/clientes')->group(function () {
        Route::livewire('/', ClientIndexComponent::class)->name('clientes.index');
        Route::livewire('/create', ClientFormComponent::class)->name('clientes.create');
        Route::livewire('/update/{itemId}', ClientFormComponent::class)->name('clientes.update');
    });
});
