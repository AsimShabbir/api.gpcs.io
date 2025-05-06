<?php
namespace App\Traits;
use GuzzleHttp\Client;
trait GetCountryCodeFromGoogleMap
{
    public function getCountry_Code($latitude,$longitude)
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
//dd($apiKey);
        if (!$apiKey) {
            return null;
        }
        try {
            $client = new Client();
            $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$apiKey}");
            $data = json_decode($response->getBody(), true);
           // dd($data['results'][0]['address_components']);
            if ($data['status'] === 'OK' && isset($data['results'][0]['address_components'])) {
                foreach ($data['results'][0]['address_components'] as $component) {
                    if (in_array('country', $component['types'])) {
                        return $component['short_name'];
                      //  return $component['short_name'] === 'GB';
                    }
                }
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }

    }
}
