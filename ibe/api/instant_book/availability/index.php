<?
/*
 * Availability Request
 *
 * Revised: Jan 21, 2015
 *          Jan 21, 2015
 *
 */


/* 
 * https://developer-tripadvisor.com/connectivity-solutions/instant-booking-api/get-started/
 /
 * http://www.webdnstools.com/articles/plesk/apache_configuration
 * 1. SSH as root
 * 2. /var/www/vhosts/fromps.com/conf
 *    /var/www/vhosts/system/finestresorts.com/conf
 * 3. create vhost.conf
 * 4. Alias /instant_availability /var/www/vhosts/fromps.com/httpdocs/ibe/api/instant_book/availability/index.php
 *                                /var/www/vhosts/iskullny.com/finestresorts/ibe/api/instant_book/availability/index.php
 * 5. sudo /usr/local/psa/admin/sbin/websrvmng -u --vhost-name=finestresorts.com
 * 6. sudo /etc/init.d/httpd restart
 *
 */

/*

http://staging.finestresorts.com/ibe/api/instant_book/availability/
session_id=stress_test
hotels=[{"partner_id":"1","partner_url":"http://www.finestresorts.com"}]
start_date=2015-07-01
end_date=2015-07-03
party=[2,2,1]

http://staging.finestresorts.com/ibe/api/instant_book/availability/?session_id=stress_test&hotels=[{"partner_id":"1","partner_url":"http://www.finestresorts.com"}]&start_date=2015-07-01&end_date=2015-07-03&party=[2,2,1]

http://staging.finestresorts.com/ibe/api/hotel_availability/?api_version=5&hotels=%5B{%22ta_id%22:6966139,%22partner_id%22:%221%22,%22partner_url%22:%22http://www.finestresorts.com%22}%5D&start_date=2015-07-01&end_date=2015-07-03&party=%5B{%22adults%22:%202},{%22adults%22:%202},{%22adults%22:%201}%5D&lang=en_US&currency=USD&user_country=US&device_type=d&query_key=stress_test

*/

error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/New_York');

//print_r($_GET);exit;

$errors = array();
$RESULT = array();

$hotels = isset($_REQUEST['hotels']) ? json_decode($_REQUEST['hotels'],true) : "";
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : "";
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : "";
$party = isset($_REQUEST['party']) ? $_REQUEST['party'] : "[]";
$query_key = isset($_REQUEST['session_id']) ? $_REQUEST['session_id'] : "";

$errors['2'] = array("message" => array());

