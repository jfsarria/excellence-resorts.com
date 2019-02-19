<?php
// GET GEO INFORMATION

include $_SERVER['DOCUMENT_ROOT']."/ibe/geo/geoipcity.inc";
include $_SERVER['DOCUMENT_ROOT']."/ibe/geo/geoipregionvars.php";
$gi = geoip_open($_SERVER['DOCUMENT_ROOT']."/ibe/geo/GeoLiteCity.dat",GEOIP_STANDARD);
    // http://www.locateandshare.com/ibe/geo/sample_city.php
    $IP = (isset($_REQUEST['RES_IP'])) ? $_REQUEST['RES_IP'] : $_SERVER["REMOTE_ADDR"];
    $geo = geoip_record_by_addr($gi,$IP);
    $RES_COUNTRY_CODE = $geo->country_code;

		//mail("juan.sarria@everlivesolutions.com","ER GEO /mobile/geo.php $IP","$IP - ".$RES_COUNTRY_CODE);

geoip_close($gi);

?>