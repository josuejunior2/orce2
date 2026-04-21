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
    Route::post('fornecedor/{siteOrcamento?}', [App\Http\Controllers\FornecedorController::class, 'store'])->name('fornecedor.store');
    Route::get('fornecedor/create/{siteOrcamento?}', 'App\Http\Controllers\FornecedorController@create')->name('fornecedor.create');
    Route::get('fornecedor/getFornecedores', 'App\Http\Controllers\FornecedorController@getFornecedores')->name('fornecedor.getFornecedores');
    Route::get('fornecedor/getFornecedoresDisponiveis', [App\Http\Controllers\FornecedorController::class, 'getFornecedoresDisponiveisParaCidade'])->name('fornecedor.getFornecedoresDisponiveis');
    Route::resource('fornecedor', App\Http\Controllers\FornecedorController::class)->except(['store', 'create']);

    Route::get('orcamento/getOrcamentos', [App\Http\Controllers\OrcamentoController::class, 'getOrcamentos'])->name('orcamento.getOrcamentos');
    Route::post('orcamento/status/{orcamento}', [App\Http\Controllers\OrcamentoController::class, 'altera_status'])->name('orcamento.altera.status');
    Route::get('orcamento/siteImport/{orcamento}', [App\Http\Controllers\OrcamentoController::class, 'siteImport'])->name('orcamento.site.import');
    Route::resource('orcamento', App\Http\Controllers\OrcamentoController::class);

    // NOVO ORCAMENTO SHOW VUE
    Route::post('siteOrcamento/storeFromTable', 'App\Http\Controllers\SiteOrcamentoController@storeFromTable')->name('siteOrcamento.table.store');
    


    Route::get('/cidade', [App\Http\Controllers\CidadeController::class, 'index'])->name('cidade.index');

    Route::post('siteOrcamento/download/modelo/planilha', 'App\Http\Controllers\SiteOrcamentoController@downloadModeloPlanilha')->name('siteOrcamento.download.modelo.planilha');
    Route::get('siteOrcamento/create/{orcamento}/', 'App\Http\Controllers\SiteOrcamentoController@create')->name('siteOrcamento.create');
    Route::post('siteOrcamento/create/sheet/{orcamento}', 'App\Http\Controllers\SiteOrcamentoController@store_sheet')->name('siteOrcamento.store.sheet');
    Route::post('siteOrcamento', 'App\Http\Controllers\SiteOrcamentoController@store')->name('siteOrcamento.store');
    Route::get('siteOrcamento/edit/{siteOrcamento}/', 'App\Http\Controllers\SiteOrcamentoController@edit')->name('siteOrcamento.edit');
    Route::post('siteOrcamento/update/{siteOrcamento}', 'App\Http\Controllers\SiteOrcamentoController@update')->name('siteOrcamento.update');
    Route::delete('siteOrcamento/destroy/{siteOrcamento}', 'App\Http\Controllers\SiteOrcamentoController@destroy')->name('siteOrcamento.destroy');

    Route::get('site', 'App\Http\Controllers\SiteController@index')->name('site.index');
    Route::get('site/getTableSites', 'App\Http\Controllers\SiteController@table')->name('site.table');
    Route::post('site/storeFromTable', 'App\Http\Controllers\SiteController@storeFromTable')->name('site.table.store');
    Route::put('site/updateFromTable/{site}', 'App\Http\Controllers\SiteController@updateFromTable')->name('site.table.update');
    Route::delete('site/destroyFromTable/{site}', 'App\Http\Controllers\SiteController@destroyFromTable')->name('site.table.destroy');

    Route::get('site/import', 'App\Http\Controllers\SiteController@indexImport')->name('site.import.index');
    Route::post('site/storeImport', 'App\Http\Controllers\SiteController@storeImport')->name('site.import.store');

    Route::post('cotacao/status/{cotacao}', [App\Http\Controllers\CotacaoController::class, 'altera_status'])->name('cotacao.altera.status');
    Route::get('cotacao/create/{siteOrcamento}', 'App\Http\Controllers\CotacaoController@create')->name('cotacao.create');
    Route::resource('cotacao', App\Http\Controllers\CotacaoController::class)->except(['create', 'show']);

    Route::get('cliente/getClientes', [App\Http\Controllers\ClienteController::class, 'getClientes'])->name('cliente.getClientes');
    Route::resource('cliente', App\Http\Controllers\ClienteController::class);

    Route::post('altera-status-home', [App\Http\Controllers\OrcamentoController::class, 'altera_status_home'])->name('orcamento.altera.status.home');

    Route::resource('servico', App\Http\Controllers\ServicoController::class)->except(['show']);
    Route::post('servico/attach-sites/{orcamento}', [App\Http\Controllers\ServicoController::class, 'attach_sites'])->name('servico.attach.sites');
    Route::post('servico/detach-site', [App\Http\Controllers\ServicoController::class, 'detach_site'])->name('servico.detach.site');

    Route::resource('empresa', App\Http\Controllers\EmpresaController::class)->except(['destroy']);
    Route::post('empresa/updateParams/{empresa}', [App\Http\Controllers\EmpresaController::class, 'updateParams'])->name('empresa.updateParams');
    Route::post('orcamento/recalculate/{orcamento}', [App\Http\Controllers\OrcamentoController::class, 'recalculate'])->name('orcamento.recalculate');
    
    Route::get('export/orcamento/{orcamento}', [App\Http\Controllers\OrcamentoController::class, 'export_orcamento'])->name('export.orcamento');

});

// Route::resource('empresa', App\Http\Controllers\EmpresaController::class); // por enquanto não vou implementar, somente quando for prestar serviço para outro cliente.

