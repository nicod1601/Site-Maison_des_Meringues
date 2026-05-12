<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Forme_Condi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageController extends Controller
{
	public function store(Request $request)
	{
		$request->validate([
			'image'          => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
			'id_produit'     => 'required|exists:produit,id_produit',
			'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
		]);

		$formeCondi = Forme_Condi::with(['forme', 'conditionnement'])
						->findOrFail($request->input('id_forme_condi'));

		$nomForme = Str::slug($formeCondi->forme->nom_forme ?? 'divers');
		$nomCondi = Str::slug($formeCondi->conditionnement->type ?? 'divers');

		$dossier = public_path("fichier/image/meringues/{$nomForme}/{$nomCondi}");

		if (!is_dir($dossier)) {
			mkdir($dossier, 0755, true);
		}

		$file      = $request->file('image');
		$extension = strtolower($file->getClientOriginalExtension());
		$baseName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$filename  = Str::slug($baseName) . '.' . $extension;

		// Éviter les doublons de nom de fichier
		$counter = 1;
		while (file_exists("{$dossier}/{$filename}")) {
			$filename = Str::slug($baseName) . '-' . $counter . '.' . $extension;
			$counter++;
		}

		$file->move($dossier, $filename);

		$url = "fichier/image/meringues/{$nomForme}/{$nomCondi}/{$filename}";

		Image::create([
			'id_produit'     => $request->input('id_produit'),
			'id_forme_condi' => $request->input('id_forme_condi'),
			'url'            => $url,
		]);

		return redirect()->route('gestion.index', ['tab' => 'images'])
			->with('success', 'Image ajoutée avec succès.');
	}

	public function remplacer(Request $request, $id)
	{
		$image = Image::findOrFail($id);

		$request->validate([
			'image' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
		]);

		$cheminExistant = public_path($image->url);

		// Supprimer l'ancien fichier s'il existe
		if (file_exists($cheminExistant)) {
			unlink($cheminExistant);
		}

		$dossier  = dirname($cheminExistant);
		$filename = basename($cheminExistant);

		if (!is_dir($dossier)) {
			mkdir($dossier, 0755, true);
		}

		// Déposer le nouveau fichier exactement au même emplacement avec le même nom
		$request->file('image')->move($dossier, $filename);

		return redirect()->route('gestion.index', ['tab' => 'images'])
			->with('success', 'Image remplacée avec succès.');
	}

	public function destroy($id)
	{
		$image = Image::findOrFail($id);

		$chemin = public_path($image->url);

		if (file_exists($chemin)) {
			unlink($chemin);
		}

		return redirect()->route('gestion.index', ['tab' => 'images'])
			->with('success', 'Fichier image supprimé. La carte produit est conservée.');
	}
}
