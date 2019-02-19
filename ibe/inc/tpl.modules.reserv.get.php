<?
/*
 * Revised: Jul 13, 2011
 */

$_HOTEL_ID = isset($_HOTEL_ID) ? $_HOTEL_ID : $RES_PROP_ID;
$_THIS_SECTION = isset($_THIS_SECTION) ? $_THIS_SECTION : $_PAGE_SECTION;

/* Modules */
$_RMOVERRIDE = (isset($_RESERVMOD[$_HOTEL_ID]) && isset($_RESERVMOD[$_HOTEL_ID][$_THIS_SECTION]) && is_array($_RESERVMOD[$_HOTEL_ID][$_THIS_SECTION])) ? $_RESERVMOD[$_HOTEL_ID][$_THIS_SECTION] : array();
$_RPMODS = isset($_RESERVMOD[0][$_THIS_SECTION]) ? $_RESERVMOD[0][$_THIS_SECTION] : array();
$_RMODULES = array_merge($_RPMODS,$_RMOVERRIDE);

//print "<pre>";print_r($_RMODULES);print "</pre>";

?>
