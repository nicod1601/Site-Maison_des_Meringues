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

class UsersImport implements ToCollection
{
	private int $idRayon;

	public function __construct(int $idRayon)
	{
		$this->idRayon = $idRayon;
	}

	public function collection(Collection $rows)
	{
		// L'import est conçu pour alimenter le rayon de base uniquement.
		// La répartition dans les rayons événementiels se fait via syncProduitsDepuisEvents()
		// lors de la création ou modification d'un rayon.
		$rayon    = Rayon::with('boutique')->findOrFail($this->idRayon);
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

			// ── Forme_Condi ────────────────────────────────────────────────
			if (!isset($formeCondiCache[$forme->id_forme])) {
				$formeCondiCache[$forme->id_forme] = Forme_Condi::where('id_forme', $forme->id_forme)->first();
			}
			$formeCondi = $formeCondiCache[$forme->id_forme];
			if (!$formeCondi) continue;

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

			// ── Thème (optionnel, indicateur de tri sur le produit) ────────
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
			// On lit et on enregistre les events du produit.
			// Mais on ne filtre PAS et on ne propage PAS ici —
			// la propagation est gérée par syncProduitsDepuisEvents() au niveau des rayons.
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
					'nom_produit'    => $nomProduit,
					'id_forme_condi' => $formeCondi->id_forme_condi,
					'id_parfum'      => $parfum->id_parfum,
				],
				[
					'id_theme'         => $idTheme,
					'description'      => $description,
					'quantite'         => $quantite,
					'nouveaute'        => $nouveaute,
					'live'             => $live,
					'dispo_emporter'   => false,
					'dispo_expedition' => $expedition,
				]
			);

			// Synchroniser les events du produit (pour que les rayons puissent s'en servir)
			if (!empty($eventIds)) {
				$produit->events()->sync($eventIds);
			} else {
				$produit->events()->detach();
			}

			// Lier uniquement au rayon d'import (le rayon Base)
			$produit->rayons()->syncWithoutDetaching([$this->idRayon]);

			$produitsImportesIds[] = $produit->id_produit;
		}

		// ── Détacher du rayon Base les produits absents du fichier ────────
		$produitsActuelsIds = $rayon->produits()->pluck('produit.id_produit')->toArray();
		$aDetacher = array_diff($produitsActuelsIds, $produitsImportesIds);
		if (!empty($aDetacher)) {
			$rayon->produits()->detach($aDetacher);
		}

		// ── Mise à jour du stock du rayon Base et de la boutique ──────────
		$rayon->recalculerStock();
		$boutique->recalculerStock();
	}
}
