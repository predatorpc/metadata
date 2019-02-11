<?php
namespace App\Services;

use App\Commands\Command;
use App\Exceptions\InvalidArgumentException;

class CliService implements IService
{
    private $commands = [];


    public function run(array $argv){
        if(array_key_exists(1, $argv) && class_exists('App\Commands\\' . $argv[1] . 'Command')) {
            $commandClass = 'App\Commands\\' . $argv[1] . 'Command';
            $command = new $commandClass;
            if($command instanceof Command){
                try {
                    return $command->run($argv);
                } catch(\Exception $ex){
                    return $ex->getMessage();
                }
            } else {
                throw new InvalidArgumentException('Command must be extends ' . Command::class);
            }

        } else {
            throw new InvalidArgumentException('Invalid command parameter');
        }
    }
}