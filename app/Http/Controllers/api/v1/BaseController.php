<?php

namespace App\Http\Controllers\api\v1;



use App\Http\Controllers\api\v1\Controller as Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    //
    public function renderResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function renderResponseWithErrors($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
            'code' => $code,
        ];
        if(!empty($errorMessages)){
            $response['error'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
