<?php

namespace common\modules\core\components;

use Yii;
use yii\db\Query;
use yii\di\Instance;
use yii\db\Connection;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class Migrate extends \yii\base\Component
{
    public $migrationTable = '{{%migration}}';
    
    public $db = 'db';
    
    public $migrationPath = '@app/migrations';
    
    const BASE_MIGRATION = 'm000000_000000_base';
    
    
    public function init()
    {
        $path = Yii::getAlias($this->migrationPath);
        if (!is_dir($path)) {
            throw new Exception("Migration failed. Directory specified in migrationPath doesn't exist: {$this->migrationPath}");
        }
        $this->migrationPath = $path;
        $this->db = Instance::ensure($this->db, Connection::className());
    }
    
    
    public function up($limit = 0)
    {
        $migrations = $this->getNewMigrations();
        if (empty($migrations)) {
            Yii::info('No new migrations found. Your system is up-to-date.\n', __METHOD__);
            return true;
        }

        $total = count($migrations);
        $limit = (int) $limit;
        if ($limit > 0) {
            $migrations = array_slice($migrations, 0, $limit);
        }

        $n = count($migrations);
        if ($n === $total) {
            Yii::info("Total $n new " . ($n === 1 ? 'migration' : 'migrations') . " to be applied:\n", __METHOD__);
        } else {
            Yii::info("Total $n out of $total new " . ($total === 1 ? 'migration' : 'migrations') . " to be applied:\n", __METHOD__);
        }

        foreach ($migrations as $migration) {
            Yii::info("\t$migration\n", __METHOD__);
        }

        $applied = 0;
        foreach ($migrations as $migration) {
            if (!$this->migrateUp($migration)) {
                Yii::info("\n$applied from $n " . ($applied === 1 ? 'migration was' : 'migrations were') ." applied.\n", __METHOD__);
                Yii::info("\nMigration failed. The rest of the migrations are canceled.\n", __METHOD__);

                return false;
            }
            $applied++;
        }

        Yii::info("\n$n " . ($n === 1 ? 'migration was' : 'migrations were') ." applied.\n", __METHOD__);
        Yii::info("\nMigrated up successfully.\n", __METHOD__);
    }
    
    
    public function down($limit = 1)
    {
        if ($limit === 'all') {
            $limit = null;
        } else {
            $limit = (int) $limit;
            if ($limit < 1) {
                throw new Exception('The step argument must be greater than 0.');
            }
        }

        $migrations = $this->getMigrationHistory($limit);

        if (empty($migrations)) {
            Yii::info("No migration has been done before.\n", __METHOD__);

            return true;
        }

        $migrations = array_keys($migrations);

        $n = count($migrations);
        Yii::info("Total $n " . ($n === 1 ? 'migration' : 'migrations') . " to be reverted:\n", __METHOD__);
        foreach ($migrations as $migration) {
            Yii::info("\t$migration\n", __METHOD__);
        }

        $reverted = 0;
        foreach ($migrations as $migration) {
            if (!$this->migrateDown($migration)) {
                Yii::info("\n$reverted from $n " . ($reverted === 1 ? 'migration was' : 'migrations were') ." reverted.\n", __METHOD__);
                Yii::info("\nMigration failed. The rest of the migrations are canceled.\n", __METHOD__);

                return false;
            }
            $reverted++;
        }
        Yii::info("\n$n " . ($n === 1 ? 'migration was' : 'migrations were') ." reverted.\n", __METHOD__);
        Yii::info("\nMigrated down successfully.\n", __METHOD__);
    }
    
    
    protected function migrateUp($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }

        Yii::info("*** applying $class\n", __METHOD__);
        $start = microtime(true);
        $migration = $this->createMigration($class);
        if ($migration->up() !== false) {
            $this->addMigrationHistory($class);
            $time = microtime(true) - $start;
            Yii::info("*** applied $class (time: " . sprintf('%.3f', $time) . "s)\n\n", __METHOD__);

            return true;
        } else {
            $time = microtime(true) - $start;
            Yii::info("*** failed to apply $class (time: " . sprintf('%.3f', $time) . "s)\n\n", __METHOD__);

            return false;
        }
    }
    
    
    protected function migrateDown($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }

        Yii::info("*** reverting $class\n", __METHOD__);
        $start = microtime(true);
        $migration = $this->createMigration($class);
        if ($migration->down() !== false) {
            $this->removeMigrationHistory($class);
            $time = microtime(true) - $start;
            Yii::info("*** reverted $class (time: " . sprintf('%.3f', $time) . "s)\n\n", __METHOD__);

            return true;
        } else {
            $time = microtime(true) - $start;
            Yii::info("*** failed to revert $class (time: " . sprintf('%.3f', $time) . "s)\n\n", __METHOD__);

            return false;
        }
    }
    
    
    protected function getNewMigrations()
    {
        $applied = [];
        foreach ($this->getMigrationHistory(null) as $version => $time) {
            $applied[substr($version, 1, 13)] = true;
        }

        $migrations = [];
        $handle = opendir($this->migrationPath);
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = $this->migrationPath . DIRECTORY_SEPARATOR . $file;
            if (preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches) && !isset($applied[$matches[2]]) && is_file($path)) {
                $migrations[] = $matches[1];
            }
        }
        closedir($handle);
        sort($migrations);

        return $migrations;
    }
    
    
    protected function getMigrationHistory($limit)
    {
        if ($this->db->schema->getTableSchema($this->migrationTable, true) === null) {
            $this->createMigrationHistoryTable();
        }
        $query = new Query;
        $rows = $query->select(['version', 'apply_time'])
            ->from($this->migrationTable)
            ->orderBy('apply_time DESC, version DESC')
            ->limit($limit)
            ->createCommand($this->db)
            ->queryAll();
        $history = ArrayHelper::map($rows, 'version', 'apply_time');
        unset($history[self::BASE_MIGRATION]);

        return $history;
    }
    
    
    protected function createMigrationHistoryTable()
    {
        $tableName = $this->db->schema->getRawTableName($this->migrationTable);
        $this->db->createCommand()->createTable($this->migrationTable, [
            'version' => 'varchar(180) NOT NULL PRIMARY KEY',
            'apply_time' => 'integer',
        ])->execute();
        $this->db->createCommand()->insert($this->migrationTable, [
            'version' => self::BASE_MIGRATION,
            'apply_time' => time(),
        ])->execute();
    }
    
    
    protected function createMigration($class)
    {
        $file = $this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
        require_once($file);

        return new $class(['db' => $this->db]);
    }
    
    
    protected function addMigrationHistory($version)
    {
        $command = $this->db->createCommand();
        $command->insert($this->migrationTable, [
            'version' => $version,
            'apply_time' => time(),
        ])->execute();
    }
    
    
    protected function removeMigrationHistory($version)
    {
        $command = $this->db->createCommand();
        $command->delete($this->migrationTable, [
            'version' => $version,
        ])->execute();
    }
}