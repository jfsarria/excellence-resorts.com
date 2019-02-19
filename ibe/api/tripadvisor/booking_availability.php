<?
/*
 * Booking Availability
 *
 * Revised: Jan 21, 2015
 *          Nov 26, 2017
 *
 */

/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

date_default_timezone_set('America/New_York');

//print_r($_GET);exit;

$errors = array();
$RESULT = array();

$api_version = 7;
$hotels = isset($_REQUEST['hotel']) ? array(json_decode($_REQUEST['hotel'],true)) : "";
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : "";
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : "";
$party = isset($_REQUEST['party']) ? $_REQUEST['party'] : "[]";
$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : "en_US";
$query_key = isset($_REQUEST['query_key']) ? $_REQUEST['query_key'] : ""; // USE FOR DEBUGGING
$currency = "USD"; //isset($_REQUEST['currency']) ? $_REQUEST['currency'] : "USD";
$user_country = isset($_REQUEST['user_country']) ? $_REQUEST['user_country'] : "US";
$device_type = isset($_REQUEST['device_type']) ? $_REQUEST['device_type'] : "d";

$errors['2'] = array("message" => array());

if ($hotels=="") { $errors['2']["message"][] = 'Missing hotels. Example: [{"partner_id":"1","partner_url":"http://www.partnerurl.com"}]'; }
if ($start_date=="") { $errors['2']["message"][] = "Missing start_date"; }
if ($end_date=="") { $errors['2']["message"][] = "Missing end_date"; }
if ($party=="") { $errors['2']["message"][] = "Missing party: Example: 5 adults in 3 rooms would come in as [2, 2, 1]. "; }
//if ($query_key=="") { $errors['2']["message"][] = "Missing session_id"; }

if (count($errors['2']["message"])==0) unset($errors['2']);

