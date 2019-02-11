<?php

namespace App;

use App\Database\IDbManager;
use App\Database\Manager;
use App\Services\CliService;
use App\Services\DataHitsAggregateService;
use App\Services\DataHitsPushService;
use App\Services\MigrationService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

class Dependencies
{
    public static function init(\Slim\App $app = null)
    {
        if($app == null) {
            $app = App::getInstance()->getSlim();
        }
        $container = $app->getContainer();

        $container['renderer'] = function ($c) {
            $settings = $c->get('settings')['renderer'];
            return new PhpRenderer($settings['template_path']);
        };

        $container[LoggerInterface::class] = $container['logger'] = function ($c) {
            $settings = $c->get('settings')['logger'];
            $logger = new Logger($settings['name']);
            $logger->pushProcessor(new UidProcessor());
            $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
            return $logger;
        };

        $container[IDbManager::class] = function($c){
            $manager = new Manager();
            return $manager;
        };


        $container[DataHitsPushService::class] = function($c){
            return new DataHitsPushService($c->get(IDbManager::class));
        };


        $container[DataHitsAggregateService::class] = function($c){
            return new DataHitsAggregateService($c->get(IDbManager::class));
        };

        $container[MigrationService::class] = $container['migrations'] = function($c){
            $migrationService = new MigrationService();
            return $migrationService;
        };

        $container[CliService::class] = $container['console'] = function($c){
            $cli = new CliService();
            return $cli;
        };

        $container['httpClient'] = new \GuzzleHttp\Client();
    }
}