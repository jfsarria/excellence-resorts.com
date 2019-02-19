<?php
/*
 * Booking Verify
 *
 * Revised: Nov 29, 2016
 *          Jan 11, 2017
 *          
 *
 */


$RETVAL = array(
  "problems" => array(),
  "reference_id" => $DATA['reference_id'],
  "status" => "Failure",
  "reservation" => array(),
  "customer_support" => array(),
);

$reservation = array();

if (count($problems)==0) {
  $RESERVATION = json_decode(file_get_contents($URL), true);

  //ob_start();print_r($RESERVATION);$ARR = ob_get_clean();mail("jaunsarria@gmail.com","RESERVATION",$URL." -- ".$ARR);
  //exit;

  if (isset($RESERVATION) && isset($RESERVATION['RESERVATION'])) {

      $confirmation_url = $DOMAIN_URL . '/' . $BOOKING_PATH . '/confirmation.php?{"RES_ID":"'.$RESERVATION['RESERVATION']['RES_ID'].'","RES_NUMBER":"'.$RESERVATION['RESERVATION']['RES_NUMBER'].'","RES_YEAR":"'.str_replace("RESERVATIONS_","",$RESERVATION['RESERVATION']['RES_TABLE']).'"}';

      $RETVAL["status"] = "Success";

      $ROOMS = array();
      foreach ($RESERVATION['RESERVATION']["ROOMS"] AS $i => $ROOM) { 
          $ROOM_ID = $i + 1;
          $ROOMS[] = array(
            "party" => array(
              "adults" => (int)$RESERVATION["RES_ROOM_{$ROOM_ID}_ADULTS_QTY"],
              "children" => array(),
            ),
            "traveler_first_name" => $ROOM["GUEST_FIRSTNAME"],
            "traveler_last_name" => $ROOM["GUEST_LASTNAME"],
          );
      }

      $reservation = array(
          "reservation_id" => $RESERVATION['RESERVATION']['RES_NUMBER'],
          "partner_hotel_code" => $partner_hotel_code,
          "status" => "Booked",
          "confirmation_url" => $confirmation_url,
          "checkin_date" => $RESERVATION['RES_CHECK_IN'], //$DATA["checkin_date"],
          "checkout_date" => $RESERVATION['RES_CHECK_OUT'], //$DATA["checkout_date"],
          "hotel" => array(
            "name" => $RESERVATION["RES_ITEMS"]["PROPERTY"]["NAME"],
            "address1" => $RESERVATION["RES_ITEMS"]["PROPERTY"]["ADDRESS"],
            "city" => $RESERVATION["RES_ITEMS"]["PROPERTY"]["CITY"],
            "country" => $RESERVATION["RES_ITEMS"]["PROPERTY"]["COUNTRY"],
            "phone" => $RESERVATION["RES_ITEMS"]["PROPERTY"]["PHONE"],
            "url" => $RESERVATION['RES_ITEMS']['PROPERTY']['HOME_URL'],
            "hotel_amenities" => array("standard"=>array(),"custom"=>array("")),
            "photos" => array(),
            "checkinout_policy" => "For early check in, please contact hotel.",
            "checkin_time" => "12:00",
            "checkout_time" => "14:00",
            "hotel_smoking_policy" => array("standard"=>array(),"custom"=>array("")),
          ),
          "customer" => array(
            "first_name" => $RESERVATION['RESERVATION']['GUEST']['FIRSTNAME'], //isset($ROOM['traveler_first_name']) ? $ROOM['traveler_first_name'] : $CUSTOMER["first_name"],
            "last_name" => $RESERVATION['RESERVATION']['GUEST']['LASTNAME'], //isset($ROOM['traveler_last_name']) ? $ROOM['traveler_last_name'] : $CUSTOMER["last_name"],
            "phone_number" => $RESERVATION['RESERVATION']['GUEST']['PHONE'], //$CUSTOMER["phone_number"],
            "email" => $RESERVATION['RESERVATION']['GUEST']['EMAIL'], //$CUSTOMER["email"],
            "country" => $RESERVATION['RESERVATION']['GUEST']['COUNTRY'], //$CUSTOMER["country"],
          ),
          "rooms" => $ROOMS, //$DATA["rooms"],
          "receipt" => array(
            "line_items" => array(
                array(
                    "price" => array(
                        "amount" => $RESERVATION['RESERVATION']['RES_TOTAL_CHARGE'], //$DATA["final_price_at_booking"]["amount"],
                        "currency" => "USD", //$DATA["final_price_at_booking"]["currency"]
                    ),
                    "type" => "rate",
                    "paid_at_checkout" => false,
                    "description" => "This is the base rate."
                )
            ),
            "final_price_at_booking" => array(
                "amount" => $RESERVATION['RESERVATION']['RES_TOTAL_CHARGE'], 
                "currency" => "USD", 
            ), //$DATA["final_price_at_booking"],
            "final_price_at_checkout" => array(
                "amount" => 0,
                "currency" => "USD",
            ), //$DATA["final_price_at_checkout"],
          ),
      );
  } else {
      $RETVAL["status"] = "UnknownReference";
      ob_start();print_r($_GET);$GET = ob_get_clean();
      mail("jaunsarria@gmail.com","Verify Error",$URL."\n\n".$GET);
  }
}
/* --- */

$phone_numbers = array();
$phone_numbers[] = array("contact"=>$RESERVATION["RES_ITEMS"]["PROPERTY"]["PHONE"]?$RESERVATION["RES_ITEMS"]["PROPERTY"]["PHONE"]:"1 (866) 540-2585","description"=>"Support phone line");
$RETVAL["customer_support"] = array("phone_numbers"=>$phone_numbers);

if (count($problems)==0) {
  unset($RETVAL["problems"]);
  $RETVAL["reservation"] = $reservation;
} else {
  unset($RETVAL["reservation"]);
  $RETVAL["problems"] = $problems;
}
