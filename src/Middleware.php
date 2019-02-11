<?php
namespace App;

class Middleware
{
    public static function init(\Slim\App $app){

        // authorization by token post request
        $app->add(function ($request, $response, $next) {
            $header = $request->getHeader('HTTP_X-Token');
            $container = App::getInstance()->getSlim()->getContainer();
            $apiTokens = $settings = $container->get('settings')['apiTokens'];

            if($request->isPost() && (empty($header) || !in_array($header[0], $apiTokens))){
                $response->withAddedHeader('Content-Type', 'application/json');
                $response->write(\GuzzleHttp\json_encode(['error' => true, 'status' => 'Invalid Token']));
                return $response->withStatus(400);
            }
            return $next($request, $response);
        });

    }
}