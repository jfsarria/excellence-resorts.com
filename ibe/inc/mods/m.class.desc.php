<?
/*
 * Revised: May 22, 2011
 */

if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) {
?>
<fieldset>
    <legend>Rate Description</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="DESCR_EN" name="DESCR_EN" class="full"><? print isset($_DATA['DESCR_EN']) ? $_DATA['DESCR_EN'] : "" ?></textarea></div>
        <div class="label">Spanish</div>
        <div class="field"><textarea id="DESCR_SP" name="DESCR_SP" class="full"><? print isset($_DATA['DESCR_SP']) ? $_DATA['DESCR_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>TripAdvisor</legend>
    <div class="fieldset">
        <div class="label">Rate Amenities</div>
        <div class="field"><input id="TA_AMENITIES_EN" name="TA_AMENITIES_EN" class="full" value="<? print isset($_DATA['TA_AMENITIES_EN']) ? $_DATA['TA_AMENITIES_EN'] : "" ?>"></div>
    </div>
</fieldset>

<? } ?>