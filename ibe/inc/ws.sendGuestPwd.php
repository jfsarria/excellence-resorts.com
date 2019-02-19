<?
/*
 * Revised: Jul 21, 2011
 */

$OUT = "";
$_EMAIL = isset($_GET['EMAIL']) ? trim($_GET['EMAIL']) : "";

if ($_EMAIL!="") {
    ob_start();
    $RSET = $clsGuest->getByKey($db, array("WHERE"=>"EMAIL='{$_EMAIL}'"));
    if ( $RSET['iCount'] != 0 ) {
        $row = $db->fetch_array($RSET['rSet']);
        //print '{"PASSWORD":"'.$row['PASSWORD'].'"}';
        print '{"PASSWORD":"sent"}';
        $clsGuest->sendPwd($db, array("ID"=>$row['ID'],"LAN"=>$row['LANGUAGE']));
    }
    $OUT = ob_get_clean();
}

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print ($OUT!="") ? $OUT : '{"error":"not found"}';

?>