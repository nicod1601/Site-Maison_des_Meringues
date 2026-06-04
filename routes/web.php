<?php

use App\Http\Controllers\CommandeController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PanierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\CreationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProController;
use App\Http\Controllers\TicketCommandeController;
use App\Http\Controllers\TestController;

// ── Pages publiques ───────────────────────────────────────────
Route::get('/',      [AccueilController::class, 'index'])->name('index');
Route::get('/blog',  [BlogController::class,    'index'])->name('blog');
Route::get('/shop/{id}', [ShopController::class, 'index'])->name('shop.index');
Route::get('/pro',   [ProController::class,     'index'])->name('pro');

// ── Panier (accessible sans compte) ──────────────────────────
Route::get   ('/panier',            [PanierController::class, 'index'])    ->name('panier.index');
Route::post  ('/panier/ajouter',    [PanierController::class, 'ajouter'])  ->name('panier.ajouter');
Route::patch ('/panier/ligne/{id}', [PanierController::class, 'modifier']) ->name('panier.modifier');
Route::delete('/panier/ligne/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
Route::post  ('/panier/vider',      [PanierController::class, 'vider'])    ->name('panier.vider');

// ── Hors auth — callback Monetico (doit rester en dehors) ─────
Route::post('/checkout/retour', [CommandeController::class, 'retour'])->name('checkout.retour');

// ── Compte obligatoire ────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

	// Profil
	Route::get   ('/profile', [ProfileController::class, 'edit'])   ->name('profile.edit');
	Route::patch ('/profile', [ProfileController::class, 'update']) ->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

	// Paramètres
	Route::prefix('settings')->group(function () {
		Route::get ('/',          [SettingsController::class, 'index'])         ->name('settings');
		Route::post('/profile',    [SettingsController::class, 'updateProfile']) ->name('settings.profile');
		Route::post('/password',   [SettingsController::class, 'updatePassword'])->name('settings.password');
		Route::post('/deactivate', [SettingsController::class, 'deactivate'])    ->name('settings.deactivate');
	});

	// Checkout ← CORRECTION : une seule route, avec le bon controller
	Route::get ('/checkout',         [CommandeController::class, 'index'])  ->name('checkout.index');
	Route::post('/checkout/payer',   [CommandeController::class, 'payer'])  ->name('checkout.payer');
	Route::get ('/checkout/success', [CommandeController::class, 'success'])->name('checkout.success');
	Route::get ('/checkout/error',   [CommandeController::class, 'error'])  ->name('checkout.error');

	// Ticket commande
	Route::get('/ticketCommande', [TicketCommandeController::class, 'index'])->name('ticketCommande');

	Route::delete('/ticket-commande/{id}', [TicketCommandeController::class, 'destroy'])
	->name('ticket.destroy')
	->middleware('auth');

    Route::patch('/ticketCommande/{id}/terminer', [TicketCommandeController::class, 'terminer'])
    ->name('ticket.terminer')
    ->middleware(['auth', 'admin']);

	// Blog — réactions et CRUD admin
	Route::post  ('/blog/{blogPost}/react', [BlogController::class, 'react'])  ->name('blog.react');
	Route::post  ('/blog',                  [BlogController::class, 'store'])  ->name('blog.store');
	Route::put   ('/blog/{blogPost}',       [BlogController::class, 'update']) ->name('blog.update');
	Route::delete('/blog/{blogPost}',       [BlogController::class, 'destroy'])->name('blog.destroy');
});

// ── Gestion (admin uniquement) ────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {
	Route::get('/gestion', [GestionController::class, 'index'])->name('gestion.index');

	Route::post('/import/excel', [ImportController::class, 'import'])->name('import.excel');
	Route::get ('/import/clear', [ImportController::class, 'clear']) ->name('import.clear');

	Route::get('/gestion/produits/export', [GestionController::class, 'exportProduits'])->name('produits.export');

	// Produits
	Route::post  ('/gestion/produit',                 [CreationController::class, 'nvproduit'])       ->name('produit.store');
	Route::delete('/gestion/produit/{id}',            [CreationController::class, 'destroy'])         ->name('produit.destroy');
	Route::put   ('/gestion/produit/{id}',            [CreationController::class, 'updateProduit'])   ->name('produit.updateProduit');
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

	// Images
	Route::post  ('/gestion/image',                  [ImageController::class, 'store'])    ->name('image.store');
	Route::post  ('/gestion/image/{image}/remplacer', [ImageController::class, 'remplacer'])->name('image.remplacer');
	Route::delete('/gestion/image/{image}',           [ImageController::class, 'destroy']) ->name('image.destroy');
});

// ── Tests ─────────────────────────────────────────────────────
Route::get('/test/foo', [TestController::class, 'foo'])->middleware('auth')->name('test.foo');
Route::get('/test/bar', [TestController::class, 'bar'])->name('test.bar');

require __DIR__.'/auth.php';
