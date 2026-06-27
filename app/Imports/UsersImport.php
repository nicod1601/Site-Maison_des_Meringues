<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Produit;
use App\Models\ProduitFormeCondi;
use App\Models\Rayon;
use App\Models\Forme;
use App\Models\Forme_Condi;
use App\Models\Parfum;
use App\Models\Theme;
use App\Models\Event;
use App\Models\Image;
use App\Models\Boutique;
use Intervention\Image\Laravel\Facades\Image as ImageManager;

class UsersImport implements ToCollection
{
	/**
	 * ID du rayon de base (rayon par défaut, premier rayon).
	 * Tous les produits sans event y sont rangés.
	 * Les produits avec event non spéciaux y sont aussi rangés (en plus du rayon event).
	 * Les produits spéciaux avec event ne sont PAS dans ce rayon.
	 */
	private const RAYON_BASE_ID = 1;

	public function collection(Collection $rows)
	{
		if ($rows->isEmpty()) {
			return;
		}

		$boutique = Boutique::first();

		// ── Lecture de l'en-tête : nom de colonne → index ─────────────
		$colIndex = [];
		foreach ($rows->first() as $idx => $label) {
			$label = trim((string) $label);
			if ($label !== '') {
				$colIndex[$label] = $idx;
			}
		}

		// Colonnes "Stock_xxx" présentes dans le fichier → type de conditionnement
		$stockAliases = [
			'boite'          => 'boite_de_8',
			'sachet'         => 'sachet_de_4',
			'sachet_de_4'    => 'sachet_de_4',
			'boite_de_8'     => 'boite_de_8',
			'sachet_de_10'   => 'sachet_de_10',
			'individuel'     => 'individuel',
			'vrac'           => 'vrac',
		];

		$stockColumns = [];
		foreach ($colIndex as $label => $idx) {
			if (Str::startsWith($label, 'Stock_')) {
				$rawType = strtolower(trim(Str::after($label, 'Stock_')));
				if ($rawType === '') continue;
				$resolvedType = $stockAliases[$rawType] ?? $rawType;
				$stockColumns[$resolvedType] = $idx;
			}
		}

		$cell = function ($row, string $label, $default = '') use ($colIndex) {
			if (!isset($colIndex[$label])) {
				return $default;
			}
			return $row[$colIndex[$label]] ?? $default;
		};

		// ── Cache ──────────────────────────────────────────────────────
		$formesCache  = [];
		$parfumsCache = [];
		$themesCache  = [];
		$eventsCache  = [];

		// Cache des rayons par event : id_event → Rayon (si un rayon porte le nom de l'event)
		$rayonsParEvent = [];

		foreach ($rows as $index => $row) {

			if ($index === 0) continue; // ligne d'en-tête

			$nomProduit = trim((string) $cell($row, 'Nom_produit'));
			if ($nomProduit === '') continue;

			// ── Forme ─────────────────────────────────────────────────
			$nomForme = trim((string) $cell($row, 'Forme'));
			if (!isset($formesCache[$nomForme])) {
				$formesCache[$nomForme] = Forme::where('nom_forme', $nomForme)->first();
			}
			$forme = $formesCache[$nomForme];
			if (!$forme) continue;

			// ── Parfum ────────────────────────────────────────────────
			$nomParfum = trim((string) $cell($row, 'Parfum'));
			if (!isset($parfumsCache[$nomParfum])) {
				$parfumsCache[$nomParfum] = Parfum::where('nom_parfum', $nomParfum)->first();
			}
			$parfum = $parfumsCache[$nomParfum];
			if (!$parfum) continue;

			// ── Champs simples ────────────────────────────────────────
			$description = trim((string) $cell($row, 'Description')) ?: 'Aucune description';
			$nouveaute   = strtolower(trim((string) $cell($row, 'Nouveauté')))  === 'oui';
			$live        = strtolower(trim((string) $cell($row, 'Live')))       === 'oui';
			$expedition  = strtolower($forme->nom_forme) === 'mini';
			$special     = strtolower(trim((string) $cell($row, 'Specialité'))) === 'oui';

			// ── Thème ─────────────────────────────────────────────────
			$nomTheme = trim((string) $cell($row, 'Theme'));
			$idTheme  = null;
			if ($nomTheme !== '') {
				if (!isset($themesCache[$nomTheme])) {
					$themesCache[$nomTheme] = Theme::where('nom_theme', $nomTheme)->first();
				}
				$theme = $themesCache[$nomTheme];
				if ($theme) $idTheme = $theme->id_theme;
			}

			// ── Events ────────────────────────────────────────────────
			$eventIds  = [];
			$nomEvents = trim((string) $cell($row, 'Event'));

			if ($nomEvents !== '') {
				foreach (array_map('trim', explode(',', $nomEvents)) as $nomEvent) {
					if ($nomEvent === '') continue;
					if (!isset($eventsCache[$nomEvent])) {
						$eventsCache[$nomEvent] = Event::where('nom_event', $nomEvent)->first();
					}
					$event = $eventsCache[$nomEvent];
					if ($event) {
						$eventIds[] = $event->id_event;

						// Charger le rayon associé à cet event (s'il existe) une seule fois
						if (!array_key_exists($event->id_event, $rayonsParEvent)) {
							$rayonsParEvent[$event->id_event] = Rayon::where('nom_rayon', $nomEvent)->first();
						}
					}
				}
			}

			// ── Stock par conditionnement ──────────────────────────────
			$stocksParType = [];
			foreach ($stockColumns as $type => $idx) {
				$stocksParType[$type] = (int) ($row[$idx] ?? 0);
			}

			// ── Produit (upsert) ──────────────────────────────────────
			$produit = Produit::updateOrCreate(
				[
					'nom_produit' => $nomProduit,
					'id_forme'    => $forme->id_forme,
					'id_parfum'   => $parfum->id_parfum,
				],
				[
					'id_theme'         => $idTheme,
					'description'      => $description,
					'nouveaute'        => $nouveaute,
					'live'             => $live,
					'dispo_emporter'   => false,
					'dispo_expedition' => $expedition,
					'special'          => $special,
				]
			);

			// ── Images + stocks par conditionnement ───────────────────
			$images = [];

			$conditionnements = Forme_Condi::where('id_forme', $produit->id_forme)
				->join('conditionnement', 'forme_condi.id_condi', '=', 'conditionnement.id_condi')
				->select('forme_condi.id_forme_condi', 'conditionnement.type')
				->get();

			foreach ($conditionnements as $condi) {

				$slugForme  = Str::slug($produit->forme->nom_forme);
				$slugParfum = Str::slug($produit->parfum->nom_parfum);
				$typeKey    = strtolower(trim($condi->type));
				$typeSlug   = Str::slug($condi->type);

				$filename     = "{$slugForme}-{$typeSlug}-{$slugParfum}.webp";
				$relativePath = "fichier/image/meringues/{$filename}";
				$fullPath     = public_path($relativePath);
				$sourcePath   = public_path(
					"fichier/image/meringues/{$slugForme}/{$typeSlug}/{$slugParfum}.jpg"
				);

				if (file_exists($sourcePath) && !file_exists($fullPath)) {
					ImageManager::read($sourcePath)
						->cover(600, 600)
						->toWebp(75)
						->save($fullPath);
				}

				$images[] = [
					'id_produit'     => $produit->id_produit,
					'id_forme_condi' => $condi->id_forme_condi,
					'url'            => $relativePath,
				];

				if (array_key_exists($typeKey, $stockColumns)) {
					ProduitFormeCondi::updateOrCreate(
						['id_produit' => $produit->id_produit, 'id_forme_condi' => $condi->id_forme_condi],
						['quantite'   => $stocksParType[$typeKey] ?? 0]
					);
				} else {
					ProduitFormeCondi::firstOrCreate(
						['id_produit' => $produit->id_produit, 'id_forme_condi' => $condi->id_forme_condi],
						['quantite'   => 0]
					);
				}
			}

			Image::where('id_produit', $produit->id_produit)->delete();
			Image::insert($images);

			$produit->recalculerStock();

			// ── Sync events ───────────────────────────────────────────
			if (!empty($eventIds)) {
				$produit->events()->sync($eventIds);
			} else {
				$produit->events()->detach();
			}

			// ── Logique de rangement dans les rayons ──────────────────
			//
			//  Cas 1 — Pas d'event
			//           → rayon de base uniquement
			//
			//  Cas 2 — Event(s) présent(s) + NON spécial
			//           → rayon de base + rayon(s) de l'event
			//
			//  Cas 3 — Event(s) présent(s) + spécial
			//           → rayon(s) de l'event uniquement (pas de rayon de base)
			//
			$rayonIds = [];

			if (empty($eventIds)) {
				// Cas 1
				$rayonIds[] = self::RAYON_BASE_ID;
			} else {
				// Récupérer les ids des rayons liés aux events du produit
				$rayonEventIds = [];
				foreach ($eventIds as $idEvent) {
					$rayonEvent = $rayonsParEvent[$idEvent] ?? null;
					if ($rayonEvent) {
						$rayonEventIds[] = $rayonEvent->id_rayon;
					}
				}

				if ($special) {
					// Cas 3 : spécial + event → uniquement les rayons event
					// Si aucun rayon event n'est trouvé, on replie sur le rayon de base
					// pour ne pas laisser le produit orphelin.
					$rayonIds = !empty($rayonEventIds) ? $rayonEventIds : [self::RAYON_BASE_ID];
				} else {
					// Cas 2 : non spécial + event → rayon de base + rayons event
					$rayonIds = array_unique(array_merge([self::RAYON_BASE_ID], $rayonEventIds));
				}
			}

			$produit->rayons()->sync($rayonIds);
		}

		// ── Recalcul des stocks de tous les rayons + boutique ─────────
		Rayon::all()->each(fn($r) => $r->recalculerStock());
		$boutique->recalculerStock();
	}
}