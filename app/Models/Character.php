<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static updateOrCreate(array $array, array $array1)
 */
class Character extends Model
{
    public const ALIVE_STATUS = 'Alive';
    public const DEAD_STATUS = 'Dead';
    public const UNKNOWN_STATUS = 'unknown';
    public const STATUSES = [
        self::ALIVE_STATUS,
        self::DEAD_STATUS,
        self::UNKNOWN_STATUS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
        'species',
        'type',
        'gender',
        'image',
        'url',
    ];

    /**
     * @return BelongsToMany
     */
    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class, 'character_episode', 'character_id', 'episode_id');
    }
}
