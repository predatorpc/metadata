<?php
namespace App\Commands;

use App\App;
use App\Exceptions\InvalidArgumentException;

class MigrationManagerCommand extends Command
{
    public function run($argv){
        if(array_key_exists(2, $argv) && method_exists($this, 'method'. strtoupper($argv[2]))){
            $method = 'method'.$argv[2];
            $this->$method($argv);
        } else {
            throw new InvalidArgumentException('Command not found');
        }
    }

    public function methodUp($argv){
        $migrationService = App::getInstance()->getSlim()->getContainer()->get('migrations');
        if(array_key_exists(3, $argv)) {
            $migrationId = $argv[3];
        } else {
            throw new InvalidArgumentException('Migration id not set');
        }
        $migrationService->up($migrationId);
    }
    public function methodCreateTables($argv){
        $migrationService = App::getInstance()->getSlim()->getContainer()->get('migrations');
        $counter = 1;
        while(true) {
            try {
                $result = $migrationService->up($counter);
                if($result){
                    $counter++;
                } else {
                    throw new InvalidArgumentException('Error into migration #' . $counter);
                }
            } catch (InvalidArgumentException $ex) {
                break;
            }
        }
    }
}