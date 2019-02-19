<?
/*
 * /hotel_availability
 *
 * Revised: Jan 21, 2015
 *          Nov 26, 2017
 *          Feb 20, 2018
 *
 */

/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

date_default_timezone_set('America/New_York');

//print "<pre>";print_r($_GET);print "</pre>";//exit;
//ob_start();print_r($_REQUEST);$output = ob_get_clean();//mail("juan.sarria@everlivesolutions.com","Test FPM Prod",$output,"Content-type:text/html;charset=UTF-8");//exit;
//ob_start();print_r($_REQUEST);$output = ob_get_clean();file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/ibe/api/tripadvisor/ta.txt", $output, FILE_APPEND);
//exit;

//print "STARTS";exit;

//ob_start();phpinfo();$output=ob_get_clean();mail("juan.sarria@everlivesolutions.com","MASTER/SLAVE INFO",$output,"Content-type:text/html;charset=UTF-8");

$errors = array();
$RESULT = array();
$DATE = date("Y-m-d");

$api_version = 7;
$hotels = isset($_REQUEST['hotels']) && !empty($_REQUEST['hotels']) ? json_decode($_REQUEST['hotels'],true) : array();
$start_date = isset($_REQUEST['start_date']) && !empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date("Y-m-d", strtotime($DATE. ' + 1 days'));
$end_date = isset($_REQUEST['end_date']) && !empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date("Y-m-d", strtotime($DATE. ' + 2 days'));
$party = isset($_REQUEST['party']) && !empty($_REQUEST['party']) ? $_REQUEST['party'] : '[{"adults":2}]';
$lang = isset($_REQUEST['lang']) && !empty($_REQUEST['lang']) ? $_REQUEST['lang'] : "en_US";
$query_key = isset($_REQUEST['query_key']) && !empty($_REQUEST['query_key']) ? $_REQUEST['query_key'] : ""; // USE FOR DEBUGGING
$currency = "USD"; //isset($_REQUEST['currency']) && !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : "USD";
$user_country = isset($_REQUEST['user_country']) && !empty($_REQUEST['user_country']) ? $_REQUEST['user_country'] : "US";
$device_type = isset($_REQUEST['device_type']) && !empty($_REQUEST['device_type']) ? $_REQUEST['device_type'] : "d";

$errors['2'] = array("message" => array());

if (count($hotels)==0) { $errors['2']["message"][] = 'Missing hotels'; }
if ($start_date=="") { $errors['2']["message"][] = "Missing start_date"; }
if ($end_date=="") { $errors['2']["message"][] = "Missing end_date"; }
if ($party=="") { $errors['2']["message"][] = "Missing party"; }
if ($query_key=="") { $errors['2']["message"][] = "Missing query_key"; }

if (count($errors['2']["message"])==0) unset($errors['2']);

$RESULT = array(
  "api_version" => $api_version,
  "hotel_ids" => array(),
  "start_date" => $start_date,
  "end_date" => $end_date,
  "party" => json_decode($party, true),
  "lang" => $lang,
  "query_key" => $query_key,
  "currency" => $currency,
  "user_country" => $user_country,
  "device_type" => $device_type,
  "num_hotels" => count($hotels),
  "hotels" => array()
);

//$errors['1']["message"][] = "Unknown Error"; 
$isOK = true;
//$isOK = false;

