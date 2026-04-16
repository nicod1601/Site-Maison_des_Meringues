<?php

namespace App\Http\Controllers;
use App\Models\Produit;
use Illuminate\Http\Request;

class CreationController extends Controller
{
    protected $fillable = ['id_parfum', 'id_forme', 'description', 'quantite'];
	public function nvproduit(Request $request)
    {
        $request->validate([
            'id_parfum'    => 'required|exists:parfum,id_parfum',
            'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
            'quantite'     => 'required|integer|min:0',
        ]);

        Produit::create([
            'id_parfum'    => $request->id_parfum,
            'id_forme_condi' => $request->id_forme_condi,
            'description'  => $request->description,
            'quantite'     => $request->quantite,
        ]);

        return redirect()->back()->with('success', 'Produit créé avec succès !');
    }
}
