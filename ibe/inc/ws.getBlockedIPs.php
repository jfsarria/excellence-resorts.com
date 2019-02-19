<?
/*
 * Revised: Feb 10, 2013
 *
 * http://excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.getBlockedIPs
 *
 */

$BLOCKED = $clsSetup->getBlockedIPs($db);

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header("Content-Type:application/json");

print_r(json_encode($BLOCKED,true));

?>