if (count($errors)==0) {

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
    'RES_COUNTRY_CODE' => $user_country,
    'RES_LANGUAGE' => "EN",
    'RES_NIGHTS' => $RES_NIGHTS,
    'RES_PROP_ID' => "",
    'RES_ROOMS_QTY' => (int)$ROOMS_QTY
  );

  //print "<pre>";print_r($PARTY_ROOMS);print "</pre>";exit;

  $ASK_CRIB = 0;
  $MAX_ADULTS = 0;
  $MAX_CHILDREN = 0;
  $CHILDREN_QTY = 0;
  for ($ROOM_NUM=1;$ROOM_NUM<=(int)$ROOMS_QTY; ++$ROOM_NUM) {
    $ADULTS_QTY = $PARTY_ROOMS[$ROOM_NUM-1]['adults'];
    $MAX_ADULTS = $MAX_ADULTS==0||$MAX_ADULTS<$ADULTS_QTY ? $ADULTS_QTY : $MAX_ADULTS;
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_ADULTS_QTY'] = $ADULTS_QTY;
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY'] = $CHILDREN_QTY;

    if (isset($PARTY_ROOMS[$ROOM_NUM-1]['children']) && count($PARTY_ROOMS[$ROOM_NUM-1]['children'])!=0) {
        $CHILDREN_QTY = count($PARTY_ROOMS[$ROOM_NUM-1]['children']);
        $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY'] = $CHILDREN_QTY;
        for ($CHILD_NUM=0;$CHILD_NUM<$CHILDREN_QTY; ++$CHILD_NUM) {
          $CHILD_AGE = $PARTY_ROOMS[$ROOM_NUM-1]['children'][$CHILD_NUM];
          $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.($CHILD_NUM+1)] = $CHILD_AGE <= 12 ? $CHILD_AGE : 12;
          $ASK_CRIB += $CHILD_AGE < 3 ? 1 : 0;
        }
        $MAX_CHILDREN = $MAX_CHILDREN==0||$MAX_CHILDREN<$CHILDREN_QTY ? $CHILDREN_QTY : $MAX_CHILDREN;
    }
  }

  //print "<pre>";print_r($QRYSTR);print "</pre>";exit;

  $RESULT = array(
    "api_version" => $api_version,
    "hotel_id" => 0,
    "start_date" => $start_date,
    "end_date" => $end_date,
    "party" => json_decode($party, true),
    "lang" => $lang,
    "query_key" => $query_key,
    "user_country" => $user_country,
    "device_type" => $device_type,
    "hotel_room_types" => array(),
    "hotel_rate_plans" => array(),
    "hotel_room_rates" => array(),
    "hotel_details" => array(),
    "accepted_credit_cards" => array("visa","MasterCard","AmericanExpress"),
    "customer_support" => array(),
    "terms_and_conditions" => "",
    "terms_and_conditions_url" => "",
    "payment_policy" => "",
  );

  $PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC","XOB");

  foreach ($hotels as $h => $hotel) {
    $partner_id = strstr($_SERVER["HTTP_HOST"],"finestresorts")!==FALSE ? 5 : $hotel['partner_hotel_code'];
    $hotel_id = $hotel['ta_id'];
    
    $QRYSTR['RES_PROP_ID'] = (int)$partner_id;
    $RES_PROP_CODE = isset($PROP_CODES[$partner_id]) ? $PROP_CODES[$partner_id] : "";

    if ($QRYSTR['RES_PROP_ID'] == "0" || empty($RES_PROP_CODE)) {
        if (!isset($errors['3'])) {
          $errors['3'] = array(
            "message" => array("Unknown partner_hotel_code {$partner_id}"),
          );
        } else {
          $errors['3']["message"][] = "Unknown partner_id {$partner_id}";
        }
    } else {

        $IPA_ACTION = "REQUEST";
        include "get_domain.php";

        //print "DOMAIN_URL: $DOMAIN_URL";exit;

        //$RESULT['query_key'] = $call_ibe;
        //print $call_ibe;exit;
        //print $json;exit;

        $_AVAILABILITY = json_decode($json, true);
        //print $call_ibe;exit;
        //print $json;exit;

        //ob_start();print_r($_AVAILABILITY);$ARR = ob_get_clean();mail("juan.sarria@everlivesolutions.com","AVAILABILITY",$ARR);
        //ob_start();print_r($_AVAILABILITY);$ARR = ob_get_clean();mail("jaunsarria@gmail.com","_AVAILABILITY",$ARR);

        $LANG = $_AVAILABILITY['RES_LANGUAGE'];
        $RES_ITEMS = $_AVAILABILITY['RES_ITEMS'];
        $PROPERTY = $RES_ITEMS['PROPERTY'];
        $CANCELLATION_POLICY = $RES_ITEMS['CANCELLATION_POLICY'];
        ob_start();
          include "terms.txt";
        $TERMS = ob_get_clean();
        $DAYS_LEFT = isset($RES_ITEMS["DEADLINE"]) ? (int)$RES_ITEMS["DEADLINE"]["DAYS_LEFT"] : 1;
        $FEES = array();

        if ($DAYS_LEFT <= 4) {
            $FEES[] = array(
              "currency" => "USD",
              "amount_type" => "percent",
              "amount" => 100,
              "tax_inclusive" => true
            );

            $CANCELLATION_FEES = array(
              "refundable" => "none",
              "cancellation_rules" => array(
                  "penalty_exists" => true,
                  "policy_info" => $RES_ITEMS["CANCELLATION_POLICY"],
                  "fees" => $FEES
              )
            );
        } else if ($DAYS_LEFT >= 5 && $DAYS_LEFT <= 6) {
            $FEES[] = array(
              "currency" => "USD",
              "amount_type" => "numNights",
              "amount" => 2,
              "tax_inclusive" => true,
              "days_before_arrival" => 2
            );

            $CANCELLATION_FEES = array(
              "refundable" => "partial",
              "cancellation_rules" => array(
                  "penalty_exists" => true,
                  "policy_info" => $RES_ITEMS["CANCELLATION_POLICY"],
                  "fees" => $FEES
              )
            );
        } else {
            $FEES[] = array(
              "currency" => "USD",
              "amount_type" => "numNights",
              "amount" => 2,
              "tax_inclusive" => true,
              "days_before_arrival" => 6                      
            );

            $FEES[] = array(
              "currency" => "USD",
              "amount_type" => "percent",
              "amount" => 100,
              "tax_inclusive" => true,
              "days_before_arrival" => 4
            );

            $CANCELLATION_FEES = array(
              "refundable" => "full",
              "cancellation_rules" => array(
                  "deadline" => $RES_ITEMS["DEADLINE"]["MINUS_7"]."T23:59:59",
                  "penalty_exists" => true,
                  "policy_info" => $RES_ITEMS["CANCELLATION_POLICY"],
                  "fees" => $FEES
              )
            );
        }

        $_ROOM_TYPES = array();
        $_RATE_PLANS = array();
        $_ROOM_RATES = array();
        $_IS_HOTEL_AVAILABLE = false;

        for ($ROOM_NUM=1;$ROOM_NUM<=(int)$ROOMS_QTY; ++$ROOM_NUM) {

          $IS_AVAILABLE = count($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;

          if ($IS_AVAILABLE) {

            foreach ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
              $ROOM_ITEMS = $RES_ITEMS[$ROOM_ID];

              $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
              $CLASS_NAMES = isset($ROOM['CLASS_NAMES']) ? $ROOM['CLASS_NAMES'] : array();
              $MAX_OCUP = (int)$ROOM_ITEMS["MAX_OCUP"];
              $MAX_ADUL = (int)$ROOM_ITEMS["MAX_ADUL"]==0 ? $MAX_OCUP : (int)$ROOM_ITEMS["MAX_ADUL"];
              $MAX_CHIL = (int)$ROOM_ITEMS["MAX_CHIL"];
              $IS_AVAILABLE = $AVAILABLE_NIGHTS == $RES_NIGHTS && count($CLASS_NAMES)==1 ? true : false;
              //mail("jaunsarria@gmail.com","MAX",$ROOM_NAME."\n".$IS_AVAILABLE."-".$MAX_ADULTS."-".$MAX_CHILDREN."-".$MAX_OCUP."-".$MAX_ADUL."-".$MAX_CHIL);
              if ($IS_AVAILABLE && ($MAX_ADULTS+$MAX_CHILDREN > $MAX_OCUP || $MAX_ADULTS > $MAX_ADUL || $MAX_CHILDREN > $MAX_CHIL)) {
                $IS_AVAILABLE = false;
              }
              if ($IS_AVAILABLE) {
                  $_IS_HOTEL_AVAILABLE = true;
                  $ROOM_TYPE = array();

                  $ROOM_CLAVE = trim($ROOM_ITEMS["CLAVE"]);
                  $ROOM_NAME = substr(trim($ROOM_ITEMS["NAME_$LANG"]),0,100);
                  $ROOM_DESCR = $ROOM_ITEMS["DESCR_$LANG"];
                  $ROOM_INCLU = $ROOM_ITEMS["INCLU_$LANG"];
                  $FINAL = (int)$ROOM['TOTAL']['FINAL'];
                  $BEDS = $ROOM_ITEMS['BEDS'];
                  $IMAGES = $ROOM_ITEMS['IMAGES'];
                  $BEDS = array();
                  $BEDS[] = array("type"=>"standard","code"=>3,"count"=>1);

                  $bed_configurations = array();
                  $bed_configurations[] = $BEDS;

                  $extra_bed_configurations = array(array("type"=>"custom","name"=>"Rollaway with wheel locks and adjustable height","count"=>1));
                  if ($ASK_CRIB!=0) {
                    $extra_bed_configurations[] = array("type"=>"standard","code"=>900302,"count"=>$ASK_CRIB);
                  }

                  $ROOM_AMENITIES = !empty($ROOM_ITEMS["TA_AMENITIES_EN"]) ? explode(",",$ROOM_ITEMS["TA_AMENITIES_EN"]) : array();
                  $ROOM_VIEW_TYPE = !empty($ROOM_ITEMS["TA_VIEWTYPE_EN"]) ? explode(",",$ROOM_ITEMS["TA_VIEWTYPE_EN"]) : array();
                  $ACCESSIBILITY_FEATURES = !empty($ROOM_ITEMS["TA_ACCESSIBILITY_EN"]) ? explode(",",$ROOM_ITEMS["TA_ACCESSIBILITY_EN"]) : array();

                  $ROOM_TYPE = array(
                    "code" => "ID_".$ROOM_ID,//!empty($ROOM_CLAVE) ? $ROOM_CLAVE : $ROOM_ID,
                    "name" => $ROOM_NAME,
                    "description" => $ROOM_DESCR,
                    "photos" => get_room_photos($IMAGES, $ibe_url),
                    "room_amenities" => array("standard"=>$ROOM_AMENITIES,"custom"=>array()),
                    "room_size_value" => 500,
                    "room_size_units" => "square_feet",
                    "bed_configurations" => $bed_configurations,
                    "extra_bed_configurations" => array($extra_bed_configurations),
                    "room_view_type" => array("standard"=>$ROOM_VIEW_TYPE,"custom"=>array()),
                    "accessibility_features" => array("standard"=>$ACCESSIBILITY_FEATURES,"custom"=>array()),
                    "max_occupancy" => array("number_of_adults"=>$MAX_ADUL,"number_of_children"=>$MAX_CHIL),
                    "room_smoking_policy" => "non_smoking",
                  );

                  $_ROOM_TYPES["ID_".$ROOM_ID] = $ROOM_TYPE;

                  //print "<pre>";print_r($CLASS_NAMES);print "</pre>";
                  foreach ($CLASS_NAMES as $CLASS_ID => $CLASS_NAME) {
                      $CLASS = $RES_ITEMS[$CLASS_ID];
                      $RATE_AMENITIES = !empty($CLASS["TA_AMENITIES_EN"]) ? explode(",",$CLASS["TA_AMENITIES_EN"]) : array();

                      $_RATE_PLAN = array(
                        "name" => $CLASS["NAME_$LANG"],
                        "code" => "ID_".$CLASS_ID,
                        "description" => $CLASS["NAME_$LANG"],
                        "photos" => array(),
                        "rate_amenities" => array("standard"=>$RATE_AMENITIES,"custom"=>array()),
                      );

                      $_RATE_PLAN += $CANCELLATION_FEES;

                      $_RATE_PLANS["ID_".$CLASS_ID] = $_RATE_PLAN;

                      $_ROOM_RATES[] = array(
                          "final_price_at_booking" => array(
                              "amount" => $FINAL,
                              "currency" => $currency
                          ),
                          "final_price_at_checkout" => array(
                              "amount" => 0,
                              "currency" => $currency
                          ),
                          "hotel_room_type_code" => "ID_".$ROOM_ID,
                          "hotel_rate_plan_code" => "ID_".$CLASS_ID,
                          "partner_data" => array(
                             "code" => $PROPERTY['CODE'],
                             "id" => "{$QRYSTR['RES_PROP_ID']}",
                             "url" => $ibe_url,
                             "room_id" => "ID_".$ROOM_ID
                          ),
                          "line_items" => array(
                              array(
                                  "price" => array(
                                      "amount" => $FINAL,
                                      "currency" => $currency
                                  ),
                                  "type" => "rate",
                                  "paid_at_checkout" => false,
                                  "description" => "This is the base rate."
                              )
                          ),
                          "payment_policy" => "Valid ID with proof of payment required at check in",
                          "rooms_remaining" => (int)$ROOM['TOTAL']["ROOMS_LEFT"]
                      );
                    
                  }

                  
              }
            }
          }
        }
    }

    $RESULT["hotel_id"] = $hotel_id;
    $RESULT["hotel_room_types"] = $_ROOM_TYPES;
    $RESULT["hotel_rate_plans"] = $_RATE_PLANS;
    $RESULT["hotel_room_rates"] = $_ROOM_RATES;
    $RESULT["hotel_details"] = array(
        "name" => $PROPERTY['NAME'],
        "phone" => $PROPERTY['PHONE'],
        "address1" => $PROPERTY['ADDRESS'],
        "city" => $PROPERTY['CITY'],
        "country" => $PROPERTY['COUNTRY'],
        "url" => $PROPERTY['HOME_URL'],
        "checkinout_policy" => "No Policy",
        "photos" => get_room_photos($PROPERTY['IMAGES'], $ibe_url),
        "child_policy" => $CHILD_POLICY,
        "extra_bed_policy_hotel" => "Rollaway beds on request"
        //'policy' => get_clean_html($PROPERTY["EMAIL_RES_$LANG"])
    );
    $RESULT["terms_and_conditions"] = substr($TERMS,0,999);
    $RESULT["payment_policy"] = substr($PROPERTY["EMAIL_CCDETAILS_$LANG"],0,999);
    $RESULT["terms_and_conditions_url"] = $PROPERTY['LEGAL_URL_EN'];

    $phone_numbers = array();
    $phone_numbers[] = array("contact"=>$PROPERTY['PHONE'],"description"=>"Support phone line");
    $RESULT["customer_support"] = array("phone_numbers"=>$phone_numbers);

  }
}

if (count($errors)!=0) {
  if (isset($RESULT["hotels"])) { unset($RESULT["hotels"]); }
  foreach ($errors as $code=>$error) {
    $RESULT['errors'][] = array(
      "error_code" => $code,
      "message" => join(", ", $error["message"]),
    );
  }
} else {

}

function get_room_photos($IMAGES, $ibe_url) {
    $OBJ = array();
    foreach ($IMAGES as $ID => $URL) {
        $OBJ[] = array(
            "url" => $ibe_url."/".$URL
        );
    }
    return $OBJ;
}

function get_clean_html($STR) {
    $STR = html_entity_decode($STR);
    $STR = str_replace("\r\n","<br>",$STR);
    return $STR;
}

//print " ok ";exit;

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RESULT);exit;

