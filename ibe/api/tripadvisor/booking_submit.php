<?
/*
 * Booking Submit
 *
 * Revised: Jun 17, 2016
 *          Nov 26, 2017
 *
 */

/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

date_default_timezone_set('America/New_York');

$DATA = isset($_GET['book']) ? json_decode($_GET['book'], true) : json_decode(file_get_contents("php://input"), true);
//print_r($DATA);exit;

$problems = array();
$ROOM_ID = str_replace("ID_","",$DATA['partner_data']['room_id']);
$CUSTOMER = $DATA["customer"];
$PAYMENT = $DATA["payment_method"];

if (!isset($CUSTOMER["first_name"])) {
    $problems[] = array(
      "problem" => "MissingTravelerFirstName",
      "explanation" => "Traveler First name is missing",
    );  
}
if (!isset($CUSTOMER["last_name"])) {
    $problems[] = array(
      "problem" => "MissingTravelerLastName",
      "explanation" => "Traveler Last name is missing",
    );  
}


$BOOKING = array('QRYSTR'=>array(), 'BOOK'=>array(
  'REFERENCE_ID' => $DATA['reference_id'],
  'RES_ROOMS_SELECTED' => array(),
  'ROOMS' => array(),
  'GUEST' => array(),
  'RES_GUESTMETHOD' => "CC",
  'PAYMENT' => array(),
  'FORWHOM' => Array(
      "RES_TO_WHOM" => "GUEST",
      "RES_GUEST_ID" => "0",
      "RES_NEW_GUEST" => "1",
      "RES_TA_ID" => "0",
      "RES_NEW_TA" => "0",
  ),
  "COMMENTS" => "",
  "HEAR_ABOUT_US" => "Tripadvisor",
  "ARRIVAL_TIME" => "",
  "ARRIVAL_AMPM" => "AM",
  "AIRLINE" => "",
  "FLIGHT" => "",
  "ARRIVAL" => "",
  "ARRIVAL_AP" => "AM",
  "DEPARTURE_AIRLINE" => "",
  "DEPARTURE_FLIGHT" => "",
  "DEPARTURE" => "",
  "DEPARTURE_AP" => "AM",
));

$BOOKING['QRYSTR'] = array(
  "RES_PROP_ID" => $DATA['partner_data']['id'],
  "RES_LANGUAGE" => "EN",
  "RES_COUNTRY_CODE" => $CUSTOMER["country"],
  "RES_CHECK_IN" => $DATA["checkin_date"],
  "RES_CHECK_OUT" => $DATA["checkout_date"],
  "RES_NIGHTS" => dateDiff($DATA["checkin_date"], $DATA["checkout_date"]),
  "RES_ROOMS_QTY" => count($DATA["rooms"]),
  "RES_SPECIAL_CODE" => "",
  "RES_STATE_CODE" => "",
);

foreach ($DATA["rooms"] as $i => $ROOM) {
  $ROOM_NUM = $i+1;
  $BOOKING['QRYSTR']["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"] = 0;
  $BOOKING['QRYSTR']["RES_ROOM_{$ROOM_NUM}_ADULTS_QTY"] = $ROOM['party']['adults'];
  for ($age=1;$age<=2;++$age) {
    $BOOKING['QRYSTR']["RES_ROOM_{$ROOM_NUM}_CHILD_AGE_{$age}"] = 0;
  }
  $BOOKING['BOOK']['RES_ROOMS_SELECTED'][] = $ROOM_ID;
  $BOOKING['BOOK']['ROOMS'][] = array(
      "GUEST_TITLE" => "",
      "GUEST_FIRSTNAME" => isset($ROOM['traveler_first_name']) ? $ROOM['traveler_first_name'] : $CUSTOMER["first_name"],
      "GUEST_LASTNAME" => isset($ROOM['traveler_last_name']) ? $ROOM['traveler_last_name'] : $CUSTOMER["last_name"],
      "GUEST_BEDTYPE" => "",
      "GUEST_SMOKING" => "",
      "GUEST_OCCASION" => "",
      "GUEST_BABYCRIB" => 0,     
  );
}

$BOOKING['BOOK']['GUEST'] = Array(
    "TITLE" => "",
    "FIRSTNAME" => $CUSTOMER["first_name"],
    "LASTNAME" => $CUSTOMER["last_name"],
    "LANGUAGE" => "EN",
    "ADDRESS" => $PAYMENT["billing_address"]["address1"] . (isset($PAYMENT["billing_address"]["address2"]) ? " - ".$PAYMENT["billing_address"]["address2"] : ""),
    "CITY" => $PAYMENT["billing_address"]["city"],
    "STATE" => "",
    "COUNTRY" => $CUSTOMER["country"],
    "ZIPCODE" => isset($PAYMENT["billing_address"]["postal_code"]) ? $PAYMENT["billing_address"]["postal_code"] : "00000",
    "PHONE" => $CUSTOMER["phone_number"],
    "EMAIL" => $CUSTOMER["email"],
    "MAILING_LIST" => "0"
);

