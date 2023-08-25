<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;
use App\Models\Episode;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EpisodeService
{
    /**
     * @return JsonResponse
     */
    public function sync(): JsonResponse
    {
        ini_set('max_execution_time', 0);
        $command = Artisan::call('fetch:episodes --all');

        if ($command === 0) {
            return response()->json([
                'message' => 'Fetch successful',
                'success' => true,
            ]);
        }

        return response()->json([
            'message' => 'Fetch failed',
            'success' => false,
        ], 500);
    }

    /**
     * @return bool
     */
    public function lock(): bool
    {
        if (! File::exists(storage_path('app/fetch_episodes_lock'))) {
            File::put(storage_path('app/fetch_episodes_lock'), (string) now());
        } else {
            $fileData = File::get(storage_path('app/fetch_episodes_lock'));
            $currentTime = now();

            $target = Carbon::parse($fileData);
            $diffInMinutes = $currentTime->diffInMinutes($target);

            if ($diffInMinutes > 5) {
                File::delete(storage_path('app/fetch_episodes_lock'));
                File::put(storage_path('app/fetch_episodes_lock'), (string) now());

                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function unLock(): void
    {
        File::delete(storage_path('app/fetch_episodes_lock'));
    }

    /**
     * @return void
     */
    public function truncateTables(): void
    {
        Character::truncate();
        Episode::truncate();
        DB::table('character_episode')->truncate();
    }

    /**
     * @param string $endpoint
     * @return string|null
     */
    public function rickAndMortyApiUrl(string $endpoint): ?string
    {
        $urls = config('rickandmortyapi');

        if (isset($urls[$endpoint])) {
            return $urls['url'] . '/' . $urls[$endpoint];
        }

        return null;
    }

    /**
     * @param array $data
     * @return Episode
     */
    public function updateOrCreate(array $data): Episode
    {
        return Episode::updateOrCreate([
            'id' => $data['id'],
        ], [
            'name' => $data['name'],
            'air_date' => $data['air_date'],
            'episode' => $data['episode'],
            'url' => $data['url'],
        ]);
    }
}
