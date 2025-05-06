<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use App\Enums\Roles;
trait RoleTrait
{
    public function isAdmin()
    {
        return Auth::user()->role->value === Roles::Admin;
    }

    public function isUser()
    {
        return Auth::user()->role->value === Roles::User;
    }
    public function isAuthorizedUser()
    {
        return  (Auth::user()->role->value === Roles::Admin || Auth::user()->role->value === Roles::User);
    }
}
