<?
/*
 * Revised: Apr 25, 2011
 */
?>
<fieldset>
    <legend>Description English</legend>
    <div class="fieldset">
        <div class="label">Room Description</div>
        <div class="field"><textarea id="DESCR_EN" name="DESCR_EN" class="full"><? print isset($_DATA['DESCR_EN']) ? $_DATA['DESCR_EN'] : "" ?></textarea></div>
        <div class="label">Room Inclusions</div>
        <div class="field"><textarea id="INCLU_EN" name="INCLU_EN" class="full"><? print isset($_DATA['INCLU_EN']) ? $_DATA['INCLU_EN'] : "" ?></textarea></div>
    </div>
</fieldset>
<fieldset>
    <legend>Description Spanish</legend>
    <div class="fieldset">
        <div class="label">Room Description</div>
        <div class="field"><textarea id="DESCR_SP" name="DESCR_SP" class="full"><? print isset($_DATA['DESCR_SP']) ? $_DATA['DESCR_SP'] : "" ?></textarea></div>
        <div class="label">Room Inclusions</div>
        <div class="field"><textarea id="INCLU_SP" name="INCLU_SP" class="full"><? print isset($_DATA['INCLU_SP']) ? $_DATA['INCLU_SP'] : "" ?></textarea></div>
    </div>
</fieldset>