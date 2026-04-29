<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
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
}
