<?
ini_set('display_errors', '0');
ini_set("magic_quotes_gpc", "1"); 
ini_set("magic_quotes_runtime", "0"); 
ini_set("magic_quotes_sybase", "0"); 

date_default_timezone_set('UTC');

//$_SYS = parse_ini_file("config/ghost.txt", true);
$DATA = array();
$BROWSER = ( !strchr($_SERVER["HTTP_USER_AGENT"],"MSIE") ) ? "FF" : "IE";
$PLATFORM = ( strchr($_SERVER["HTTP_USER_AGENT"],"Windows") ) ? "PC" : "Mac";
$USERAGENT = strtolower($_SERVER["HTTP_USER_AGENT"]);
$isMOBILE = ( strchr($USERAGENT,"mobile") || strchr($USERAGENT,"blackberry") ) ? true : false;
$isIPad = strchr($USERAGENT,"ipad");

if (strchr($_SERVER["HTTP_USER_AGENT"],"Safari")) $BROWSER = "safari";
$ER_APP_LANG = isset($_REQUEST['ER_APP_LANG']) ? $_REQUEST['ER_APP_LANG'] : "EN";

// GET PAGE URL FIXED
$PAGE_QSTR = split("\?",ereg_replace("^".$_SERVER["SCRIPT_NAME"], "", $_SERVER["REQUEST_URI"]));
$TMP = array();
foreach (split("/",$PAGE_QSTR[0]) as $KEY => $VALUE) if ($VALUE!="") array_push($TMP,$VALUE);
$PAGE_URL = join("/",$TMP);
$PAGE_QSTR = (count($PAGE_QSTR)>1) ? $PAGE_QSTR[1] : "";
// ** ** ** ** ** ** ** 

?>