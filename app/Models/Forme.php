<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forme extends Model
{
	protected $table = 'forme';

	protected $primaryKey = 'id_forme';

	public $timestamps = false;

	protected $fillable = [
        'id_forme',
        'nom_forme',
	];

    public function forme_condis() {
        return $this->hasMany(Forme_Condi::class, 'id_forme');
    }

    // La méthode migre ici
    public function tous_conditionnements() {
        return $this->hasMany(Forme_Condi::class, 'id_forme')
                    ->with('conditionnement');
    }
}
