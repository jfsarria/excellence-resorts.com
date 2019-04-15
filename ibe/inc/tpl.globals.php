<?
/*
 * Revised: Oct 20, 2011
 *          Jan 26, 2015
 */

global $_DATA;
global $_IBE_LANG;
global $_APP_ROOT;
global $_PAGE_CODE;
global $_TODAY, $_NOW;
global $_YESTERDAY;
global $_PICKUP_DAYS;
global $_TRANSFER_DAYS;

$_APP_ROOT = $_SERVER["DOCUMENT_ROOT"]."/ibe/";
$_IBE_LANG = isset($_COOKIE['IBE_LANG']) ? $_COOKIE['IBE_LANG'] : "EN";
$_PAGE_CODE = isset($_REQUEST['PAGE_CODE']) ? $_REQUEST['PAGE_CODE'] : "";
$_PAGE_SECTION = isset($_REQUEST['PAGE_SECTION']) ? $_REQUEST['PAGE_SECTION'] : "";
$_TODAY = date("Y-m-d");
$_NOW = date("Y-m-d H:i:s");
$_YESTERDAY = date("Y-m-d",mktime(0,0,0,date("m") ,date("d")-1,date("Y")));
$_PICKUP_DAYS = 2;
$_TRANSFER_DAYS = 31;//4;

global $PROP_ID;
global $ROOM_ID;
global $ID_CAB;
global $ID_DOC;
global $SEASON_ID;
global $CLASS_ID;
global $BANNER_ID;
global $MARKUP_ID;

$ACTION = isset($_REQUEST['ACTION']) ? $_REQUEST['ACTION'] : "LIST";
$PROP_ID = isset($_REQUEST['PROP_ID']) ? (int)$_REQUEST['PROP_ID'] : 0;
$ROOM_ID = isset($_REQUEST['ROOM_ID']) ? (int)$_REQUEST['ROOM_ID'] : 0;
$ID_CAB = isset($_REQUEST['ID_CAB']) ? (int)$_REQUEST['ID_CAB'] : 0;
$ID_DOC = isset($_REQUEST['ID_DOC']) ? (int)$_REQUEST['ID_DOC'] : 0;
$SEASON_ID = isset($_REQUEST['SEASON_ID']) ? (int)$_REQUEST['SEASON_ID'] : 0;
$CLASS_ID = isset($_REQUEST['CLASS_ID']) ? (int)$_REQUEST['CLASS_ID'] : 0;
$SPECIAL_ID = isset($_REQUEST['SPECIAL_ID']) ? (int)$_REQUEST['SPECIAL_ID'] : 0;
$CHILDRATE_ID = isset($_REQUEST['CHILDRATE_ID']) ? (int)$_REQUEST['CHILDRATE_ID'] : 0;
$BANNER_ID = isset($_REQUEST['BANNER_ID']) ? (int)$_REQUEST['BANNER_ID'] : 0;
$MARKUP_ID = isset($_REQUEST['MARKUP_ID']) ? (int)$_REQUEST['MARKUP_ID'] : 0;

/* Defaults Pages */
if ($_PAGE_CODE=="") {
    $_PAGE_CODE = ($PROP_ID==0) ? "availability" : "desktop";
}

ob_start();
print "
    <script>
        var _PAGE_CODE = '$_PAGE_CODE',
            _PROP_ID = '$PROP_ID',
            _ROOM_ID = '$ROOM_ID',
            _ID_CAB='$ID_CAB',
            _ID_DOC='$ID_DOC',
            _BANNER_ID = '$BANNER_ID',
            _SEASON_ID = '$SEASON_ID',
            _CLASS_ID = '$CLASS_ID',
            _SPECIAL_ID = '$SPECIAL_ID',
            _CHILDRATE_ID = '$CHILDRATE_ID',
            _CMARKUP_ID = '$MARKUP_ID',
            _TODAY = '$_TODAY';
    </script>
";
$tmpl->scripts = ob_get_clean();

$_DATA = sanitizeArray($isWEBSERVICE?(isset($_GET)?$_GET:$_POST):$_POST);
if (!$isWEBSERVICE && isset($_GET) && count($_GET)!=0) $_DATA = array_merge($_DATA, $_GET);
$_REQUEST = sanitizeArray($_REQUEST);

//print "<pre>";print_r($_POST);print "</pre>";
//print "<pre>";print_r($_DATA);print "</pre>";
//print "<pre>";print_r($_REQUEST);print "</pre>";

?>