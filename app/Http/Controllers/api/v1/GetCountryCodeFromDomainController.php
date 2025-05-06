<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\api\v1\BaseController;
use App\Http\Requests\CoordinateRequest;
use App\Traits\GetCountryCodeFromDomain;
use Illuminate\Http\Request;

class GetCountryCodeFromDomainController extends BaseController
{
    use GetCountryCodeFromDomain;

    public function getCountryCodeFromDomain(Request $request)
    {

       // dd($request->all());
        $request->validate([
            'domain' => 'required|string',
        ]);

        $domain = $request->domain;
        $countryCode = $this->getCountryCode($domain);

        if ($countryCode) {
            return response()->json(['country_code' => $countryCode]);
        } else {
            return response()->json(['error' => 'Country not found for the given domain.'], 404);
        }
    }



}
