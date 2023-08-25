<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static updateOrCreate(array $array, array $array1)
 */
class Episode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'air_date',
        'episode',
        'url',
    ];

    /**
     * @return BelongsToMany
     */
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_episode', 'episode_id', 'character_id');
    }
}
