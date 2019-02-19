<?
/*
 * Booking Verify
 *
 * Revised: Jun 21, 2016
 *          Jul 07, 2017
 *
 */


$PROP_ID = $_GET['PROP_ID'];
$YEAR = $_GET['YEAR'];
$RES_NUM = $_GET['RES_NUM'];
$isTripAdvisor = isset($_GET['isTripAdvisor']) ? $_GET['isTripAdvisor'] : 0;

$PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC","XOB");
$CODE = isset($PROP_CODES[$PROP_ID]) ? $PROP_CODES[$PROP_ID] : "FPM";

$RSET = $clsReserv->getReservationByNumber($db, array(
    "NUMBER"=>$RES_NUM,
    "RES_TABLE"=>"RESERVATIONS_{$CODE}_{$YEAR}",
    "FIELDS"=>"ID"
));

if ($RSET['iCount']>0) {
    $row = $db->fetch_array($RSET['rSet']);
    $RES_ID = $row['ID'];
} else {
    $RES_ID = 0;
}

//print "ID: $ID";

$_GET['ID'] = $RES_ID;
$_GET['CODE'] = $CODE;

include "ws.cancelReservation.php";

$retVal['RES_ID'] = $RES_ID;
$retVal['PHONE'] = isset($PHONE) ? $PHONE : "";

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($retVal);