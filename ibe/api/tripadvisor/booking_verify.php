<?
/*
 * Booking Verify
 *
 * Revised: Jun 21, 2016
 *          Jan 01, 2017
 *
 */

/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

date_default_timezone_set('America/New_York');

$PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC");
$problems = array();
$reservation = array();
$DATA = $_GET;
$RETVAL = array();

$partner_hotel_code = $_GET['partner_hotel_code'];
$partner_id = $partner_hotel_code;
$reference_id = $_GET['reference_id'];
$reservation_id = $_GET['reservation_id'];

$RES_PROP_CODE = isset($PROP_CODES[$partner_id]) ? $PROP_CODES[$partner_id] : "";
$PROP_ID = substr($reservation_id, 0, 1);
$YEAR = substr($reservation_id, 1, 2);

$IPA_ACTION = "VERIFY";
include "get_domain.php";

//print $PROP_ID." :: ".$YEAR;
/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

$URL = $DOMAIN_URL."/ibe/index.php?PAGE_CODE=ws.getByNumber&PROP_ID=$PROP_ID&YEAR=20$YEAR&NUMBER=$reservation_id";

//PRINT $URL;exit;

include "reservation.php";

/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RETVAL);exit;
