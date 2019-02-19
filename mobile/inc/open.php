<?
session_start();

error_reporting(E_ALL); ini_set('display_errors', '1');

//error_reporting(E_ALL ^ E_DEPRECATED);

ini_set('display_errors', '1');
ini_set("magic_quotes_gpc", "1"); 
ini_set("magic_quotes_runtime", "0"); 
ini_set("magic_quotes_sybase", "0"); 
ini_set("session.gc_maxlifetime", 24*60*60);

date_default_timezone_set('America/New_York');

$_TODAY = date("Y-m-d");
$_YESTERDAY = date("Y-m-d",mktime(0,0,0,date("m") ,date("d")-1,date("Y")));
$_TOMORROW = date("Y-m-d",mktime(0,0,0,date("m") ,date("d")+1,date("Y")));


include $_SERVER['DOCUMENT_ROOT']."/mobile/inc/server.php";
include $_SERVER['DOCUMENT_ROOT']."/ibe/inc/ibe.fns.php";
include $_SERVER['DOCUMENT_ROOT']."/ibe/inc/db.ini.php";
include $_SERVER['DOCUMENT_ROOT']."/ibe/cls/ibe.global.cls.php";
include $_SERVER['DOCUMENT_ROOT']."/ibe/cls/ibe.reserv.cls.php";

ob_start(); 
?>
