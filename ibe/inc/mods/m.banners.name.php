<?
/*
 * Revised: Jun 08, 2015
 */
?>
<fieldset>
    <legend>Banner Name</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><input type="text" id="NAME_EN" name="NAME_EN" value="<? print isset($_DATA['NAME_EN']) ? $_DATA['NAME_EN'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Spanish</div>
        <div class="field"><input type="text" id="NAME_SP" name="NAME_SP" value="<? print isset($_DATA['NAME_SP']) ? $_DATA['NAME_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
    </div>
</fieldset>