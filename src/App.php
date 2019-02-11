<?php
namespace App;

class App
{
    protected static $instance;
    protected $slim;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    /**
     * @return \Slim\App
     */
    public function getSlim()
    {
        return $this->slim;
    }

    protected function __construct()
    {
        require __DIR__ . '/../vendor/autoload.php';
        session_start();

        // Instantiate the app
        $settings = require __DIR__ . '/../src/settings.php';
        $this->slim = new \Slim\App($settings);

        Dependencies::init($this->slim);

        Middleware::init($this->slim);

        $routes = new Routes($this->slim);
        $routes->init();
    }
}