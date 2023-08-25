<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Character;
use App\Repositories\Eloquent\Contracts\CharacterRepositoryInterface;

class CharacterRepository extends EloquentRepository implements CharacterRepositoryInterface
{
    public function __construct(Character $model)
    {
        parent::__construct($model);
    }
}
