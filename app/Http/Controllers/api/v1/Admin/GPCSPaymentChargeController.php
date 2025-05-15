<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Controllers\api\v1\BaseController as BaseController;
use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GpcsPaymentCharge;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GPCSPaymentChargeResource;
use App\Http\Requests\Admin\GpcsPaymentChargeRequest;
class GPCSPaymentChargeController extends BaseController
{
    //
    public function __construct()
    {

    }
    public function index(Request $request)
    {
       $auth_user = Auth::User();
       $user_id = $auth_user->id;
       $gpcs_payment_charge = GpcsPaymentCharge::all();

        if ($gpcs_payment_charge) {
            // Access the latest record's attributes
            //return response()->json($gpcs_payment_charge); // Returns the record as JSON

            return $this->renderResponse(GPCSPaymentChargeResource::collection($gpcs_payment_charge), 'Code Payment Charges');
            //return $this->renderResponse('Success',['success' => 'All GPCS Payment Charge','data' =>$gpcs_payment_charge],StatusCode::OK);
            //return $this->renderResponse(new GPCSPaymentChargeResource($gpcs_payment_charge), 'All Code Payment Charge');
        } else {
            return $this->renderResponseWithErrors('Error', ['error'=> 'An unexpected database error occurred'], StatusCode::INTERNAL_SERVER_ERROR);
        }
    //   dd($auth_user->id);
    }
    public function get_active_payment_charge(Request $request)
    {
        $gpcs_payment_charge = GpcsPaymentCharge::where('is_active','=','1')->firstOrFail();

        if ($gpcs_payment_charge) {
            return $this->renderResponse(new GPCSPaymentChargeResource($gpcs_payment_charge), 'Code Payment Charges');
        } else {
            return $this->renderResponseWithErrors('Error', ['error'=> 'An unexpected database error occurred'], StatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    public function store(GpcsPaymentChargeRequest $request)
    {
         try {
          $gpcs_payment_charge = $request->save();
          return $this->renderResponse(new GPCSPaymentChargeResource($gpcs_payment_charge), 'Code Payment Charge added successfully');
          //return $this->renderResponse('Success',['success' => 'Code Payment Charge added successfully','data' =>$gpcs_payment_charge],StatusCode::OK);
        } catch (QueryException $exception) {
            // Check if the exception is related to unique violation
            if ($exception->errorInfo[1] == 7) {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Code Payment Charge Currency already exists'], StatusCode::UNPROCESSABLE_ENTITY);
            }
        }
    }

    public function update(GpcsPaymentChargeRequest $request, $id)
    {
        GpcsPaymentCharge::query()->update(['is_active' => 0]);
        $gpcs_payment_charge = GpcsPaymentCharge::find($id);
        if(is_null($gpcs_payment_charge))
        {
            return $this->renderResponseWithErrors('Error', ['error'=> 'Payment code not found'], StatusCode::NOT_FOUND);
        }
        try {
            $gpcs_payment_charge = $request->update($gpcs_payment_charge);
            return $this->renderResponse(new GPCSPaymentChargeResource($gpcs_payment_charge), 'Payment Code update successfully.');
        } catch (QueryException $exception) {
            // Check if the exception is related to unique violation
            if ($exception->errorInfo[1] == 7) {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Payment Code already exists'], StatusCode::UNPROCESSABLE_ENTITY);
            }
        }
    }

}
