<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Panier extends Model
{
    /** Clé de session qui identifie le panier d'un visiteur non connecté. */
    public const SESSION_KEY = 'panier_token';

    protected $table = 'panier';
    protected $primaryKey = 'id_panier';

    protected $fillable = ['user_id', 'session_id'];

    public function lignes()
    {
        return $this->hasMany(PanierLigne::class, 'id_panier');
    }

    public function total(): float
    {
        return $this->lignes->sum(fn($l) => $l->prix_unitaire * $l->quantite);
    }

    //récupérer une liste des produits dans le panier
    public function produits() : array
    {
        return $this->lignes()->with('produit')->get()->pluck('produit')->toArray();
    }

    /**
     * Jeton propre au visiteur. Il est stocké DANS la session (et non dérivé de
     * session()->getId()) : il survit donc au session()->regenerate() fait au login.
     */
    public static function jetonVisiteur(): string
    {
        if (! session()->has(self::SESSION_KEY)) {
            session([self::SESSION_KEY => (string) Str::uuid()]);
        }

        return session(self::SESSION_KEY);
    }

    /**
     * Panier de la personne en cours (compte connecté ou visiteur).
     *
     * @param bool $creer false → ne crée rien en base : renvoie un panier vide
     *                    non enregistré si la personne n'en a pas encore (lecture seule).
     */
    public static function courant(bool $creer = true): self
    {
        $critere = Auth::check()
            ? ['user_id' => Auth::id()]
            : ['session_id' => self::jetonVisiteur()];

        if ($creer) {
            return static::firstOrCreate($critere);
        }

        return static::where($critere)->first()
            ?? tap(new static($critere))->setRelation('lignes', collect());
    }
}
