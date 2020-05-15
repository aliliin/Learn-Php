<?php


namespace App\models;



use Core\lib\BaseModel;

class UsersModel extends BaseModel
{
    protected $table = "users";
    protected  $connection = "slave";

}