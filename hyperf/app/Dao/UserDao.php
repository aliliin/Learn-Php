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
namespace App\Dao;

use App\Model\User;

class UserDao
{
    public function first(int $id)
    {
        return User::query()->find($id);
    }

    public function firstCache(int $id)
    {
        return User::findFromCache($id);
    }

    public function find(array $ids)
    {
        return User::query()->find($ids);
    }

    public function findCache(array $ids)
    {
        return User::findManyFromCache($ids);
    }

    public function incr()
    {
        // $user = $this->first(1);
        $user = $this->firstCache(1);
        return $user->increment('gender');
    }
}
