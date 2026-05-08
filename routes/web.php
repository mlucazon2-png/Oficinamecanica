<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\PecaController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\ItemOsController;
use App\Http\Controllers\GarantiaController;
use App\Http\Controllers\FipeController;
use App\Http\Controllers\RelatorioController;

// ── Rotas públicas ───────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

Route::get ('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
Route::get ('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Autorização de orçamento pelo cliente via link (sem login)
Route::get ('/orcamento/{token}',  [OrdemServicoController::class, 'showAutorizacao'])->name('os.autorizar.show');
Route::post('/orcamento/{token}',  [OrdemServicoController::class, 'autorizar'])->name('os.autorizar');

// ── Rotas autenticadas ───────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes/{cliente}/veiculos', [VeiculoController::class, 'porCliente'])
         ->name('clientes.veiculos');

    // Veículos - rotas com {id} em vez de implicit model binding
    Route::get('/veiculos', [VeiculoController::class, 'index'])->name('veiculos.index');
    Route::get('/veiculos/create', [VeiculoController::class, 'create'])->name('veiculos.create');
    Route::post('/veiculos', [VeiculoController::class, 'store'])->name('veiculos.store');
    Route::get('/veiculos/{id}', [VeiculoController::class, 'show'])->name('veiculos.show');
    Route::get('/veiculos/{id}/edit', [VeiculoController::class, 'edit'])->name('veiculos.edit');
    Route::put('/veiculos/{id}', [VeiculoController::class, 'update'])->name('veiculos.update');
    Route::delete('/veiculos/{id}', [VeiculoController::class, 'destroy'])->name('veiculos.destroy');

    // Mecânicos
    Route::resource('mecanicos', MecanicoController::class);
    Route::patch('/mecanicos/{mecanico}/toggle', [MecanicoController::class, 'toggle'])
         ->name('mecanicos.toggle');

    // Catálogo de serviços
    Route::resource('servicos', ServicoController::class);

    // Peças / Estoque
    Route::resource('pecas', PecaController::class);
    Route::patch('/pecas/{peca}/estoque', [PecaController::class, 'ajustarEstoque'])
         ->name('pecas.estoque');

    // Ordens de Serviço
    // Ordens de Serviço - rotas com {id} em vez de implicit model binding
    Route::get('/ordens-servico', [OrdemServicoController::class, 'index'])->name('os.index');
    Route::get('/ordens-servico/create', [OrdemServicoController::class, 'create'])->name('os.create');
    Route::post('/ordens-servico', [OrdemServicoController::class, 'store'])->name('os.store');
    Route::get('/ordens-servico/{id}', [OrdemServicoController::class, 'show'])->name('os.show');
    Route::get('/ordens-servico/{id}/edit', [OrdemServicoController::class, 'edit'])->name('os.edit');
    Route::put('/ordens-servico/{id}', [OrdemServicoController::class, 'update'])->name('os.update');
    Route::delete('/ordens-servico/{id}', [OrdemServicoController::class, 'destroy'])->name('os.destroy');
    
    Route::patch('/ordens-servico/{id}/status',  [OrdemServicoController::class, 'atualizarStatus'])->name('os.status');
    Route::patch('/ordens-servico/{id}/aprovar', [OrdemServicoController::class, 'aprovar'])->name('os.aprovar');
    Route::patch('/ordens-servico/{id}/fechar',  [OrdemServicoController::class, 'fechar'])->name('os.fechar');
    Route::get  ('/ordens-servico/{id}/print',   [OrdemServicoController::class, 'imprimir'])->name('os.print');
    Route::post ('/ordens-servico/{id}/fotos',   [OrdemServicoController::class, 'uploadFotos'])->name('os.fotos.store');
    Route::delete('/ordens-servico/{id}/fotos/{foto}', [OrdemServicoController::class, 'deletarFoto'])->name('os.fotos.destroy');

    // Itens da OS (serviços e peças)
    Route::post  ('/ordens-servico/{id}/itens',        [ItemOsController::class, 'store'])->name('os.itens.store');
    Route::put   ('/ordens-servico/{id}/itens/{item}', [ItemOsController::class, 'update'])->name('os.itens.update');
    Route::delete('/ordens-servico/{id}/itens/{item}', [ItemOsController::class, 'destroy'])->name('os.itens.destroy');

    // Garantias
    Route::resource('garantias', GarantiaController::class)->only(['index','show','edit','update']);
    Route::patch('/garantias/{garantia}/acionar', [GarantiaController::class, 'acionar'])->name('garantias.acionar');

    // FIPE (UC011)
    Route::prefix('fipe')->name('fipe.')->group(function () {
        Route::get('/marcas',                    [FipeController::class, 'marcas'])->name('marcas');
        Route::get('/modelos/{marca}',           [FipeController::class, 'modelos'])->name('modelos');
        Route::get('/anos/{marca}/{modelo}',     [FipeController::class, 'anos'])->name('anos');
        Route::get('/valor',                     [FipeController::class, 'valor'])->name('valor');
    });

    // Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/',                  [RelatorioController::class, 'index'])->name('index');
        Route::get('/os-status',         [RelatorioController::class, 'osPorStatus'])->name('os-status');
        Route::get('/faturamento',       [RelatorioController::class, 'faturamento'])->name('faturamento');
        Route::get('/produtividade',     [RelatorioController::class, 'produtividade'])->name('produtividade');
        Route::get('/pecas-usadas',      [RelatorioController::class, 'pecasMaisUsadas'])->name('pecas');
        Route::get('/garantias',         [RelatorioController::class, 'garantias'])->name('garantias');
        Route::get('/tempo-reparo',      [RelatorioController::class, 'tempoReparo'])->name('tempo-reparo');
        Route::get('/clientes',          [RelatorioController::class, 'clientes'])->name('clientes');
        Route::get('/orcamentos',        [RelatorioController::class, 'orcamentos'])->name('orcamentos');
    });

    // Área do cliente (RF004)
    Route::middleware('role:cliente')->prefix('minha-conta')->name('conta.')->group(function () {
        Route::get('/os',       [ClienteController::class, 'minhasOs'])->name('os');
        Route::get('/veiculos', [ClienteController::class, 'meusVeiculos'])->name('veiculos');
    });
});