$BOOKING['BOOK']['PAYMENT'] = Array(
    "CC_TYPE" => $PAYMENT['card_type'],
    "CC_NUMBER" => $PAYMENT['card_number'],
    "CC_NAME" => $PAYMENT['cardholder_name'],
    "CC_CODE" => $PAYMENT['cvv'],
    "CC_EXP" => $PAYMENT['expiration_month']."/".substr($PAYMENT['expiration_year'],2),
    "CC_BILL_EMAIL" => $CUSTOMER["email"],
    "CC_BILL_ADDRESS" => $PAYMENT["billing_address"]["address1"] . (isset($PAYMENT["billing_address"]["address2"]) ? " - ".$PAYMENT["billing_address"]["address2"] : ""),
    "CC_BILL_CITY" => $PAYMENT["billing_address"]["city"],
    "CC_BILL_STATE" => "NN",
    "CC_BILL_COUNTRY" => $PAYMENT["billing_address"]["country"],
    "CC_BILL_ZIPCODE" => isset($PAYMENT["billing_address"]["postal_code"]) ? $PAYMENT["billing_address"]["postal_code"] : "00000"
);

$BOOKING['QRYSTR'] = http_build_query($BOOKING['QRYSTR']);

/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */
$partner_hotel_code = $DATA["partner_hotel_code"];
$partner_id = strstr($_SERVER["HTTP_HOST"],"finestresorts")!==FALSE ? 5 : $partner_hotel_code;

$BOOKING_PATH = ($partner_id=="1" || $partner_id=="2" || $partner_id=="3" || $partner_id=="6") ? "er" : "booking";

$SITE_URL = $DATA['partner_data']['url']; 
$IBE_URL = $SITE_URL . "/ibe/";
$API_URL = $SITE_URL . "/$BOOKING_PATH/";

//$MAKE_URL = strstr($API_URL,".com")!==FALSE ? str_replace("http://","https://",$API_URL) : $API_URL;

$isStaging = strstr($_SERVER["HTTP_HOST"],"staging") || strstr($_SERVER["HTTP_HOST"],"hoopsydoopsy") || strstr($_SERVER["HTTP_HOST"],"locate");
$MAKE_URL = strstr($API_URL,".com")!==FALSE && !$isStaging ? str_replace("http://","https://",$API_URL) : $API_URL;

$MAKE_URL .= "make-booking.php";

// Find if user exists
$GUEST_ID_URL = $IBE_URL."index.php?PAGE_CODE=ws.checkGuestEmail&getid=1&email=".$CUSTOMER["email"];
$GUEST_ID = file_get_contents($GUEST_ID_URL);

//print $GUEST_ID_URL."<br>";//exit;
//mail("jaunsarria@gmail.com","Guest ID",$GUEST_ID_URL);

if ($GUEST_ID!=0) {
  $BOOKING['BOOK']['FORWHOM']["RES_GUEST_ID"] = "$GUEST_ID";
  $BOOKING['BOOK']['FORWHOM']["RES_NEW_GUEST"] = "0";
}

//ob_start();print_r($BOOKING);$ARR = ob_get_clean();mail("jaunsarria@gmail.com","BOOK",$ARR);

$POSTFIELDS = array(
    'QRYSTR'=>$BOOKING["QRYSTR"], 
    'BOOK'=>json_encode($BOOKING['BOOK'])
);

//print "MAKE_URL :: " . $MAKE_URL."<br>POSTFIELDS :: <pre>";print_r($POSTFIELDS);print "</pre>";//exit;

$MAKE_BOOKING = make_call($MAKE_URL, $POSTFIELDS);
$RESULT = json_decode($MAKE_BOOKING, true);

//print "$MAKE_URL :: POSTFIELDS: ";print_r($POSTFIELDS)." :: RESULT: ";print_r($RESULT);exit;
//print "$MAKE_URL :: RESULT: ";print_r($RESULT);exit;
//mail("jaunsarria@gmail.com","MAKE_URL",$MAKE_URL);
//ob_start();print_r($RESULT);$ARR = ob_get_clean();mail("jaunsarria@gmail.com","RESULT",$ARR);

//print "RESULT: <pre>";print_r($RESULT);print "</pre>";exit;

if (!isset($RESULT['RES_NUMBER'])) {
  $problems[] = array(
    "problem" => "UnknownPartnerProblem",
    "explanation" => "Unknown Problem",
    "detail" => $MAKE_BOOKING //"Unknown Problem when submitting the booking"
  );
}

$IPA_ACTION = "SUBMIT";

$PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC","XOB");
$RES_PROP_CODE = isset($PROP_CODES[$partner_id]) ? $PROP_CODES[$partner_id] : "";

include "get_domain.php";

//print "DOMAIN_URL: $partner_id - $DOMAIN_URL";exit;

/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

$CY = explode("_",$RESULT['RES_YEAR']);
$URL = $IBE_URL."index.php?PAGE_CODE=ws.getJSON&ID={$RESULT['RES_ID']}&CODE={$CY[0]}&YEAR={$CY[1]}";

//print $URL;exit;


include "reservation.php";

/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */

function dateDiff($start, $end) {
  $start = (int)str_replace("-","",$start);
  $end = (int)str_replace("-","",$end);
  return $end - $start;
}

function make_call($URL, $POSTFIELDS) {
  //print $URL;exit;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $URL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_POST, 1);

  curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!

  $RESULT = curl_exec($ch);

  //print "RESULT :: " . $RESULT;
  return $RESULT;

}


header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RETVAL);exit;
