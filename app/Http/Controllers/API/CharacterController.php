<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Character\CharacterResource;
use App\Models\Character;
use Illuminate\Http\JsonResponse;

class CharacterController extends Controller
{
    /**
     * @param Character $character
     * @return JsonResponse
     */
    public function show(Character $character): JsonResponse
    {
        return (new CharacterResource($character))->response();
    }
}
