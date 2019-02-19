<?
/*
 * Revised: Oct 16, 2011
 */

$B_WEBSERVER = "";

$B_SERVER_NAME = strtolower($_SERVER["SERVER_NAME"]);
if (strstr($B_SERVER_NAME,"excellence-resorts")!==FALSE) {
    $B_WEBSERVER = "https://secure-excellence-resorts.com/";
} else {
    $B_WEBSERVER = "http://www.smprojects2.com/";
}

$B_WEBSERVER = "https://secure-excellence-resorts.com/";

?>