<?php
define('APP_ROOT', __DIR__);
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        //api token
        'apiTokens' => [
            '1111111111111' //TODO test token
        ],

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'clickhouse' => [
            'connection' => [
                'host' => '127.0.0.1',
                'port' => '8123',
                'username' => 'default',
                'password' => ''
            ],
            'database' => 'default'
        ],
    ],
];
