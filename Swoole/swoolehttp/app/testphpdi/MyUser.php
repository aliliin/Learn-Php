<?php


namespace App\testphpdi;


use DI\Annotation\Inject;

class MyUser
{
    private $mydb;

    /**
     * @Inject()
     * @param MyDB $DB
     */
    public function __construct(MyDB $DB)
    {
        $this->mydb = $DB;
    }

    public function getAll(): array // 业务代码
    {
        return $this->mydb->queryForRows('select * form users');
    }
}