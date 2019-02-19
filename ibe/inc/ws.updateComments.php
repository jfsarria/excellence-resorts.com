<?
/*
 * Revised: Jul 26, 2011
 */

$isGetJSON = true;
include "ws.getJSON.php";
$JSON = $ARRAY;

$MODIFY = "COMMENTS";
$SUBMIT = "SUBMIT";
include "mods/m.edit_reserv.comments.php";
include "ws.sendConfirmation.php";

print $result;

?>