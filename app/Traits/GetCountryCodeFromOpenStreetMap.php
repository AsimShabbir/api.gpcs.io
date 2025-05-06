<?php
namespace App\Traits;
use GuzzleHttp\Client;
trait GetCountryCodeFromOpenStreetMap
{
    public function getCountryCode($latitude,$longitude)
    {

        $code = null;
        try {
            $client = new Client();
            // Using OpenStreetMap's Nominatim Reverse Geocoding API.
            $response = $client->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}");

            $data = json_decode($response->getBody(), true);
            if (isset($data['address']['country_code'])) {
                $countryCode = strtoupper($data['address']['country_code']);
                $code = ($countryCode === 'GB')?'UK':null;
                $code;

            } else {
                $code = '404';
            }
        } catch (\Exception $e) {
            $code = $e->getMessage();//'500'; //return response()->json(['error' => 'Geocoding service error: ' . $e->getMessage()], 500);
        }
        return $code;
    }
}
