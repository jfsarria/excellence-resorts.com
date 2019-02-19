<?
/*
 * Revised: Jul 25, 2011
 */

$RESERVATIONS = array();
$TA_ID = isset($_GET['ID']) ? trim($_GET['ID']) : "";
$GROUPED = isset($_GET['GROUPED']) ? trim($_GET['GROUPED']) : "0";

if ($TA_ID!="") {
    $RSET = $clsTA->getReservations($db, array("TA_ID"=>$TA_ID,"GROUPED"=>$GROUPED));
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $RESERVATIONS[$row['ID']] = $clsGlobal->cleanUp_rSet_Array($row);
    }
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RESERVATIONS);


?>