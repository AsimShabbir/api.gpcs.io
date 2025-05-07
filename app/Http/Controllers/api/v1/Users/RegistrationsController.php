<?php

namespace App\Http\Controllers\api\v1\Users;

use App\Http\Controllers\api\v1\BaseController as BaseController;
use App\Http\Requests\Users\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function verify_user_otp(Request $request)
    {
        $otp_code = $request->otp_code;
        $contact_number = $request->contact_number;
        $user = User::where('contact_number','=',$contact_number)->where('otp_code','=',$otp_code)->first();
        if($user)
        {
            $user->verified = 1;
            $user->save();
            return $this->renderResponse('Success',['success' => 'OTP verified successfully.']);
        }
        return $this->renderResponse('Error',['error' => 'Invalid OTP / Contact number']);
    }


}
