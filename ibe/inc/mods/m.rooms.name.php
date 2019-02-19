<?
/*
 * Revised: Jun 27, 2015
 *          Oct 04, 2016
 */
?>
<fieldset>
    <legend>Room Type Name</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><input type="text" id="NAME_EN" name="NAME_EN" value="<? print isset($_DATA['NAME_EN']) ? $_DATA['NAME_EN'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Spanish</div>
        <div class="field"><input type="text" id="NAME_SP" name="NAME_SP" value="<? print isset($_DATA['NAME_SP']) ? $_DATA['NAME_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
    </div>
</fieldset>
<fieldset>
    <legend>Clave</legend>
    <div class="fieldset">
        <div class="field"><input type="text" id="CLAVE" name="CLAVE" value="<? print isset($_DATA['CLAVE']) ? $_DATA['CLAVE'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
    </div>
</fieldset>

<fieldset>
    <legend>TripAdvisor</legend>
    <div class="fieldset">
        <div class="label">Amenities</div>
        <div class="field"><input id="TA_AMENITIES_EN" name="TA_AMENITIES_EN" class="full" value="<? print isset($_DATA['TA_AMENITIES_EN']) ? $_DATA['TA_AMENITIES_EN'] : "" ?>"></div>
    </div>
    <div class="fieldset">
        <div class="label">View Type</div>
        <div class="field"><input id="TA_VIEWTYPE_EN" name="TA_VIEWTYPE_EN" class="full" value="<? print isset($_DATA['TA_VIEWTYPE_EN']) ? $_DATA['TA_VIEWTYPE_EN'] : "" ?>"></div>
    </div>
    <div class="fieldset">
        <div class="label">Accessibility Features</div>
        <div class="field"><input id="TA_ACCESSIBILITY_EN" name="TA_ACCESSIBILITY_EN" class="full" value="<? print isset($_DATA['TA_ACCESSIBILITY_EN']) ? $_DATA['TA_ACCESSIBILITY_EN'] : "" ?>"></div>
    </div>
</fieldset>
