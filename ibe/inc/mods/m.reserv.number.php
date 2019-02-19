<?
/*
 * Revised: Jul 15, 2011
 *          Nov 12, 2014
 */

if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'])) {
    if (isset($_SESSION['AVAILABILITY']['RES_REBOOKING']) && 
        isset($_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_NUM']) && 
        trim($_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_NUM'])!="") {
            // *** GET RESERVATION NUMBER FROM REBOOKING
            $RES_NUMBER = $_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_NUM'];
    } else {
        // *** GET NEW RESERVATION NUMBER AND UNIQUE ID
        $RES_NUMBER = $clsReserv->newReservationNumber($db, array("PROP_ID"=>$RES_PROP_ID));
    }
    $RES_ID = dbNextId($db);
    $_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'] = $RES_NUMBER;
    $_SESSION['AVAILABILITY']['RESERVATION']['RES_ID'] = $RES_ID;

    if ($isWEBSERVICE) {
        $_ws_result['RES_ID'] = $RES_ID;
        $_ws_result['RES_NUMBER'] = $RES_NUMBER;
    }
}
?>