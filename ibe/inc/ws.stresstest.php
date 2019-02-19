<?
/*
 * Revised: Jan 29, 2017
 */

$RSET = $clsGlobal->getUserTypes($db, array());
$RESULTS = array();

if ( $RSET['iCount'] != 0 ) {
    $cnt=0;
    while ($row = $db->fetch_array($RSET['rSet'])) {
        $RESULTS[] = $clsGlobal->cleanUp_rSet_Array($row);
    }
}


header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/json");

print json_encode($RESULTS);