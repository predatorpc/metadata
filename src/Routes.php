<?php

namespace App;

use App\Filters\HitsStatDtoFilter;
use App\Services\DataHitsAggregateService;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\DataHitsPushService;
use App\Database\Repository\DataHitsRepository;
use App\Database\IDbManager;

class Routes
{
    public $app;

    public function __construct(\Slim\App $app)
    {
        $this->app = $app;
    }

    public function init()
    {
        //Test metadata-v2
        $this->app->get('/test', function (Request $request, Response $response, array $args) {
            $response->getBody()->write('running');
        });

        $this->app->get('/hit/{click_id}', function (Request $request, Response $response, array $args) { //TODO удалить после  тестов
            $dbManager = App::getInstance()->getSlim()->getContainer()->get(IDbManager::class);
            $repo = $dbManager->getRepository(DataHitsRepository::class);
            $rawString = $repo->getHitByClickId($args['click_id']);
            $response->write($rawString);
            $response->withAddedHeader('Content-Type', 'application/json');
            return $response->withStatus(200);
        });

        $this->app->get('/hits/', function (Request $request, Response $response, array $args) {
            $filter = HitsStatDtoFilter::load($request->getQueryParams());
            $aggService = $this->get(DataHitsAggregateService::class);
            try {
                $result = $aggService->getStatByFilter($filter);
                $response = $response->withAddedHeader('Content-Type', 'application/json');
                return $response->write(\GuzzleHttp\json_encode($result));
            } catch (\Exception $ex) {
                $this->get(LoggerInterface::class)->info($ex->getMessage());
                return $response->withStatus(400);
            }
        });

        // API method for create hits
        $this->app->post('/set/hits', function (Request $request, Response $response, array $args) {
            $hits = $request->getParams();
            try {
                $pushService = App::getInstance()->getSlim()->getContainer()->get(DataHitsPushService::class);
                $pushService->insert($hits);
                $response->withAddedHeader('Content-Type', 'application/json');
                $response->write(\GuzzleHttp\json_encode(['error' => false, 'status' => 'OK']));
                return $response->withStatus(200);
            } catch (\Exception $ex) {
                $this->get(LoggerInterface::class)->info($ex->getMessage());
                $response->withAddedHeader('Content-Type', 'application/json');
                $response->write(\GuzzleHttp\json_encode(['error' => true, 'status' => 'Error']));
                return $response->withStatus(400);
            }
        });

    }
}