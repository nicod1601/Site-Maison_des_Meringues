<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Rayon;
use App\Models\Theme;
use App\Models\Event;
use App\Models\Boutique;
use App\Models\Forme;
use App\Models\Conditionnement;
use App\Models\Forme_Condi;
use App\Models\Parfum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CreationController extends Controller
{
	// ── PRODUITS ────────────────────────────────────────────────────────

	public function nvproduit(Request $request): RedirectResponse
	{
		$request->validate([
			'nom_produit'    => 'required|string|max:255',
			'id_parfum'      => 'required|exists:parfum,id_parfum',
			'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
			'id_rayon'       => 'required|exists:rayon,id_rayon',
			'id_theme'       => 'nullable|exists:theme,id_theme',
			'quantite'       => 'required|integer|min:0',
			'description'    => 'nullable|string',
		]);

		$produit = Produit::create([
			'nom_produit'      => $request->nom_produit,
			'id_parfum'        => $request->id_parfum,
			'id_forme_condi'   => $request->id_forme_condi,
			'id_theme'         => $request->id_theme,
			'description'      => $request->description ?? 'Aucune description',
			'quantite'         => $request->quantite,
			'nouveaute'        => false,
			'live'             => false,
			'dispo_emporter'   => false,
			'dispo_expedition' => false,
		]);

		$produit->rayons()->attach($request->id_rayon);

		$rayon = Rayon::find($request->id_rayon);
		if ($rayon) {
			$rayon->recalculerStock();
			$this->recalculerStockBoutique();
		}

		return redirect()->back()->with('success', 'Produit créé avec succès !');
	}

	public function destroy(int $id): RedirectResponse
	{
		$produit = Produit::findOrFail($id);

		$rayonIds = $produit->rayons()->pluck('rayon.id_rayon');

		$produit->delete(); // cascade supprime produit_rayon et produit_event

		foreach ($rayonIds as $rayonId) {
			$rayon = Rayon::find($rayonId);
			if ($rayon) $rayon->recalculerStock();
		}

		$this->recalculerStockBoutique();

		return redirect()->back()->with('success', 'Produit supprimé.');
	}

	public function toggleLive(Request $request, int $id): RedirectResponse
	{
		$produit = Produit::findOrFail($id);
		$produit->live = $request->boolean('live');
		$produit->save();
		return redirect()->back();
	}

	// ── RAYONS ───────────────────────────────────────────────────────────

	public function nvrayon(Request $request): RedirectResponse
	{
		$request->validate([
			'nom_rayon'   => 'required|string|max:255',
			'id_boutique' => 'required|exists:boutique,id_boutique',
			'id_events'   => 'nullable|array',
			'id_events.*' => 'exists:event,id_event',
		]);

		$rayon = Rayon::create([
			'nom_rayon'         => $request->nom_rayon,
			'id_boutique'       => $request->id_boutique,
			'stock_total_rayon' => 0,
			'live_rayon'        => false,
		]);

		if ($request->filled('id_events')) {
			//themes()->sync → events()->sync
			$rayon->events()->sync($request->id_events);

			//méthode du modèle pour lier les produits existants éligibles
			$rayon->syncProduitsDepuisEvents();
			$this->recalculerStockBoutique();
		}

		return redirect()->back()->with('success', 'Rayon créé avec succès !');
	}


	public function updateRayon(Request $request, int $id): RedirectResponse
	{
		$request->validate([
			'id_events'   => 'nullable|array',
			'id_events.*' => 'exists:event,id_event',
		]);

		$rayon = Rayon::findOrFail($id);

		// Mettre à jour les events du rayon
		$rayon->events()->sync($request->id_events ?? []);

		// Re-synchroniser les produits selon les nouveaux events
		$rayon->syncProduitsDepuisEvents();
		$this->recalculerStockBoutique();

		return redirect()->back()->with('success', 'Rayon mis à jour.');
	}
	public function destroyRayon(int $id): RedirectResponse
	{
		$rayon = Rayon::findOrFail($id);

		$produits = $rayon->produits()->get();

		$rayon->produits()->detach();

		foreach ($produits as $produit) {
			if ($produit->rayons()->count() === 0) {
				$produit->delete();
			}
		}

		$rayon->events()->detach();
		$rayon->delete();

		$this->recalculerStockBoutique();

		return redirect()->back()->with('success', 'Rayon supprimé.');
	}

    public function toggleLiveRayon(Request $request, int $id): RedirectResponse
	{
		$rayon = Rayon::findOrFail($id);
		$rayon->live_rayon = $request->boolean('live_rayon');
		$rayon->save();
		return redirect()->back();
	}


	// ── THÈMES ───────────────────────────────────────────────────────────

	public function nvtheme(Request $request): RedirectResponse
	{
		$request->validate([
			'nom_theme' => 'required|string|max:255',
			'icone'     => 'nullable|string|max:10',
			'couleur'   => 'nullable|string|max:7',
		]);

		Theme::create([
			'nom_theme' => $request->nom_theme,
			'icone'     => $request->icone   ?? '🎨',
			'couleur'   => $request->couleur ?? '#C0395A',
		]);

		return redirect()->back()->with('success', 'Thème créé avec succès !');
	}

	public function destroyTheme(int $id): RedirectResponse
	{
		$theme = Theme::findOrFail($id);
		$theme->delete();

		return redirect()->back()->with('success', 'Thème supprimé.');
	}

	// ── EVENTS ───────────────────────────────────────────────────────────

	public function nvevent(Request $request): RedirectResponse
	{
		$request->validate([
			'nom_event' => 'required|string|max:255',
			'icone'     => 'nullable|string|max:4',
			'couleur'   => 'nullable|string|max:7',
		]);

		Event::create([
			'nom_event' => $request->nom_event,
			'icone'     => $request->icone   ?? '🎉',
			'couleur'   => $request->couleur ?? '#C0392B',
		]);

		return redirect()->back()->with('success', 'Événement créé avec succès !');
	}

	public function destroyEvent(int $id): RedirectResponse
	{
		$event = Event::findOrFail($id);
		$event->rayons()->detach();   // nettoyer rayon_event
		$event->produits()->detach(); // nettoyer produit_event
		$event->delete();

		return redirect()->back()->with('success', 'Événement supprimé.');
	}

	// ── FORMES ────────────────────────────────────────────────────────────

	public function nvforme(Request $request): RedirectResponse
	{
		$request->validate(['nom_forme' => 'required|string|max:255']);
		Forme::create(['nom_forme' => $request->nom_forme]);
		return redirect()->back()->with('success', 'Forme créée avec succès !');
	}

	public function destroyForme(int $id): RedirectResponse
	{
		Forme::findOrFail($id)->delete();
		return redirect()->back()->with('success', 'Forme supprimée.');
	}

	// ── CONDITIONNEMENTS ──────────────────────────────────────────────────

	public function nvconditionnement(Request $request): RedirectResponse
	{
		$request->validate(['type' => 'required|string|max:255']);
		Conditionnement::create(['type' => $request->type]);
		return redirect()->back()->with('success', 'Conditionnement créé avec succès !');
	}

	public function destroyConditionnement(int $id): RedirectResponse
	{
		Conditionnement::findOrFail($id)->delete();
		return redirect()->back()->with('success', 'Conditionnement supprimé.');
	}

	// ── FORME_CONDI (PRIX) ────────────────────────────────────────────────

	public function nvformecondi(Request $request): RedirectResponse
	{
		$request->validate([
			'id_forme' => 'required|exists:forme,id_forme',
			'id_condi' => 'required|exists:conditionnement,id_condi',
			'prix'     => 'required|numeric|min:0',
		]);

		// ✅ Corrigé : FormeCondi → Forme_Condi
		Forme_Condi::create([
			'id_forme' => $request->id_forme,
			'id_condi' => $request->id_condi,
			'prix'     => $request->prix,
		]);

		return redirect()->back()->with('success', 'Prix créé avec succès !');
	}

	public function destroyFormeCondi(int $id): RedirectResponse
	{
		// ✅ Corrigé : FormeCondi → Forme_Condi
		Forme_Condi::findOrFail($id)->delete();
		return redirect()->back()->with('success', 'Prix supprimé.');
	}

	// ── PARFUMS ───────────────────────────────────────────────────────────

	public function nvparfum(Request $request): RedirectResponse
	{
		$request->validate(['nom_parfum' => 'required|string|max:255']);
		Parfum::create(['nom_parfum' => $request->nom_parfum]);
		return redirect()->back()->with('success', 'Parfum créé avec succès !');
	}

	public function destroyParfum(int $id): RedirectResponse
	{
		Parfum::findOrFail($id)->delete();
		return redirect()->back()->with('success', 'Parfum supprimé.');
	}

	// ── HELPERS ───────────────────────────────────────────────────────────

	private function recalculerStockBoutique(): void
	{
		$boutique = Boutique::first();
		if ($boutique) {
			$boutique->recalculerStock();
		}
	}
}
