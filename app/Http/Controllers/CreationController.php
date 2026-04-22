<?php

namespace App\Http\Controllers;
use App\Models\Produit;
use App\Models\Rayon;
use Illuminate\Http\Request;

class CreationController extends Controller
{
	protected $fillable = ['id_parfum', 'id_forme', 'description', 'quantite'];
	public function nvproduit(Request $request, $idRayon)
	{
		$request->validate([
			'id_parfum'    => 'required|exists:parfum,id_parfum',
			'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
			'quantite'     => 'required|integer|min:0',
		]);

		Produit::create([
			'id_parfum'    => $request->id_parfum,
			'id_forme_condi' => $request->id_forme_condi,
			'id_rayon'     => $idRayon,
			'description'  => $request->description,
			'quantite'     => $request->quantite,
		]);

		return redirect()->back()->with('success', 'Produit créé avec succès !');
	}

	public function destroy($id)
	{
		Produit::findOrFail($id)->delete();

		return redirect()->back();
	}

	public function nvrayon(Request $request)
	{
		$request->validate([
			'nom_rayon' => 'required|string|max:255',
			'id_boutique' => 'required|exists:boutique,id_boutique',
		]);

		Rayon::create([
			'nom_rayon' => $request->nom_rayon,
			'id_boutique' => $request->id_boutique,
			'stock_total_rayon' => 0,
		]);

		return redirect()->back()->with('success', 'Rayon créé avec succès !');
	}
}
