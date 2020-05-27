<?php

namespace App\Models;

class User
{
    protected $firstName;

    protected $lastName;

    protected $email;

    public function setFirstName($firstName)
    {
        $this->firstName = trim($firstName);
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($LastName)
    {
        $this->lastName = trim($LastName);
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFullName()
    {
        return "$this->firstName $this->lastName";
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getEmailVariables()
    {
        return [
            'full_name' => $this->getFullName(),
            'email' => $this->email,
        ];
    }
}
