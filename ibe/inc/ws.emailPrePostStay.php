<?
/*
 * Revised: Aug 12, 2011
 */

$TODAY = isset($_GET['TODAY'])?$_GET['TODAY']:$_TODAY;
print "Today: $TODAY <br>";

$isPostStay = false;
$isPreStay = true;

$PPRSET = $clsReserv->searchPrePostBooked($db, array("isPreStay"=>$isPreStay,"RESTYPE_IDs"=>array("booked"),"DAYS"=>7,"TODAY"=>$TODAY));
while ($pprow = $db->fetch_array($PPRSET['rSet'])) {
    print "Pre: {$pprow['NUMBER']} - {$pprow['CHECK_IN']} - {$pprow['CHECK_OUT']} <br>";
    $_ID = $pprow['ID'];
    $_CODE = $pprow['HOTEL'];
    $_YEAR = date("Y", strtotime($pprow['CREATED']));
    $clsReserv->modifyReservation($db, array("EMAILED"=>"1","ID"=>$_ID,"RES_TABLE"=>"RESERVATIONS_{$_CODE}_{$_YEAR}"));
    include "ws.sendConfirmation.php";
}

$isPreStay = false;
$isPostStay = true;

$PPRSET = $clsReserv->searchPrePostBooked($db, array("isPostStay"=>$isPostStay,"RESTYPE_IDs"=>array("arrived"),"DAYS"=>-7,"TODAY"=>$TODAY));
while ($pprow = $db->fetch_array($PPRSET['rSet'])) {
    print "Post: {$pprow['NUMBER']} - {$pprow['CHECK_IN']} - {$pprow['CHECK_OUT']} <br>";
    $_ID = $pprow['ID'];
    $_CODE = $pprow['HOTEL'];
    $_YEAR = date("Y", strtotime($pprow['CREATED']));
    $clsReserv->modifyReservation($db, array("EMAILED"=>"2","ID"=>$_ID,"RES_TABLE"=>"RESERVATIONS_{$_CODE}_{$_YEAR}"));
    include "ws.sendConfirmation.php";
}

?>