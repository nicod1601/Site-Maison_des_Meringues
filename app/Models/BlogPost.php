<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'content',
        'emoji',
        'image_url',   // Lien externe
        'image_path',  // Fichier uploadé → public/fichier/image/publication/
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(BlogReaction::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs utiles
    |--------------------------------------------------------------------------
    */

    public function getLikesCountAttribute(): int
    {
        return $this->reactions->where('type', 'like')->count();
    }

    public function getDislikesCountAttribute(): int
    {
        return $this->reactions->where('type', 'dislike')->count();
    }

    /** URL finale de l'image : priorité au fichier uploadé, sinon le lien externe */
    public function getImageDisplayUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return asset($this->image_path);
        }

        return $this->image_url ?: null;
    }

    /** Réaction de l'utilisateur connecté ('like' | 'dislike' | null) */
    public function getUserReactionAttribute(): ?string
    {
        if (!auth()->check()) return null;

        $reaction = $this->reactions
            ->where('user_id', auth()->id())
            ->first();

        return $reaction?->type;
    }
}
