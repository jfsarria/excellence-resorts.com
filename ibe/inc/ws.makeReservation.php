<?
/*
 * Revised: Mar 09, 2012
 *
 * http://www.locateandshare.com/ibe/index.php
 * PAGE_CODE=ws.makeReservation
 * JSON = {json}
 *
 */

$_ws_result = array();

$JSON = $_REQUEST['JSON'];
$JSON = html_entity_decode($JSON);
$_SESSION['AVAILABILITY'] = $clsGlobal->jsonDecode($JSON, false);
if (isset($_SESSION['AVAILABILITY']["RESERVATION"]["PAYMENT"]["CC_NUMBER"])) {
    // Make clean ups
    $CC_NUMBER = preg_replace("/[^\d]+/", "", $_SESSION['AVAILABILITY']["RESERVATION"]["PAYMENT"]["CC_NUMBER"]);  
    $_SESSION['AVAILABILITY']["RESERVATION"]["PAYMENT"]["CC_NUMBER"] = $CC_NUMBER;
}

if (is_array($_SESSION['AVAILABILITY']) && isset($_SESSION['AVAILABILITY']['RESERVATION'])) {
    extract($_SESSION['AVAILABILITY']);

    $err = array();
    $errMsg = array();
    $isOk = true;

    $_HOTEL_ID = $RES_PROP_ID;
    $_THIS_SECTION = "make";

    include "tpl.modules.reserv.get.php";

    foreach($_RMODULES as $RMOD_KEY => $RMOD_FILE) {
        if (file_exists("{$_APP_ROOT}inc/mods/{$RMOD_FILE}")) {
            include_once "mods/$RMOD_FILE";
        }
    }

    if (count($err)!=0) {
        $_ws_result['error'] = $err;
        if (isset($_ws_result['RES_NUMBER'])) unset($_ws_result['RES_NUMBER']);
    }

    //print "<hr><pre>";print_r($_SESSION['AVAILABILITY']);print "</pre>";
} else {
    $_ws_result = $_SESSION['AVAILABILITY'];
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/json");

print $clsGlobal->jsonEncode($_ws_result);

?>