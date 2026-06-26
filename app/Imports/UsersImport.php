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
use Intervention\Image\Laravel\Facades\Image as ImageManager;

class UsersImport implements ToCollection
{
	private const RAYON_BASE_ID = 1;

	public function collection(Collection $rows)
	{
		if ($rows->isEmpty()) {
			return;
		}

		$rayon    = Rayon::with('boutique')->findOrFail(self::RAYON_BASE_ID);
		$boutique = $rayon->boutique;

		// ── Lecture de l'en-tête : nom de colonne → index ─────────────
		// On ne dépend plus de l'ordre des colonnes dans le fichier.
		$colIndex = [];
		foreach ($rows->first() as $idx => $label) {
			$label = trim((string) $label);
			if ($label !== '') {
				$colIndex[$label] = $idx;
			}
		}

		// Colonnes "Stock_xxx" présentes dans le fichier → type de conditionnement
		// Le nom après "Stock_" doit correspondre (insensible à la casse / espaces)
		// au champ "type" de la table "conditionnement".
		//
		// Table d'alias : permet de gérer des noms de colonnes raccourcis dans le
		// fichier Excel (ex. "Stock_boite" → type BDD "boite_de_8").
		// Clé   = suffixe normalisé tel qu'il apparaît dans le fichier Excel
		// Valeur = type exact stocké dans la table "conditionnement"
		$stockAliases = [
			'boite'          => 'boite_de_8',
			'sachet'         => 'sachet_de_4',
			'sachet_de_4'    => 'sachet_de_4',
			'boite_de_8'     => 'boite_de_8',
			'sachet_de_10'   => 'sachet_de_10',
			'individuel'     => 'individuel',
			'vrac'           => 'vrac',
		];

		$stockColumns = []; // ex: 'individuel' => 12
		foreach ($colIndex as $label => $idx) {
			if (Str::startsWith($label, 'Stock_')) {
				$rawType = strtolower(trim(Str::after($label, 'Stock_')));
				if ($rawType === '') continue;
				// Résolution via alias, puis fallback sur la valeur brute
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

		$produitsImportesIds = [];

		$formesCache  = [];
		$parfumsCache = [];
		$themesCache  = [];
		$eventsCache  = [];

		foreach ($rows as $index => $row) {

			if ($index === 0) continue; // ligne d'en-tête

			$nomProduit = trim((string) $cell($row, 'Nom_produit'));
			if ($nomProduit === '') continue;

			// ── Forme ─────────────────────────────
			$nomForme = trim((string) $cell($row, 'Forme'));
			if (!isset($formesCache[$nomForme])) {
				$formesCache[$nomForme] = Forme::where('nom_forme', $nomForme)->first();
			}
			$forme = $formesCache[$nomForme];
			if (!$forme) continue;

			// ── Parfum ─────────────────────────────
			$nomParfum = trim((string) $cell($row, 'Parfum'));
			if (!isset($parfumsCache[$nomParfum])) {
				$parfumsCache[$nomParfum] = Parfum::where('nom_parfum', $nomParfum)->first();
			}
			$parfum = $parfumsCache[$nomParfum];
			if (!$parfum) continue;

			// ── Champs ─────────────────────────────
			$description = trim((string) $cell($row, 'Description')) ?: 'Aucune description';
			$nouveaute   = strtolower(trim((string) $cell($row, 'Nouveauté'))) === 'oui';
			$live        = strtolower(trim((string) $cell($row, 'Live')))      === 'oui';
			$expedition  = strtolower($forme->nom_forme) === 'mini';
			$special     = strtolower(trim((string) $cell($row, 'Specialité'))) === 'oui';

			// ── Theme ─────────────────────────────
			$nomTheme = trim((string) $cell($row, 'Theme'));
			$idTheme  = null;

			if ($nomTheme !== '') {
				if (!isset($themesCache[$nomTheme])) {
					$themesCache[$nomTheme] = Theme::where('nom_theme', $nomTheme)->first();
				}
				$theme = $themesCache[$nomTheme];
				if ($theme) $idTheme = $theme->id_theme;
			}

			// ── Events ─────────────────────────────
			$eventIds  = [];
			$nomEvents = trim((string) $cell($row, 'Event'));

			if ($nomEvents !== '') {
				foreach (array_map('trim', explode(',', $nomEvents)) as $nomEvent) {
					if (!isset($eventsCache[$nomEvent])) {
						$eventsCache[$nomEvent] = Event::where('nom_event', $nomEvent)->first();
					}
					$event = $eventsCache[$nomEvent];
					if ($event) $eventIds[] = $event->id_event;
				}
			}

			// ── Stock par conditionnement (colonnes Stock_xxx présentes) ──
			$stocksParType = [];
			foreach ($stockColumns as $type => $idx) {
				$stocksParType[$type] = (int) ($row[$idx] ?? 0);
			}

			// ── Produit ─────────────────────────────
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

			// ── IMAGES OPTIMISÉES WEBP + STOCK PAR CONDITIONNEMENT ──
			$images = [];

			$conditionnements = Forme_Condi::where('id_forme', $produit->id_forme)
				->join('conditionnement', 'forme_condi.id_condi', '=', 'conditionnement.id_condi')
				->select('forme_condi.id_forme_condi', 'conditionnement.type')
				->get();

			foreach ($conditionnements as $condi) {

				$slugForme  = Str::slug($produit->forme->nom_forme);
				$slugParfum = Str::slug($produit->parfum->nom_parfum);

				// $typeKey : utilisé pour matcher la colonne "Stock_<type>" du fichier Excel
				// (doit rester identique au texte stocké en BDD, juste normalisé en minuscule/trim).
				$typeKey = strtolower(trim($condi->type));

				// $typeSlug : utilisé pour les noms de fichiers/dossiers (évite espaces, accents, etc.
				// ex: "sachet de 4" → "sachet-de-4"), utile si un type contient des caractères spéciaux.
				$typeSlug = Str::slug($condi->type);

				$filename = "{$slugForme}-{$typeSlug}-{$slugParfum}.webp";

				$relativePath = "fichier/image/meringues/{$filename}";
				$fullPath     = public_path($relativePath);

				$sourcePath = public_path(
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

				// Stock : si la colonne Stock_<type> est présente dans le
				// fichier, on écrase la valeur. Si elle est absente (vieux
				// fichier sans cette colonne), on ne touche pas au stock
				// existant — on ne crée la ligne qu'à 0 si elle n'existe pas.
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

			// ── Events sync ─────────────────────────────
			if (!empty($eventIds)) {
				$produit->events()->sync($eventIds);
			} else {
				$produit->events()->detach();
			}

			// ── Rayons ─────────────────────────────
			if ($produit->special && !empty($eventIds)) {

				$rayonIds = Rayon::whereHas('events', function ($q) use ($eventIds) {
						$q->whereIn('event.id_event', $eventIds);
					})
					->where('id_boutique', $boutique->id_boutique)
					->pluck('id_rayon')
					->toArray();

				if (!empty($rayonIds)) {
					$produit->rayons()->detach(self::RAYON_BASE_ID);
					$produit->rayons()->syncWithoutDetaching($rayonIds);
				}

			} else {

				$rayonsEvents = Rayon::whereHas('events')
					->where('id_boutique', $boutique->id_boutique)
					->pluck('id_rayon')
					->toArray();

				$produit->rayons()->detach($rayonsEvents);
				$produit->rayons()->syncWithoutDetaching([self::RAYON_BASE_ID]);
			}

			$produitsImportesIds[] = $produit->id_produit;
		}

		// ── Nettoyage rayons ─────────────────────────────
		$produitsActuelsIds = $rayon->produits()->pluck('produit.id_produit')->toArray();
		$aDetacher          = array_diff($produitsActuelsIds, $produitsImportesIds);

		if (!empty($aDetacher)) {
			$rayon->produits()->detach($aDetacher);
		}

		$rayon->recalculerStock();
		$boutique->recalculerStock();
	}
}