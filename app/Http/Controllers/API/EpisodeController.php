<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Episode\EpisodeResourceCollection;
use App\Models\Episode;
use App\Repositories\Eloquent\Contracts\EpisodeRepositoryInterface;
use App\Services\EpisodeService;
use Illuminate\Http\JsonResponse;

class EpisodeController extends Controller
{
    /**
     * @param EpisodeRepositoryInterface $episodeRepository
     * @param EpisodeService $episodeService
     */
    public function __construct(
        private readonly EpisodeRepositoryInterface $episodeRepository,
        private readonly EpisodeService $episodeService,
    ) {}

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $episodes = $this->episodeRepository->all();

        return (new EpisodeResourceCollection($episodes))->response();
    }

    /**
     * @param Episode $episode
     * @return mixed
     */
    public function characters(Episode $episode): mixed
    {
        return $episode->characters;
    }

    /**
     * @return JsonResponse
     */
    public function sync(): JsonResponse
    {
        return $this->episodeService->sync();
    }
}
