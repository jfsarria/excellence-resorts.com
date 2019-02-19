<?
/*
 * Revised: Jul 26, 2011
 */

$isGetJSON = true;
include "ws.getJSON.php";
$JSON = $ARRAY;

$MODIFY = "PAYMENT";
$SUBMIT = "SUBMIT";
include "mods/m.edit_reserv.payment.php";
include "ws.sendConfirmation.php";

print $result;

?>