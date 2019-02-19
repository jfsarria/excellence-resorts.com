<?php

if (isset($_GET['USER_COUNTRY'])&&!empty($_GET['USER_COUNTRY'])) {
    $_GEO['RES_COUNTRY_CODE'] = $_GET['USER_COUNTRY'];
} else {
    include($_SERVER['DOCUMENT_ROOT']."/ibe/geo/geoipcity.inc");
    include($_SERVER['DOCUMENT_ROOT']."/ibe/geo/geoipregionvars.php");
    $gi = geoip_open($_SERVER['DOCUMENT_ROOT']."/ibe/geo/GeoLiteCity.dat",GEOIP_STANDARD);
        // http://www.locateandshare.com/ibe/geo/sample_city.php
        $IP = (isset($_REQUEST['RES_IP'])) ? $_REQUEST['RES_IP'] : $_SERVER["REMOTE_ADDR"];
        $geo = geoip_record_by_addr($gi,$IP);
        $_GEO['RES_COUNTRY_CODE'] = isset($geo->country_code) ? $geo->country_code : "US";

				//mail("juan.sarria@everlivesolutions.com","ER GEO /er/get_geo.php $IP","$IP - ".$_GEO['RES_COUNTRY_CODE']);

    geoip_close($gi);
}

?>