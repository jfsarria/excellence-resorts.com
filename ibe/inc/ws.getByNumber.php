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
$NUMBER = $_GET['NUMBER'];

$PROP_CODES = array("","XRC","XPM","XPC","TBH","FPM","XEC","XOB");
$CODE = isset($PROP_CODES[$PROP_ID]) ? $PROP_CODES[$PROP_ID] : "FPM";
/*
if ($PROP_ID==5) {
  $CODE = "FPM";
}
*/
$RSET = $clsReserv->getReservationByNumber($db, array(
    "NUMBER"=>$NUMBER,
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

$_GET['RES_ID'] = $RES_ID;
$_GET['CODE'] = $CODE;

include "ws.getJSON.php";