<?

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
}
?>