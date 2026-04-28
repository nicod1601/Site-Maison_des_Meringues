<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Produit;
use App\Models\Rayon;
use App\Models\Forme;
use App\Models\Forme_Condi;
use App\Models\Parfum;
use App\Models\Theme;
use App\Models\Event;
use App\Models\Image;

class UsersImport implements ToCollection
{
	private const RAYON_BASE_ID = 1;

	public function collection(Collection $rows)
	{
		$rayon    = Rayon::with('boutique')->findOrFail(self::RAYON_BASE_ID);
		$boutique = $rayon->boutique;

		$produitsImportesIds = [];

		$formesCache     = [];
		$formeCondiCache = [];
		$parfumsCache    = [];
		$themesCache     = [];
		$eventsCache     = [];

		foreach ($rows as $index => $row) {

			if ($index === 0) continue;

			$nomProduit = trim($row[0] ?? '');
			if ($nomProduit === '') continue;

			// ── Forme ──────────────────────────────────────────────────────
			$nomForme = trim($row[1] ?? '');
			if (!isset($formesCache[$nomForme])) {
				$formesCache[$nomForme] = Forme::where('nom_forme', $nomForme)->first();
			}
			$forme = $formesCache[$nomForme];
			if (!$forme) continue;

			// ── Parfum ─────────────────────────────────────────────────────
			$nomParfum = trim($row[2] ?? '');
			if (!isset($parfumsCache[$nomParfum])) {
				$parfumsCache[$nomParfum] = Parfum::where('nom_parfum', $nomParfum)->first();
			}
			$parfum = $parfumsCache[$nomParfum];
			if (!$parfum) continue;

			// ── Champs simples ─────────────────────────────────────────────
			$description = trim($row[3] ?? '') ?: 'Aucune description';
			$quantite    = (int) ($row[4] ?? 0);
			$nouveaute   = strtolower(trim($row[5] ?? '')) === 'oui';
			$live        = strtolower(trim($row[6] ?? '')) === 'oui';
			$expedition  = strtolower($forme->nom_forme) === 'mini';
			$special     = strtolower(trim($row[9] ?? '')) === 'oui';

			// ── Thème (optionnel) ──────────────────────────────────────────
			$nomTheme = trim($row[7] ?? '');
			$idTheme  = null;
			if ($nomTheme !== '') {
				if (!isset($themesCache[$nomTheme])) {
					$themesCache[$nomTheme] = Theme::where('nom_theme', $nomTheme)->first();
				}
				$theme = $themesCache[$nomTheme];
				if ($theme) $idTheme = $theme->id_theme;
			}

			// ── Events (optionnels, séparés par des virgules) ─────────────
			$eventIds  = [];
			$nomEvents = trim($row[8] ?? '');
			if ($nomEvents !== '') {
				foreach (array_map('trim', explode(',', $nomEvents)) as $nomEvent) {
					if ($nomEvent !== '') {
						if (!isset($eventsCache[$nomEvent])) {
							$eventsCache[$nomEvent] = Event::where('nom_event', $nomEvent)->first();
						}
						$event = $eventsCache[$nomEvent];
						if ($event) $eventIds[] = $event->id_event;
					}
				}
			}

			// ── Créer ou mettre à jour le produit ─────────────────────────
			$produit = Produit::updateOrCreate(
				[
					'nom_produit' => $nomProduit,
					'id_forme'    => $forme->id_forme,
					'id_parfum'   => $parfum->id_parfum,
				],
				[
					'id_theme'         => $idTheme,
					'description'      => $description,
					'quantite'         => $quantite,
					'nouveaute'        => $nouveaute,
					'live'             => $live,
					'dispo_emporter'   => false,
					'dispo_expedition' => $expedition,
					'special'          => $special,
				]
			);

			//- Créer les Images associées
			$images = [];

			if ($produit->forme->nom_forme === 'Mini'){

				$conditionnements = Forme_Condi::where('id_forme', $produit->id_forme)
					->join('conditionnement', 'forme_condi.id_condi', '=', 'conditionnement.id_condi')
					->select('forme_condi.id_forme_condi', 'conditionnement.type')
					->get();

				foreach ($conditionnements as $condi) {
					$images[] = [
						'id_produit' => $produit->id_produit,
						'id_forme_condi' => $condi->id_forme_condi,
						'url' => "fichier/image/meringues/Mini/" . $condi->type . "/" . $produit->parfum->nom_parfum . ".jpg",
					];
				}
			}

			if ($produit->forme->nom_forme === 'Nid'){

				$conditionnements = Forme_Condi::where('id_forme', $produit->id_forme)
					->join('conditionnement', 'forme_condi.id_condi', '=', 'conditionnement.id_condi')
					->select('forme_condi.id_forme_condi', 'conditionnement.type')
					->get();

				foreach ($conditionnements as $condi) {
					$images[] = [
						'id_produit' => $produit->id_produit,
						'id_forme_condi' => $condi->id_forme_condi,
						'url' => "fichier/image/meringues/Nid/". $condi->type . "/" . $produit->parfum->nom_parfum . ".jpg",
					];
				}
			}

			Image::where('id_produit', $produit->id_produit)->delete();
			Image::insert($images);

			// ── Synchroniser les events du produit ────────────────────────
			if (!empty($eventIds)) {
				$produit->events()->sync($eventIds);
			} else {
				$produit->events()->detach();
			}

			// ── Lier aux rayons selon special ─────────────────────────────
			if ($produit->special && !empty($eventIds)) {
				// Trouver tous les rayons liés à ses events
				$rayonIds = Rayon::whereHas('events', function ($q) use ($eventIds) {
						$q->whereIn('event.id_event', $eventIds);
					})
					->where('id_boutique', $boutique->id_boutique)
					->pluck('id_rayon')
					->toArray();

				if (!empty($rayonIds)) {
					// Détacher le rayon Base si présent, attacher les rayons events
					$produit->rayons()->detach(self::RAYON_BASE_ID);
					$produit->rayons()->syncWithoutDetaching($rayonIds);
				}
			} else {
				// Produit normal → rayon Base uniquement
				// Détacher les rayons events au cas où il ne serait plus special
				$rayonsEvents = Rayon::whereHas('events')
					->where('id_boutique', $boutique->id_boutique)
					->pluck('id_rayon')
					->toArray();

				$produit->rayons()->detach($rayonsEvents);
				$produit->rayons()->syncWithoutDetaching([self::RAYON_BASE_ID]);
			}

			$produitsImportesIds[] = $produit->id_produit;
		}

		// ── Détacher du rayon Base les produits absents du fichier ────────
		$produitsActuelsIds = $rayon->produits()->pluck('produit.id_produit')->toArray();
		$aDetacher = array_diff($produitsActuelsIds, $produitsImportesIds);
		if (!empty($aDetacher)) {
			$rayon->produits()->detach($aDetacher);
		}

		// ── Mise à jour du stock ───────────────────────────────────────────
		$rayon->recalculerStock();
		$boutique->recalculerStock();
	}
}
