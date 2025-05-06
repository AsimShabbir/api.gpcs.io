<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\api\v1\BaseController;


use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SMSController extends Controller
{
    public function sendSMS(Request $request)
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $fromNumber = env('TWILIO_PHONE_NUMBER');
        $toNumber = $request->to; // Get recipient number from request
        $message = $request->message; // Get message from request

        try {
            $client = new Client($sid, $token);

            $client->messages->create(
                $toNumber,
                [
                    'from' => $fromNumber,
                    'body' => $message,
                ]
            );
            return response()->json(['message' => 'SMS sent successfully!'], 200);
            //return 'SMS sent successfully!';

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

// class SMSController extends BaseController
// {

//     public function generateGPCSCode(Request $request)
//     {

//         try {

//             return response()->json(['message' => $gpcs_code], 200);
//         } catch (\Exception $e) {
//             return response()->json(['error' => $e->getMessage()], 400);
//         }
//     }


// }
