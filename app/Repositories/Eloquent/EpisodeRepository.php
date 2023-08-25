<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Episode;
use App\Repositories\Eloquent\Contracts\EpisodeRepositoryInterface;

class EpisodeRepository extends EloquentRepository implements EpisodeRepositoryInterface
{
    /**
     * @param Episode $model
     */
    public function __construct(Episode $model)
    {
        parent::__construct($model);
    }
}
