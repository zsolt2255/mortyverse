<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait HttpRequestsTrait
{
    /**
     * @param string $url
     * @return array|mixed
     */
    protected function makeHttpRequest(string $url): mixed
    {
        $response = Http::withoutVerifying()
            ->withOptions([
                'verify' => false
            ])
            ->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => $response->body()
        ];
    }
}
