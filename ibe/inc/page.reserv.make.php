<?
/*
 * Revised: Sep 19, 2011
 *          Feb 21, 2014
 *          Aug 15, 2016
 */

/* GUEST */
if (isset($_REQUEST['RES_USE_THIS_GUEST']) && (int)$_REQUEST['RES_USE_THIS_GUEST']!=0) {
    $_SESSION['AVAILABILITY']['RESERVATION']['GUEST'] = $clsReserv->getGuest($db, array("ID"=>$_REQUEST['RES_USE_THIS_GUEST']));
    $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_GUEST_ID'] = $_REQUEST['RES_USE_THIS_GUEST'];
    $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_NEW_GUEST'] = 0;
} else {
    if (isset($_REQUEST['RES_GUEST_TITLE'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['TITLE'] = $_REQUEST['RES_GUEST_TITLE'];
    if (isset($_REQUEST['RES_GUEST_FIRSTNAME'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['FIRSTNAME'] = $_REQUEST['RES_GUEST_FIRSTNAME'];
    if (isset($_REQUEST['RES_GUEST_LASTNAME'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['LASTNAME'] =  $_REQUEST['RES_GUEST_LASTNAME'];
    if (isset($_REQUEST['RES_GUEST_LANGUAGE'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['LANGUAGE'] = $_REQUEST['RES_GUEST_LANGUAGE'];
    if (isset($_REQUEST['RES_GUEST_ADDRESS'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['ADDRESS'] = $_REQUEST['RES_GUEST_ADDRESS'];
    if (isset($_REQUEST['RES_GUEST_CITY'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['CITY'] = $_REQUEST['RES_GUEST_CITY'];
    if (isset($_REQUEST['RES_GUEST_STATE'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['STATE'] = $_REQUEST['RES_GUEST_STATE'];
    if (isset($_REQUEST['RES_GUEST_COUNTRY'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['COUNTRY'] = $_REQUEST['RES_GUEST_COUNTRY'];
    if (isset($_REQUEST['RES_GUEST_ZIPCODE'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['ZIPCODE'] = $_REQUEST['RES_GUEST_ZIPCODE'];
    if (isset($_REQUEST['RES_GUEST_PHONE'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['PHONE'] = justNumbers($_REQUEST['RES_GUEST_PHONE']);
    if (isset($_REQUEST['RES_GUEST_EMAIL'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['EMAIL'] = $_REQUEST['RES_GUEST_EMAIL'];
    if (isset($_REQUEST['RES_GUEST_MAILING_LIST'])) $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['MAILING_LIST'] = $_REQUEST['RES_GUEST_MAILING_LIST'];
}

/* TA */
if (isset($_REQUEST['RES_USE_THIS_TA']) && (int)$_REQUEST['RES_USE_THIS_TA']!=0) {
    $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['TA'] = $clsReserv->getTA($db, array("ID"=>$_REQUEST['RES_USE_THIS_TA']));
    $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID'] = $_REQUEST['RES_USE_THIS_TA'];
    $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_NEW_TA'] = 0;
} else {
    if (isset($_REQUEST['EMAIL'])) $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['TA']['EMAIL'] = $_REQUEST['EMAIL'];
    $TA['EMAIL'] = isset($_REQUEST['EMAIL']) ? $_REQUEST['EMAIL'] : "";
}
 
/* PAYMENT */

if (isset($_REQUEST['RES_GUESTMETHOD'])) $_SESSION['AVAILABILITY']['RESERVATION']['RES_GUESTMETHOD'] = $_REQUEST['RES_GUESTMETHOD'];

if (isset($_SESSION['AVAILABILITY']['RESERVATION']['RES_GUESTMETHOD']) && $_SESSION['AVAILABILITY']['RESERVATION']['RES_GUESTMETHOD']=="CC") {
    if (isset($_REQUEST['RES_CC_TYPE'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_TYPE'] = $_REQUEST['RES_CC_TYPE'];
    if (isset($_REQUEST['RES_CC_NUMBER'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_NUMBER'] = $_REQUEST['RES_CC_NUMBER'];
    if (isset($_REQUEST['RES_CC_NAME'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_NAME'] = $_REQUEST['RES_CC_NAME'];
    if (isset($_REQUEST['RES_CC_CODE'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_CODE'] = $_REQUEST['RES_CC_CODE'];
    if (isset($_REQUEST['RES_CC_EXP'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_EXP'] = $_REQUEST['RES_CC_EXP'];
    if (isset($_REQUEST['RES_CC_BILL_ADDRESS'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_ADDRESS'] = $_REQUEST['RES_CC_BILL_ADDRESS'];
    if (isset($_REQUEST['RES_CC_BILL_CITY'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_CITY'] = $_REQUEST['RES_CC_BILL_CITY'];
    if (isset($_REQUEST['RES_CC_BILL_STATE'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_STATE'] = $_REQUEST['RES_CC_BILL_STATE'];
    if (isset($_REQUEST['RES_CC_BILL_COUNTRY'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_COUNTRY'] = $_REQUEST['RES_CC_BILL_COUNTRY'];
    if (isset($_REQUEST['RES_CC_BILL_ZIPCODE'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_ZIPCODE'] = $_REQUEST['RES_CC_BILL_ZIPCODE'];
    if (isset($_REQUEST['RES_CC_BILL_EMAIL'])) $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT']['CC_BILL_EMAIL'] = $_REQUEST['RES_CC_BILL_EMAIL'];
} else {
    $_SESSION['AVAILABILITY']['RESERVATION']['PAYMENT'] = array();
}

/* ROOMS */
foreach ($_SESSION['AVAILABILITY']['RESERVATION']['RES_ROOMS_SELECTED'] AS $ROOM_KEY => $ROOM_ID) {
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_TITLE"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_TITLE'] = ($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_TITLE"]!="") ? $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_TITLE"] : $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['TITLE'];
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_FIRSTNAME"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_FIRSTNAME'] = ($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_FIRSTNAME"]!="") ? $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_FIRSTNAME"] : $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['FIRSTNAME'];
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_LASTNAME"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_LASTNAME'] = ($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_LASTNAME"]!="") ? $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_LASTNAME"] : $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['LASTNAME'];
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_REPEATED"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_REPEATED'] = $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_REPEATED"];
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_BEDTYPE"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_BEDTYPE'] = $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_BEDTYPE"];
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_BABYCRIB"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_BABYCRIB'] = $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_BABYCRIB"];
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_SMOKING"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_SMOKING'] = $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_SMOKING"];
    if (isset($_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_OCCASION"])) $_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY]['GUEST_OCCASION'] = $_REQUEST["RES_GUEST_ROOM_{$ROOM_KEY}_OCCASION"];
    //$_SESSION['AVAILABILITY']['RESERVATION']['ROOMS'][$ROOM_KEY] = $IROOM;
}

/* OPTIONAL */
$_SESSION['AVAILABILITY']['RESERVATION']['ARRIVAL_TIME'] = (isset($_REQUEST['RES_GUEST_ARRIVAL_TIME'])) ? $_REQUEST['RES_GUEST_ARRIVAL_TIME'] : "";
$_SESSION['AVAILABILITY']['RESERVATION']['ARRIVAL_AMPM'] = (isset($_REQUEST['RES_GUEST_ARRIVAL_AMPM'])) ? $_REQUEST['RES_GUEST_ARRIVAL_AMPM'] : "";
$_SESSION['AVAILABILITY']['RESERVATION']['AIRLINE'] = (isset($_REQUEST['RES_GUEST_AIRLINE'])) ? $_REQUEST['RES_GUEST_AIRLINE'] : "";
$_SESSION['AVAILABILITY']['RESERVATION']['FLIGHT'] = (isset($_REQUEST['RES_GUEST_FLIGHT'])) ? $_REQUEST['RES_GUEST_FLIGHT'] : "";
$_SESSION['AVAILABILITY']['RESERVATION']['ARRIVAL'] = (isset($_REQUEST['RES_GUEST_ARRIVAL'])) ? $_REQUEST['RES_GUEST_ARRIVAL'] : "";
$_SESSION['AVAILABILITY']['RESERVATION']['ARRIVAL_AP'] = (isset($_REQUEST['RES_GUEST_ARRIVAL_AP'])) ? $_REQUEST['RES_GUEST_ARRIVAL_AP'] : "";

/* TRANSFERS */
if (isset($_REQUEST['RES_GUEST_DEPARTURE_AIRLINE'])) $_SESSION['AVAILABILITY']['RESERVATION']['DEPARTURE_AIRLINE'] = $_REQUEST['RES_GUEST_DEPARTURE_AIRLINE'];
if (isset($_REQUEST['RES_GUEST_DEPARTURE_FLIGHT'])) $_SESSION['AVAILABILITY']['RESERVATION']['DEPARTURE_FLIGHT'] = $_REQUEST['RES_GUEST_DEPARTURE_FLIGHT'];
if (isset($_REQUEST['RES_GUEST_DEPARTURE'])) $_SESSION['AVAILABILITY']['RESERVATION']['DEPARTURE'] = $_REQUEST['RES_GUEST_DEPARTURE'];
if (isset($_REQUEST['RES_GUEST_DEPARTURE_AP'])) $_SESSION['AVAILABILITY']['RESERVATION']['DEPARTURE_AP'] = $_REQUEST['RES_GUEST_DEPARTURE_AP'];
if (isset($_REQUEST['RES_GUEST_TRANSFER_TYPE'])) $_SESSION['AVAILABILITY']['RESERVATION']['TRANSFER_TYPE'] = $_REQUEST['RES_GUEST_TRANSFER_TYPE'];
if (isset($_REQUEST['RES_GUEST_TRANSFER_CAR'])) $_SESSION['AVAILABILITY']['RESERVATION']['TRANSFER_CAR'] = $_REQUEST['RES_GUEST_TRANSFER_CAR'];
if (isset($_REQUEST['RES_GUEST_TRANSFER_FEE'])) $_SESSION['AVAILABILITY']['RESERVATION']['TRANSFER_FEE'] = $_REQUEST['RES_GUEST_TRANSFER_FEE'];

if (isset($_REQUEST['RES_GUEST_COMMENTS'])) $_SESSION['AVAILABILITY']['RESERVATION']['COMMENTS'] = $_REQUEST['RES_GUEST_COMMENTS'];
if (isset($_REQUEST['RES_GUEST_HEAR_ABOUT_US'])) $_SESSION['AVAILABILITY']['RESERVATION']['HEAR_ABOUT_US'] = $_REQUEST['RES_GUEST_HEAR_ABOUT_US'];
if (isset($_REQUEST['CC_COMMENTS'])) $_SESSION['AVAILABILITY']['RESERVATION']['CC_COMMENTS'] = $_REQUEST['CC_COMMENTS'];


/* REBOOKING */
if (isset($_REQUEST['RES_FEES'])) $_SESSION['AVAILABILITY']['RESERVATION']['FEES'] = $_REQUEST['RES_FEES'];
if (isset($_REQUEST['RES_NOTES'])) $_SESSION['AVAILABILITY']['RESERVATION']['NOTES'] = $_REQUEST['RES_NOTES'];


/* CONVERSION */
if (isset($_REQUEST['CURRENCY_CODE'])) $_SESSION['AVAILABILITY']['RESERVATION']['CURRENCY_CODE'] = $_REQUEST['CURRENCY_CODE'];
if (isset($_REQUEST['CURRENCY_QUOTE'])) $_SESSION['AVAILABILITY']['RESERVATION']['CURRENCY_QUOTE'] = $_REQUEST['CURRENCY_QUOTE'];

?>
