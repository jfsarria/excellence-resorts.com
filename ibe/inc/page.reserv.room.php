<?
/*
 * Revised: Nov 10, 2011
 */

$FORWHOM = array();
$FORWHOM['RES_TO_WHOM'] = (isset($_REQUEST['RES_TO_WHOM']) && $_REQUEST['RES_TO_WHOM']=="TA") ? "TA" : (isset($_REQUEST['RES_TA_ID']) && (int)$_REQUEST['RES_TA_ID']!=0) ? "TA" : "GUEST";
$FORWHOM['RES_GUEST_ID'] = isset($_REQUEST['RES_GUEST_ID']) ? (int)$_REQUEST['RES_GUEST_ID'] : 0;
$FORWHOM['RES_NEW_GUEST'] = (isset($_REQUEST['RES_NEW_GUEST']) && (int)$_REQUEST['RES_NEW_GUEST']==1) ? 1 : 0;
$FORWHOM['RES_TA_ID'] = isset($_REQUEST['RES_TA_ID']) ? (int)$_REQUEST['RES_TA_ID'] : 0;
$FORWHOM['RES_NEW_TA'] = (isset($_REQUEST['RES_NEW_TA']) && (int)$_REQUEST['RES_NEW_TA']==1) ? 1 : 0;
$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM'] = $FORWHOM;

if ($FORWHOM['RES_NEW_GUEST']==0 && $FORWHOM['RES_GUEST_ID']!=0) {
    $GUEST = $clsReserv->getGuest($db, array("ID"=>$FORWHOM['RES_GUEST_ID']));
    $_SESSION['AVAILABILITY']['RESERVATION']['GUEST'] = $GUEST;
}

$TA = array();
if ($FORWHOM['RES_NEW_TA'] == 0) {
    $TA = $clsReserv->getTA($db, array("ID"=>$FORWHOM['RES_TA_ID']));
} else {
    /* AGENCY INFORMATION */
    $TA['IATA'] = isset($_REQUEST['IATA']) ? $_REQUEST['IATA'] : "";
    $TA['AGENCY_NAME'] = isset($_REQUEST['AGENCY_NAME']) ? $_REQUEST['AGENCY_NAME'] : "";
    $TA['AGENCY_PHONE'] = isset($_REQUEST['AGENCY_PHONE']) ? $_REQUEST['AGENCY_PHONE'] : "";
    $TA['AGENCY_ADDRESS'] = isset($_REQUEST['AGENCY_ADDRESS']) ? $_REQUEST['AGENCY_ADDRESS'] : "";
    $TA['AGENCY_CITY'] = isset($_REQUEST['AGENCY_CITY']) ? $_REQUEST['AGENCY_CITY'] : "";
    $TA['AGENCY_STATE'] = isset($_REQUEST['AGENCY_STATE']) ? $_REQUEST['AGENCY_STATE'] : "";
    $TA['AGENCY_COUNTRY'] = isset($_REQUEST['AGENCY_COUNTRY']) ? $_REQUEST['AGENCY_COUNTRY'] : "";
    $TA['AGENCY_ZIPCODE'] = isset($_REQUEST['AGENCY_ZIPCODE']) ? $_REQUEST['AGENCY_ZIPCODE'] : "";
    $TA['IN_MEXICO'] = isset($_REQUEST['IN_MEXICO']) ? (int)$_REQUEST['IN_MEXICO'] : 0;
    /* CONTACT INFORMATION */
    $TA['FIRSTNAME'] = isset($_REQUEST['FIRSTNAME']) ? $_REQUEST['FIRSTNAME'] : "";
    $TA['LASTNAME'] = isset($_REQUEST['LASTNAME']) ? $_REQUEST['LASTNAME'] : "";
    $TA['EMAIL'] = isset($_REQUEST['EMAIL']) ? $_REQUEST['EMAIL'] : "";
    $TA['PASSWORD'] = isset($_REQUEST['PASSWORD']) ? $_REQUEST['PASSWORD'] : "";
    $TA['COMMENTS'] = isset($_REQUEST['COMMENTS']) ? $_REQUEST['COMMENTS'] : "";
    $TA['HEAR_ABOUT_US'] = isset($_REQUEST['HEAR_ABOUT_US']) ? $_REQUEST['HEAR_ABOUT_US'] : "";
}
$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['TA'] = $TA;

?>
