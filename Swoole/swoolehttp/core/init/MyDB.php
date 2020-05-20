<?php


namespace Core\init;

use Core\annotations\Bean;
use Core\BeanFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * @Bean()
 * Class DBHandlers
 * @method \Illuminate\Database\Query\Builder table(string $table, string|null $as = null, string|null $connection = null)
 */
class MyDB
{
    private Capsule $laDB;

    private string $dbSource = 'default';

    /**
     * @var PDOPool
     */
    public $pdoPool;

    /**
     * 支持事务的 数据库 对象
     */
    public $translationDB = null;

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


    public function __construct($tranDB = false)
    {
        global $GLOBALS_CONFIGS;

        if (isset($GLOBALS_CONFIGS['db'])) {
            $configs = $GLOBALS_CONFIGS['db'];

            $this->laDB = new Capsule();

            foreach ($configs as $key => $v) {
                $this->laDB->addConnection($v, $key);
//                $this->laDB->addConnection(['driver' => 'mysql'], $key);
            }

            $this->laDB->setAsGlobal();

            $this->laDB->bootEloquent();
        }
        $this->translationDB = $tranDB;
        $this->pdoPool = BeanFactory::getBean(PDOPool::class);
        // 如果有值代表事务了，则直接设置PDO 对象和开启事务
        if ($tranDB) {
            $this->laDB->getConnection($this->dbSource)->setPdo($this->translationDB->db);
            $this->laDB->getConnection($this->dbSource)->beginTransaction();
        }
    }

    public function __call(string $methodName, $arguments)
    {
        // 是否开启事务
        $pdoObject = false;
        $isTranslations = false;
        if ($this->translationDB) {
            $pdoObject = $this->translationDB;
            $isTranslations = true;
        } else {
            $pdoObject = $this->pdoPool->getConnection();
        }

        try {
            if (!$pdoObject) return [];
            // 是否开启事务 只有不在事务中才需要设置 pdo 对象
            if (!$isTranslations) {
                $this->laDB->getConnection($this->dbSource)->setPdo($pdoObject->db);
            }

            return $this->laDB::connection($this->dbSource)->$methodName(...$arguments);
        } catch (\Exception $exception) {
            return null;
        } finally {
            if ($pdoObject && !$isTranslations) {
                $this->pdoPool->close($pdoObject); // 放回链接
            }
        }
    }

    /**
     * 数据库事务 开启
     */
    public function Begin()
    {
        return new self($this->pdoPool->getConnection());
    }

    /**
     *  数据库事务 提交
     * @throws \Throwable
     */
    public function Commit()
    {
        try {
            $this->laDB->getConnection($this->dbSource)->commit();
        } finally {
            if ($this->translationDB) {
                $this->pdoPool->close($this->translationDB);
                $this->translationDB = false;
            }
        }
    }

    /**
     *  数据库事务 回滚
     * @throws \Throwable
     */
    public function Rollback()
    {
        try {
            $this->laDB->getConnection($this->dbSource)->rollback();
        } catch (\Exception $exception) {
            var_dump($exception);
        } finally {
            if ($this->translationDB) {
                $this->pdoPool->close($this->translationDB);
                $this->translationDB = false;
            }
        }
    }

    // 支持 Model 的方式查询数据 ，支持事务
    public function releaseConnection($pdoObject)
    {
        if ($pdoObject && !$this->translationDB) {
            $this->pdoPool->close($pdoObject);
        }
    }

    /**
     * 从链接池中借出来一个链接
     * @return bool|mixed|null
     */
    public function genConnection()
    {
        $pdoObject = null;
        // 是否开启事务
        $isTranslations = false;
        if ($this->translationDB) {
            $pdoObject = $this->translationDB;
            $isTranslations = true;
        } else {
            $pdoObject = $this->pdoPool->getConnection();
        }

        // 是否开启事务 只有不在事务中才需要设置 pdo 对象
        if (!$isTranslations && $pdoObject) {
            $this->laDB->getConnection($this->dbSource)->setPdo($pdoObject->db);
            return $pdoObject;
        }
        return false;
    }

}