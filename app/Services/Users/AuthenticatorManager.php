<?php

namespace App\Services\Users;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User;
use App\Http\Requests\Users\AuthenticatorRequest;
use Illuminate\Support\Facades\DB;

Class AuthenticatorManager
{
    public function signin(AuthenticatorRequest $request)
    {
        //dd(Auth);

        $authUser=null;
        $password = "test";
        if(Auth::attempt(['contact_number' => $request->contact_number, 'password' => $password])){
            $authUser = Auth::user();
        }

        return $authUser;
    }
}
