<?
/*
 * Revised: Aug 30, 2011
 */

$_ARRAY = array(
    "ACTION" => "MIGRATED",
    "RES_LANGUAGE" => "EN",
    "RES_IN_THE_FUTURE" => 0,
    "RES_DATE" => $_DATA["CREATED"],
    "RES_PROP_ID" => $_DATA["RES_PROP_ID"],
    "RES_USERTYPE" => Array(1,2,3),
    "RES_COUNTRY_CODE" => "US",
    "RES_STATE_CODE" => "",
    "RES_SPECIAL_CODE" => "",
    "RES_CHECK_IN" => $_DATA["CHECK_IN"],
    "RES_CHECK_OUT" => $_DATA["CHECK_OUT"],
    "RES_NIGHTS" => $_DATA["NIGHTS"],
    "RES_ROOMS_QTY" => $_DATA["ROOMS"],
    "RES_REBOOKING" => array(),
    "RES_ROOM_1_ADULTS_QTY" => $_DATA["ADULTS"],
    "RES_ROOM_1_GUESTS_QTY" => $_DATA["ADULTS"],
    "RES_ROOM_1_ROOMS" => array(array(
        "NAME" => $_DATA["RES_RT_NAME"],
        "NIGTHS" => array(),
        "CLASS_NAMES" => array($_DATA["RES_C_NAME"]),
        "SPECIAL_NAMES" => "X"
    )),
    "RES_ITEMS" => array(
        "PROPERTY" => $clsAvailability->ITEMS['PROPERTY'],
        "PROPERTIES" => $PROPERTIES
    ),
    "RES_ROOMS_ADULTS_QTY" => $_DATA["ADULTS"],
    "RES_ROOMS_CHILDREN_QTY" => 0,
    "RESERVATION" => array(
        "RES_ROOMS_SELECTED" => array(0),
        "RES_ROOM_CHARGE" => array($_DATA["TOTAL"]),
        "RES_TOTAL_CHARGE" => $_DATA["TOTAL"],
        "RES_ROOMS_SELECTED_NAMES" => array($_DATA["RES_RT_NAME"]),
        "FORWHOM" => array (
            "RES_TO_WHOM" => ($_AGENT_ID==0) ? "GUEST" : "TA",
            "RES_GUEST_ID" => $_GUEST['ID'],
            "RES_NEW_GUEST" => 0,
            "RES_TA_ID" => $_AGENT_ID,
            "RES_NEW_TA" => 0,
            "TA" => array()
        ),
        "GUEST" => array (
            "TITLE" => $_GUEST["TITLE"],
            "FIRSTNAME" => $_GUEST["FIRSTNAME"],
            "LASTNAME" => $_GUEST["LASTNAME"],
            "LANGUAGE" => "EN",
            "ADDRESS" => $_GUEST["ADDRESS"],
            "CITY" => $_GUEST["CITY"],
            "STATE" => $_GUEST["STATE"],
            "COUNTRY" => $_GUEST["COUNTRY"],
            "ZIPCODE" => $_GUEST["ZIPCODE"],
            "PHONE" => $_GUEST["PHONE"],
            "EMAIL" => $_GUEST["EMAIL"],
            "MAILING_LIST" => 0,
        ),
        "RES_GUESTMETHOD" => $_DATA["PAYMENT_METHOD"],
        "PAYMENT" => array (
            "CC_TYPE" => $_DATA["CC_TYPE"],
            "CC_NUMBER" => $_DATA["CC_NUMBER"],
            "CC_NAME" => $_DATA["CC_NAME"],
            "CC_CODE" => $_DATA["CC_CODE"],
            "CC_EXP" => $_DATA["CC_EXP"],
            "CC_BILL_ADDRESS" => $_DATA["CC_BILL_ADDRESS"],
            "CC_BILL_CITY" => $_DATA["CC_BILL_CITY"],
            "CC_BILL_STATE" => $_DATA["CC_BILL_STATE"],
            "CC_BILL_COUNTRY" => $_DATA["CC_BILL_COUNTRY"],
            "CC_BILL_ZIPCODE" => $_DATA["CC_BILL_ZIPCODE"],
            "CC_BILL_EMAIL" => $_GUEST["EMAIL"]
        ),
        "ROOMS" => array(
            array (
                "GUEST_TITLE" => $_GUEST["TITLE"],
                "GUEST_FIRSTNAME" => $_GUEST["FIRSTNAME"],
                "GUEST_LASTNAME" => $_GUEST["LASTNAME"],
                "GUEST_BEDTYPE" => $_DATA["RES_BEDDING_TYPE"],
                "GUEST_SMOKING" => $_DATA["RES_SMOKING_PREFERENCE"],
                "GUEST_OCCASION" => ""
            )
        ),
        "ARRIVAL_TIME" => $_DATA["ARRIVAL_TIME"],
        "ARRIVAL_AMPM" => $_DATA["ARRIVAL_AMPM"],
        "AIRLINE" => $_DATA["AIRLINE"],
        "FLIGHT" => $_DATA["FLIGHT"],
        "COMMENTS" => $_DATA["COMMENTS"],
        "HEAR_ABOUT_US" => $_DATA["HEAR_ABOUT_US"],
        "RES_NUMBER" => $RES_NUMBER,
        "RES_ID" => $_DATA["ID"],
        "RES_TABLE" => $_DATA["RES_TABLE"]
    )
);

$JSON = json_encode($_ARRAY);
$JSON = $clsGlobal->cleanJSON($JSON);
$_DATA['ARRAY'] = $JSON;
//print "$RES_NUMBER:<pre>";print_r($_DATA);print "</pre>";

$clsReserv->saveReservation($db, $_DATA);
$clsReserv->createReservationRoomOptsTable($db, array("TABLENAME"=>$_DATA["RES_TABLE"]."_ROOM_OPTS"));

?>