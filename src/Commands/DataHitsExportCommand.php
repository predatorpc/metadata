<?php

namespace App\Commands;

use App\App;
use App\Database\IDbManager;
use App\Database\Repository\DataHitsRepository;
use App\Services\DataHitsPushService;
use GuzzleHttp\Psr7\Response;

class DataHitsExportCommand extends Command
{
    private $url;
    private $hitsPerTick = 50000;

    public function __construct()
    {
        $this->url = 'https://metacpa.ru/api/hit/get?key={TestKey}=' . $this->hitsPerTick;
    }

    public function run($argv)
    {
        if ($argv[count($argv) - 1] === 'deferred') {
            sleep(30);
        }
        $client = App::getInstance()->getSlim()->getContainer()->get('httpClient');
        $dbManager = App::getInstance()->getSlim()->getContainer()->get(IDbManager::class);
        $repo = $dbManager->getRepository(DataHitsRepository::class);
        $maxId = $repo->getMaxExtId() + 1;

            $response = $client->get($this->url . '&start=' . $maxId, ['timeout' => 60]);

        if (!($response instanceof Response)) {
            throw new \HttpRequestException('Response is not ' . Response::class);
        }
        if ($response->getStatusCode() != 200) {
            throw new \HttpRequestException('Response code is ' . $response->getStatusCode());
        }
        $hits = \GuzzleHttp\json_decode($response->getBody(), true);

        $pushService = App::getInstance()->getSlim()->getContainer()->get(DataHitsPushService::class);
        $pushService->insert($hits);

        return 1;

    }
}