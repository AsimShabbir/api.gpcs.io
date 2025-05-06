<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\api\v1\BaseController;
use App\Http\Requests\GpscRequest;
use App\Traits\GetGEOCountryCode;

class GpcsController extends BaseController
{
    use GetGEOCountryCode;

    public function generateGPCSCode(GpscRequest $request)
    {

        //dd($request->currency);
        $gpcs_code = $this->generate($request->countrycode);
        //dd($stripeSecretKey);
        try {

            return response()->json(['message' => $gpcs_code], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


}
