<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;

class CharacterService
{
    /**
     * @param array $data
     * @return Character
     */
    public function updateOrCreate(array $data): Character
    {
        return Character::updateOrCreate([
            'id' => $data['id'],
        ], [
            'name' => $data['name'],
            'status' => $data['status'],
            'species' => $data['species'],
            'type' => $data['type'],
            'gender' => $data['gender'],
            'image' => $data['image'],
            'url' => $data['url'],
        ]);
    }
}
