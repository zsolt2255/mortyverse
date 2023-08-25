<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Episode;
use App\Services\CharacterService;
use App\Services\EpisodeService;
use App\Traits\HttpRequestsTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class FetchEpisodes extends Command
{
    use HttpRequestsTrait;

    public function __construct(
        private readonly EpisodeService $episodeService,
        private readonly CharacterService $characterService
    ) {
        parent::__construct();
    }


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:episodes {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch episodes and characters from Rick and Morty API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Fetching episodes and characters...');
        $this->newLine();

        if (! $this->episodeService->lock()) {
            return Command::FAILURE;
        }

        $this->episodeService->truncateTables();

        $this->fetchEpisodesAndCharacters(
            $this->episodeService->rickAndMortyApiUrl('episodes')
        );

        $this->newLine();
        $this->info('Episodes and characters fetched and saved.');

        $this->episodeService->unLock();

        return Command::SUCCESS;
    }

    /**
     * @param string $url
     * @return void
     */
    private function fetchEpisodesAndCharacters(string $url): void
    {
        $response = $this->makeHttpRequest($url);
        if (! isset($response['error'])) {
            $episodes = $response['results'];
            $info = $response['info'];
            $options = $this->options();

            $progressBar = $this->createProgressBar(count($episodes));

            foreach ($episodes as $episodeData) {
                DB::transaction(function () use ($episodeData, $progressBar) {
                    $progressBar->setMessage('Episode: ' . $episodeData['name']);
                    $airDate = Carbon::parse();

                    $episode = $this->insertEpisodes($episodeData, $airDate);
                    $charactersResponse = $this->makeHttpRequest($episodeData['url']);

                    if (! isset($charactersResponse['error'])) {
                        $characters = $charactersResponse['characters'];

                        foreach ($characters as $characterUrl) {
                            $characterData = $this->makeHttpRequest($characterUrl);

                            $character = $this->insertCharacters($characterData);

                            $episode->characters()->attach($character->id);
                        }
                    }

                    $progressBar->advance();
                });
            }

            $progressBar->finish();
            $this->newLine();

            if ($info['next'] !== null) {
                if ($options['all']) {
                    $this->nextEpisode($info);
                } else {
                    $continue = $this->confirm('Continue?', true);

                    if ($continue) {
                        $this->nextEpisode($info);
                    }
                }
            }
        }
    }

    /**
     * @param int $max
     * @return ProgressBar
     */
    private function createProgressBar(int $max): ProgressBar
    {
        $progressBar = $this->output->createProgressBar($max);
        $progressBar->setFormat("%message%\n %current%/%max% [%bar%] %percent:3s%%");
        $progressBar->setRedrawFrequency(1);

        return $progressBar;
    }

    /**
     * @param array $episodeData
     * @param Carbon $airDate
     * @return Episode
     */
    private function insertEpisodes(array $episodeData, Carbon $airDate): Episode
    {
        return $this->episodeService->updateOrCreate([
            'id' => data_get($episodeData, 'id'),
            'name' => data_get($episodeData, 'name'),
            'air_date' => $airDate->format('Y-m-d'),
            'episode' => data_get($episodeData, 'episode'),
            'url' => data_get($episodeData, 'url'),
        ]);
    }

    /**
     * @param array $characterData
     * @return Character
     */
    private function insertCharacters(array $characterData): Character
    {
        return $this->characterService->updateOrCreate([
            'id' => data_get($characterData, 'id'),
            'name' => data_get($characterData, 'name'),
            'status' => data_get($characterData, 'status'),
            'species' => data_get($characterData, 'species'),
            'type' => data_get($characterData, 'type'),
            'gender' => data_get($characterData, 'gender'),
            'image' => data_get($characterData, 'image'),
            'url' => data_get($characterData, 'url'),
        ]);
    }

    /**
     * @param array $info
     * @return void
     */
    private function nextEpisode(array $info): void
    {
        $this->info('Next episode: ' . $info['next']);
        $this->fetchEpisodesAndCharacters($info['next']);
    }
}
