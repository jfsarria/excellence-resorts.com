<?
/*
 * Revised: May 31, 2011
 *          May 22, 2016
 */

//print "<pre>";print_r($_POST);print "</pre>";
$_SESSION['AVAILABILITY']['SEARCH']['RES_ROOMS_SELECTED'] = explode(",",$_POST['RES_ROOMS_SELECTED']);

$RES_REBOOKING = $_SESSION['AVAILABILITY']['RES_REBOOKING'];
$is_Rebooking = false;
if (trim($RES_REBOOKING['RES_ID'])!="") {
    $is_Rebooking = true;
    $REBOOK_GUEST_ID = $RES_REBOOKING['GUEST_ID'];
    $REBOOK_TA_ID = $RES_REBOOKING['TA_ID'];
}
?>
<input type="hidden" name="RES_GUEST_ID" id="RES_GUEST_ID" value="<? print isset($REBOOK_GUEST_ID) ? $REBOOK_GUEST_ID : "0" ?>">
<input type="hidden" name="RES_NEW_GUEST" id="RES_NEW_GUEST" value="0">

<input type="hidden" name="RES_TA_ID" id="RES_TA_ID" value="<? print isset($REBOOK_TA_ID) ? $REBOOK_TA_ID : "0" ?>">
<input type="hidden" name="RES_NEW_TA" id="RES_NEW_TA" value="0">

<input type="hidden" name="RES_GUEST_EMAIL" id="RES_GUEST_EMAIL" value="">

<? 
if ($is_Rebooking) {
    print "<script>ibe.reserv.forWhom.nextStep();</script>";
}
?>

