<?php

namespace App\Http\Controllers\api\v1\Users;

use App\Http\Controllers\api\v1\BaseController as BaseController;
use App\Enums\StatusCode;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\GpcsCode;
use App\Http\Requests\GpscRequest;
use App\Models\GpcsCodesHistory;

use App\Http\Requests\Users\GpscCodeGenerationHistoryRequest;
use App\Traits\GenerateGPCSCode;
use App\Traits\GetCountryCodeFromDomain;
use App\Traits\GetCountryCodeFromGoogleMap;
use App\Http\Requests\Users\GpscCodeGenerationRequest;

class GenerateCodeController extends BaseController
{
    use GenerateGPCSCode,GetCountryCodeFromDomain,GetCountryCodeFromGoogleMap;
    protected $GpscCodeGenerationHistoryRequest;
    //
    public function __construct(GpscCodeGenerationHistoryRequest $GpscCodeGenerationHistoryRequest)
    {
        $this->$GpscCodeGenerationHistoryRequest = $GpscCodeGenerationHistoryRequest;
    }
    public function index(Request $request)
    {
       $auth_user = Auth::User();
       $user_id = $auth_user->id;
       $latestGpcsCode = GpcsCode::where('user_id', $user_id)
            ->latest() // Orders by created_at DESC
            ->first();

        if ($latestGpcsCode) {
            // Access the latest record's attributes
            return response()->json($latestGpcsCode); // Returns the record as JSON
        } else {
            return response()->json(['message' => 'No GpcsCode found for this user.'], 404);
        }
    //   dd($auth_user->id);
    }

    public function get_users_all_gpcs(Request $request)
    {
       $auth_user = Auth::User();
       $user_id = $auth_user->id;
       $gpcs_codes = GpcsCode::where('user_id', $user_id)->where('is_deleted','=','0')->get();
        if ($gpcs_codes) {
            //return response()->json($gpcs_codes); // Returns the record as JSON
            return $this->renderResponse('Success',['success' => 'GPCS load successfully','data' =>$gpcs_codes],StatusCode::OK);
        } else {
            //return response()->json(['message' => 'No GpcsCode found for this user.'], 404);
            return $this->renderResponseWithErrors('Error', ['error'=> 'No GpcsCode found for this user.'], StatusCode::NOT_FOUND);
        }
    //   dd($auth_user->id);
    }

