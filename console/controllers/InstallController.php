<?php

namespace console\controllers;

use yii\console\controllers\MigrateController;

class InstallController extends MigrateController
{
    public $migrationPaths;
    
    protected function getNewMigrations()
    {
        $applied = [];
        foreach ($this->getMigrationHistory(null) as $version => $time) {
            $applied[substr($version, 1, 13)] = true;
        }

        $migrations = [];
        $dir = \Yii::getAlias('@common/modules');
        $handle = opendir($dir);
        if ($handle === false) {
            throw new InvalidParamException("Unable to open directory: $dir");
        }
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..' || is_file($file)) {
                continue;
            }
            
            $migrationPath = $dir . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . 'migrations';
            if(!file_exists($migrationPath)) {
                continue;
            }
            $migrationHandle = opendir($migrationPath);
            while (($migrationFile = readdir($migrationHandle)) !== false) {
                if ($migrationFile === '.' || $migrationFile === '..') {
                    continue;
                }
                $path = $migrationPath . DIRECTORY_SEPARATOR . $migrationFile;
                if (preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $migrationFile, $matches) && !isset($applied[$matches[2]]) && is_file($path)) {
                    $migrations[] = $matches[1];
                    $this->migrationPaths[$matches[1]] = $path;
                }
            }
            closedir($migrationHandle);
        }
        closedir($handle);
        sort($migrations);
        return $migrations;
    }
    
    protected function createMigration($class)
    {
        $file = $this->migrationPaths[$class];
        require_once($file);

        return new $class();
    }
}