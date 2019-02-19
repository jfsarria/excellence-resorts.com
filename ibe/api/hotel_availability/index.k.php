<?
/*
 * Revised: Nov 05, 2013
 *          Nov 24, 2014
 *
 */

/* 
 * http://www.webdnstools.com/articles/plesk/apache_configuration
 * 1. SSH as root
 * 2. /var/www/vhosts/fromps.com/conf
 *    /var/www/vhosts/system/finestresorts.com/conf
 * 3. create vhost.conf
 * 4. Alias /hotel_availability /var/www/vhosts/fromps.com/httpdocs/ibe/api/hotel_availability/index.php
 *                              /var/www/vhosts/iskullny.com/finestresorts/ibe/api/hotel_availability/index.php
 * 5. sudo /usr/local/psa/admin/sbin/websrvmng -u --vhost-name=finestresorts.com
 * 6. sudo /etc/init.d/httpd restart
 *
 */

/*

http://excellence-resorts.com/ibe/index.php
PAGE_CODE=ws.availability
ACTION=SUBMIT
RES_SRC=GP
RES_IN_THE_FUTURE=0
RES_DATE=2013-09-15
RES_COUNTRY_CODE=US
RES_USERTYPE[]=1
RES_LANGUAGE=EN
RES_NIGHTS=2
RES_PROP_ID=1

RES_CHECK_IN=2013-11-15
RES_CHECK_OUT=2013-11-17
RES_ROOMS_QTY=1
RES_ROOM_1_ADULTS_QTY=2
RES_ROOM_1_CHILDREN_QTY=0

http://excellence-resorts.com/hotel_availability?api_version=4&hotels=[{"ta_id":499896,"partner_id":"1","partner_url":"http://excellence-resorts.com"},{"ta_id":649432,"partner_id":"2","partner_url":"http://excellence-resorts.com"},{"ta_id":218524,"partner_id":"3","partner_url":"http://excellence-resorts.com"}]&start_date=2013-11-15&end_date=2013-11-19&num_adults=2&lang=en_US&num_rooms=2&lang=en_US&currency=USD&user_country=US&device_type=d&query_key=stress_test

http://secure-belovedhotels.com/hotel_availability/?api_version=4&&hotels=[{"ta_id":1180633,"partner_id":"1","partner_url":"http://"}]&start_date=2013-10-27&end_date=2013-10-29&num_adults=2&lang=en_US&num_rooms=2&lang=en_US&currency=USD&user_country=US&device_type=d&query_key=stress_test

http://www.finestresorts.com/hotel_availability/?api_version=5&hotels=[{"ta_id":6966139,"partner_id":"1","partner_url":"http://www.finestresorts.com"}]&start_date=2015-07-01&end_date=2015-07-03&party=[{"adults": 3},{"adults": 2, "children": [9,5]}]&lang=en_US&currency=USD&user_country=US&device_type=d&query_key=stress_test

http://staging.finestresorts.com/ibe/api/hotel_availability/?api_version=5&&hotels=[{%22ta_id%22:6966139,%22partner_id%22:%221%22,%22partner_url%22:%22http://www.finestresorts.com%22}]&start_date=2015-10-27&end_date=2015-10-29&num_adults=2&lang=en_US&num_rooms=2&lang=en_US&currency=USD&user_country=US&device_type=d&query_key=stress_test

1 - XRC - 499896
2 - XPM - 649432
3 - XPC - 218524
4 - TBH - 1180633
5 - FPM - 6966139

*/

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/New_York');

//print_r($_GET);

$errors = array();
$RESULT = array();

$api_version = isset($_REQUEST['api_version']) ? $_REQUEST['api_version'] : "";
$hotels = isset($_REQUEST['hotels']) ? json_decode($_REQUEST['hotels'],true) : "";
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : "";
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : "";
//$num_adults = isset($_REQUEST['num_adults']) ? $_REQUEST['num_adults'] : "";
//$num_rooms = isset($_REQUEST['num_rooms']) ? $_REQUEST['num_rooms'] : "";
$party = isset($_REQUEST['party']) ? $_REQUEST['party'] : "[]";
$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : "";
$currency = "USD";//isset($_REQUEST['currency']) ? $_REQUEST['currency'] : "";
$user_country = isset($_REQUEST['user_country']) ? $_REQUEST['user_country'] : "";
$device_type = isset($_REQUEST['device_type']) ? $_REQUEST['device_type'] : "";
$query_key = isset($_REQUEST['query_key']) ? $_REQUEST['query_key'] : "";

