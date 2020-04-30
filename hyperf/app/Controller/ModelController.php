<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Dao\UserDao;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="model")
 */
class ModelController extends AbstractController
{
    /**
     * @Inject
     * @var UserDao
     */
    protected $dao;

    public function first()
    {
        $model = $this->dao->first(1);
        return $model->number;
    }

    public function firstCache()
    {
        $model = $this->dao->firstCache(1);
        return $model->number;
    }

    public function find()
    {
        return $this->dao->find([1, 2]);
    }

    public function findCache()
    {
        return $this->dao->findCache([1, 2]);
    }

    public function incr()
    {
        return $this->dao->incr();
    }
}
