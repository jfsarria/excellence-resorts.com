<?
/*
 * Started: Apr 11, 2011
 * Revised: Oct 04, 2012
 */

$isTrust = true;
include_once "inc/ibe.open.php";
//include_once "security.php";

if ($isTrust) {
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
?>