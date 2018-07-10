<?php
namespace App\MyLibs;

use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Storage;
use GeoIp2\Exception\GeoIp2Exception;
use Log;

class GeoLookup {

    protected function GetCityInformation($ip)
    {
        if (empty($ip)||$ip=='::1'||$ip=='127.0.0.1')
            return '';

        try
        {
            //This product includes GeoLite2 data created by MaxMind, available from http://www.maxmind.com
            //Log::info(storage_path().'/geo/GeoLite2-City.mmdb');
            
            $reader = new Reader(storage_path().'/geo/GeoLite2-City.mmdb');
            $omni = $reader->City($ip);
            return $omni;
        }
        //catch (AddressNotFoundException exc)
        catch (GeoIP2Exception $exc)
        {
            //address is not found
            //do not throw exceptions
            Log::warning("GeoIP2Exception".json_encode($exc));
            return '';
        }
        catch (Exception $exc)
        {
            //do not throw exceptions
            Log::warning("Exception".json_encode($exc));
            return '';
        }
    }

    public function LookupCityName($ip)
    {
        $response = $this->GetCityInformation($ip);
        if ($response==='') {
            return '';
        }
        if (isset($response->city)) {
            return $response->subdivisions[0]->names["zh-CN"]
        .$response->city->names["zh-CN"];
        }
        
        return '';
    }
}