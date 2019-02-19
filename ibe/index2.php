<?
/*
 * Started: Apr 11, 2011
 * Revised: Oct 04, 2012
 */

$isTrust = true;

session_start();

$WEB_ROOT = $_SERVER["DOCUMENT_ROOT"];
error_reporting(E_ALL ^ E_DEPRECATED);
/*
ini_set('display_errors', '1');
ini_set("magic_quotes_gpc", "1"); 
ini_set("magic_quotes_runtime", "0"); 
ini_set("magic_quotes_sybase", "0"); 
ini_set("session.gc_maxlifetime", 24*60*60);

date_default_timezone_set('America/New_York');

global $isWEBSERVICE;
$isWEBSERVICE = (isset($_REQUEST['PAGE_CODE']) && !stristr($_REQUEST['PAGE_CODE'],"ws.")===FALSE) ? true : false;
$isEXPORT = (isset($_REQUEST['EXPORT'])&&(int)$_REQUEST['EXPORT']==1) ? 1 : 0;
$isSUPER = (isset($_SESSION['AUTHENTICATION']) && isset($_SESSION['AUTHENTICATION']['ROLE']) && (int)$_SESSION['AUTHENTICATION']['ROLE']==1) ? true : false;
$isUSER_TRANSFERS = (isset($_SESSION['AUTHENTICATION']) && isset($_SESSION['AUTHENTICATION']['ROLE']) && (int)$_SESSION['AUTHENTICATION']['ROLE']==10) ? true : false;

if (isset($_SERVER["HTTP_USER_AGENT"])) {
    $PLATFORM = ( strchr($_SERVER["HTTP_USER_AGENT"],"Windows") ) ? "PC" : "Mac";
    if (strchr($_SERVER["HTTP_USER_AGENT"],"Safari")) $BROWSER = "Safari";
    if (strchr($_SERVER["HTTP_USER_AGENT"],"Chrome")) $BROWSER = "Chrome";
    if (strchr($_SERVER["HTTP_USER_AGENT"],"MSIE")) $BROWSER = "IE";
    if (strchr($_SERVER["HTTP_USER_AGENT"],"Firefox")) $BROWSER = "FF";
} else {
    $PLATFORM = "unknow";
    $BROWSER = "unknow";
}
*/
/*
include_once "inc/ibe.fns.php";
include_once "inc/db.ini.php";
include_once "cnf/cnf.emails.php";
include_once "cnf/cnf.modules.php";
include_once "cls/tpl.main.cls.php";
include_once "inc/tpl.globals.php";
include_once "inc/ibe.auth.php";
include_once "cls/ibe.global.cls.php";
include_once "cls/ibe.setup.cls.php";
include_once "cls/ibe.banner.cls.php";
include_once "cls/ibe.images.cls.php";
include_once "cls/ibe.uploads.cls.php";
include_once "cls/ibe.users.cls.php";
include_once "cls/ibe.rooms.cls.php";
include_once "cls/ibe.seasons.cls.php";
include_once "cls/ibe.classes.cls.php";
include_once "cls/ibe.child.cls.php";
include_once "cls/ibe.specials.cls.php";
include_once "cls/ibe.global.cls.php";
include_once "cls/ibe.availability.cls.php";
include_once "cls/ibe.reserv.cls.php";
include_once "cls/ibe.ta.cls.php";
include_once "cls/ibe.guest.cls.php";
include_once "cls/ibe.inventory.cls.php";
include_once "cls/ibe.transfers.cls.php";

ob_start();

$_SESSION['AUTHENTICATION']['SETUP'] = $clsSetup->getById($db, array("ID"=>$PROP_ID,"asArray"=>true));
//print "<div><!--<pre>";print_r($_SESSION['AUTHENTICATION']);print "</pre>--></div>";

*/

/*
//include_once "security.php";

if ($isTrust) {

    //print "POST:<pre>";print_r($_POST);print "</pre>";
    //print "_DATA:<pre>";print_r($_DATA);print "</pre>";

    if ($isUSER_TRANSFERS && $_PAGE_CODE=="availability") {
        $_PAGE_CODE = "reports_transfers";
    }

    $_PAGE_PREFIX = "";
    if ($_PAGE_CODE!="") {
        $_PAGE_PREFIX = ( stristr($_PAGE_CODE,"ajax.") === FALSE && !$isWEBSERVICE ) ? "page." : "";
        $include = "inc/{$_PAGE_PREFIX}{$_PAGE_CODE}.php";
        if (file_exists("{$_APP_ROOT}{$include}")) {
            include_once $include;
        } 
    }
} else {
    print '{"error":"'.$HTTP_REFERER.'"}';
}

include_once "inc/ibe.close.php";
*/
?>
ok 4