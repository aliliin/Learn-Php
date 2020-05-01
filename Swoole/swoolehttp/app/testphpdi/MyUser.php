<?php


namespace App\testphpdi;


class MyUser
{
    private $mydb;

    public function __construct(myDB $DB)
    {
        $this->mydb = $DB;
    }

    public function getAll(): array // 业务代码
    {
        return $this->mydb->queryForRows('select * form users');
    }
}