if ($hotels=="") { $errors['2']["message"][] = 'Missing hotels. Example: [{"partner_id":"1","partner_url":"http://www.partnerurl.com"}]'; }
if ($start_date=="") { $errors['2']["message"][] = "Missing start_date"; }
if ($end_date=="") { $errors['2']["message"][] = "Missing end_date"; }
if ($party=="") { $errors['2']["message"][] = "Missing party: Example: 5 adults in 3 rooms would come in as [2, 2, 1]. "; }
if ($query_key=="") { $errors['2']["message"][] = "Missing session_id"; }

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
    'RES_COUNTRY_CODE' => "US",
    'RES_LANGUAGE' => "EN",
    'RES_NIGHTS' => $RES_NIGHTS,
    'RES_PROP_ID' => "",
    'RES_ROOMS_QTY' => (int)$num_rooms
  );

  //print "<pre>";print_r($PARTY_ROOMS);print "</pre>";exit;

  for ($ROOM_NUM=1;$ROOM_NUM<=(int)$num_rooms; ++$ROOM_NUM) {
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_ADULTS_QTY'] = $PARTY_ROOMS[$ROOM_NUM-1];
    $QRYSTR['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY'] = 0;
  }

  //print "<pre>";print_r($QRYSTR);print "</pre>";exit;

  $RESULT = array(
    'query_key' => $query_key,
    'party' => $PARTY_ROOMS,
    'start_date' => $start_date,
    'end_date' => $end_date,
    'currency' => "USD",
    'hotels' => array()
  );

  foreach ($hotels as $h => $hotel) {
    $partner_id = $hotel['partner_id'];
    
    $QRYSTR['RES_PROP_ID'] = "0";
    $DOMAIN_URL = "http://www.excellence-resorts.com";

    if ($partner_id=="1") {
      if (strstr($_SERVER["HTTP_HOST"],"excellence-resorts.com")!==FALSE) {
        $QRYSTR['RES_PROP_ID'] = "1";
      } else if (strstr($_SERVER["HTTP_HOST"],"finestresorts.com")!==FALSE) {
        $QRYSTR['RES_PROP_ID'] = "5";
        $PREFIX = strstr($_SERVER["HTTP_HOST"],"staging")==FALSE ? "www" : "staging";
        $DOMAIN_URL = "http://$PREFIX.finestresorts.com";
      } else {
        $QRYSTR['RES_PROP_ID'] = "4";
        $DOMAIN_URL = "http://www.belovedhotels.com";
      }
    } elseif ($partner_id=="2") {
      $QRYSTR['RES_PROP_ID'] = "2";
    } elseif ($partner_id=="3") {
      $QRYSTR['RES_PROP_ID'] = "3";
    }

    if ($QRYSTR['RES_PROP_ID'] == "0") {
      if (!isset($errors['3'])) {
        $errors['3'] = array(
          "message" => array("Unknown partner_id {$partner_id}"),
        );
      } else {
        $errors['3']["message"][] = "Unknown partner_id {$partner_id}";
      }
    } else {

      if (strstr($_SERVER["HTTP_HOST"],"excellence-resorts.com")!==FALSE) {
        $ibe_url = $DOMAIN_URL;
      } else if (strstr($_SERVER["HTTP_HOST"],"finestresorts.com")!==FALSE) {
        $ibe_url = $DOMAIN_URL;
      } else {
        $ibe_url = "http://secure-belovedhotels.com";
      }

      $QRYSTR = array_merge($QRYSTRPRMS, $QRYSTR);
      $call_ibe = $ibe_url . '/ibe/index.php?' . http_build_query($QRYSTR);
      $json = file_get_contents($call_ibe);

      //print $call_ibe;exit;
      //print $json;exit;

      $_AVAILABILITY = json_decode($json, true);
      //print_r($_AVAILABILITY);

      $LANG = $_AVAILABILITY['RES_LANGUAGE'];
      $RES_ITEMS = $_AVAILABILITY['RES_ITEMS'];
      $PROPERTY = $RES_ITEMS['PROPERTY'];
      $CANCELLATION_POLICY = $RES_ITEMS['CANCELLATION_POLICY'];
      ob_start();
        include "terms.txt";
      $TERMS = ob_get_clean();


      $HOTEL = array(
        'rooms' => array(),
        'hotel_details' => array(),
        'accepted_credit_cards' => array("visa","master card","american express"),
        'terms_conditions' => $TERMS,
        'payment_policy' => $PROPERTY["EMAIL_CCDETAILS_$LANG"]
      );

      for ($ROOM_NUM=1;$ROOM_NUM<=(int)$num_rooms; ++$ROOM_NUM) {

        $IS_AVAILABLE = count($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;

        if ($IS_AVAILABLE) {
         
          foreach ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
            $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
            $IS_AVAILABLE = ($AVAILABLE_NIGHTS == $RES_NIGHTS) ? true : false;
            if ($IS_AVAILABLE) {
                $ROOM_ITEMS = $RES_ITEMS[$ROOM_ID];
                $ROOM_NAME = $ROOM_ITEMS["NAME_$LANG"];
                $ROOM_DESCR = $ROOM_ITEMS["DESCR_$LANG"];
                $ROOM_INCLU = $ROOM_ITEMS["INCLU_$LANG"];
                $BEDS = $ROOM_ITEMS['BEDS'];
                $IMAGES = $ROOM_ITEMS['IMAGES'];
            
                $PRICE = (int)$ROOM['TOTAL']['FINAL'];
                $PRICE_OBJECT = array(array("price"=>$PRICE,"currency"=>"USD","type"=>"rate"));
                if (!isset($HOTEL['rooms'][$ROOM_ID])) {
                    $HOTEL['rooms'][$ROOM_ID] = array(
                        'room_key' => $ROOM_ID,
                        'short_description' => $ROOM_NAME,
                        'long_description' => $ROOM_DESCR,
                        'room_code' => get_bed_codes($BEDS, $PROPERTY['BED_TYPES']),
                        'booking_price' => $PRICE,
                        'checkout_price' => $PRICE,
                        'price_breakdown' => $PRICE_OBJECT,
                        'amenities' => $ROOM_INCLU,
                        'room_photos' => get_room_photos($IMAGES, $ibe_url),
                        'refundable' => "Partial Refund",
                        'cancellation_policy' => $CANCELLATION_POLICY,
                    );
                } else {
                    $HOTEL['rooms'][$ROOM_ID]['booking_price'] += $PRICE;
                    $HOTEL['rooms'][$ROOM_ID]['checkout_price'] += $PRICE;
                    $HOTEL['rooms'][$ROOM_ID]['price_breakdown'][0]['price'] += $PRICE;
                }
            }
          }
        }
      }

      $HOTEL['hotel_details'] = array(
        'name' => $PROPERTY['NAME'],
        'street' => "",
        'city' => "",
        'state' => "",
        'country' => "",
        'postal_code' => "",
        'hotel_url' => $DOMAIN_URL,
        'hotel_phone' => "",
        'latitude' => "",
        'longitude' => "",
        'amenities' => "",
        'hotel_photos' => get_room_photos($PROPERTY['IMAGES'], $ibe_url),
        'policy' => get_clean_html($PROPERTY["EMAIL_RES_$LANG"])
      );

      if (count($HOTEL['rooms'])!=0) {
        $RESULT['hotels'][] = $HOTEL;
      }
    }
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

function get_bed_codes($BEDS, $BED_TYPES) {
    $IDs = explode(",",$BEDS);
    $BEDS = array();
    foreach ($IDs as $i => $ID) {
        $NAME = $BED_TYPES[$ID];
        if ($NAME=="1 King") {
            $BEDS[] = "KING";
        } else if ($NAME=="2 Doubles") {
            $BEDS[] = "2_QUEEN";
        }
    }
    return $BEDS;
}

function get_room_photos($IMAGES, $ibe_url) {
    $OBJ = array();
    foreach ($IMAGES as $ID => $URL) {
        $OBJ = array(
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

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RESULT);



?>
