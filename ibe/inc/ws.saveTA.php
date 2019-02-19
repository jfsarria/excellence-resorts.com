<?
/*
 * Revised: Jul 28, 2011
 */


$MODIFY = "TA";
$SUBMIT = "SUBMIT";
$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "0";
include "mods/m.reserv.forwhom.ta.form.php";

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($result);

?>