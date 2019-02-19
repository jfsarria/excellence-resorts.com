<?
/*
 * Revised: Jul 23, 2011
 */

if (isset($RES_REBOOKING) && isset($RES_REBOOKING['RES_ID']) && trim($RES_REBOOKING['RES_ID'])!="") {
    $arg = array (
        "CHECK_IN"=>$RES_REBOOKING['CHECK_IN'],
        "TOTAL_CHARGE"=>$RES_REBOOKING['TOTAL_CHARGE'],
        "NIGHTS"=>$RES_REBOOKING['NIGHTS']
    );
    $RESERVATION['FEES'] = isset($RESERVATION['FEES']) ? $RESERVATION['FEES'] : $clsReserv->calculateFees($db, $arg);
    ?>
    <fieldset>
        <legend>Rebooking</legend>
        <div class="fieldset">
            <div class="field">Fees $<input name="RES_FEES" value="<? print isset($RESERVATION['FEES']) ? $RESERVATION['FEES'] : "" ?>" style="width:70px"></div>
        </div>
        <div class="fieldset">
            <div class="label">Notes</div>
            <div class="field"><textarea name="RES_NOTES" style="width:480px;height:75px"><? print isset($RESERVATION['NOTES']) ? $RESERVATION['NOTES'] : "Rebooking" ?></textarea></div>
        </div>
    </fieldset>
<? } ?>
