<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\api\v1\BaseController;
use App\Http\Requests\GpscRequest;
use App\Traits\GenerateGPCSCode;
use App\Traits\GetCountryCodeFromDomain;
use App\Traits\GetCountryCodeFromGoogleMap;

class GenerateGpcsController extends BaseController
{
    use GenerateGPCSCode,GetCountryCodeFromDomain,GetCountryCodeFromGoogleMap;

    public function generateGPCSCode(GpscRequest $request)
    {

        //dd($request->all());
        //dd(!isset($domain));
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $domain = $request->domain;
        $country_code = "";
        if(isset($domain))
        {
           // dd("hello");
            $country_code = $this->getCountryCode($request->domain);
           // return response()->json(['error' => 'domain is missing'], 400);
        }
        else if(isset($latitude) and isset($longitude))
        {
            $country_code = $this->getCountry_Code($latitude, $longitude);
          //  return response()->json(['error' => 'Latitude / Longitude must required'], 400);
        }
        else{
            return response()->json(['error' => 'Domain or Latitude / Longitude must required'], 400);
        }
        //$country_code = $this->getCountryCode($request->domain);//$request->countrycode;
       // dd($country_code);
        $first_part = $this->generateCode($request->firstcode);
        $second_part = $this->generateCode($request->secondcode);
        //dd($stripeSecretKey);
        $gpcs_code = $country_code . "-" . $first_part . "-" . $second_part;
        try {

            return response()->json(['message' => $gpcs_code], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


}
