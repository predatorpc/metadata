<?php
namespace App\Services;

use App\Exceptions\InvalidArgumentException;
use App\Exceptions\PathNotFoundException;
use App\Migrations\Migration;

class MigrationService implements IService
{
    private $migrationsPath = APP_ROOT . DIRECTORY_SEPARATOR . 'Migrations' . DIRECTORY_SEPARATOR;

    public function up($migrationId)
    {
        $migration = $this->getMigration($migrationId);
        return $migration->up();
    }

    public function down($migrationId){
        $migration = $this->getMigration($migrationId);
        return $migration->down();
    }

    public function getMigration($migrationId){
        $migrationFiles = scandir($this->migrationsPath);
        $migrationsList = array();
        if($migrationFiles) {
            array_map(function ($file) use (&$migrationsList) {
                if(is_file($this->migrationsPath . $file) && intval($file) > 0){
                    $fileNameParts = explode('_', $file);
                    if(count($fileNameParts) > 1){
                        $migrationsList[intval($fileNameParts[0])] = $file;
                    } else {
                        throw new InvalidArgumentException("Invalid migration file name: " . $file);
                    }
                }
            }, $migrationFiles);
        } else {
            throw new PathNotFoundException('Empty or invalid path for migrations:' . $this->migrationsPath);
        }

        print_r($migrationId);
        echo PHP_EOL;
        print_r($migrationsList);
        echo PHP_EOL;

        if(array_key_exists($migrationId, $migrationsList) && is_readable($this->migrationsPath . $migrationsList[$migrationId])){
            require_once $this->migrationsPath . $migrationsList[$migrationId];

            $class = $this->getClass($this->migrationsPath . $migrationsList[$migrationId]);
            $migration = new $class;
            if($migration instanceof Migration){
                return $migration;
            } else {
                throw new InvalidArgumentException('Migration ' . $class . ' must be extended ' . Migration::class);
            }

        } else {
            throw new InvalidArgumentException('Migrations #' . $migrationId . ' not available');
        }
    }


    private function getClass($filePath)
    {
        //Grab the contents of the file
        $contents = file_get_contents($filePath);

        //Start with a blank namespace and class
        $namespace = $class = "";

        //Set helper values to know that we have found the namespace/class token and need to collect the string values after them
        $getting_namespace = $getting_class = false;

        //Go through each token and evaluate it as necessary
        foreach (token_get_all($contents) as $token) {

            //If this token is the namespace declaring, then flag that the next tokens will be the namespace name
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $getting_namespace = true;
            }

            //If this token is the class declaring, then flag that the next tokens will be the class name
            if (is_array($token) && $token[0] == T_CLASS) {
                $getting_class = true;
            }

            //While we're grabbing the namespace name...
            if ($getting_namespace === true) {

                //If the token is a string or the namespace separator...
                if(is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {

                    //Append the token's value to the name of the namespace
                    $namespace .= $token[1];

                }
                else if ($token === ';') {

                    //If the token is the semicolon, then we're done with the namespace declaration
                    $getting_namespace = false;

                }
            }

            //While we're grabbing the class name...
            if ($getting_class === true) {

                //If the token is a string, it's the name of the class
                if(is_array($token) && $token[0] == T_STRING) {

                    //Store the token's value as the class name
                    $class = $token[1];

                    //Got what we need, stope here
                    break;
                }
            }
        }

        //Build the fully-qualified class name and return it
        return $namespace ? $namespace . '\\' . $class : $class;

    }
}

