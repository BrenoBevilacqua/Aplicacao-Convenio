<?php

use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Redireciona '/' para a tela de login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('convenio.authenticate');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');


// Rotas protegidas por autenticação
Route::middleware('auth', 'logged_out')->group (function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/index', [ConvenioController::class, 'index'])->name('convenio.index');
    Route::get('/convenios/info', [ConvenioController::class, 'historico']) ->name('convenios.info');
    Route::get('/convenios/{id}/exportar-pdf', [ConvenioController::class, 'exportarPdf'])->name('convenios.exportar.pdf');

    // Acesso somente para admin e admin master
    Route::middleware('admin')->group(function () {
        // rotas de convênios (acessar, criar, editar, excluir)
        Route::get('/create', [ConvenioController::class, 'create'])->name('convenio.create');
        Route::post('/', [ConvenioController::class, 'store'])->name('convenio.store');
        Route::get('/convenios/{id}/edit', [ConvenioController::class, 'edit'])->name('convenio.edit');
        Route::put('/convenios/{id}', [ConvenioController::class, 'update'])->name('convenio.update');
        Route::delete('/convenios/{id}', [ConvenioController::class, 'destroy'])->name('convenio.destroy');

        // rotas de ações
        Route::post('/convenios/{convenio}/acoes', [ConvenioController::class, 'storeAcao'])->name('convenios.acoes.store');
        Route::delete('/convenios/{convenio}/acoes/{acao?}', [ConvenioController::class, 'destroy'])->name('convenios.acoes.destroy');

        // rota de acompanhamentos
        Route::post('/convenios/{convenio}/acompanhamentos', [ConvenioController::class, 'storeAcompanhamento'])->name('convenios.acompanhamentos.store');

        // rotas contratos
        Route::post('/convenios/{convenio}/contratos', [ConvenioController::class, 'storeContrato'])->name('convenios.contratos.store');
        Route::delete('/convenios/{convenio}/contratos/{contrato}', [ConvenioController::class, 'destroyContrato'])->name('convenios.contratos.destroy');
    });

    // Acesso exclusivo para admin master
    Route::middleware('admin_master')->group(function () {
        Route::get('/admin/requisicoes', [AuthController::class, 'requisicoesPendentes'])->name('admin.requisicoes');
        Route::post('/admin/aprovar/{id}', [AuthController::class, 'approveAdmin'])->name('admin.aprovar');
        
    });
});