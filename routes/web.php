<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\Admin\HomeController as AdminHomeController;

use App\Http\Controllers\AuthAdmin\LoginController as AdminLoginController;

use App\Http\Controllers\AuthAdmin\ForgotPasswordController as AdminForgotPasswordController;
use Inertia\Inertia;
use App\Http\Controllers\AuthAdmin\ResetPasswordController as AdminResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
Auth::routes(['verify' => true]);
Route::middleware('verified')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::middleware('auth:web')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});*/

// VERSÃO 1 - SEM USUÁRIO COMUM, SÓ ADMIN E COLABORADOR.

Route::get('/', function () {
    return redirect()->route('admin.home');
});

Route::get('/home', function () {
    return redirect()->route('admin.home');
});
Route::get('/login', function () {
    return redirect()->route('admin.login.index');
})->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login.index');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::get('password/reset', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [AdminResetPasswordController::class, 'reset'])->name('password.update');
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('home', [AdminHomeController::class, 'index'])->name('home');
        Route::get('/', function () {
            return redirect()->route('admin.home');
        });
    });
});

Route::middleware(['auth:admin'])->group(function () {
    Route::resource('admin', AdminController::class)->except(['index']);

    Route::post('fornecedor/detach', [App\Http\Controllers\FornecedorCidadeController::class, 'detach_cidade'])->name('fornecedor.detach.cidade');
    Route::post('fornecedor/attach/cidade/{fornecedor}', [App\Http\Controllers\FornecedorCidadeController::class, 'attach_cidade'])->name('fornecedor.attach.cidade');
    Route::post('fornecedor/adicionar/cidades/{fornecedor}', [App\Http\Controllers\FornecedorCidadeController::class, 'update_cidades'])->name('fornecedor.update.cidades');
    Route::post('fornecedor/{site?}', [App\Http\Controllers\FornecedorController::class, 'store'])->name('fornecedor.store');
    Route::get('fornecedor/create/{site?}', 'App\Http\Controllers\FornecedorController@create')->name('fornecedor.create');
    Route::resource('fornecedor', App\Http\Controllers\FornecedorController::class)->except(['store', 'create']);

    Route::post('orcamento/status/{orcamento}', [App\Http\Controllers\OrcamentoController::class, 'altera_status'])->name('orcamento.altera.status');
    Route::resource('orcamento', App\Http\Controllers\OrcamentoController::class);

    Route::get('/cidade', [App\Http\Controllers\CidadeController::class, 'index'])->name('cidade.index');

    Route::post('site/download/modelo/planilha', 'App\Http\Controllers\SiteController@downloadModeloPlanilha')->name('site.download.modelo.planilha');
    Route::get('site/create/+1Site/{orcamento}/{botao?}', 'App\Http\Controllers\SiteController@create')->name('site.create.+1');
    Route::get('site/create/{orcamento}/', 'App\Http\Controllers\SiteController@create')->name('site.create');
    Route::get('site/create/L2L/{orcamento}/{botao?}/{isPontaA?}', 'App\Http\Controllers\SiteController@createl2l')->name('site.create.l2l');
    Route::get('site/create/+1PontaA/{orcamento}/{isPontaA}', 'App\Http\Controllers\SiteController@createMais1PontaA')->name('site.create.+1.PontaA');
    Route::post('site/create/sheet/{orcamento}', 'App\Http\Controllers\SiteController@store_sheet')->name('site.store.sheet');
    Route::post('site/create/L2L', 'App\Http\Controllers\SiteController@storel2l')->name('site.store.l2l');
    Route::resource('site', App\Http\Controllers\SiteController::class)->except(['create', 'show', 'index']);

    Route::post('cotacao/status/{cotacao}', [App\Http\Controllers\CotacaoController::class, 'altera_status'])->name('cotacao.altera.status');
    Route::get('cotacao/create/{site}', 'App\Http\Controllers\CotacaoController@create')->name('cotacao.create');
    Route::resource('cotacao', App\Http\Controllers\CotacaoController::class)->except(['create', 'show']);

    Route::resource('cliente', App\Http\Controllers\ClienteController::class);

    Route::post('altera-status-home', [App\Http\Controllers\OrcamentoController::class, 'altera_status_home'])->name('orcamento.altera.status.home');

    Route::resource('servico', App\Http\Controllers\ServicoController::class)->except(['show']);
    Route::post('servico/attach-sites/{orcamento}', [App\Http\Controllers\ServicoController::class, 'attach_sites'])->name('servico.attach.sites');
    Route::post('servico/detach-site', [App\Http\Controllers\ServicoController::class, 'detach_site'])->name('servico.detach.site');

    Route::resource('empresa', App\Http\Controllers\EmpresaController::class)->except(['destroy']);
    Route::post('empresa/updateParams/{empresa}', [App\Http\Controllers\EmpresaController::class, 'updateParams'])->name('empresa.updateParams');
    Route::post('orcamento/recalculate/{orcamento}', [App\Http\Controllers\OrcamentoController::class, 'recalculate'])->name('orcamento.recalculate');
    
    Route::get('export/orcamento/{orcamento}', [App\Http\Controllers\OrcamentoController::class, 'export_orcamento'])->name('export.orcamento');

    Route::get('/sites/getSites', [App\Http\Controllers\SiteController::class, 'getSites']);
});

// Route::resource('empresa', App\Http\Controllers\EmpresaController::class); // por enquanto não vou implementar, somente quando for prestar serviço para outro cliente.

