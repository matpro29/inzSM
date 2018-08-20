<?php

namespace App\Entity;

class UserSearch
{
    private $username;

    public function getUserame()
    {
        return $this->username;
    }

    public function setUserame($username)
    {
        $this->username = $username;
    }
}
