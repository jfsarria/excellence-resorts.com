<?
/*
 * Revised: Nov 01, 2016
 *          May 13, 2017
 */

global $wpdb;

$CURRENCY = $clsSetup->updateCurrency($db, $_GET);

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ("Content-Type:application/json");

print json_encode($CURRENCY);
