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

// Formes
Route::post('/gestion/forme', [CreationController::class, 'nvforme'])->name('nvforme');
Route::delete('/gestion/forme/{id}', [CreationController::class, 'destroyForme'])->name('destroyForme');

// Conditionnements
Route::post('/gestion/conditionnement', [CreationController::class, 'nvconditionnement'])->name('nvconditionnement');
Route::delete('/gestion/conditionnement/{id}', [CreationController::class, 'destroyConditionnement'])->name('destroyConditionnement');

// Forme-Condi (Prix)
Route::post('/gestion/forme_condi', [CreationController::class, 'nvformecondi'])->name('nvformecondi');
Route::delete('/gestion/forme_condi/{id}', [CreationController::class, 'destroyFormeCondi'])->name('destroyFormeCondi');

// Parfums (bonus)
Route::post('/gestion/parfum', [CreationController::class, 'nvparfum'])->name('nvparfum');
Route::delete('/gestion/parfum/{id}', [CreationController::class, 'destroyParfum'])->name('destroyParfum');
