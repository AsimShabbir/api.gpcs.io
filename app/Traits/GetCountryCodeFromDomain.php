<?php
namespace App\Traits;
use App\Models\TldCountryCode;
trait GetCountryCodeFromDomain
{
    public function getCountryCode($url)
    {
        $host = self::getDomainName($url);
       // dd($host);
        $parts = explode('.', $host);
        $tld = end($parts); // Get the last part (TLD)
      //  dd($tld);
        // Simple TLD-based country mapping (incomplete)
        // $countryMap = [
        //     'uk' => 'UK', // United Kingdom
        //     'us' => 'US', // United States
        //     'ca' => 'CA', // Canada
        //     'au' => 'AU', // Australia
        //     'de' => 'DE', // Germany
        //     'fr' => 'FR', // France
        //     // Add more mappings as needed
        // ];

        $tld_country_code = TldCountryCode::all();

        $countryMap =array();
        foreach ($tld_country_code as $tld_code) {
            $domain = rtrim(ltrim($tld_code->domain_country_code, '.')); // Remove leading dot
            $countryMap[$domain] = $tld_code->map_code;
        }
       // dd($countryMap);
        if (isset($countryMap[$tld])) {
            return $countryMap[$tld];
        }

        //Check for .co.uk, .org.uk etc.
        if (count($parts) >= 3){
            $secondLevelTld = $parts[count($parts) - 2] . '.' . $tld;
            if (isset($countryMap[$secondLevelTld])){
                return $countryMap[$secondLevelTld];
            }
        }

        return null; // Country not found
    }
    private static function getDomainName($url)
    {
        $parsedUrl = parse_url($url);
      //  dd($parsedUrl['host']);
        if (isset($parsedUrl['host'])) {
            $host = $parsedUrl['host'];
            // Remove subdomains (e.g., "gemini" from "gemini.google.com")
            $parts = explode('.', $host);
            if (count($parts) >= 2) {
                return $parts[count($parts) - 2] . '.' . end($parts); // Get the last two parts
            }
            return $host; // If no subdomains, return the host.
        }

        return null; // Domain name not found
    }
}
