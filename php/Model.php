<?php

abstract class Model
{
    const EXISTS_VALIDATE = 1;

    abstract public function test();

    public function validate()
    {
        return self::EXISTS_VALIDATE;
    }

    final public function conn()
    {
        return '此方法不支持子类重写';
    }
}

class UserModel extends Model
{
    public function getUsers()
    {
        return $this->validate();
    }
    public function test()
    {
    }
}

echo (new UserModel())->getUsers();
