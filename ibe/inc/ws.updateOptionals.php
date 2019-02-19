<?
/*
 * Revised: Mar 21, 2012
 *          Feb 25, 2014
 */

//$_SESSION['SENT'] = array();

$isGetJSON = true;
include "ws.getJSON.php";
$JSON = $ARRAY;
extract($JSON);

$MODIFY = "OPTIONALS";
$SUBMIT = "SUBMIT";
include "mods/m.edit_reserv.prefer.php";
include "mods/m.edit_reserv.comments.php";

$MODIFY = "TRANSFER";
include "mods/m.edit_reserv.modify.php";

//include "ws.sendConfirmation.php"; // ALREADY INCLUDED IN mods/m.edit_reserv.modify.php

print $result;

?>