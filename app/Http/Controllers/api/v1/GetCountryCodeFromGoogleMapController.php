<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\api\v1\BaseController;
use App\Http\Requests\CoordinateRequest;
use App\Traits\GetCountryCodeFromGoogleMap;
use GuzzleHttp\Client;

class GetCountryCodeFromGoogleMapController extends BaseController
{
    use GetCountryCodeFromGoogleMap;

    public function getCountryCodeFromGoogleMap(CoordinateRequest $request)
    {

        //dd($request->currency);
         $latitude = $request->latitude;
         $longitude = $request->longitude;
        // //dd($stripeSecretKey);
        try {
            $key = $this->getCountry_Code($latitude, $longitude);
           // dd($key);

            if (isset($key)) {
                return response()->json(['key' => $key]);
            } else {
                return response()->json(['error' => 'Location is not in the UK.'], 400);
            }



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
