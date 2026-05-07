<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Forme_Condi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * Enregistrer une nouvelle image dans le bon sous-dossier.
     * Chemin : fichier/image/meringues/{forme}/{conditionnement}/{nom_fichier}
     */
    public function store(Request $request)
    {
        $request->validate([
            'image'          => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'id_produit'     => 'required|exists:produit,id_produit',
            'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
        ]);

        // Récupérer la forme et le conditionnement pour construire le chemin
        $formeCondi = Forme_Condi::with(['forme', 'conditionnement'])
                        ->findOrFail($request->input('id_forme_condi'));

        $nomForme   = Str::slug($formeCondi->forme->nom_forme ?? 'divers');
        $nomCondi   = Str::slug($formeCondi->conditionnement->type ?? 'divers');

        // Dossier de destination : fichier/image/meringues/{forme}/{conditionnement}/
        $dossier    = public_path("fichier/image/meringues/{$nomForme}/{$nomCondi}");

        // Créer le dossier s'il n'existe pas
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        $file     = $request->file('image');
        $filename = $file->getClientOriginalName(); // Conserve le nom original

        // Déplacer le fichier dans le bon dossier
        $file->move($dossier, $filename);

        // L'URL stockée en BDD reflète le chemin public
        $url = "fichier/image/meringues/{$nomForme}/{$nomCondi}/{$filename}";

        Image::create([
            'id_produit'     => $request->input('id_produit'),
            'id_forme_condi' => $request->input('id_forme_condi'),
            'url'            => $url,
        ]);

        return redirect()->route('gestion.index')
            ->with('success', 'Image ajoutée avec succès.');
    }

    /**
     * Remplacer le fichier physique sans changer l'url en base.
     * Le nouveau fichier écrase l'ancien au même emplacement.
     */
    public function remplacer(Request $request, Image $image)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        // Chemin complet du fichier existant
        $cheminExistant = public_path($image->url);

        // Supprimer l'ancien fichier s'il existe
        if (file_exists($cheminExistant)) {
            unlink($cheminExistant);
        }

        // On garde le même dossier et le même nom de fichier
        $dossier  = dirname($cheminExistant);
        $filename = basename($cheminExistant);

        // Créer le dossier si besoin (au cas où il aurait été supprimé)
        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        // Enregistrer le nouveau fichier avec le même nom → même URL en BDD
        $request->file('image')->move($dossier, $filename);

        return redirect()->route('gestion.index')
            ->with('success', 'Image remplacée avec succès.');
    }

    /**
     * Supprimer une image (fichier physique + entrée BDD).
     */
    public function destroy(Image $image)
    {
        $chemin = public_path($image->url);

        if (file_exists($chemin)) {
            unlink($chemin);
        }

        $image->delete();

        return redirect()->route('gestion.index')
            ->with('success', 'Image supprimée.');
    }
}
