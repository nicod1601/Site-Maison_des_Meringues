<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeLigne extends Model
{
	protected $table      = 'commande_ligne';
	protected $primaryKey = 'id_ligne';

	protected $fillable = [
		'id_commande',
		'designation',
		'sous_designation',
		'quantite',
		'prix_unitaire',
	];

	// ── Relations ─────────────────────────────────────────────────

	public function commande()
	{
		return $this->belongsTo(Commande::class, 'id_commande', 'id_commande');
	}
}
