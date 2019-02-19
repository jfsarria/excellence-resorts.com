<?
/*
 * Booking Sync
 *
 * Revised: Jun 21, 2016
 *          Jan 22, 2017
 *
 */

/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

date_default_timezone_set('America/New_York');

$PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC");
$problems = array();
$RETVAL = array();

$DATA = isset($_GET['sync']) ? json_decode($_GET['sync'], true) : json_decode(file_get_contents("php://input"), true);

//print "DATA<pre>";print_r($DATA);print "</pre>";

foreach ($DATA as $ITEM) {
  
  //print "ITEM<pre>";print_r($ITEM);print "</pre>";

  $partner_hotel_code = $PROP_CODES[$ITEM['partner_hotel_code']];
  $partner_id = $ITEM['partner_hotel_code'];
  $reservation_id = $ITEM['reservation_id'];

  $PROP_ID = substr($reservation_id, 0, 1);
  $YEAR = substr($reservation_id, 1, 2);

  $RES_PROP_CODE = isset($PROP_CODES[$partner_id]) ? $PROP_CODES[$partner_id] : "";

  $IPA_ACTION = "SYNC";
  include "get_domain.php";

  /* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

  $URL = $DOMAIN_URL."/ibe/index.php?PAGE_CODE=ws.getByNumber&PROP_ID=$PROP_ID&YEAR=20$YEAR&NUMBER=$reservation_id";
  //print "<br>$URL<br>";exit;
  $RESERVATION = json_decode(file_get_contents($URL), true);

  $status = "UnknownReference";
  $amount = 0;

  //print "RESERVATION<pre>";print_r($RESERVATION);print "</pre>";

  $RES = array(
      "partner_hotel_code" => $partner_hotel_code,
      "reservation_id" => $reservation_id,
      "status" => $status,
  );

  if (count($RESERVATION)!=0) {

      $STATUS_STR = $RESERVATION["RESERVATION"]["STATUS_STR"];
      if ($STATUS_STR=="booked") {
        $status = "Booked";
      } else if ($STATUS_STR=="cancelled") {
        $status = "Cancelled";
        $RES["cancelled_date"] = $RESERVATION["RESERVATION"]["CANCELLED"];
        $RES["cancellation_number"] = isset($RESERVATION["RESERVATION"]['RES_ID'])&&$RESERVATION["RESERVATION"]['RES_ID']!=0 ? $partner_hotel_code.$YEAR.$RESERVATION["RESERVATION"]['RES_ID'] : $RESERVATION["RESERVATION"]["REFERENCE_ID"];
      } else if ($STATUS_STR=="no show") {
        $status = "NoShow";
      } else if ($STATUS_STR=="arrived") {
        $status = "CheckedIn";
      }

      $RES["status"] = $status;
      $RES["checkin_date"] = $RESERVATION["RES_CHECK_IN"];
      $RES["checkout_date"] = $RESERVATION["RES_CHECK_OUT"];

      $RES["total_rate"] = array(
        "amount" => (int)$RESERVATION["RESERVATION"]["RES_TOTAL_CHARGE"],
        "currency" => "USD"
      );
      $RES["total_taxes"] = array(
        "amount" => 0,
        "currency" => "USD"
      );
      $RES["total_fees"] = array(
        "amount" => (int)$RESERVATION["RESERVATION"]["FEES"],
        "currency" => "USD"
      );
  }

  $RETVAL[] = $RES;

}


/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RETVAL);exit;
