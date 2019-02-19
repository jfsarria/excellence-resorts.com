<?
/*
 * Revised: Jan 22, 2013
 */

$RSET = $clsReserv->searchReservation($db, $_GET);
while ($row = $db->fetch_array($RSET['rSet'])) {
    $RESERVATIONS[$row['ID']] = $clsGlobal->cleanUp_rSet_Array($row);
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($RESERVATIONS);


?>