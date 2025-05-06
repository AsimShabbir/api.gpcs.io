<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\api\v1\BaseController;
use App\Http\Requests\CoordinateRequest;
use App\Traits\GetCountryCodeFromOpenStreetMap;
use GuzzleHttp\Client;

class GetCountryCodeFromOpenStreetMapController extends BaseController
{
    use GetCountryCodeFromOpenStreetMap;

    public function getCountryCodeFromOpenStreetMap(CoordinateRequest $request)
    {
        //dd($request->all());
        //dd($request->currency);
         $latitude = $request->latitude;
         $longitude = $request->longitude;
        // //dd($stripeSecretKey);
        try {
            $code = $this->getCountryCode($latitude,$longitude);
            dd($code);
            if($code == '404')
                return response()->json(['error' => 'Country code not found'], 404);
            elseif($code == '500')
                return response()->json(['error' => 'Geocoding service error'], 500);
            return response()->json(['message' => $code], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        // try {
        //     $client = new Client();
        //     $response = $client->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}");
        //     $data = json_decode($response->getBody(), true);

        //     if (isset($data['address']['country_code'])) {
        //         return response()->json(['key'=> strtoupper($data['address']['country_code']) === 'GB']);
        //     }
        //     return response()->json(['key'=> strtoupper($data['address']['country_code'])]);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => $e->getMessage()], 400);
        // }
    }


}
