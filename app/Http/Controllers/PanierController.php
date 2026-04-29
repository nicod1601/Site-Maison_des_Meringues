<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\PanierLigne;
use App\Models\Forme_Condi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    // Récupère ou crée le panier (connecté ou anonyme)
    private function getPanier(): Panier
    {
        if (Auth::check()) {
            return Panier::firstOrCreate(['user_id' => Auth::id()]);
        }
        return Panier::firstOrCreate(['session_id' => session()->getId()]);
    }

    // Afficher le panier
    public function index()
    {
        $panier = $this->getPanier()->load(
            'lignes.produit.parfum',
            'lignes.produit.forme',
            'lignes.formeCondi.conditionnement'
        );
        return view('panier', compact('panier'));
    }

    // Ajouter un produit
    public function ajouter(Request $request)
    {
        $request->validate([
            'id_produit'     => 'required|exists:produit,id_produit',
            'id_forme_condi' => 'required|exists:forme_condi,id_forme_condi',
            'quantite'       => 'required|integer|min:1',
        ]);

        $panier     = $this->getPanier();
        $formeCondi = Forme_Condi::findOrFail($request->id_forme_condi);

        // Si la ligne existe déjà, on augmente la quantité
        $ligne = $panier->lignes()
            ->where('id_produit', $request->id_produit)
            ->where('id_forme_condi', $request->id_forme_condi)
            ->first();

        if ($ligne) {
            $ligne->increment('quantite', $request->quantite);
        } else {
            $panier->lignes()->create([
                'id_produit'     => $request->id_produit,
                'id_forme_condi' => $request->id_forme_condi,
                'quantite'       => $request->quantite,
                'prix_unitaire'  => $formeCondi->prix,
            ]);
        }

        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    // Modifier la quantité
    public function modifier(Request $request, int $id)
    {
        $request->validate(['quantite' => 'required|integer|min:1']);

        $ligne = PanierLigne::findOrFail($id);
        $ligne->update(['quantite' => $request->quantite]);

        return redirect()->back()->with('success', 'Quantité mise à jour.');
    }

    // Supprimer une ligne
    public function supprimer(int $id)
    {
        PanierLigne::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }

    // Vider le panier
    public function vider()
    {
        $this->getPanier()->lignes()->delete();
        return redirect()->back()->with('success', 'Panier vidé.');
    }
}
