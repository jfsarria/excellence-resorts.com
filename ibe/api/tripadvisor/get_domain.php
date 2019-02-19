<?php
/*
 * Revised: Jun 21, 2016
 *          Nov 26, 2017
 *
 */

// .:
set_include_path($_SERVER["DOCUMENT_ROOT"] . "/ibe");

if ($IPA_ACTION == "REQUEST") {
  $DOMAIN_URL = "http://www.excellence-resorts.com";
  $BOOKING_PATH = "booking";
  $CHILD_POLICY = "Adult only resort";
}

// Was using $partner_id and changed to $RES_PROP_CODE

if ($RES_PROP_CODE=="XRC" || $RES_PROP_CODE=="XPM" || $RES_PROP_CODE=="XPC" || $RES_PROP_CODE=="XEC" || $RES_PROP_CODE=="XOB") {
    $DOMAIN_URL = strstr($_SERVER["HTTP_HOST"],"excellence-resorts.com")!==FALSE || strstr($_SERVER["HTTP_HOST"],"205.186.160.44")!==FALSE ? "http://".$_SERVER["HTTP_HOST"] : "http://locateandshare.com";
    $BOOKING_PATH = "er";
}

if ($RES_PROP_CODE=="FPM") {
    $PREFIX = strstr($_SERVER["HTTP_HOST"],"staging")==FALSE ? "www" : "staging";
    $DOMAIN_URL = "http://$PREFIX.finestresorts.com";
    $CHILD_POLICY = "All children are welcome";
}

if ($RES_PROP_CODE=="TBH") {
    $DOMAIN_URL = strstr($_SERVER["HTTP_HOST"],"belovedhotels.com")!==FALSE || strstr($_SERVER["HTTP_HOST"],"205.186.163.157")!==FALSE ? "http://".$_SERVER["HTTP_HOST"] : "http://hoopsydoopsy.com";
}

if ($IPA_ACTION == "REQUEST") {
    $ibe_url = $DOMAIN_URL;

    $QRYSTR_IBE = array_merge($QRYSTRPRMS, $QRYSTR);

    //$call_ibe = $ibe_url . '/ibe/index.php?' . http_build_query($QRYSTR_IBE);
    //$json = file_get_contents($call_ibe);
    
    $_REQUEST_ORIGINAL = $_REQUEST;
    $_GET_ORIGINAL = $_GET;

    ob_start();
      $_REQUEST = $QRYSTR_IBE;
      $_GET = $QRYSTR_IBE;
      $index = $_SERVER["DOCUMENT_ROOT"] . "/ibe/index.php";
      //print get_include_path();
      //print $index;exit;
      //print "<pre>";print_r($_REQUEST);print "</pre>";exit;
      //ob_start();print_r($_REQUEST);$output = ob_get_clean();file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/ibe/api/tripadvisor/index_ta.txt", $output, FILE_APPEND);
      include $index;
    $json = ob_get_clean();

    $_REQUEST = $_REQUEST_ORIGINAL;
    $_GET = $_GET_ORIGINAL;

    //print $json;exit;

    $call_front = str_replace("http://","https://",$ibe_url) . "/$BOOKING_PATH/?" . http_build_query($QRYSTR);
}

if ($IPA_ACTION == "BOOKING") {

}

