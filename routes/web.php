<?php

use App\Http\Controllers\GestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;

Route::get('/', [AccueilController::class, 'index'])->name('index');
Route::get('/gestion', [GestionController::class, 'index'])->name('gestion');

