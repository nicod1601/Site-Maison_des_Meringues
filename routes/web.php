<?php

use App\Http\Controllers\GestionController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PanierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CreationController;
use App\Http\Controllers\ShopController;

// ── Pages publiques ───────────────────────────────────────────────────────────
Route::get('/',     [AccueilController::class, 'index'])->name('index');
Route::get('/news', [NewsController::class,    'index'])->name('news');
Route::get('/shop/{id}', [ShopController::class, 'index'])->name('shop.index');

// ── Panier (accessible sans compte) ──────────────────────────────────────────
Route::get   ('/panier',            [PanierController::class, 'index'])    ->name('panier.index');
Route::post  ('/panier/ajouter',    [PanierController::class, 'ajouter'])  ->name('panier.ajouter');
Route::patch ('/panier/ligne/{id}', [PanierController::class, 'modifier']) ->name('panier.modifier');
Route::delete('/panier/ligne/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
Route::post  ('/panier/vider',      [PanierController::class, 'vider'])    ->name('panier.vider');

// ── Checkout (compte obligatoire) ─────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout.index');

    Route::get('/profile', [ProfileController::class, 'edit'])    ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Gestion (admin uniquement) ────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/gestion', [GestionController::class, 'index'])->name('gestion');

    Route::post('/import/excel', [ImportController::class, 'import'])->name('import.excel');
    Route::get ('/import/clear', [ImportController::class, 'clear']) ->name('import.clear');

    // Produits, Rayons, Thèmes, etc...
});

// Produits
Route::post  ('/gestion/produit',                 [CreationController::class, 'nvproduit'])       ->name('produit.store');
Route::delete('/gestion/produit/{id}',            [CreationController::class, 'destroy'])         ->name('produit.destroy');
Route::patch ('/gestion/produit/{id}/live',       [CreationController::class, 'toggleLive'])      ->name('produit.live');
Route::patch ('/gestion/produit/{id}/expedition', [CreationController::class, 'toggleExpedition'])->name('produit.expedition');
Route::patch ('/gestion/produit/{id}/emporter',   [CreationController::class, 'toggleEmporter'])  ->name('produit.emporter');
Route::patch ('/gestion/produit/{id}/nouveaute',  [CreationController::class, 'toggleNouveaute']) ->name('produit.nouveaute');

// Rayons
Route::post  ('/gestion/rayon',           [CreationController::class, 'nvrayon'])        ->name('nvrayon');
Route::delete('/gestion/rayon/{id}',      [CreationController::class, 'destroyRayon'])   ->name('rayon.destroy');
Route::put   ('/gestion/rayon/{id}',      [CreationController::class, 'updateRayon'])    ->name('rayon.update');
Route::patch ('/gestion/rayon/{id}/live', [CreationController::class, 'toggleLiveRayon'])->name('rayon.live');

// Thèmes
Route::post  ('/gestion/theme',      [CreationController::class, 'nvtheme'])      ->name('nvtheme');
Route::delete('/gestion/theme/{id}', [CreationController::class, 'destroyTheme']) ->name('theme.destroy');

// Formes
Route::post  ('/gestion/forme',      [CreationController::class, 'nvforme'])      ->name('nvforme');
Route::delete('/gestion/forme/{id}', [CreationController::class, 'destroyForme']) ->name('forme.destroy');

// Conditionnements
Route::post  ('/gestion/conditionnement',      [CreationController::class, 'nvconditionnement'])      ->name('nvconditionnement');
Route::delete('/gestion/conditionnement/{id}', [CreationController::class, 'destroyConditionnement']) ->name('conditionnement.destroy');

// Forme-Condi (Prix)
Route::post  ('/gestion/forme_condi',      [CreationController::class, 'nvformecondi'])      ->name('nvformecondi');
Route::delete('/gestion/forme_condi/{id}', [CreationController::class, 'destroyFormeCondi']) ->name('formecondi.destroy');

// Parfums
Route::post  ('/gestion/parfum',      [CreationController::class, 'nvparfum'])      ->name('nvparfum');
Route::delete('/gestion/parfum/{id}', [CreationController::class, 'destroyParfum']) ->name('parfum.destroy');

// Événements
Route::post  ('/gestion/event',      [CreationController::class, 'nvevent'])      ->name('nvevent');
Route::delete('/gestion/event/{id}', [CreationController::class, 'destroyEvent']) ->name('event.destroy');

require __DIR__.'/auth.php';
