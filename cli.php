<?php
require __DIR__ . '/vendor/autoload.php';
$appPath = implode(DIRECTORY_SEPARATOR, array(
    __DIR__,
    'src',
    'App.php'
));

include_once $appPath;

$app = App\App::getInstance();
echo $app->getSlim()->getContainer()->get('console')->run($argv);