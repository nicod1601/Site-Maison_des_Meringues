<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingCheckout extends Model
{
    protected $table = 'pending_checkouts';

    protected $fillable = ['reference', 'user_id', 'montant', 'mode_livraison', 'lignes'];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'lignes'  => 'array',
        ];
    }
}
