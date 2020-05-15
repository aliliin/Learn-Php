<?php


namespace Core\init;

use Core\annotations\Bean;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * @Bean()
 * Class DBHandlers
 * @method \Illuminate\Database\Query\Builder table(string $table, string|null $as = null, string|null $connection = null)
 */
class MyDB
{
    private $laDB;

    private $dbSource = 'default';

    /**
     * @return string
     */
    public function getDbSource(): string
    {
        return $this->dbSource;
    }

    /**
     * @param string $dbSource
     */
    public function setDbSource(string $dbSource): void
    {
        $this->dbSource = $dbSource;
    }


    public function __construct()
    {
        global $GLOBALS_CONFIGS;

        if (isset($GLOBALS_CONFIGS['db'])) {
            $configs = $GLOBALS_CONFIGS['db'];

            $this->laDB = new Capsule();

            foreach ($configs as $key => $v) {
                $this->laDB->addConnection($v, $key);
            }

            $this->laDB->setAsGlobal();

            $this->laDB->bootEloquent();
        }
    }

    public function __call(string $methodName, $arguments)
    {
        return $this->laDB::connection($this->dbSource)->$methodName(...$arguments);
    }


}