if ($isOK && count($errors)==0) {

  $PARTY_ROOMS = json_decode($party, true);
  $ROOMS_QTY = count($PARTY_ROOMS);
    //print "<pre>";print_r($PARTY_ROOMS);print "</pre>";
    //print "ok 3: $party";exit;

  $ts1 = strtotime($start_date.' 01:01:01 GMT');
  $ts2 = strtotime($end_date.' 01:01:01 GMT');
  $seconds_diff = $ts2 - $ts1;
  $RES_NIGHTS = floor($seconds_diff/(24*60*60));

  $QRYSTRPRMS = array(
    'useSlaveDB' => 1,
    'PAGE_CODE' => 'ws.availability',
    'ACTION' => 'SUBMIT',
    'RES_SRC' => 'GP',
    'RES_IN_THE_FUTURE' => '0',
    'RES_DATE' => date("Y-m-d"),
    'RES_USERTYPE[]' => '1',
  );

  $QRYSTR = array(
    'RES_CHECK_IN' => $start_date,
    'RES_CHECK_OUT' => $end_date,
    'USER_COUNTRY' => $user_country,
    'RES_COUNTRY_CODE' => $user_country,
    'RES_LANGUAGE' => "EN",
    'RES_NIGHTS' => $RES_NIGHTS,
    'RES_PROP_ID' => "",
    'RES_ROOMS_QTY' => (int)$ROOMS_QTY
  );

  //print "<pre>";print_r($PARTY_ROOMS);print "</pre>";exit;

  $ASK_CRIB = false;
  $MAX_ADULTS = 0;
  $MAX_CHILDREN = 0;
  $CHILDREN_QTY = 0;
  for ($ROOM_NUM=1;$ROOM_NUM<=(int)$ROOMS_QTY; ++$ROOM_NUM) {
    $ADULTS_QTY = $PARTY_ROOMS[$ROOM_NUM-1]['adults'];
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_ADULTS_QTY'] = $ADULTS_QTY;
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY'] = $CHILDREN_QTY;

    if (isset($PARTY_ROOMS[$ROOM_NUM-1]['children']) && count($PARTY_ROOMS[$ROOM_NUM-1]['children'])!=0) {
        $CHILDREN_QTY = count($PARTY_ROOMS[$ROOM_NUM-1]['children']);
        $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY'] = $CHILDREN_QTY;
        for ($CHILD_NUM=0;$CHILD_NUM<$CHILDREN_QTY; ++$CHILD_NUM) {
          $CHILD_AGE = $PARTY_ROOMS[$ROOM_NUM-1]['children'][$CHILD_NUM];
          $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.($CHILD_NUM+1)] = $CHILD_AGE <= 12 ? $CHILD_AGE : 12;
          $ASK_CRIB = $CHILD_AGE < 3 ? true : $ASK_CRIB;
        }
        $MAX_ADULTS = $MAX_ADULTS==0||$MAX_ADULTS<$ADULTS_QTY ? $ADULTS_QTY : $MAX_ADULTS;
        $MAX_CHILDREN = $MAX_CHILDREN==0||$MAX_CHILDREN<$CHILDREN_QTY ? $CHILDREN_QTY : $MAX_CHILDREN;
    }
  }

  //print "<pre>";print_r($QRYSTR);print "</pre>";exit;

  $PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC","XOB");
  $hotel_ids = array();

  foreach ($hotels as $h => $hotel) {
    $partner_id = strstr($_SERVER["HTTP_HOST"],"finestresorts")!==FALSE ? 5 : $hotel['partner_id'];
    $hotel_id = $hotel['ta_id'];
    $hotel_ids[] = $hotel_id;
    
    $QRYSTR['RES_PROP_ID'] = (int)$partner_id;
    $RES_PROP_CODE = isset($PROP_CODES[$partner_id]) ? $PROP_CODES[$partner_id] : "";

    if ($QRYSTR['RES_PROP_ID'] == "0") {
        if (!isset($errors['3'])) {
          $errors['3'] = array(
            "message" => array("Unknown partner_id {$partner_id}"),
          );
        } else {
          $errors['3']["message"][] = "Unknown partner_id {$partner_id}";
        }
    } else {

        $IPA_ACTION = "REQUEST";
        include "get_domain.php";

        //print "DOMAIN_URL: $DOMAIN_URL";exit;
        
        //$RESULT['query_key'] = $call_ibe;

        $_AVAILABILITY = json_decode($json, true);
        //print_r($_AVAILABILITY);

        $LANG = $_AVAILABILITY['RES_LANGUAGE'];
        $RES_ITEMS = $_AVAILABILITY['RES_ITEMS'];

        $_HOTEL = array(
          "hotel_id" => $hotel_id,
          "room_types" => array()
        );

        $_ROOM_TYPES = array();
        $_IS_HOTEL_AVAILABLE = false;

        for ($ROOM_NUM=1;$ROOM_NUM<=(int)$ROOMS_QTY; ++$ROOM_NUM) {

          $IS_AVAILABLE = count($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;

          if ($IS_AVAILABLE) {

            foreach ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
              $ROOM_ITEMS = $RES_ITEMS[$ROOM_ID];
              $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
              $MAX_OCUP = (int)$ROOM_ITEMS["MAX_OCUP"];
              $MAX_ADUL = (int)$ROOM_ITEMS["MAX_ADUL"];
              $MAX_CHIL = (int)$ROOM_ITEMS["MAX_CHIL"];
              $IS_AVAILABLE = ($AVAILABLE_NIGHTS == $RES_NIGHTS) ? true : false;
              if ($IS_AVAILABLE && ($MAX_ADULTS+$MAX_CHILDREN > $MAX_OCUP || $MAX_ADULTS > $MAX_ADUL || $MAX_CHILDREN > $MAX_CHIL)) {
                $IS_AVAILABLE = false;
              }
              if ($IS_AVAILABLE) {
                  $_IS_HOTEL_AVAILABLE = true;
                  $ROOM_TYPE = array();
                  
                  $ROOM_NAME = substr(trim($ROOM_ITEMS["NAME_$LANG"]),0,100);
                  $ROOM_DESCR = $ROOM_ITEMS["DESCR_$LANG"];
                  $ROOM_INCLU = $ROOM_ITEMS["INCLU_$LANG"];
                  $SPECIALS = $ROOM['SPECIAL_NAMES'];

                  $GROSS = (int)$ROOM['TOTAL']['GROSS'];
                  $FINAL = (int)$ROOM['TOTAL']['FINAL'];

                  // ONLY IN THE EVENT THAT ONLY ALLOW BOOKING SAME ROOM TYPE FOR MULTI-ROOMS. OTHERWISE THE PRICE IS PER SINGLE ROOM
                  $GROSS *= (int)$ROOMS_QTY;
                  $FINAL *= (int)$ROOMS_QTY;

                  $discounts = array();
                  if (is_array($SPECIALS)) {
                    foreach ($SPECIALS as $SPECIAL_ID => $REFERENCE) {
                      $SPECIAL_ITEM = $RES_ITEMS[$SPECIAL_ID];
                      $discount = array(
                          "marketing_text" => $SPECIAL_ITEM["NAME_$LANG"],
                          "is_percent" => true,
                          "amount" => (int)$SPECIAL_ITEM["OFF"],
                          "price" => $GROSS - $FINAL,
                          "fees" => 0,
                          "fees_at_checkout" => 0,
                          "taxes" => 0,
                          "taxes_at_checkout" => 0,
                          "final_price" => $GROSS - $FINAL                    
                      );
                    }
                    $discounts[] = $discount;
                  }
                  
                  $ROOM_TYPE = array(
                    "url" => $call_front,
                    "price" => $FINAL,
                    "taxes" => 0,
                    "taxes_at_checkout" => 0,
                    "fees" => 0,
                    "fees_at_checkout" => 0,
                    "final_price" => $FINAL,
                    "currency" => $currency,
                    "num_rooms" => (int)$ROOM['TOTAL']["ROOMS_LEFT"] <= 10 ? (int)$ROOM['TOTAL']["ROOMS_LEFT"] : 10,
                    "discounts" => $discounts,
                  );

                  $_ROOM_TYPES[$ROOM_NAME] = $ROOM_TYPE;
              }
            }
          }
        }

        $_HOTEL["room_types"] = $_ROOM_TYPES;

        if ($_IS_HOTEL_AVAILABLE) {
          $RESULT['hotels'][] = $_HOTEL;
        }

    }
  }
  $RESULT["hotel_ids"] = $hotel_ids;
}

if (!$isOK || count($errors)!=0) {
  foreach ($hotels as $h => $hotel) {
    $RESULT["hotel_ids"][] = $hotel['ta_id'];
    $RESULT['hotels'][] = array(
      "hotel_id" => $hotel['ta_id'],
    );
  }
  $RESULT['hotels'] = array();

  foreach ($errors as $code=>$error) {
    $RESULT['errors'][] = array(
      "error_code" => $code,
      "message" => join(", ", $error["message"]),
      "hotel_ids" => $RESULT["hotel_ids"],
    );
  }
} 

$RESULT["num_hotels"] = count($RESULT["hotels"]);

//print " ok ";exit;

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RESULT);exit;

