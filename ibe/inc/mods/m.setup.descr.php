<?
/*
 * Revised: May 09, 2011
 */
?>
<fieldset>
    <legend>Property Description</legend>
    <div class="fieldset">
        <div class="label">Description English</div>
        <div class="field"><textarea id="DESCR_EN" name="DESCR_EN" class="full"><? print isset($_DATA['DESCR_EN']) ? $_DATA['DESCR_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Description Spanish</div>
        <div class="field"><textarea id="DESCR_SP" name="DESCR_SP" class="full"><? print isset($_DATA['DESCR_SP']) ? $_DATA['DESCR_SP'] : "" ?></textarea></div>
    </div>
</fieldset>
