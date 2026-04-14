<?php

use App\Http\Controllers\GestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ImportController;

Route::get('/', [AccueilController::class, 'index'])->name('index');
Route::get('/gestion', [GestionController::class, 'index'])->name('gestion');


Route::post('/import-excel', [ImportController::class, 'import'])
    ->name('import.excel');

