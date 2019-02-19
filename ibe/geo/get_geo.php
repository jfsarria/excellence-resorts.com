<?php
// #!/usr/bin/php -q

// This code demonstrates how to lookup the country, region, city,
// postal code, latitude, and longitude by IP Address.
// It is designed to work with GeoIP/GeoLite City

// Note that you must download the New Format of GeoIP City (GEO-133).
// The old format (GEO-132) will not work.

include("geoipcity.inc");
include("geoipregionvars.php");

// uncomment for Shared Memory support
// geoip_load_shared_mem("/usr/local/share/GeoIP/GeoIPCity.dat");
// $gi = geoip_open("/usr/local/share/GeoIP/GeoIPCity.dat",GEOIP_SHARED_MEMORY);

$jsoncallback = isset($_REQUEST['jsoncallback']) ? trim($_REQUEST['jsoncallback']) : "";
$IP = (isset($_REQUEST['IP'])&&$_REQUEST['IP']!="") ? $_REQUEST['IP'] : $_SERVER["REMOTE_ADDR"];
$gi = geoip_open($_SERVER['DOCUMENT_ROOT']."/ibe/geo/GeoLiteCity.dat",GEOIP_STANDARD);

$record = geoip_record_by_addr($gi,$IP);

$array = array(
    "IP" => $IP,
    "country_code" => $record->country_code,
    "country_code3" => $record->country_code3,
    "country_name" => $record->country_name,
    "region" => $record->region,
    "GEOIP" => $GEOIP_REGION_NAME[$record->country_code][$record->region],
    "city" => $record->city,
    "postal_code" => $record->postal_code,
    "latitude" => $record->latitude,
    "longitude" => $record->longitude,
    "metro_code" => $record->metro_code,
    "area_code" => $record->area_code
);

geoip_close($gi);

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/x-javascript; charset=utf-8");

print $jsoncallback."(" . json_encode($array) . ")";

?>