    public function store(GpscRequest $request)
    {

        $auth_user = Auth::User();
        $user_id = $auth_user->id;
        $first_code="";
        $second_code="";
        $latitude = $request->latitude;
         $longitude = $request->longitude;
         $domain = $request->domain;
         $label = $request->label;
         $country_code = "";
         //dd($domain);
         if(isset($domain))
         {
          //   dd("hello");
             $country_code = $this->getCountryCode($request->domain);
            // return response()->json(['error' => 'domain is missing'], 400);
         }
         else if(isset($latitude) and isset($longitude))
         {
          //  dd("hello");
             $country_code = $this->getCountry_Code($latitude, $longitude);
           //  return response()->json(['error' => 'Latitude / Longitude must required'], 400);
         }
         else{
             return response()->json(['error' => 'Domain or Latitude / Longitude must required'], 400);
         }
        // dd($country_code);
         $latestGpcsCode = GpcsCode::where('user_id', $user_id)
         ->where('country_code', $country_code)
         ->latest() // Orders by created_at DESC
         ->first();

     if ($latestGpcsCode) {
         // Access the latest record's attributes
         //return response()->json($latestGpcsCode); // Returns the record as JSON
         $first_code = $latestGpcsCode->first_part;
         $second_code = $latestGpcsCode->second_part;
     } else {
        // return response()->json(['message' => 'No GpcsCode found for this user.'], 404);
        $first_code=null;
        $second_code=null;
     }
         $first_part = $this->generateCode($first_code);
        $second_part = $this->generateCode($second_code);
        //dd($stripeSecretKey);
        $gpcs_code = $country_code . "-" . $first_part . "-" . $second_part;
        try {
          //  $request->save();
          $GpscCodeGenerationRequest = new GpcsCode();
          $GpscCodeGenerationRequest->first_part = $first_part;
          $GpscCodeGenerationRequest->second_part = $second_part;
          $GpscCodeGenerationRequest->user_id = $user_id;
          $GpscCodeGenerationRequest->country_code = $country_code;
          $GpscCodeGenerationRequest->gpcscode = $gpcs_code;
          $GpscCodeGenerationRequest->domain = $domain;
          $GpscCodeGenerationRequest->latitude = $latitude;
          $GpscCodeGenerationRequest->longitude = $longitude;
          $GpscCodeGenerationRequest->label = $label;
          $GpscCodeGenerationRequest->is_deleted=0;
          $GpscCodeGenerationRequest->save();
          $latestGpcsCode = GpcsCode::where('user_id', $user_id)
            ->latest() // Orders by created_at DESC
            ->first();

         $this->save_gpcs_history($latestGpcsCode);
            return $this->renderResponse('Success',['success' => 'Code added successfully','data' =>$GpscCodeGenerationRequest],StatusCode::OK);
        } catch (QueryException $exception) {
            // Check if the exception is related to unique violation
            if ($exception->errorInfo[1] == 7) {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Client already exists'], StatusCode::UNPROCESSABLE_ENTITY);
            }
        }
    }
    public function verified_location(GpscRequest $request)
    {
        //dd($request->all());
        $auth_user = Auth::User();
        $user_id = $auth_user->id;
        $code_id = $request->id;
        try
        {
           // $gpcs_code = GpcsCode::find($code_id); // Retrieve the model by its primary key
           $gpcs_code = GpcsCode::where('id', $code_id)
           ->where('user_id', $user_id)
           ->first();
            if ($gpcs_code) { // Make sure the model was found
                $this->save_gpcs_history($gpcs_code);
                $gpcs_code->verified = 1;
                $gpcs_code->save();
                return $this->renderResponse('Success',['success' => 'Location verified successfully','data' =>$gpcs_code],StatusCode::OK);
            } else {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Location code not found'], StatusCode::NOT_FOUND);
            }
        } catch (QueryException $exception) {
            // Check if the exception is related to unique violation
            if (isset($exception->errorInfo[1]) && $exception->errorInfo[1] == 7) {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Something went wrong with the database'], StatusCode::UNPROCESSABLE_ENTITY);
            }
            // Consider logging the exception for debugging
            // Log::error('Database error during location verification: ' . $exception->getMessage());
            return $this->renderResponseWithErrors('Error', ['error'=> 'An unexpected database error occurred'], StatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    public function update_label(GpscRequest $request)
    {
        //dd($request->all());
        $auth_user = Auth::User();
        $user_id = $auth_user->id;
        $code_id = $request->id;
        $label = $request->label;
        try
        {
            //$gpcs_code = GpcsCode::find($code_id); // Retrieve the model by its primary key
            $gpcs_code = GpcsCode::where('id', $code_id)
           ->where('user_id', $user_id)
           ->first();
            if ($gpcs_code) { // Make sure the model was found
                $this->save_gpcs_history($gpcs_code);
                $gpcs_code->label = $label;
                $gpcs_code->save();
                return $this->renderResponse('Success',['success' => 'Label Added successfully','data' =>$gpcs_code],StatusCode::OK);
            } else {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Location code not found'], StatusCode::NOT_FOUND);
            }
        } catch (QueryException $exception) {
            // Check if the exception is related to unique violation
            if (isset($exception->errorInfo[1]) && $exception->errorInfo[1] == 7) {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Something went wrong with the database'], StatusCode::UNPROCESSABLE_ENTITY);
            }
            // Consider logging the exception for debugging
            // Log::error('Database error during location verification: ' . $exception->getMessage());
            return $this->renderResponseWithErrors('Error', ['error'=> 'An unexpected database error occurred'], StatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function delete_gpc(GpscRequest $request)
    {
        //dd($request->all());
        $auth_user = Auth::User();
        $user_id = $auth_user->id;
        $code_id = $request->id;
        try
        {
            //$gpcs_code = GpcsCode::find($code_id); // Retrieve the model by its primary key
            $gpcs_code = GpcsCode::where('id', $code_id)
            ->where('user_id', $user_id)
            ->first();
            if ($gpcs_code) { // Make sure the model was found
                $this->save_gpcs_history($gpcs_code);
                $gpcs_code->is_deleted = 1;
                $gpcs_code->save();
                return $this->renderResponse('Success',['success' => 'GPCS delete successfully','data' =>$gpcs_code],StatusCode::OK);
            } else {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Location code not found'], StatusCode::NOT_FOUND);
            }
        } catch (QueryException $exception) {
            // Check if the exception is related to unique violation
            if (isset($exception->errorInfo[1]) && $exception->errorInfo[1] == 7) {
                return $this->renderResponseWithErrors('Error', ['error'=> 'Something went wrong with the database'], StatusCode::UNPROCESSABLE_ENTITY);
            }
            // Consider logging the exception for debugging
            // Log::error('Database error during location verification: ' . $exception->getMessage());
            return $this->renderResponseWithErrors('Error', ['error'=> 'An unexpected database error occurred'], StatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    private function save_gpcs_history($GpscCodeGenerationRequest)
    {
            $GpscCodeGenerationHistoryRequest = new GpcsCodesHistory();
            $GpscCodeGenerationHistoryRequest->gpcs_codes_id = $GpscCodeGenerationRequest->id;
            $GpscCodeGenerationHistoryRequest->first_part = $GpscCodeGenerationRequest->first_part;
            $GpscCodeGenerationHistoryRequest->second_part = $GpscCodeGenerationRequest->second_part;
            $GpscCodeGenerationHistoryRequest->user_id = $GpscCodeGenerationRequest->user_id;
            $GpscCodeGenerationHistoryRequest->country_code = $GpscCodeGenerationRequest->country_code;
            $GpscCodeGenerationHistoryRequest->gpcscode = $GpscCodeGenerationRequest->gpcscode;
            $GpscCodeGenerationHistoryRequest->domain = $GpscCodeGenerationRequest->domain;
            $GpscCodeGenerationHistoryRequest->latitude = $GpscCodeGenerationRequest->latitude;
            $GpscCodeGenerationHistoryRequest->longitude = $GpscCodeGenerationRequest->longitude;
            $GpscCodeGenerationHistoryRequest->label = $GpscCodeGenerationRequest->label;
            $GpscCodeGenerationHistoryRequest->is_deleted=$GpscCodeGenerationRequest->is_deleted;
            $GpscCodeGenerationHistoryRequest->verified=$GpscCodeGenerationRequest->verified;
         //dd($GpscCodeGenerationHistoryRequest . '----'. $GpscCodeGenerationRequest);
            $GpscCodeGenerationHistoryRequest->save();

    }
    public function get_gpcs_counts(Request $request)
    {
        try
        {
            $auth_user = Auth::User();
            $user_id = $auth_user->id;
            $gpcs_codes_count = GpcsCode::where('user_id', $user_id)->where('is_deleted','=','0')->count();
            return $this->renderResponse('Success',['success' => 'Total GPCS','data' =>$gpcs_codes_count],StatusCode::OK);
        } catch (QueryException $exception) {
            return $this->renderResponseWithErrors('Error', ['error'=> 'Something went wrong!'], StatusCode::UNPROCESSABLE_ENTITY);
        }
    }
}
