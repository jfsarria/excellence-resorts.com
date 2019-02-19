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

$IP = (isset($_REQUEST['IP'])) ? $_REQUEST['IP'] : $_SERVER["REMOTE_ADDR"];
$gi = geoip_open($_SERVER['DOCUMENT_ROOT']."/ibe/geo/GeoLiteCity.dat",GEOIP_STANDARD);

$record = geoip_record_by_addr($gi,$IP);
/*
print $record->country_code . " " . $record->country_code3 . " " . $record->country_name . "\n";
print $record->region . " " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "\n";
print $record->city . "\n";
print $record->postal_code . "\n";
print $record->latitude . "\n";
print $record->longitude . "\n";
print $record->metro_code . "\n";
print $record->area_code . "\n";
*/

$result .= "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
$result .= "<geoip>\n";
$result .= "<ip>".$IP . "</ip>\n";
$result .= "<country_code>".$record->country_code . "</country_code>\n";
$result .= "<country_code3>".$record->country_code3 . "</country_code3>\n";
$result .= "<country_name>".$record->country_name . "</country_name>\n";
$result .= "<region>".$record->region . "</region>\n";
$result .= "<GEOIP>".$GEOIP_REGION_NAME[$record->country_code][$record->region] . "</GEOIP>\n";
$result .= "<city>".$record->city . "</city>\n";
$result .= "<postal_code>".$record->postal_code . "</postal_code>\n";
$result .= "<latitude>".$record->latitude . "</latitude>\n";
$result .= "<longitude>".$record->longitude . "</longitude>\n";
$result .= "<metro_code>".$record->metro_code . "</metro_code>\n";
$result .= "<area_code>".$record->area_code . "</area_code>\n";
$result .= "</geoip>\n";
header('content-type: text/xml'); 
print utf8_encode($result);

geoip_close($gi);

?>
