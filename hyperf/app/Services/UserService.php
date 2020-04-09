<?php

namespace App\Services;

class UserService
{
    public function getUsername(int $uid): string
    {
        if ($uid == 11) {
            return "Aliliin";
        } else {
            return '不存在';
        }
    }
}
