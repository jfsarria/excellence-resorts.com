<?
/*
 * Revised: May 09, 2012
 *          Jun 20, 2016
 */

$_GETID = isset($_GET['getid']) ? (int)$_GET['getid'] : 0;
$_EMAIL = isset($_GET['email']) ? $_GET['email'] : "";
$_ID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$RSET = $clsGuest->getByKey($db, array("WHERE"=>"EMAIL = '$_EMAIL'".(($_ID!=0)?" AND ID <> '$_ID'":"")));
if ($_GETID==1) {
  while ($row = $db->fetch_array($RSET['rSet'])) {
    $ID = $row['ID'];
  }
  print ($RSET['iCount']==0) ? 0 : $ID;
} else {
  print ($RSET['iCount']==0) ? "not found" : "found";
}

?>
