<?
/*
 * Revised: Aug 04, 2011
 *          Nov 17, 2014
 */

global $clsGlobal;

?>
<fieldset>
    <legend>Property Information</legend>
    <div class="fieldset">
        <div class="label">Information English</div>
        <div class="field"><textarea id="INFO_EN" name="INFO_EN" class="full"><? print isset($_DATA['INFO_EN']) ? $clsGlobal->br2nl($_DATA['INFO_EN']) : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Information Spanish</div>
        <div class="field"><textarea id="INFO_SP" name="INFO_SP" class="full"><? print isset($_DATA['INFO_SP']) ? $clsGlobal->br2nl($_DATA['INFO_SP']) : "" ?></textarea></div>
    </div>
</fieldset>
