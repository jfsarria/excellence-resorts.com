<?
/*
 * Revised: Jun 24, 2011
 */

/*

http://www.locateandshare.com/ibe/index.php?PAGE_CODE=ws.availability&ACTION=SUBMIT&RES_LANGUAGE=EN&RES_IN_THE_FUTURE=0&RES_DATE=2011-06-24&RES_PROP_ID=1&RES_USERTYPE[]=1&RES_COUNTRY_CODE=US&RES_STATE_CODE=&RES_SPECIAL_CODE=&RES_CHECK_IN=2011-06-24&RES_CHECK_OUT=2011-06-26&RES_NIGHTS=2&RES_ROOMS_QTY=1&RES_ROOM_1_ADULTS_QTY=2

*/
include "inc/page.availability.php";

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/json");

if (isset($_SESSION['AVAILABILITY'])) {
    print json_encode($_SESSION['AVAILABILITY']);
} else {
    print "{}";
}

?>
