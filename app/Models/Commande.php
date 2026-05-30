<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table      = 'commande';
    protected $primaryKey = 'id_commande';

    protected $fillable = [
        'user_id',
        'reference',
        'montant',        // ← on garde 'montant' (cohérent avec CommandeController)
        'mode_livraison',
        'statut',
        'monetico_reference',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lignes()
    {
        return $this->hasMany(CommandeLigne::class, 'id_commande', 'id_commande');
    }

    public function estExpedition(): bool
    {
        return $this->mode_livraison === 'expedition';
    }

    public function estLivraison(): bool
    {
        return $this->mode_livraison === 'livraison';
    }
}
