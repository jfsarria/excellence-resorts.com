<?
/*
 * Revised: Aug 01, 2011
 *          Aug 02, 2017
 */

extract($_DATA);

$isGetJSON = true;
include "ws.getJSON.php";
$JSON = $ARRAY;
$PHONE = $JSON['RES_ITEMS']['PROPERTY']['PHONE'];

//print "==> <pre>";print_r($JSON);print "</pre>"; 

$SUBMIT="SUBMIT";
$MODIFY="CANCEL";
$RESVIEW['NUMBER'] = $RES_NUM;

include "inc/mods/m.edit_reserv.modify.php";

if (!isset($isTripAdvisor) || !$isTripAdvisor) {

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header ("Content-Type:application/json");

  print json_encode($retVal);

}

?>