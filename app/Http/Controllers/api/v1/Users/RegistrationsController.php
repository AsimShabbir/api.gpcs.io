<?php

namespace App\Http\Controllers\api\v1\Users;

use App\Http\Controllers\api\v1\BaseController as BaseController;
use App\Http\Requests\Users\RegistrationRequest;
//use App\Services\Users\SignupStatusManager;

class RegistrationsController extends BaseController
{
    //
    public function __construct()
    {
    }
    public function registration(RegistrationRequest $request)
    {
        $user = $request->save();
  //      $service = new SignupStatusManager($user);
    //    $service->execute();
        return $this->renderResponse('Success',['success' => 'You are register successfully.']);
    }
}
