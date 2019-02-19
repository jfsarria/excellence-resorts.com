<?
/*
 * Revised: Aug 02, 2011
 *          Feb 10, 2014
 */
?>
<fieldset>
    <legend>Apply Supplement to the Reservation</legend>
    <input type='hidden' name='RES_GUEST_TRANSFER_FEE' id='TRANSFER_FEE' value='<?=$RESERVATION['TRANSFER_FEE']?>'>
    <div class="fieldset">
        <div class="field">Supplement $<input id="SUPPLEMENT" name="SUPPLEMENT" value="<? print isset($RESERVATION['SUPPLEMENT']) ? $RESERVATION['SUPPLEMENT'] : "" ?>" style="width:70px"></div>
    </div>
    <div class="fieldset">
        <div class="label">Notes</div>
        <div class="field"><textarea id="NOTES" name="NOTES" class="full"><? print isset($RESERVATION['NOTES']) ? $RESERVATION['NOTES'] : "" ?></textarea></div>
    </div>
</fieldset>