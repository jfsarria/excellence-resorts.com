<?
/*
 * Revised: Aug 13, 2011
 */

ob_start();

if ($isOk && $isDone) {
    /*
     * Make CCPS call
     */
    $RES_PAYMENT_VERIFY = 0;
    include "m.reserv.payment.er.php";

    /*
     * Guest Password if new and is a Guest reservation
     */
    if ((int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_NEW_GUEST']==1 && $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TO_WHOM']=="GUEST") {
        $clsGuest->sendPwd($db, array(
            "ID"=>$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_GUEST_ID'],
            "LAN"=>$_SESSION['AVAILABILITY']['RES_LANGUAGE']
        ));
    }

    /*
     * Travel Agent Account Approval Confirmation if new
     */
    if ((int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_NEW_TA'] == 1) {
        $clsTA->sendApproval($db, array(
            "ID"=>$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID']
        ));        
    }

    /*
     * Send Reservation Confirmation to Call Center & Guest/TA
     */
    include "m.reserv.confirmation.php";
}

$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;

?>
