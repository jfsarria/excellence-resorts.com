<?
/*
 * Booking Cancel
 *
 * Revised: Jun 21, 2016
 *          Mar 08, 2017
 *
 */

/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

date_default_timezone_set('America/New_York');

$_GET = isset($_GET['cancel']) ? json_decode($_GET['cancel'], true) : json_decode(file_get_contents("php://input"), true);

//print_r($_GET);//exit;

$PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC");
$problems = array();
$reservation = array();
$DATA = $_GET;
$RETVAL = array();

$partner_hotel_code = $_GET['partner_hotel_code'];
$partner_id = $partner_hotel_code;
$reservation_id = $_GET['reservation_id'];

$RES_PROP_CODE = isset($PROP_CODES[$partner_id]) ? $PROP_CODES[$partner_id] : "";
$PROP_ID = substr($reservation_id, 0, 1);
$YEAR = substr($reservation_id, 1, 2);

$IPA_ACTION = "CANCEL";
include "get_domain.php";

//print $PROP_ID." :: ".$YEAR;
/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

$URL = $DOMAIN_URL."/ibe/index.php?PAGE_CODE=ws.cancelByNumber&isTripAdvisor=1&RES_NUM=$reservation_id&YEAR=20$YEAR&PROP_ID=$PROP_ID&NOTES=Tripadvisor%20Cancellation";
//print $partner_id."<BR>".$URL;exit;
$RESERVATION = json_decode(file_get_contents($URL), true);

//print $URL;exit;
//print "<pre>";print_r($RESERVATION);print "</pre>";

$cancellation_number = isset($RESERVATION['RES_ID'])&&$RESERVATION['RES_ID']!=0 ? $RES_PROP_CODE.$YEAR.$RESERVATION['RES_ID'] : $RESERVATION["REFERENCE_ID"];

$RETVAL = array(
    "partner_hotel_code" => $partner_hotel_code,
    "reservation_id" => $reservation_id,
    "status" => "Success",
    "cancellation_number" => $cancellation_number,
    "customer_support" => array(),
);

if (empty($cancellation_number)) {
  $RETVAL['status'] = 'UnknownReference';
  unset($RETVAL['cancellation_number']);
}

$PHONE = $RESERVATION['PHONE'] ? $RESERVATION['PHONE'] : "+1 866 540 2585";

$phone_numbers = array();
$phone_numbers[] = array("contact"=>$PHONE,"description"=>"Support phone line");
$RETVAL["customer_support"] = array("phone_numbers"=>$phone_numbers);

/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RETVAL);exit;