$errors['2'] = array("message" => array());

if ($api_version=="") { $errors['2']["message"][] = "Missing api_version"; }
if ($hotels=="") { $errors['2']["message"][] = "Missing hotels"; }
if ($start_date=="") { $errors['2']["message"][] = "Missing start_date"; }
if ($end_date=="") { $errors['2']["message"][] = "Missing end_date"; }
if ($party=="") { $errors['2']["message"][] = "Missing party"; }
//if ($num_adults=="") { $errors['2']["message"][] = "Missing num_adults"; }
//if ($num_rooms=="") { $errors['2']["message"][] = "Missing num_rooms"; }
//if ($currency=="") { $errors['2']["message"][] = "Missing currency"; }
if ($lang=="") { $errors['2']["message"][] = "Missing lang"; }
if ($user_country=="") { $errors['2']["message"][] = "Missing user_country"; }
if ($device_type=="") { $errors['2']["message"][] = "Missing device_type"; }
if ($query_key=="") { $errors['2']["message"][] = "Missing query_key"; }

if (count($errors['2']["message"])==0) unset($errors['2']);

if (count($errors)==0) {

  $PARTY_ROOMS = json_decode($party, true);
  $num_rooms = count($PARTY_ROOMS);
  $ts1 = strtotime($start_date);
  $ts2 = strtotime($end_date);
  $seconds_diff = $ts2 - $ts1;
  $RES_NIGHTS = floor($seconds_diff/3600/24);

  $QRYSTRPRMS = array(
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
    'RES_LANGUAGE' => $lang=="es_ES" || $lang=="es_AR" || $lang=="es_MX" ? "SP" : "EN",
    'RES_NIGHTS' => $RES_NIGHTS,
    'RES_PROP_ID' => "",
    'RES_ROOMS_QTY' => (int)$num_rooms
  );

  //print "<pre>";print_r($PARTY_ROOMS);print "</pre>";exit;

  for ($ROOM_NUM=1;$ROOM_NUM<=(int)$num_rooms; ++$ROOM_NUM) {
    $CHILDREN = isset($PARTY_ROOMS[$ROOM_NUM-1]['children']) ? count($PARTY_ROOMS[$ROOM_NUM-1]['children']) : 0;
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_ADULTS_QTY'] = $PARTY_ROOMS[$ROOM_NUM-1]['adults'];
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY'] = $CHILDREN;
    if ($CHILDREN!=0) {
        for ($CHILD_NUM=1;$CHILD_NUM<=$CHILDREN; ++$CHILD_NUM) {
            $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM] = $PARTY_ROOMS[$ROOM_NUM-1]['children'][$CHILD_NUM-1];
        }
    }
  }

  $RESULT = array(
    'api_version' => (int)$api_version,
    'hotel_ids' => array(),
    'start_date' => $start_date,
    'end_date' => $end_date,
    //'num_adults' => (int)$num_adults,
    //'num_rooms' => (int)$num_rooms,
    'party' => $PARTY_ROOMS,
    'currency' => $currency,
    'user_country' => $user_country,
    'device_type' => $device_type,
    'query_key' => $query_key,
    'lang' => $lang,
    'num_hotels' => 0,
    'hotels' => array()
  );

  foreach ($hotels as $h => $hotel) {
    $ta_id = $hotel['ta_id'];
    $partner_id = $hotel['partner_id'];
    
    $QRYSTR['RES_PROP_ID'] = "0";

    if ($partner_id=="1") {
      if (strstr($_SERVER["HTTP_HOST"],"excellence-resorts.com")!==FALSE) {
        $QRYSTR['RES_PROP_ID'] = "1";
        $ROOMS_URL = "http://www.excellence-resorts.com/guest-suites/suites-at-riviera-cancun";
      } else if (strstr($_SERVER["HTTP_HOST"],"finestresorts.com")!==FALSE) {
        $QRYSTR['RES_PROP_ID'] = "5";
        $ROOMS_URL = "http://www.finestresorts.com/suites/";
      } else {
        $QRYSTR['RES_PROP_ID'] = "4";
        $ROOMS_URL = "http://www.belovedhotels.com/luxury-suites/";
      }
    } elseif ($partner_id=="2") {
      $QRYSTR['RES_PROP_ID'] = "2";
      $ROOMS_URL = "http://www.excellence-resorts.com/guest-suites/suites-at-playa-mujeres";
    } elseif ($partner_id=="3") {
      $QRYSTR['RES_PROP_ID'] = "3";
      $ROOMS_URL = "http://www.excellence-resorts.com/guest-suites/suites-at-punta-cana";
    }

    if ($QRYSTR['RES_PROP_ID'] == "0") {
      if (!isset($errors['3'])) {
        $errors['3'] = array(
          "message" => array("Unknown partner_id {$partner_id}"),
          "hotel_ids" => array($ta_id)
        );
      } else {
        $errors['3']["message"][] = "Unknown partner_id {$partner_id}";
        $errors['3']["hotel_ids"][] = $ta_id;
      }
    } else {

      if (strstr($_SERVER["HTTP_HOST"],"excellence-resorts.com")!==FALSE) {
        $book_url = 'https://www.excellence-resorts.com/er/?' . http_build_query($QRYSTR);
        $ibe_url = "http://excellence-resorts.com/ibe/index.php";
      } else if (strstr($_SERVER["HTTP_HOST"],"finestresorts.com")!==FALSE) {
        $book_url = 'https://www.finestresorts.com/booking/index.php?' . http_build_query($QRYSTR);
        $ibe_url = "http://www.finestresorts.com/ibe/index.php";
      } else {
        $book_url = 'https://secure-belovedhotels.com/booking/index.php?' . http_build_query($QRYSTR);
        $ibe_url = "http://secure-belovedhotels.com/ibe/index.php";
      }

      $QRYSTR = array_merge($QRYSTRPRMS, $QRYSTR);
      $ibe_url = $ibe_url . '?' . http_build_query($QRYSTR);
      $json = file_get_contents($ibe_url);

      //print $ibe_url;exit;
      //print $json;exit;

      $_AVAILABILITY = json_decode($json, true);
      //print_r($_AVAILABILITY);

      $HOTEL = array(
        'hotel_id' => $ta_id,  
        'room_types' => array()
      );

      for ($ROOM_NUM=1;$ROOM_NUM<=(int)$num_rooms; ++$ROOM_NUM) {

        $IS_AVAILABLE = count($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;

        if ($IS_AVAILABLE) {
         
          foreach ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
            $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
            $IS_AVAILABLE = ($AVAILABLE_NIGHTS == $RES_NIGHTS) ? true : false;
            if ($IS_AVAILABLE) {
              $ROOM_DESCR = $_AVAILABILITY['RES_ITEMS'][$ROOM_ID]['DESCR_'.$_AVAILABILITY['RES_LANGUAGE']];
              $ROOM_NAME = $_AVAILABILITY['RES_ITEMS'][$ROOM_ID]['NAME_'.$_AVAILABILITY['RES_LANGUAGE']];
              if (!isset($HOTEL['room_types'][$ROOM_NAME])) {
                $HOTEL['room_types'][$ROOM_NAME] = array(
                  'url' => $book_url,
                  'price' => (int)$ROOM['TOTAL']['FINAL'],
                  'fees' => 0,
                  'taxes' => 0,
                  'final_price' => (int)$ROOM['TOTAL']['FINAL'],
                  //'discounts' => array(),
                  'currency' => $currency,
                  'num_rooms' => 1,//(int)$num_rooms,
                  //'room_amenities' => array()
                );
              } else {
                $HOTEL['room_types'][$ROOM_NAME]['price'] += (int)$ROOM['TOTAL']['FINAL'];
                $HOTEL['room_types'][$ROOM_NAME]['final_price'] += (int)$ROOM['TOTAL']['FINAL'];
              }
            }
          }
        }
      }

      $RESULT['hotel_ids'][] = $ta_id;

      if (count($HOTEL['room_types'])!=0) {
        $RESULT['hotels'][] = $HOTEL;
      }
    }
  }
}

if (count($errors)!=0) {
  if (isset($RESULT["num_hotels"])) { unset($RESULT["num_hotels"]); }
  if (isset($RESULT["hotels"])) { unset($RESULT["hotels"]); }
  foreach ($errors as $code=>$error) {
    $RESULT['errors'][] = array(
      "error_code" => $code,
      "message" => join(", ", $error["message"]),
      //"hotel_ids" => ""//$error["hotel_ids"]
    );
  }
} else {
  $RESULT['num_hotels'] = count($RESULT['hotels']);
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RESULT);



?>
