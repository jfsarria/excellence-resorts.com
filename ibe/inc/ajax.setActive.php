<?
/*
 * Revised: Feb 09, 2012
 */

$TABLE = isset($_GET['TABLE']) ? $_GET['TABLE'] : "";
$ACTIVE = isset($_GET['ACTIVE']) ? (int)$_GET['ACTIVE'] : 0;
$ID = isset($_GET['ID']) ? (int)$_GET['ID'] : 0;

print $clsGlobal->setActive($db, array("TABLE"=>$TABLE,"ACTIVE"=>$ACTIVE,"ID"=>$ID)).",".$ID;

?>
