<?
$DATA = array_merge($_GET, $_POST);

$T_ACCESO='Desktop';
$isMobile = (isset($_GET['isMobile']) && (int)$_GET['isMobile']==1) ? true : false;
$isIPad = false;
if (!$isMobile) {
    $HTTP_USER_AGENT = strtolower($_SERVER['HTTP_USER_AGENT']);
    $INCLUDES = array('iphone','ipod','android','wp7','symbianos','blackberry','sonyericsson','nokia','samsung','lg ');
    $EXCLUDES = array('tablet');
    $isIPad = strchr($HTTP_USER_AGENT,"ipad");
    foreach($INCLUDES as $I=>$device) if (strpos($HTTP_USER_AGENT,$device) !== FALSE) $isMobile = true;
    foreach($EXCLUDES as $I=>$device) if (strpos($HTTP_USER_AGENT,$device) !== FALSE) $isMobile = false;
}

if ($isMobile && !$isIPad) {
    $T_ACCESO='Movil';
    #hereeeee https
$mobile_url = "http://".$_SERVER['HTTP_HOST']."/mobile/availability.php?".http_build_query($DATA)."&PAGE_CODE=ws.availability&RES_PROP_ID=".(isset($_REQUEST['RES_PROP_ID'])?$_REQUEST['RES_PROP_ID']:"1")."&ENTORNO=".($ENTORNO!=""?$ENTORNO:"")."&ACTION=SUBMIT";
    //print $mobile_url;
    header("Location: ".$mobile_url);
}
?>