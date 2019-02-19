<?
/*
 * Revised: Jul 07, 2011
 */

$_HOTEL_ID = isset($_HOTEL_ID) ? $_HOTEL_ID : $PROP_ID;

/* Modules */
$_MOVERRIDE = (isset($_EDITMOD[$_HOTEL_ID]) && isset($_EDITMOD[$_HOTEL_ID][$_PAGE_CODE]) && is_array($_EDITMOD[$_HOTEL_ID][$_PAGE_CODE])) ? $_EDITMOD[$_HOTEL_ID][$_PAGE_CODE] : array();
$_PMODS = isset($_EDITMOD[0][$_PAGE_CODE]) ? $_EDITMOD[0][$_PAGE_CODE] : array();
$_MODULES = array_merge($_PMODS,$_MOVERRIDE);

//print "<pre>";print_r($_MODULES);print "</pre>";

?>
