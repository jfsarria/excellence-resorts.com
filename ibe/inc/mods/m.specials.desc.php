<?
/*
 * Revised: May 04, 2011
 */
?>
<fieldset>
    <legend>Special Description</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="DESCR_EN" name="DESCR_EN" class="full"><? print isset($_DATA['DESCR_EN']) ? $_DATA['DESCR_EN'] : "" ?></textarea></div>
        <div class="label">Spanish</div>
        <div class="field"><textarea id="DESCR_SP" name="DESCR_SP" class="full"><? print isset($_DATA['DESCR_SP']) ? $_DATA['DESCR_SP'] : "" ?></textarea></div>
    </div>
</fieldset>