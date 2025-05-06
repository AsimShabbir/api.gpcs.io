<?php

namespace App\Http\Controllers\api\v1\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\v1\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Services\Users\AuthenticatorManager;
use App\Http\Requests\Users\AuthenticatorRequest;
use App\Http\Resources\UserResource;
use App\Enums\StatusCode;
class AuthController  extends BaseController
{
    protected $userAuthenticator;
    //
    public function __construct(AuthenticatorManager $userAuthenticator)
    {

        $this->userAuthenticator = $userAuthenticator;
    }

    public function signin(AuthenticatorRequest $request)
    {
       $authUser = $this->userAuthenticator->signin($request);
       if(isset($authUser))
       {
            $authUser->token= $authUser->createToken(env('APP_NAME'))->plainTextToken;
            $user = new User();
            $user = $authUser;
            return $this->renderResponse(new UserResource($user), 'User signed in');
       }
       else
       {
            return $this->renderResponseWithErrors('Unauthorized.', ['error'=>'Unauthorized'],StatusCode::UNAUTHORIZED);
       }
    }
    public function logout()
    {
        Auth::logout();
        return $this->renderResponse('Success',['success' => 'logout successfully']);
    }

}
