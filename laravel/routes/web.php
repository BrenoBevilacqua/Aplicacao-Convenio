<?php

use App\Models\Convenio;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConvenioController;


Route::get('/', [ConvenioController::class, 'login'])->name('convenio.login');
Route::get('/login', [ConvenioController::class, 'login'])->name('login'); 
Route::post('/login', [ConvenioController::class, 'authenticate'])->name('convenio.authenticate');
Route::middleware('auth')->group(function () {
    Route::get('/index', [ConvenioController::class, 'index'])->name('convenio.index');
    Route::get('/create', [ConvenioController::class, 'create'])->name('convenio.create');
    Route::post('/', [ConvenioController::class, 'store'])->name('convenio.store');
    Route::get('/convenios/{id}/edit', [ConvenioController::class, 'edit'])->name('convenio.edit');
    Route::put('/convenios/{id}', [ConvenioController::class, 'update'])->name('convenio.update');
    Route::delete('/convenios/{id}', [ConvenioController::class, 'destroy'])->name('convenio.destroy');
});


