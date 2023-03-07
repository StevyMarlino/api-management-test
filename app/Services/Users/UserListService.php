<?php

namespace App\Services\Users;

use App\Models\User;

class UserListService 
{
    public function list()
    {
        return User::all();
    }
}