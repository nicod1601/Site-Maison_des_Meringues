<?php

use App\Http\Controllers\GestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ImportController;

Route::get('/', [AccueilController::class, 'index'])->name('index');
Route::get('/gestion', [GestionController::class, 'index'])->name('gestion');
Route::get('/import/clear',     [ImportController::class,   'clear'])  ->name('import.clear');


Route::post('/import/excel',    [ImportController::class,   'import']) ->name('import.excel');


