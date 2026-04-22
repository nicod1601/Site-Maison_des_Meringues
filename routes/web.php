<?php

use App\Http\Controllers\GestionController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CreationController;

Route::get('/',       [AccueilController::class, 'index'])->name('index');
Route::get('/news',   [NewsController::class,    'index'])->name('news');
Route::get('/gestion',[GestionController::class, 'index'])->name('gestion');

// Import
Route::post('/import/excel', [ImportController::class, 'import'])->name('import.excel');
Route::get('/import/clear',  [ImportController::class, 'clear']) ->name('import.clear');

// Produits
Route::post  ('/gestion/produit/{idRayon}',  [CreationController::class, 'nvproduit'])    ->name('nvproduit');
Route::delete('/gestion/produit/{id}',       [CreationController::class, 'destroy']);

// Rayons
Route::post  ('/gestion/rayon',              [CreationController::class, 'nvrayon'])      ->name('nvrayon');
Route::delete('/gestion/rayon/{id}',         [CreationController::class, 'destroyRayon'])->name('destroyRayon');

// Thèmes
Route::post  ('/gestion/theme',              [CreationController::class, 'nvtheme'])      ->name('nvtheme');
Route::delete('/gestion/theme/{id}',         [CreationController::class, 'destroyTheme'])->name('destroyTheme');
