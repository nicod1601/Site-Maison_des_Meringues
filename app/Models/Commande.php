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
        'montant',
        'statut',
        'monetico_reference',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
