<?
/*
 * Revised: Jan 05, 2016
 */

$RES_NUM = isset($_GET['NUM'])?$_GET['NUM']:"";

if (!empty($RES_NUM)) {
  include $_SERVER["DOCUMENT_ROOT"] . "/ibe/api/navision/call.php";
} else {
  include $_SERVER["DOCUMENT_ROOT"] . "/ibe/api/navision/re-submit.php";
}


?>