<?php

namespace App\Services\Users;

use App\Models\User;

class UserListService 
{
    public function list()
    {
        return User::all();
    }

    public function activeOrDesactive($data)
    {
        $user = User::findOrfail($data->user_id);

        $user->is_active = $data->action;

        $user->save();

        return $user;
    }
}