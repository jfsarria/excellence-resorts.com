<?
/*
 * Revised: Mar 23, 2012
 */

// RES_ID, CODE, YEAR

ob_start();
    $isGetJSON = true;

    if (!isset($ARRAY) || isset($isPreStay) || isset($_isPostStay)) {
        include "ws.getJSON.php";
    }
    if ((isset($ARRAY)&&is_array($ARRAY))||isset($ORIGINAL_RES)) {
        if (!isset($ORIGINAL_RES)) $ORIGINAL_RES = $ARRAY;
        include "ws.getJSON.php";
        $_SESSION['AVAILABILITY'] = $ARRAY;
    }
    if (isset($_SESSION['AVAILABILITY'])&&is_array($_SESSION['AVAILABILITY'])) {
        include "mods/m.reserv.confirmation.php";
    }
$OUT = ob_get_clean();

print $OUT;

?>